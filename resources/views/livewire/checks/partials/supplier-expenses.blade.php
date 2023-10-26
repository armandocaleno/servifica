<p class=" mb-2">Facturas a pagar</p>
<div class=" grid grid-cols-2 mb-2">
    <div class=" flex items-center space-x-2">
        <button wire:click="openSuppliersModal"
            class="bg-orange-600 items-center px-3 py-3 md:py-1 rounded-md font-semibold text-white shadow-md hover:bg-opacity-80 focus:outline-none focus:ring active:text-gray-700 disabled:opacity-25 transition">
            <span class="text-gray-100">Proveedor</span>
        </button>
    
        <button wire:click="openInvoicesModal"
            class="bg-orange-600 items-center px-3 py-3 md:py-1 rounded-md font-semibold text-white shadow-md hover:bg-opacity-80 focus:outline-none focus:ring active:text-gray-700 disabled:opacity-25 transition">
            <span class="text-gray-100">Facturas</span>
        </button>

        {{-- <div class=""> --}}
            <x-jet-label for="supplier" value="Proveedor"/>
            <input type="text" class="block sm:py-0 rounded border-gray-400 w-full text-gray-600" disabled wire:model="supplier">
            <x-jet-input-error for="supplier" class="mt-2" /> 
        {{-- </div> --}}
        
    </div>
</div>

<div>
    {{-- Table --}}
    <div class="flex flex-col">
        <div class="-my-2 py-2 overflow-x-auto lg:-mx-8 lg:px-8">
            <div
                class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="thead w-32">Fecha</th>
                            <th class="thead w-32 ">Numero</th>
                            <th class="thead">Referencia</th>
                            <th class="thead w-32 text-right">Valor a pagar</th>
                            <th class="thead w-32 text-right">Total</th>
                            <th class="thead w-32 "></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @if (Cart::instance('expenses')->count())
                            @foreach (Cart::instance('expenses')->content() as $item)
                                <tr>
                                    <td class="row whitespace-nowrap lowercase">{{ $item->options['date'] }}</td>

                                    <td class="row whitespace-nowrap lowercase">{{ $item->name }}</td>

                                    <td class="row whitespace-nowrap lowercase">{{ $item->options['reference'] }}</td>
                                   
                                    <td class="row whitespace-nowrap text-right flex items-center space-x-2">
                                        $ <input type="text" class="block sm:py-0 rounded border-gray-400 w-full text-gray-600 payment" onchange="changePayValue('{{ $item->rowId }}', '{{ $item->id }}')" id="{{ $item->id }}pay_value" value="{{ $item->price }}">
                                    </td>
                                    <td class="row whitespace-nowrap text-right">
                                        $ {{ $item->options['total']  }}
                                    </td>
                                    <td class="row whitespace-nowrap text-center">
                                        <button wire:click="unsetExpense('{{ $item->rowId }}')"
                                            class="hover:opacity-25 text-orange-600"><i class="fa-solid fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="text-center text-gray-400">No ha seleccionado ninguna factura
                                </td>
                            </tr>
                        @endif
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="row">Total</td>
                            <td class="row text-right">$ {{ Cart::instance('expenses')->subtotal }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>