<div>
    @php
        $total_activo = 0;
        $total_pasivo = 0;
        $total_patrimonio = 0;
    @endphp


    <div class="mb-4">
        <form action="{{ route('reporting.balance.pdf') }}" target="_blank">
            @csrf
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-6 gap-4">
                {{-- from --}}
                <div>
                    <x-jet-label value="Desde" />
                    <input type="date" name="from" class="block py-0 px-4 rounded -gray-400 w-full text-gray-700"
                        wire:model="from">
                </div>

                {{-- to --}}
                <div>
                    <x-jet-label value="Hasta" />
                    <input type="date" name="to" class="block py-0 rounded -gray-400 w-full text-gray-700"
                        wire:model="to">
                </div>

                {{-- TYPE --}}
                <div class="">
                    <x-jet-label for="type" value="Tipo" />
                    <div>
                        <select name="type" class="py-0 rounded border-gray-400 w-full text-gray-600" id="type">
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                        </select>
                    </div>
                </div>

                {{-- LEVEL --}}
                <div class="">
                    <x-jet-label for="level" value="Niveles" />
                    <div>
                        <select name="level" class="py-0 rounded border-gray-400 w-full text-gray-600" id="level"
                            wire:model="level">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>
                </div>

                @can('accounting.sfp.export')
                    <button type="submit"
                        class="px-4 py-2 mt-2 bg-orange-500 rounded-md font-semibold text-white shadow-md hover:bg-opacity-80 
                                            focus:outline-none focus:ring active:text-gray-700 disabled:opacity-25 transition">Exportar
                    </button>
                @endcan
            </div>
        </form>
    </div>

    <div class=" grid grid-cols-2 gap-4">
        <div>
            {{-- Activo --}}
            <table class="w-full text-sm">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Cuenta</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($activo as $item)
                        @php
                            if ($item['nivel'] == 1) {
                                $total_activo = $item['total'];
                            }
                        @endphp

                        @if ($item['nivel'] <= $level)
                            <tr :class="{{ $item['grupo'] }} ? 'uppercase font-semibold' : ' capitalize'">
                                <td class=" w-28" :class="{{ $item['grupo'] }} ? '' : ' pl-2'">{{ $item['codigo'] }}</td>
                                <td class=" " :class="{{ $item['grupo'] }} ? '' : ' pl-2'">{{ $item['cuenta'] }}</td>
                                <td class=" w-28 text-right">$ {{ number_format($item['total'], 2) }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td class=" text-lg font-semibold">Total Activo</td>
                        <td class=" text-lg text-right font-semibold"> $ @php echo number_format($total_activo, 2); @endphp </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class=" mb-4">
            {{-- Pasivo --}}
            <table class="w-full text-sm mb-4">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Cuenta</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($pasivo as $item)
                        @php
                            if ($item['nivel'] == 1) {
                                $total_pasivo = $item['total'];
                            }

                        @endphp
                        @if ($item['nivel'] <= $level)
                            <tr :class="{{ $item['grupo'] }} ? 'uppercase font-semibold' : ' capitalize'">
                                <td class=" w-28" :class="{{ $item['grupo'] }} ? '' : ' pl-2'">{{ $item['codigo'] }}</td>
                                <td class=" " :class="{{ $item['grupo'] }} ? '' : ' pl-2'">{{ $item['cuenta'] }}</td>
                                <td class=" w-28 text-right">$ {{ number_format($item['total'], 2) }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td class=" font-semibold">Total Pasivo</td>
                        <td class=" text-right font-semibold"> $ @php echo number_format($total_pasivo, 2); @endphp </td>
                    </tr>
                </tfoot>
            </table>

            {{-- Patrimonio --}}
            <table class="w-full text-sm">
                <tbody>

                    @foreach ($patrimonio as $item)
                        @php
                            if ($item['nivel'] == 1) {
                                $total_patrimonio = $item['total'];
                            }
                        @endphp

                        @if ($item['nivel'] <= $level)
                            <tr :class="{{ $item['grupo'] }} ? 'uppercase font-semibold' : ' capitalize'">
                                <td class=" w-28" :class="{{ $item['grupo'] }} ? '' : ' pl-2'">{{ $item['codigo'] }}</td>
                                <td class=" " :class="{{ $item['grupo'] }} ? '' : ' pl-2'">{{ $item['cuenta'] }}</td>
                                <td class=" w-28 text-right">$ {{ number_format($item['total'], 2) }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td class=" font-semibold ">Total patrimonio</td>
                        <td class=" text-right font-semibold"> $ @php echo number_format($total_patrimonio, 2); @endphp </td>
                    </tr>

                    <tr>
                        <td></td>
                        <td class=" text-lg font-semibold ">Pasivo + Patrimonio</td>
                        <td class=" text-lg text-right font-semibold"> $ @php echo number_format($total_pasivo + $total_patrimonio, 2); @endphp </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
