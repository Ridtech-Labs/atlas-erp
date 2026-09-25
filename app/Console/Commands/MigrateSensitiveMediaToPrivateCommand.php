<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MigrateSensitiveMediaToPrivateCommand extends Command
{
    protected $signature = 'atlas:migrate-sensitive-media-to-private {--execute : Move existing sensitive media after reviewing the dry run}';

    protected $description = 'Dry-run or move public operational evidence and VAT receipts to the configured private media disk';

    public function handle(): int
    {
        $targetDisk = (string) config('media-library.disk_name');
        $media = Media::query()
            ->where('disk', 'public')
            ->where(function ($query): void {
                $query->where(fn ($nested) => $nested->where('model_type', 'job_card')->where('collection_name', 'job-card-documents'))
                    ->orWhere(fn ($nested) => $nested->where('model_type', 'waybill')->where('collection_name', 'waybill-documents'))
                    ->orWhere(fn ($nested) => $nested->where('model_type', 'billing_record')->where('collection_name', 'vat-receipt'));
            })
            ->orderBy('id')
            ->get();

        if (! $this->option('execute')) {
            $this->table(['ID', 'Type', 'Collection', 'File'], $media->map(fn (Media $item): array => [
                $item->getKey(), $item->model_type, $item->collection_name, $item->file_name,
            ])->all());
            $this->warn(sprintf('Dry run only: %d file(s) would move to the %s disk. Re-run with --execute after backup verification.', $media->count(), $targetDisk));

            return self::SUCCESS;
        }

        if ($targetDisk === 'public') {
            $this->error('MEDIA_DISK must be a private disk before migration can run.');

            return self::FAILURE;
        }

        foreach ($media as $item) {
            $model = $item->model;

            if (! $model instanceof HasMedia) {
                $this->error(sprintf('Media %d has no movable model. No files were moved.', $item->getKey()));

                return self::FAILURE;
            }
        }

        foreach ($media as $item) {
            $this->move($item, $targetDisk);
        }

        $this->info(sprintf('Moved %d sensitive media file(s) to the %s disk.', $media->count(), $targetDisk));

        return self::SUCCESS;
    }

    private function move(Media $item, string $targetDisk): void
    {
        $model = $item->model;

        if (! $model instanceof HasMedia) {
            throw new \LogicException(sprintf('Media %d has no movable model.', $item->getKey()));
        }

        $item->move($model, $item->collection_name, $targetDisk);
    }
}
