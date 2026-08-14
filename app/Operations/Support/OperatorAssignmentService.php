<?php

declare(strict_types=1);

namespace App\Operations\Support;

use App\Core\Shared\Exceptions\BusinessException;
use App\Models\User;
use App\Operations\Models\Job;
use App\Operations\Models\JobCard;
use Illuminate\Support\Str;

class OperatorAssignmentService
{
    /**
     * @return array<int, string>
     */
    public function companyOperatorOptions(int $tenantId, int $companyId): array
    {
        return User::query()
            ->where('tenant_id', $tenantId)
            ->whereHas('companies', fn ($query) => $query->whereKey($companyId))
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get()
            ->mapWithKeys(fn (User $operator) => [$operator->getKey() => $operator->full_name])
            ->all();
    }

    public function resolveCompanyOperator(mixed $operatorId, int $tenantId, int $companyId): ?User
    {
        if (! is_numeric($operatorId)) {
            return null;
        }

        $operator = User::query()
            ->whereKey((int) $operatorId)
            ->where('tenant_id', $tenantId)
            ->first();

        if (! $operator instanceof User || ! $operator->companies()->whereKey($companyId)->exists()) {
            throw new BusinessException('The selected operator is not authorized for the active company.', 422);
        }

        return $operator;
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
     * @return array{operator:?User, external_name:?string}
     */
    public function resolveAssignment(
        mixed $operatorId,
        mixed $externalName,
        int $tenantId,
        int $companyId,
        bool $require = false,
        string $requiredMessage = 'Assign an operator before continuing.',
    ): array {
        $operator = $this->resolveCompanyOperator($operatorId, $tenantId, $companyId);
        $externalName = $this->normalizeExternalName($externalName);

        if ($operator instanceof User && $externalName !== null) {
            throw new BusinessException('Select a company operator or enter an external operator name, not both.', 422);
        }

        if ($require && ! $operator instanceof User && $externalName === null) {
            throw new BusinessException($requiredMessage, 422);
        }

        return [
            'operator' => $operator,
            'external_name' => $externalName,
        ];
    }

    public function displayName(?User $operator, ?string $externalName): ?string
    {
        return $operator->full_name ?? $this->normalizeExternalName($externalName);
    }

    /**
     * @return array{operator_id:?int, external_name:?string, source:?string}
     */
    public function defaultJobCardAssignment(Job $job): array
    {
        $latestCard = $job->jobCards()->orderByDesc('card_date')->orderByDesc('id')->first();

        $operatorId = $latestCard instanceof JobCard ? $latestCard->operator_id : $job->assigned_operator_id;
        $externalName = $this->normalizeExternalName($latestCard instanceof JobCard ? $latestCard->operated_by : $job->assigned_operator_name);

        return [
            'operator_id' => is_int($operatorId) ? $operatorId : null,
            'external_name' => $externalName,
            'source' => is_int($operatorId) ? 'company_personnel' : ($externalName !== null ? 'external' : null),
        ];
    }

    /**
     * @return array{operator_id:?int, external_name:?string, source:?string}
     */
    public function suspectedAutoAssignmentForJob(Job $job): array
    {
        return [
            'operator_id' => $job->assigned_operator_id,
            'external_name' => $job->assigned_operator_name,
            'source' => $job->assigned_operator_id !== null ? 'company_personnel' : ($job->assigned_operator_name !== null ? 'external' : null),
        ];
    }

    public function jobNeedsOperator(Job $job): bool
    {
        return $this->displayName($job->assignedOperator, $job->assigned_operator_name) !== null;
    }

    public function jobCardOperatorName(JobCard $jobCard): ?string
    {
        return $this->displayName($jobCard->operator, $jobCard->operated_by);
    }
}
