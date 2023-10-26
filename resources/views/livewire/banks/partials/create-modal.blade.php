<x-jet-dialog-modal wire:model="openCreateModal" maxWidth="sm">
    <x-slot name="title">
        Nuevo banco
    </x-slot>

    <x-slot name="content">
        <div>
            <x-jet-label value="Nombre"/>
            <x-jet-input type="text" placeholder="Nombre del banco" wire:model.defer="bank.name" class="w-full"/>
            <x-jet-input-error for="bank.name" class="mt-2" />
        </div>      
    </x-slot>

    <x-slot name="footer">
        <div class=" space-x-4">
            <x-jet-button wire:click="save">
                Aceptar
            </x-jet-button>

            <x-jet-secondary-button wire:click="$set('openCreateModal', false)">
                Cerrar
            </x-jet-secondary-button>               
        </div>
    </x-slot>
</x-jet-dialog-modal>