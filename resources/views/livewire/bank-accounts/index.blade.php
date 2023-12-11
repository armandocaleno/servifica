<div>
    <div class=" mb-4">
        @can('accounting.bankaccounts.create') 
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
                            <th class="thead">Número</th> 
                            <th class="thead">Titular</th>
                            <th class="thead">Tipo</th>
                            <th class="thead">Nombre/Referencia</th>  
                            <th class="thead">Banco</th>                           
                            <th class="thead">Cuenta contable</th>
                            <th class="thead"></th>
                        </tr>                        
                    </thead>
                    <tbody class="bg-white">               
                        @if ($bankaccounts->count())                                                                                
                            @foreach ($bankaccounts as $item)
                                <tr>
                                    <td class="row whitespace-nowrap">{{ $item->number }}</td>
                                    <td class="row whitespace-nowrap">{{ $item->owner }}</td>
                                    <td class="row whitespace-nowrap">
                                        @if ($item->type == '1')
                                            Ahorro
                                        @elseif ($item->type == '2')
                                            Corriente
                                        @else
                                            Caja / Efectivo
                                        @endif
                                    </td>
                                    <td class="row whitespace-nowrap">{{ $item->reference }}</td>
                                    <td class="row whitespace-nowrap">
                                        @if ( $item->bank)
                                            {{ $item->bank->name }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="row ">{{ $item->accounting->name }}</td>
                                    
                                    <td class="row flex space-x-4">
                                        @can('accounting.bankaccounts.edit') 
                                            <button wire:click="edit({{ $item }})" class="hover:opacity-25 text-blue-600">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                        @endcan

                                        @can('accounting.bankaccounts.delete') 
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
    @if ($bankaccounts->hasPages())
        <div class="px-4 py-4 bg-white border border-gray-200 mt-2 rounded-md shadow-lg">
            {{ $bankaccounts->links() }}   
        </div>
    @endif 

    {{-- create modal --}}
    @include('livewire.bank-accounts.partials.create-modal')

    {{-- create modal --}}
    @include('livewire.bank-accounts.partials.delete-modal')
</div>
