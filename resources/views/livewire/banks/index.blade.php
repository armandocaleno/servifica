<div>
    <div class=" mb-4">
        @can('accounting.banks.create') 
            <x-jet-button class="bg-orange-600" wire:click="create">Nuevo</x-jet-button>
        @endcan
    </div>

     {{-- Table --}}
     <div class="flex flex-col">
        <div class="-my-2 py-2 overflow-x-auto lg:-mx-8 lg:px-8">       
            <div class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">
                <table class="min-w-full">
                    <thead>
                        <tr>                            
                            <th class="thead">Nombre</th>                            
                            <th class="thead"></th>
                        </tr>                        
                    </thead>
                    <tbody class="bg-white">               
                        @if ($banks->count())                                                                                
                            @foreach ($banks as $item)
                                <tr>
                                    <td class="row whitespace-nowrap">{{ $item->name }}</td>
                                    
                                    <td class="row space-x-4">
                                        @can('accounting.banks.edit') 
                                            <button wire:click="edit({{ $item }})" class="hover:opacity-25 text-blue-600">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                        @endcan

                                        @can('accounting.banks.delete') 
                                            <button wire:click="delete({{ $item }})" class="hover:opacity-25 text-orange-600">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        @endcan
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
    @if ($banks->hasPages())
        <div class="px-4 py-4 bg-white border border-gray-200 mt-2 rounded-md shadow-lg">
            {{ $banks->links() }}   
        </div>
    @endif 

    {{-- create modal --}}
    @include('livewire.banks.partials.create-modal')

    {{-- create modal --}}
    @include('livewire.banks.partials.delete-modal')
</div>
