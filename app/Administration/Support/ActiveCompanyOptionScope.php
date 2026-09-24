<?php

declare(strict_types=1);

namespace App\Administration\Support;

use App\Administration\Services\AdministrationAccessService;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

/** Keeps user-facing option lists inside the active operational workspace. */
class ActiveCompanyOptionScope
{
    public function __construct(
        private readonly AdministrationAccessService $access,
    ) {}

    /** @template TModel of \Illuminate\Database\Eloquent\Model
     * @param  Builder<TModel>  $query
     * @return Builder<TModel>
     */
    public function apply(Builder $query, ?User $user = null): Builder
    {
        $user ??= auth()->user();
        $company = $user instanceof User ? $this->access->activeCompany($user) : null;

        if ($company === null) {
            return $query->whereRaw('1 = 0');
        }

        return $query
            ->where('tenant_id', $company->tenant_id)
            ->where('company_id', $company->getKey());
    }
}
