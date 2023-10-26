<x-jet-dialog-modal wire:model="suppliersModal">
    <x-slot name="title">
        Proveedores
    </x-slot>

    <x-slot name="content">
        <div class=" grid grid-cols-2 mb-4 text-gray-500">
            
            <div>
                <x-jet-label for="value" value="Buscar:" />
                <input type="text" class="block sm:py-0 rounded border-gray-400 w-full text-gray-600" wire:model="search">
            </div>

            <div>
                
            </div>                
    
        </div>
        <div class="flex flex-col">
            <div class="-my-2 py-2 overflow-x-auto lg:-mx-8 lg:px-8">
                <div class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">
                    <table class="min-w-full">
                        <thead>
                            <tr>                             
                                <th class="thead">Identificacion</th>         
                                <th class="thead">Nombre</th>   
                                <th class="thead"></th>                                                              
                            </tr>
                        </thead>
                        <tbody>
                            @if ($suppliers)
                                @foreach ($suppliers as $item)                                     
                                    <tr>
                                        <td class="row">{{ $item->identity }}</td>                               
                                        <td class="row">{{ $item->name }}</td>
                                        <td class="row">
                                            <button wire:click="selectSupplier('{{ $item->id }}')"
                                                class="w-full bg-orange-600 items-center px-3 py-3 md:py-1 rounded-md font-semibold text-white shadow-md hover:bg-opacity-80 focus:outline-none focus:ring active:text-gray-700 disabled:opacity-25 transition">
                                                <span class="text-gray-100">Seleccionar</span>
                                            </button>    
                                        </td>                                                                                                                        
                                    </tr>                                    
                                @endforeach  
                            @else
                                <tr>
                                    <td colspan="2" class="text-center">No se encontraron registros.</td>
                                </tr>
                            @endif
                        </tbody>                          
                    </table>                        
                </div>
            </div>
        </div>                       
    </x-slot>

    <x-slot name="footer">
        <div class=" space-x-4">
            <x-jet-secondary-button wire:click="$set('suppliersModal', false)">
                Cerrar
            </x-jet-secondary-button>               
        </div>
    </x-slot>
</x-jet-dialog-modal>