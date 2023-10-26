<div>
    <div class="flex space-x-2 mb-4 ">  
        {{-- Search --}}            
        <x-jet-input type="text" class="flex-1" wire:model="search" placeholder="Buscar por nombre o identificación ..."/>             
        
        {{-- New input button --}}      
        @can('admin.suppliers.create') 
            <a href="{{ route('suppliers.create') }}" class="px-4 py-2 bg-orange-500 rounded-md font-semibold text-white shadow-md hover:bg-opacity-80 focus:outline-none focus:ring active:text-gray-700 disabled:opacity-25 transition">Nuevo</a>               
        @endcan
    </div>

    {{-- Table --}}
    <div class="flex flex-col">
        <div class="-my-2 py-2 overflow-x-auto lg:-mx-8 lg:px-8">
            <div class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">                                                                                      
                <table class="min-w-full">
                    <thead>
                        <tr>                                
                            <th class="thead">
                                Identificación
                            </th>

                            <th class="thead cursor-pointer" wire:click="order('name')">
                                Nombre
                                {{-- sort --}}
                                @if ($sort == 'name')                                    
                                    @if ($direction == 'desc')
                                        <i class="fas fa-sort-alpha-up-alt float-right mt-1"></i>
                                    @else
                                        <i class="fas fa-sort-alpha-down-alt float-right mt-1"></i>
                                    @endif
                                @else
                                    <i class="fas fa-sort float-right mt-1"></i>
                                @endif 
                            </th>

                            <th class="thead">
                                Teléfono                                   
                            </th>    
                            
                            <th class="thead">
                                Dirección                                   
                            </th>
                            
                            <th class="thead"></th>
                        </tr>
                    </thead>

                    <tbody class="bg-white">
                        @if ($suppliers->count())  
                            @foreach ($suppliers as $item)
                                <tr>
                                    <td class="row whitespace-nowrap">
                                        {{ $item->identity }}
                                    </td>

                                    <td class="row whitespace-nowrap">                                        
                                        {{ $item->name }}                                                                      
                                    </td>   
                                    
                                    <td class="row">
                                        {{ $item->phone }}
                                    </td>
                                    
                                    <td class="row">
                                        {{ $item->address }}
                                    </td>

                                    <td class="row font-medium">
                                        <div class="flex space-x-4">                                                                                        
                                            @can('admin.suppliers.edit')
                                                <a href="{{ route('suppliers.edit', $item) }}" class="text-gray-600 hover:opacity-50"><i class="fa-solid fa-pen-to-square"></i></a>
                                            @endcan

                                            @can('admin.suppliers.delete')                                                                                                             
                                                <a href="#" wire:click="delete({{ $item }})" class="text-orange-500 hover:opacity-50"><i class="fa-solid fa-trash-can"></i></a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach  
                        @else
                            <tr>
                                <td colspan="8">
                                    <div class="p-4 text-center">
                                        <strong>No se encontraron registros</strong>
                                    </div>
                                </td>
                            </tr>                            
                        @endif                                                  
                    </tbody>
                </table>                
            </div>
        </div>                            
    </div>

    {{-- Pagination --}}
    @if ($suppliers->hasPages())
        <div class="px-4 py-4 bg-white border border-gray-200 mt-2 rounded-md shadow-lg">
            {{ $suppliers->links() }}   
        </div>
    @endif   

    {{-- Delete conformation modal --}}
    <x-jet-confirmation-modal wire:model="confirmingDeletion">
        <x-slot name="title">
            Eliminar proveedor
        </x-slot>
    
        <x-slot name="content">
            ¿Estás seguro de eliminar este proveedor?.
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
</div>
