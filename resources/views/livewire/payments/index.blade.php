<div>
    <div class="flex space-x-2 mb-4 ">  
        {{-- Search --}}            
        <x-jet-input type="text" class="flex-1" wire:model="search" placeholder="Buscar por número, beneficiario o referencia..."/>             
        
        {{-- New input button --}}      
        <a href="{{ route('payments.create') }}" class="px-4 py-2 bg-orange-500 rounded-md font-semibold text-white shadow-md hover:bg-opacity-80 focus:outline-none focus:ring active:text-gray-700 disabled:opacity-25 transition">Nuevo</a>               
    </div>

    {{-- Table --}}
    <div class="flex flex-col">
        <div class="-my-2 py-2 overflow-x-auto lg:-mx-8 lg:px-8">
            <div class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">                                                                                      
                <table class="min-w-full">
                    <thead>
                        <tr>         
                            <th class="thead cursor-pointer" wire:click="order('date')">
                                Fecha
                                {{-- sort --}}
                                @if ($sort == 'date')                                    
                                    @if ($direction == 'desc')
                                        <i class="fas fa-sort-alpha-up-alt float-right mt-1"></i>
                                    @else
                                        <i class="fas fa-sort-alpha-down-alt float-right mt-1"></i>
                                    @endif
                                @else
                                    <i class="fas fa-sort float-right mt-1"></i>
                                @endif 
                            </th>

                            <th class="thead cursor-pointer" wire:click="order('number')">
                                Numero
                                {{-- sort --}}
                                @if ($sort == 'number')                                    
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
                                Beneficiario                                
                            </th> 
                            
                            <th class="thead">
                                Cuenta b.                                
                            </th> 

                            <th class="thead">
                                Forma de pago                                
                            </th> 
                            
                            <th class="thead">
                                Referencia                                
                            </th>
                            
                            <th class="thead">
                                Total                       
                            </th>
                            
                            <th class="thead"></th>
                        </tr>
                    </thead>

                    <tbody class="bg-white">
                        @if ($payments->count())  
                            @foreach ($payments as $item)
                                <tr>
                                    <td class="row whitespace-nowrap">
                                        {{ $item->date }}
                                    </td>

                                    <td class="row">
                                        {{ $item->number }}
                                    </td>                                                                                                                              

                                    <td class="row">
                                        {{ $item->beneficiary }}
                                    </td>

                                    <td class="row">
                                        {{ $item->bankAccount->reference }}
                                    </td>

                                    <td class="row">
                                        {{ $item->paymentMethod->name }}
                                    </td>

                                    <td class="row">
                                        {{ $item->reference }}
                                    </td>

                                    <td class="row">
                                        ${{ $item->total }}
                                    </td>

                                    <td class="row font-medium">
                                        <div class="flex space-x-4">    
                                            {{-- @can('banks.payments.voucher')                                                                                --}}
                                                <a href="{{ route('payments.voucher', $item) }}" target="_blank" class="text-red-600 hover:opacity-50"><i class="fa-solid fa-file-pdf"></i></a>
                                            {{-- @endcan  --}}
                                            {{-- <a href="{{ route('payments.edit', $item) }}" class="text-gray-600 hover:opacity-50"><i class="fa-solid fa-pen-to-square"></i></a> --}}
                                            
                                            {{-- @can('banks.payments.delete')  --}}
                                                {{-- @if ($item->state == 1)                                                                                                                --}}
                                                    <a href="#" wire:click="delete({{ $item }})" class="text-orange-500 hover:opacity-50"><i class="fa-solid fa-trash-can"></i></a>
                                                {{-- @endif  --}}
                                            {{-- @endcan --}}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach  
                        @else
                            <tr>
                                <td colspan="7">
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
    @if ($payments->hasPages())
        <div class="px-4 py-4 bg-white border border-gray-200 mt-2 rounded-md shadow-lg">
            {{ $payments->links() }}   
        </div>
    @endif   

    {{-- Delete conformation modal --}}
    <x-jet-confirmation-modal wire:model="confirmingDeletion">
        <x-slot name="title">
            Eliminar pago
        </x-slot>
    
        <x-slot name="content">
            ¿Estás seguro de eliminar este pago?
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
