<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Estado de Resultados</title>        
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>       
        .text{
            color: #344454;
        }

        .head{
            background-color: #344454;
            color: #f3f4f6;            
        }             

        header h1{
            margin-bottom: 5px;                        
        }                      

        #logo2{
            position: fixed;
            top: -30px;
            width: 6%;
            right: 4px;
        }

        #logo1{
            position: fixed;
            top: -50px;
            width: 15%;   
            left: -15px;         
        }


        #signatures tr td{
            width: 50%;
        }

    </style>
</head>
<body>
    <header>
        <div class="text-left">
            <img src="{{ asset('/images/fondo/fondo.jpg') }}" alt="" id="logo1">
        </div>
                
        <h1 class="font-bold text-lg text-center">Estado de Resultados</h1>  
        <h2 class="font-bold text-center">{{ $company->businessname }}</h2> 
        <div class=" text-center"> 
            <span class=" font-bold px-2">desde:</span><span>{{ $desde }}</span>   <span class=" font-bold">hasta:</span>  <span>{{ $hasta }}</span>
        </div>  
    </header>        
    
    <div id="content" class="align-middle min-w-full mt-4">
        @php
            $total_ingresos = 0;
            $total_otros_ingresos = 0;
            $total_costos = 0;
            $total_gastos = 0;
            $total_impuestos = 0;
        @endphp
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
</body>
</html>