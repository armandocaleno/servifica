<div>
    <div class=" mb-4">
        @can('accounting.journals.create') 
            <a href="{{ route('journals.create') }}" class="p-2 border rounded-md bg-orange-600 text-gray-200">Nuevo</a>
        @endcan
    </div>
    <div class="grid grid-cols-4 mb-4">
        <div class="flex items-center space-x-2">
            <x-jet-label value="Desde"/>
            <x-jet-input type="date" wire:model="from"/>
        </div>

        <div class="flex items-center space-x-2">
            <x-jet-label value="Hasta"/>
            <x-jet-input type="date" wire:model="to"/>
        </div>

        <div class="flex items-center space-x-2">
            <x-jet-label value="Tipo"/>
            <select name="" id="" class="py-2 rounded border-gray-400 text-gray-600" wire:model="type">
                <option value="">Todos</option>
                <option value="1">Automático</option>
                <option value="2">Manual</option>
            </select>
        </div>

        <div class="flex items-center space-x-2 justify-end">
            <x-jet-label value="Buscar"/>
            <x-jet-input type="text" placeholder="Por número o referencia" wire:model="search"/>
        </div>
    </div>

    {{-- Table --}}
    <div class="flex flex-col">
        <div class="-my-2 py-2 overflow-x-auto lg:-mx-8 lg:px-8">       
            <div class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">
                <table class="min-w-full">
                    <thead>
                        <tr>                            
                            <th class="thead">Número</th>
                            <th class="thead">Fecha</th>
                            <th class="thead">Referencia</th>                            
                            <th class="thead">Tipo</th>  
                            <th class="thead">Detalle</th>
                            <th class="thead"></th>
                        </tr>                        
                    </thead>
                    <tbody class="bg-white">               
                        @if ($journals->count())                                                                                
                            @foreach ($journals as $item)
                                <tr>
                                    <td class="row whitespace-nowrap">{{ $item->number }}</td>
                                    <td class="row">{{ $item->date }}</td>                                   
                                    <td class="row">{{ $item->refence }}</td>         
                                    <td class="row">
                                        @if ($item->type == 1)
                                            A
                                        @else
                                            M
                                        @endif
                                    </td>   
                                    <td class="row">
                                        <button wire:click="showDetail({{ $item }})" class="text-cyan-600 hover:opacity-50"><i class="fa-solid fa-list"></i></button>
                                    </td>
                                    <td class="row space-x-4">
                                        @if ($item->type != 1)
                                            @can('accounting.journals.edit') 
                                                <a href="{{ route('journals.edit', $item) }}" class="hover:opacity-25 text-blue-600"><i class="fa-solid fa-pen-to-square"></i></a>
                                            @endcan
                                            
                                            @can('accounting.journals.delete') 
                                                <button wire:click="delete('{{ $item->id }}')" class="hover:opacity-25 text-orange-600">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            @endcan
                                        @endif

                                        <a href="{{ route('journals.voucher', $item) }}" target="_blank" class="text-red-600 hover:opacity-50"><i class="fa-solid fa-file-pdf"></i></a>
                                    </td>
                                </tr>   
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="text-center text-gray-400">No hay registros</td>
                            </tr>
                        @endif
                    </tbody>
                </table>                
            </div>
        </div>
    </div>

    {{-- Pagination --}}
    @if ($journals->hasPages())
        <div class="px-4 py-4 bg-white border border-gray-200 mt-2 rounded-md shadow-lg">
            {{ $journals->links() }}   
        </div>
    @endif 

    {{-- Detail input modal --}}
    @include('livewire.journals.partials.detail-modal')

    {{-- Confirming delete modal --}}
    @include('livewire.journals.partials.delete-modal')
</div>
