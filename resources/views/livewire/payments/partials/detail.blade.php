<p class=" mb-2">Cuentas contables</p>
<div class="bg-gray-50 border-2 p-4">
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4 mb-4">

        <div class="flex items-center space-x-2 md:col-span-2">
            <x-jet-label value="Cuentas:" />
            <div wire:ignore class="flex-1">
                <select name="" id="accounts_select" class="shadow-md z-10 w-full">
                    <option value="-1"></option>
                    @foreach ($accountings as $item)
                        <option value="{{ $item->id }}">{{ $item->code }} - {{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex items-center space-x-2">
            <x-jet-label value="Valor:" />
            <x-jet-input type="text" wire:model.defer="value"
                class="text-center w-full md:py-0 rounded border-gray-400 text-gray-700" />
        </div>

        <div class="">
            <button wire:click="addAccount"
                class="w-full bg-orange-600 items-center px-3 py-3 md:py-1 rounded-md font-semibold text-white shadow-md hover:bg-opacity-80 focus:outline-none focus:ring active:text-gray-700 disabled:opacity-25 transition">
                <span class="text-gray-100">Añadir</span>
            </button>
        </div>
    </div>

    {{-- Table --}}
    <div class="flex flex-col">
        <div class="-my-2 py-2 overflow-x-auto lg:-mx-8 lg:px-8">
            <div
                class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="thead">Cuenta</th>
                            <th class="thead w-32 text-right">Haber</th>
                            <th class="thead w-32"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @if (Cart::instance('new_journal')->count())
                            @foreach (Cart::instance('new_journal')->content() as $item)
                                <tr>
                                    <td class="row whitespace-nowrap lowercase">{{ $item->name }}</td>
                                   
                                    <td class="row whitespace-nowrap text-right">
                                        $ {{ $item->subtotal() }}
                                    </td>
                                    <td class="row whitespace-nowrap text-center">
                                        <button wire:click="unsetAccount('{{ $item->rowId }}')"
                                            class="hover:opacity-25 text-orange-600"><i class="fa-solid fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3" class="text-center text-gray-400">No ha seleccionado ninguna cuenta
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-center">
                                    <x-jet-input-error for="transaction.content" class="mt-2" />
                                </td>
                            </tr>
                        @endif
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="row">Total</td>
                            <td class="row text-right">$ {{ Cart::instance('new_journal')->subtotal }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

</div>
