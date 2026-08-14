@php
    use App\Administration\Services\AdministrationAccessService;

    $user = auth()->user();
    $access = app(AdministrationAccessService::class);
    $isPlatformSession = $access->isPlatformSession($user);
    $companies = $user ? $access->authorizedCompanies($user) : collect();
    $activeCompanyId = $access->activeCompanyId($user);
@endphp

@if (! $isPlatformSession && $companies->isNotEmpty())
    <div class="atlas-company-switcher-wrap">
        @if ($companies->count() === 1)
            <div class="atlas-company-switcher atlas-company-switcher--static">
                <span class="atlas-company-switcher-label">Company</span>
                <span class="atlas-company-switcher-value">{{ $companies->first()->name }}</span>
            </div>
        @else
            <form method="POST" action="{{ route('atlas.company-context.switch') }}" class="atlas-company-switcher-form">
                @csrf

                <label for="atlas-company-switcher" class="sr-only">Switch company</label>
                <select
                    id="atlas-company-switcher"
                    name="company_id"
                    class="atlas-company-switcher atlas-company-switcher--select"
                    onchange="this.form.submit()"
                >
                    @foreach ($companies as $company)
                        <option value="{{ $company->getKey() }}" @selected($activeCompanyId === $company->getKey())>
                            {{ $company->name }}
                        </option>
                    @endforeach
                </select>
            </form>
        @endif
    </div>
@endif
