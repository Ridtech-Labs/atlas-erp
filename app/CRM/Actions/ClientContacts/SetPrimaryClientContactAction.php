<?php

declare(strict_types=1);

namespace App\CRM\Actions\ClientContacts;

use App\Administration\Enums\PermissionName;
use App\Administration\Services\AdministrationAccessService;
use App\Administration\Services\AdministrationActivityLogger;
use App\Core\Shared\Exceptions\BusinessException;
use App\CRM\Models\ClientContact;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SetPrimaryClientContactAction
{
    public function __construct(
        private readonly AdministrationAccessService $access,
        private readonly AdministrationActivityLogger $logger,
    ) {}

    public function execute(ClientContact $contact, User $actor): ClientContact
    {
        if (! $actor->hasPermissionTo(PermissionName::ClientContactsUpdate->value) || ! $this->access->canAccessTenant($actor, $contact->tenant_id)) {
            throw new BusinessException('You are not allowed to update this client contact.', 403);
        }

        return DB::transaction(function () use ($contact, $actor): ClientContact {
            ClientContact::query()
                ->where('client_id', $contact->client_id)
                ->lockForUpdate()
                ->update(['is_primary' => false]);

            $contact->forceFill(['is_primary' => true])->save();

            $this->logger->log('client.primary_contact_changed', 'Primary client contact changed', $actor, $contact, [
                'tenant_id' => $contact->tenant_id,
                'client_id' => $contact->client_id,
            ]);

            return $contact->refresh();
        });
    }
}
