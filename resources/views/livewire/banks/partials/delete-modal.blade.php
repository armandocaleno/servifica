{{-- Delete conformation modal --}}
<x-jet-confirmation-modal wire:model="confirmingDeletion">
    <x-slot name="title">
        Eliminar banco
    </x-slot>

    <x-slot name="content">
        ¿Estás seguro de eliminar este banco?
    </x-slot>

    <x-slot name="footer">
        <x-jet-secondary-button wire:click="$toggle('confirmingDeletion')" wire:loading.attr="disabled">
            Cancelar    
        </x-jet-secondary-button>

        <x-jet-danger-button class="ml-2" wire:click="destroy" wire:loading.attr="disabled">
            Eliminar
        </x-jet-danger-button>
    </x-slot>
</x-jet-confirmation-modal> 