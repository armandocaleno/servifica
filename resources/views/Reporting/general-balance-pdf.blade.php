<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Estado de Situación Inicial</title>        
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
                
        <h1 class="font-bold text-lg text-center">Estado de Situación Inicial</h1>  
        <h2 class="font-bold text-center">{{ $company->businessname }}</h2> 
        <div class=" text-center"> 
            <span class=" font-bold px-2">desde:</span><span>{{ $desde }}</span>   <span class=" font-bold">hasta:</span>  <span>{{ $hasta }}</span>
        </div>  
    </header>        
    
    <div id="content" class="align-middle min-w-full mt-4">
        @php
            $total_activo = 0;
            $total_pasivo = 0;
            $total_patrimonio = 0;
        @endphp
         {{-- activo --}}
         <div class="">
            <table class="w-full text-sm">
                <thead>
                    <tr>
                        <th class=" text-left">Activo</th>
                        <th class=" text-left"></th>
                        <th class=" text-right"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($activo as $item)
                        @php
                            if ($item['nivel'] == 1) {
                                $total_activo = $item['total'];
                            }
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
                        <td class="text-lg font-semibold">Total Activo</td>
                        <td></td>
                        <td class="text-lg text-right font-semibold"> $ @php echo number_format($total_activo, 2); @endphp </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div></div>

        {{-- pasivo --}}
        <div>
            <table class="w-full text-sm">
                <thead>
                    <tr>
                        <th class=" text-left">Pasivo</th>
                        <th class=" text-left"></th>
                        <th class=" text-right"></th>
                    </tr>
                </thead>
                <tbody>
                    
                    @foreach ($pasivo as $item)
                        @php
                            if ($item['nivel'] == 1) {
                                $total_pasivo = $item['total'];
                            }
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
                        <td class=" font-semibold ">Total pasivo</td>
                        <td></td>
                        <td class=" text-right font-semibold"> $ @php echo number_format($total_pasivo, 2); @endphp </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div></div>

        {{-- patrimonio --}}
        <div>
            <table class="w-full text-sm">
                <thead>
                    <tr>
                        <th class=" text-left">Patrimonio</th>
                        <th class=" text-left"></th>
                        <th class=" text-right"></th>
                    </tr>
                </thead>
                <tbody>
                    
                    @foreach ($patrimonio as $item)
                        @php
                            if ($item['nivel'] == 1) {
                                $total_patrimonio = $item['total'];
                            }
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
                        <td class=" font-semibold ">Total patrimonio</td>
                        <td></td>
                        <td class=" text-right font-semibold"> $ @php echo number_format($total_patrimonio, 2); @endphp </td>
                    </tr>
                    <tr>
                        <td class="text-lg font-semibold ">Pasivo + Patrimonio</td>
                        <td></td>
                        <td class="text-lg text-right font-semibold"> $ @php echo number_format($total_pasivo + $total_patrimonio, 2); @endphp </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div></div>
    </div>    
</body>
</html>