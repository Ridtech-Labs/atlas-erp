<?php

declare(strict_types=1);

namespace App\Operations\Support;

use App\Core\Tenancy\Models\Company;
use App\Operations\Models\Job;
use Illuminate\Support\Facades\Schema;

class JobPlanningFieldMapper
{
    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function canonicalAndLegacyAttributes(array $data, Company $company, ?Job $job = null): array
    {
        $attributes = $data;
        $currency = $this->resolveCurrency($data, $company, $job);

        if (Schema::hasColumn('client_jobs', 'currency')) {
            $attributes['currency'] = $currency;
        }

        if (Schema::hasColumn('client_jobs', 'customer_reference') && array_key_exists('client_reference', $attributes)) {
            $attributes['customer_reference'] = $attributes['client_reference'];
        }

        if (Schema::hasColumn('client_jobs', 'purchase_order_number') && array_key_exists('internal_reference', $attributes)) {
            $attributes['purchase_order_number'] = $attributes['internal_reference'];
        }

        if (Schema::hasColumn('client_jobs', 'scheduled_start_date') && array_key_exists('planned_start_date', $attributes)) {
            $attributes['scheduled_start_date'] = $attributes['planned_start_date'];
        }

        if (Schema::hasColumn('client_jobs', 'scheduled_end_date') && array_key_exists('planned_end_date', $attributes)) {
            $attributes['scheduled_end_date'] = $attributes['planned_end_date'];
        }

        if (Schema::hasColumn('client_jobs', 'estimated_amount') && array_key_exists('estimated_value', $attributes)) {
            $attributes['estimated_amount'] = $attributes['estimated_value'];
        }

        if (Schema::hasColumn('client_jobs', 'assigned_to') && array_key_exists('assigned_operator_id', $attributes)) {
            $attributes['assigned_to'] = $attributes['assigned_operator_id'];
        }

        if (Schema::hasColumn('client_jobs', 'notes') && array_key_exists('description', $attributes)) {
            $attributes['notes'] = $attributes['description'];
        }

        return $attributes;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function resolveCurrency(array $data, Company $company, ?Job $job = null): string
    {
        unset($data['currency']);

        $currency = $job?->getAttribute('currency')
            ?? $company->currency
            ?? $company->tenant->currency
            ?? 'GHS';

        return strtoupper((string) $currency);
    }
}
