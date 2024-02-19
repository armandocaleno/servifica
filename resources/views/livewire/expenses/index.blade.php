<div>
    <div class="flex space-x-2 mb-4 ">
        {{-- Search --}}
        <x-jet-input type="text" class="flex-1" wire:model="search"
            placeholder="Buscar por número, proveedor o referencia..." />

        {{-- New input button --}}
        @can('transactions.expenses.create')
            <a href="{{ route('expenses.create') }}"
                class="px-4 py-2 bg-orange-500 rounded-md font-semibold text-white shadow-md hover:bg-opacity-80 focus:outline-none focus:ring active:text-gray-700 disabled:opacity-25 transition">Nuevo</a>
        @endcan
    </div>

    {{-- Table --}}
    <div class="flex flex-col">
        <div class="-my-2 py-2 overflow-x-auto lg:-mx-8 lg:px-8">
            <div
                class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">
                <table class="min-w-full">
                    <thead>
                        <tr>
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

                            <th class="thead">
                                Proveedor
                            </th>

                            <th class="thead">
                                Referencia
                            </th>

                            <th class="thead">
                                Pagos
                            </th>

                            <th class="thead">
                                IVA
                            </th>

                            <th class="thead">
                                Total
                            </th>

                            <th class="thead">
                                Saldo
                            </th>

                            <th class="thead">
                                Estado
                            </th>

                            <th class="thead"></th>
                        </tr>
                    </thead>

                    <tbody class="bg-white">
                        @if ($expenses->count())
                            @foreach ($expenses as $item)
                                <tr>
                                    <td class="row whitespace-nowrap">
                                        {{-- {{ $item->number }} --}}
                                        @php
                                            echo substr($item->number, 0, 3) . '-' . substr($item->number, 3, 3) . '-' . substr($item->number, 6, 15);
                                        @endphp
                                    </td>

                                    <td class="row whitespace-nowrap">
                                        {{ $item->date }}
                                    </td>

                                    <td class="row">
                                        {{ $item->suppliers->name }}
                                    </td>

                                    <td class="row">
                                        {{ $item->reference }}
                                    </td>

                                    <td class="row whitespace-nowrap">
                                        <ul>
                                            @foreach ($item->checks as $check)
                                                <li>Ch. #:{{ $check->number }}</li>
                                                <li>Valor: $ {{ $check->total }}</li>
                                            @endforeach
                                        </ul>
                                    </td>

                                    <td class="row whitespace-nowrap">
                                        $ {{ $item->tax }}
                                    </td>

                                    <td class="row whitespace-nowrap">
                                        $ {{ $item->total }}
                                    </td>

                                    <td class="row whitespace-nowrap">
                                        $ {{ $item->residue() }}
                                    </td>

                                    <td class="row text-center w-full">
                                        @if ($item->totalValueChecks() == $item->total)
                                            <div class="w-full bg-green-500 text-white p-1 rounded-md text-xs">
                                                <span>Pagada</span>
                                            </div>
                                        @else
                                            @if ($item->totalValueChecks($item->id) == 0)
                                                <div class="w-full bg-red-500 text-white p-1 rounded-md text-xs">
                                                    <span>Pendiente</span>
                                                </div>
                                            @else
                                                <div class="w-full bg-blue-500 text-white p-1 rounded-md text-xs">
                                                    <span>Abonada</span>
                                                </div>
                                            @endif
                                        @endif

                                    </td>

                                    <td class="row font-medium">
                                        <div class="flex space-x-4">
                                            @if ($item->totalValueChecks($item->id) == 0)
                                                @can('transactions.expenses.edit')
                                                    <a href="{{ route('expenses.edit', $item) }}"
                                                        class="text-gray-600 hover:opacity-50"><i
                                                            class="fa-solid fa-pen-to-square"></i></a>
                                                @endcan

                                                @can('transactions.expenses.delete')
                                                    <a href="#" wire:click="delete({{ $item }})"
                                                        class="text-orange-500 hover:opacity-50"><i
                                                            class="fa-solid fa-trash-can"></i></a>
                                                @endcan
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6">
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
    @if ($expenses->hasPages())
        <div class="px-4 py-4 bg-white border border-gray-200 mt-2 rounded-md shadow-lg">
            {{ $expenses->links() }}
        </div>
    @endif

    {{-- Delete conformation modal --}}
    <x-jet-confirmation-modal wire:model="confirmingDeletion">
        <x-slot name="title">
            Eliminar compra
        </x-slot>

        <x-slot name="content">
            ¿Estás seguro de eliminar esta compra?.
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
