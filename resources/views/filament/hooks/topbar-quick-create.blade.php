@php
    use App\Core\Administration\Filament\Resources\Clients\ClientResource;
    use App\Core\Administration\Filament\Resources\Jobs\JobResource;

    $quickCreateUrl = null;

    if (JobResource::canCreate()) {
        $quickCreateUrl = JobResource::getUrl('create');
    } elseif (ClientResource::canCreate()) {
        $quickCreateUrl = ClientResource::getUrl('create');
    }
@endphp

@if ($quickCreateUrl)
    <a
        href="{{ $quickCreateUrl }}"
        class="atlas-topbar-quick-create"
    >
        + Quick Create
    </a>
@endif
