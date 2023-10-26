<div>
    @php
        $total_ingresos = 0;
        $total_otros_ingresos = 0;
        $total_costos = 0;
        $total_gastos = 0;
        $total_impuestos = 0;
    @endphp

    <div class="mb-4">
        <form action="{{ route('reporting.results.pdf') }}" target="_blank">
            @csrf
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-6 gap-4">
                {{-- from --}}
                <div>
                    <x-jet-label value="Desde" />                
                    <input type="date" name="from" class="block py-0 px-4 rounded -gray-400 w-full text-gray-700" wire:model="from">
                </div>
                
                {{-- to --}}
                <div>
                    <x-jet-label value="Hasta" />                
                    <input type="date" name="to" class="block py-0 rounded -gray-400 w-full text-gray-700" wire:model="to">
                </div>

                {{-- TYPE --}}
                <div class="">
                    <x-jet-label for="type" value="Tipo"/>
                    <div>
                        <select name="type" class="py-0 rounded border-gray-400 w-full text-gray-600" id="type">
                            <option value="pdf">PDF</option>                            
                            <option value="excel">Excel</option>  
                        </select> 
                    </div>                                                    
                </div>  
                @can('accounting.balance.export')
                    
                
                <button type="submit" class="px-4 py-2 mt-2 bg-orange-500 rounded-md font-semibold text-white shadow-md hover:bg-opacity-80 
                                            focus:outline-none focus:ring active:text-gray-700 disabled:opacity-25 transition"
                                            >Exportar
                </button>
                @endcan
            </div>
        </form>
    </div>

    <div class="grid grid-cols-2 gap-4">
        {{-- ingresos --}}
        <div class="">
            <table class="w-full text-sm">
                <thead>
                    <tr>
                        <th class=" text-left">Ingresos</th>
                        <th class=" text-left"></th>
                        <th class=" text-right"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ingresos as $item)
                        @php
                        // if ($item['nivel'] == 1) {
                        //     $total_ingresos = $item['total'];
                        // }
                        $total_ingresos += $item['total'];
                        @endphp
                        <tr>
                            <td class=" w-28">{{ $item['codigo'] }}</td>
                            <td class=" lowercase">{{ $item['cuenta'] }}</td>
                            <td class=" text-right">$ {{ number_format($item['total'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td class=" font-semibold">Total Ingresos</td>
                        <td></td>
                        <td class=" text-right font-semibold"> $ @php echo number_format($total_ingresos, 2); @endphp </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div></div>

        {{-- costos --}}
        <div>
            <table class="w-full text-sm">
                <thead>
                    <tr>
                        <th class=" text-left">Costo de ventas</th>
                        <th class=" text-left"></th>
                        <th class=" text-right"></th>
                    </tr>
                </thead>
                <tbody>
                    
                    @foreach ($costos as $item)
                        @php
                        // if ($item['nivel'] == 1) {
                        //     $total_costos = $item['total'];
                        // }
                        $total_costos += $item['total'];
                        @endphp
                        <tr class="">
                            <td class=" w-28">{{ $item['codigo'] }}</td>
                            <td class=" lowercase">{{ $item['cuenta'] }}</td>
                            <td class=" text-right">$ {{ number_format($item['total'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td class=" font-semibold ">Total costos</td>
                        <td></td>
                        <td class=" text-right font-semibold"> $ @php echo number_format($total_costos, 2); @endphp </td>
                    </tr>
                    <tr>
                        <td class="text-lg font-semibold ">Utilidad bruta</td>
                        <td></td>
                        <td class="text-lg text-right font-semibold"> $ @php echo number_format($total_ingresos - $total_costos, 2); @endphp </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div></div>

        {{-- gastos --}}
        <div>
            <table class="w-full text-sm">
                <thead>
                    <tr>
                        <th class=" text-left">Gastos</th>
                        <th class=" text-left"></th>
                        <th class=" text-right"></th>
                    </tr>
                </thead>
                <tbody>
                    
                    @foreach ($gastos as $item)
                        @php
                        // if ($item['nivel'] == 1) {
                        //     $total_gastos = $item['total'];
                        // }
                        $total_gastos += $item['total'];
                        @endphp
                        <tr class="">
                            <td class=" w-28">{{ $item['codigo'] }}</td>
                            <td class=" lowercase">{{ $item['cuenta'] }}</td>
                            <td class=" text-right">$ {{ number_format($item['total'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td class=" font-semibold ">Total gastos</td>
                        <td></td>
                        <td class=" text-right font-semibold"> $ @php echo number_format($total_gastos, 2); @endphp </td>
                    </tr>
                    <tr>
                        <td class="text-lg font-semibold ">Utilidad operativa</td>
                        <td></td>
                        <td class="text-lg text-right font-semibold"> $ @php echo number_format($total_ingresos - $total_costos - $total_gastos, 2); @endphp </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div></div>

        {{-- otros ingresos --}}
        <div class="">
            {{-- Pasivo --}}
            <table class="w-full text-sm mb-4">
                <thead>
                    <tr>
                        <th class=" text-left">Otros Ingresos</th>
                        <th class=" text-left"></th>
                        <th class=" text-right"></th>
                    </tr>
                </thead>
                <tbody>
                    
                    @foreach ($otros_ingresos as $item)
                        @php
                        // if ($item['nivel'] == 1) {
                        //     $total_otros_ingresos = $item['total'];
                        // }
                        $total_otros_ingresos += $item['total'];
                        @endphp
                        <tr>
                            <td class=" w-28">{{ $item['codigo'] }}</td>
                            <td class=" lowercase">{{ $item['cuenta'] }}</td>
                            <td class=" text-right">$ {{ number_format($item['total'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td class=" font-semibold">Total otros ingresos</td>
                        <td></td>
                        <td class=" text-right font-semibold"> $ @php echo number_format($total_otros_ingresos, 2); @endphp </td>
                    </tr>
                    <tr>
                        <td class="text-lg font-semibold ">Utilidad antes de impuestos</td>
                        <td></td>
                        <td class="text-lg text-right font-semibold"> $ @php echo number_format($total_ingresos - $total_costos - $total_gastos + $total_otros_ingresos, 2); @endphp </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div></div>

        {{-- impuestos --}}
        <div class="">
            <table class="w-full text-sm mb-4">
                <thead>
                    <tr>
                        <th class=" text-left">Impuestos</th>
                        <th class=" text-left"></th>
                        <th class=" text-right"></th>
                    </tr>
                </thead>
                <tbody>
                    
                    @foreach ($impuestos as $item)
                        @php
                        // if ($item['nivel'] == 1) {
                        //     $total_impuestos = $item['total'];
                        // }
                        $total_impuestos += $item['total'];
                        @endphp
                        <tr>
                            <td class=" w-28">{{ $item['codigo'] }}</td>
                            <td class=" lowercase">{{ $item['cuenta'] }}</td>
                            <td class=" text-right">$ {{ number_format($item['total'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td class=" font-semibold">Total impuestos</td>
                        <td></td>
                        <td class=" text-right font-semibold"> $ @php echo number_format($total_impuestos, 2); @endphp </td>
                    </tr>
                    <tr>
                        <td class="text-lg font-semibold ">Utilidad neta</td>
                        <td></td>
                        <td class="text-lg text-right font-semibold"> $ @php echo number_format($total_ingresos - $total_costos - $total_gastos + $total_otros_ingresos - $total_impuestos, 2); @endphp </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div></div>
    </div>
</div>
