<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Throwable;

class MigrateSensitiveMediaToPrivateCommand extends Command
{
    protected $signature = 'atlas:migrate-sensitive-media-to-private
        {--dry-run : Report the migration without changing files or metadata}
        {--execute : Copy verified files and switch Media Library metadata to the private disk}';

    protected $description = 'Safely copy referenced operational evidence and VAT receipts from public to private storage';

    /** @var array<string, string> */
    private const COLLECTIONS = [
        'job_card' => 'job-card-documents',
        'waybill' => 'waybill-documents',
        'billing_record' => 'vat-receipt',
    ];

    public function handle(): int
    {
        $targetDisk = (string) config('media-library.disk_name');
        $dryRun = (bool) $this->option('dry-run') || ! (bool) $this->option('execute');

        if (! $dryRun && $targetDisk === 'public') {
            $this->error('MEDIA_DISK must reference a private disk before migration can run.');

            return self::FAILURE;
        }

        $publicMedia = Media::query()->where('disk', 'public')->orderBy('id')->get();
        $sensitiveMedia = Media::query()
            ->where(function ($query): void {
                foreach (self::COLLECTIONS as $modelType => $collection) {
                    $query->orWhere(fn ($nested) => $nested
                        ->where('model_type', $modelType)
                        ->where('collection_name', $collection));
                }
            })
            ->orderBy('id')
            ->get();

        $counts = [
            'scanned' => $sensitiveMedia->count(),
            'eligible' => 0,
            'migrated' => 0,
            'already_private' => 0,
            'skipped' => 0,
            'failed' => 0,
            'missing_source' => 0,
            'ignored_unrelated' => $publicMedia->count() - $publicMedia->filter(fn (Media $media): bool => $this->isSensitive($media))->count(),
        ];
        $rows = [];

        foreach ($sensitiveMedia as $media) {
            if ($media->disk === $targetDisk) {
                $counts['already_private']++;
                $rows[] = [$media->getKey(), $media->model_type, $media->collection_name, 'already private'];

                continue;
            }

            if ($media->disk !== 'public') {
                $counts['skipped']++;
                $rows[] = [$media->getKey(), $media->model_type, $media->collection_name, 'unsupported source disk'];

                continue;
            }

            $counts['eligible']++;

            if (! $this->sourceExists($media)) {
                $counts['failed']++;
                $counts['missing_source']++;
                $rows[] = [$media->getKey(), $media->model_type, $media->collection_name, 'missing source'];

                continue;
            }

            if ($dryRun) {
                $rows[] = [$media->getKey(), $media->model_type, $media->collection_name, 'would migrate'];

                continue;
            }

            try {
                $this->copyAndVerify($media, $targetDisk);
                $media->forceFill(['disk' => $targetDisk, 'conversions_disk' => $targetDisk])->save();

                $counts['migrated']++;
                $rows[] = [$media->getKey(), $media->model_type, $media->collection_name, 'migrated'];
            } catch (Throwable $exception) {
                report($exception);
                $counts['failed']++;
                $rows[] = [$media->getKey(), $media->model_type, $media->collection_name, 'copy or verification failed'];
            }
        }

        $this->table(['Media ID', 'Model', 'Collection', 'Result'], $rows);
        $this->newLine();
        $this->line(sprintf('scanned=%d eligible=%d migrated=%d already_private=%d skipped=%d failed=%d missing_source=%d ignored_unrelated=%d', ...array_values($counts)));

        if ($dryRun) {
            $this->warn('Dry run only: no files or Media Library metadata were changed.');
        } else {
            $this->info('Public source files were retained for rollback and later verified cleanup.');
        }

        return $counts['failed'] === 0 ? self::SUCCESS : self::FAILURE;
    }

    private function isSensitive(Media $media): bool
    {
        return (self::COLLECTIONS[$media->model_type] ?? null) === $media->collection_name;
    }

    private function sourceExists(Media $media): bool
    {
        return Storage::disk('public')->exists($media->getPathRelativeToRoot());
    }

    private function copyAndVerify(Media $media, string $targetDisk): void
    {
        $source = Storage::disk('public');
        $destination = Storage::disk($targetDisk);
        $directory = dirname($media->getPathRelativeToRoot());
        $files = $source->allFiles($directory);

        if ($files === [] || ! in_array($media->getPathRelativeToRoot(), $files, true)) {
            throw new \RuntimeException('The original evidence file is missing from its Media Library directory.');
        }

        foreach ($files as $path) {
            if (! $this->filesMatch($source, $destination, $path)) {
                $stream = $source->readStream($path);

                if (! is_resource($stream)) {
                    throw new \RuntimeException('The source evidence file could not be read.');
                }

                try {
                    if (! $destination->writeStream($path, $stream)) {
                        throw new \RuntimeException('The private evidence file could not be written.');
                    }
                } finally {
                    fclose($stream);
                }
            }

            if (! $this->filesMatch($source, $destination, $path)) {
                throw new \RuntimeException('The copied evidence file did not pass integrity verification.');
            }
        }
    }

    private function filesMatch(FilesystemAdapter $source, FilesystemAdapter $destination, string $path): bool
    {
        if (! $destination->exists($path)) {
            return false;
        }

        try {
            $sourceChecksum = $source->checksum($path);
            $destinationChecksum = $destination->checksum($path);

            if (is_string($sourceChecksum) && is_string($destinationChecksum)) {
                return hash_equals($sourceChecksum, $destinationChecksum);
            }
        } catch (Throwable) {
            // Some adapters cannot provide checksums; compare reliable metadata instead.
        }

        return $source->size($path) === $destination->size($path);
    }
}
