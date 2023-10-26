{{-- Delete conformation modal --}}
<x-jet-confirmation-modal wire:model="openDeleteModal">
    <x-slot name="title">
        Eliminar asiento contable
    </x-slot>

    <x-slot name="content">
        ¿Estás seguro de eliminar este asiento contable?
    </x-slot>

    <x-slot name="footer">
        <x-jet-secondary-button wire:click="$toggle('openDeleteModal')" wire:loading.attr="disabled">
            Cancelar    
        </x-jet-secondary-button>

        <x-jet-danger-button class="ml-2" wire:click="destroy" wire:loading.attr="disabled">
            Eliminar
        </x-jet-danger-button>
    </x-slot>
</x-jet-confirmation-modal> 