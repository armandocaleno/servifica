<x-jet-dialog-modal wire:model="openCreateModal" maxWidth="sm">
    <x-slot name="title">
        Nuevo banco
    </x-slot>

    <x-slot name="content">
        <div class="mb-2">
            <x-jet-label value="Número" />
            <x-jet-input type="text" placeholder="Número de cuenta" wire:model.defer="bankaccount.number" class="w-full"/>
            <x-jet-input-error for="bankaccount.number" class="mt-2" />
        </div>

        <div class="mb-2">
            <x-jet-label value="Titular" />
            <x-jet-input type="text" placeholder="Titular de la cuenta" wire:model.defer="bankaccount.owner" class="w-full"/>
            <x-jet-input-error for="bankaccount.owner" class="mt-2" />
        </div>

        <div class="mb-2">
            <x-jet-label value="Tipo *" />
            <select name="" id="" wire:model.defer="bankaccount.type" class="py-2 rounded border-gray-300 w-full text-gray-600">
                <option value="1">Ahorros</option>
                <option value="2">Corriente</option>
                <option value="3">Caja/Efectivo</option>
            </select>
            <x-jet-input-error for="bankaccount.type" class="mt-2" />
        </div>

        <div class="mb-2">
            <x-jet-label value="Nombre / Referencia *" />
            <x-jet-input type="text" placeholder="Nombre / Referencia" wire:model.defer="bankaccount.reference" class="w-full"/>
            <x-jet-input-error for="bankaccount.reference" class="mt-2" />
        </div>

        <div class="mb-2">
            <x-jet-label value="Banco" />
            <select name="" id="" wire:model.defer="bankaccount.bank_id" class="py-2 rounded border-gray-300 w-full text-gray-600">
                <option value="">Seleccione</option>
                @foreach ($banks as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            </select>
            <x-jet-input-error for="bankaccount.bank_id" class="mt-2" />
        </div>

        <div class="mb-2">
            <x-jet-label value="Cuenta contable *" />
            <select name="" id="" wire:model.defer="bankaccount.accounting_id" class="py-2 rounded border-gray-300 w-full text-gray-600">
                <option value="">Seleccione</option>
                @foreach ($accountings as $item)
                    <option value="{{ $item->id }}">{{ $item->code }} - {{ $item->name }}</option>
                @endforeach
            </select>
            <x-jet-input-error for="bankaccount.accounting_id" class="mt-2" />
        </div>

        <label for="">( * ) Obligatorio</label>
    </x-slot>

    <x-slot name="footer">
        <div class=" space-x-4">
            <x-jet-button wire:click="save" wire:loading.attr="disabled">
                Aceptar
            </x-jet-button>

            <x-jet-secondary-button wire:click="$set('openCreateModal', false)">
                Cerrar
            </x-jet-secondary-button>
        </div>
    </x-slot>
</x-jet-dialog-modal>
