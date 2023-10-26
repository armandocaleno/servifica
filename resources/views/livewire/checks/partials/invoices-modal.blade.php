<x-jet-dialog-modal wire:model="invoicesModal" maxWidth="2xl">
    <x-slot name="title">
        Facturas de {{ $supplier }}
    </x-slot>

    <x-slot name="content">
        <div class=" grid grid-cols-2 mb-4 gap-2 text-gray-500">

            {{-- <div>
                <x-jet-label for="value" value="Buscar:" />
                <input type="text" class="block sm:py-0 rounded border-gray-400 w-full text-gray-600" wire:model="search_invoice">
            </div> --}}

            {{-- <div>
                <x-jet-label for="value" value="Valor a cancelar:" />
                <input type="text" class="block sm:py-0 rounded border-gray-400 w-full text-gray-600" wire:model="pay_value">
            </div>                 --}}
        </div>
        <div class="flex flex-col">
            <div class="-my-2 py-2 overflow-x-auto lg:-mx-8 lg:px-8">
                <div class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">
                    <table class="min-w-full text-xs">
                        <thead>
                            <tr>                             
                                <th class="thead">Fecha</th>         
                                <th class="thead">Numero</th>   
                                <th class="thead">Total</th>
                                <th class="thead">Saldo</th>
                                <th class="thead">Estado</th>
                                <th class="thead"></th>                                                              
                            </tr>
                        </thead>
                        <tbody>
                            @if ($expenses)
                                @foreach ($expenses as $item)     
                                @if ($item->state() != 'pagada')                     
                                        <tr>
                                            <td class="row whitespace-nowrap">{{ $item->date }}</td>                               
                                            <td class="row whitespace-nowrap">{{ $item->number }}</td>
                                            <td class="row whitespace-nowrap">$ {{ $item->total }}</td>
                                            <td class="row whitespace-nowrap">$ {{ $item->residue() }}</td>
                                            <td class="row text-center w-full">
                                                @if ($item->state() == 'pendiente')
                                                    <div class="w-full bg-red-500 text-white p-1 rounded-md text-xs">
                                                        <span >{{ $item->state() }}</span>
                                                    </div>
                                                @else
                                                    <div class="w-full bg-blue-500 text-white p-1 rounded-md text-xs">
                                                        <span >{{ $item->state() }}</span>
                                                    </div>
                                                @endif
                                                                                    
                                            </td>
                                            <td class="row">
                                                <button wire:click="selectInvoice('{{ $item->id }}')"
                                                    class="w-full bg-orange-600 items-center px-3 py-3 md:py-1 rounded-md font-semibold text-white shadow-md hover:bg-opacity-80 focus:outline-none focus:ring active:text-gray-700 disabled:opacity-25 transition">
                                                    <span class="text-gray-100">s</span>
                                                </button>    
                                            </td>                                                                                                                        
                                        </tr>  
                                    @endif                                    
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
            <x-jet-secondary-button wire:click="$set('invoicesModal', false)">
                Cerrar
            </x-jet-secondary-button>               
        </div>
    </x-slot>
</x-jet-dialog-modal>