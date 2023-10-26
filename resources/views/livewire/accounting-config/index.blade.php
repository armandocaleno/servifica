<div>
    <table>
        <thead>
            <tr>
                <th>Cuenta</th>
                <th>Descripcion</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($accountingConfig as $item)
                <tr>
                    <td class="row">
                        @if ($item->accounting != null)
                            {{ $item->accounting->code }} - {{ $item->accounting->name }}
                        @else
                            --
                        @endif
                    </td>
                    <td class="row">{{ $item->description }}</td>
                    <td>
                        @can('admin.config.accounting.edit')  
                            <button wire:click="edit({{ $item }})" class="hover:opacity-25 text-blue-600">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                        @endcan
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <x-jet-dialog-modal wire:model="openCreateModal" maxWidth="sm">
        <x-slot name="title">
            Configuracion de cuenta contable
        </x-slot>
    
        <x-slot name="content">
    
            <div class="mb-2">
                <x-jet-label value="Cuenta contable" />
                <select name="" id="" wire:model="accounting_config.accounting_id" class="py-2 rounded border-gray-300 w-full text-gray-600">
                    <option value="">Seleccione</option>
                    @foreach ($accountings as $item)
                        <option value="{{ $item->id }}">{{ $item->code }} - {{ $item->name }}</option>
                    @endforeach
                </select>
                <x-jet-input-error for="accounting_config.accounting_id" class="mt-2" />
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
</div>
