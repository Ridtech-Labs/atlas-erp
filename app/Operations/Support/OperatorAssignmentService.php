<?php

declare(strict_types=1);

namespace App\Operations\Support;

use App\Core\Shared\Exceptions\BusinessException;
use App\Models\User;
use App\Operations\Models\Job;
use App\Operations\Models\JobCard;
use App\Operations\Models\Personnel;
use Illuminate\Support\Str;

class OperatorAssignmentService
{
    /** @return array<int, string> */
    public function companyOperatorOptions(int $tenantId, int $companyId): array
    {
        return Personnel::query()->where(['tenant_id' => $tenantId, 'company_id' => $companyId, 'status' => 'active'])->where('can_operate_equipment', true)->orderBy('first_name')->orderBy('last_name')->get()->mapWithKeys(fn (Personnel $person): array => [$person->getKey() => $person->full_name])->all();
    }

    /** @return array<int, string> */
    public function companyDriverOptions(int $tenantId, int $companyId): array
    {
        return Personnel::query()->where(['tenant_id' => $tenantId, 'company_id' => $companyId, 'status' => 'active'])->where('can_drive', true)->orderBy('first_name')->orderBy('last_name')->get()->mapWithKeys(fn (Personnel $person): array => [$person->getKey() => $person->full_name])->all();
    }

    public function resolvePersonnel(mixed $id, int $tenantId, int $companyId, bool $driver = false): ?Personnel
    {
        if (! is_numeric($id)) {
            return null;
        }
        $person = Personnel::query()->whereKey((int) $id)->where(['tenant_id' => $tenantId, 'company_id' => $companyId, 'status' => 'active'])->when($driver, fn ($q) => $q->where('can_drive', true), fn ($q) => $q->where('can_operate_equipment', true))->first();
        if (! $person instanceof Personnel) {
            throw new BusinessException($driver ? 'The selected driver is not available for the active company.' : 'The selected operator is not available for the active company.', 422);
        }

        return $person;
    }

    public function normalizeExternalName(mixed $name): ?string
    {
        if (! is_string($name)) {
            return null;
        }
        $name = Str::squish($name);

        return $name === '' ? null : $name;
    }

    /**
     * Legacy User references remain readable during the Personnel migration.
     * New Filament forms never submit this field.
     */
    public function resolveLegacyUser(mixed $id, int $tenantId, int $companyId): ?User
    {
        if (! is_numeric($id)) {
            return null;
        }

        $user = User::query()->whereKey((int) $id)->where('tenant_id', $tenantId)->first();
        if (! $user instanceof User || ! $user->companies()->whereKey($companyId)->exists()) {
            throw new BusinessException('The selected operator is not authorized for the active company.', 422);
        }

        return $user;
    }

    /** @return array{personnel:?Personnel,external_name:?string} */
    public function resolveAssignment(mixed $id, mixed $name, int $tenantId, int $companyId, bool $require = false, string $message = 'Assign an operator before continuing.', bool $driver = false): array
    {
        $personnel = $this->resolvePersonnel($id, $tenantId, $companyId, $driver);
        $externalName = $this->normalizeExternalName($name);
        if ($personnel && $externalName) {
            throw new BusinessException($driver ? 'Select Personnel or enter an external driver name, not both.' : 'Select Personnel or enter an external operator name, not both.', 422);
        }
        if ($require && ! $personnel && ! $externalName) {
            throw new BusinessException($message, 422);
        }

        return ['personnel' => $personnel, 'external_name' => $externalName];
    }

    /** @return array{personnel:?Personnel,external_name:?string} */
    public function resolveDriverAssignment(mixed $id, mixed $name, int $tenantId, int $companyId): array
    {
        $personnel = $this->resolvePersonnel($id, $tenantId, $companyId, true);
        $externalName = $this->normalizeExternalName($name);
        if ($personnel && $externalName) {
            throw new BusinessException('Select Personnel or enter an external driver name, not both.', 422);
        }
        if (! $personnel && ! $externalName) {
            throw new BusinessException('Record a driver before continuing.', 422);
        }

        return ['personnel' => $personnel, 'external_name' => $externalName];
    }

    /** @return array{personnel_id:?int,external_name:?string,source:?string} */
    public function defaultJobCardAssignment(Job $job): array
    {
        $card = $job->jobCards()->orderByDesc('card_date')->orderByDesc('id')->first();
        $id = $card instanceof JobCard ? $card->operator_personnel_id : $job->assigned_personnel_id;
        $name = $this->normalizeExternalName($card instanceof JobCard ? $card->operated_by : $job->assigned_operator_name);

        return ['personnel_id' => is_int($id) ? $id : null, 'external_name' => $name, 'source' => is_int($id) ? 'personnel' : ($name ? 'external' : null)];
    }

    /** @return list<array{personnel_id:?int,operator_name:?string}> */
    public function resolveOperatorEntries(mixed $operators, int $tenantId, int $companyId): array
    {
        if (! is_array($operators)) {
            return [];
        }
        $resolved = [];
        foreach ($operators as $operator) {
            if (! is_array($operator)) {
                continue;
            }
            $assignment = $this->resolveAssignment($operator['personnel_id'] ?? null, $operator['operator_name'] ?? null, $tenantId, $companyId);
            if ($assignment['personnel'] || $assignment['external_name']) {
                $resolved[] = ['personnel_id' => $assignment['personnel']?->getKey(), 'operator_name' => $assignment['external_name']];
            }
        }

        return $resolved;
    }

    /** @param list<array{personnel_id:?int,operator_name:?string}> $operators */
    public function syncJobCardOperators(JobCard $card, array $operators): void
    {
        $card->operators()->delete();
        foreach ($operators as $index => $operator) {
            $card->operators()->create(['personnel_id' => $operator['personnel_id'], 'operator_name' => $operator['operator_name'], 'sort_order' => $index]);
        }
        $primary = $card->operators()->with('personnel')->orderBy('sort_order')->first();
        $card->forceFill(['operator_personnel_id' => $primary?->personnel_id, 'operator_id' => null, 'operated_by' => $primary?->personnel_id === null ? $primary?->operator_name : null])->saveQuietly();
        $card->unsetRelation('operatorPersonnel');
        $card->unsetRelation('operator');
        $card->unsetRelation('operators');
    }
}
