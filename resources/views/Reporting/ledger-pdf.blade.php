<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Libro mayor</title>        
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
                
        <h1 class="font-bold text-lg text-center">Libro mayor</h1>  
        <h2 class="font-bold text-center">{{ $company->businessname }}</h2> 
        <div class=" text-center"> 
            <span class=" font-bold px-2">desde:</span><span>{{ $desde }}</span>   <span class=" font-bold">hasta:</span>  <span>{{ $hasta }}</span>
        </div>  
        <div>
            <span class=" font-bold">Cuenta contable:</span><span class="px-2">{{ $accounting->name }}</span>
        </div>
    </header>        
    
    <div id="content" class="align-middle min-w-full mt-4">
        @php
            $total_debe = 0;
            $total_haber = 0;
            $saldo = 0;
        @endphp

        <div class="">
            <table class="w-full text-sm mb-4">
                <thead>
                    <tr>
                        <th class=" text-left">Fecha</th>
                        <th class=" text-left">Concepto</th>
                        <th class=" text-right">Debe</th>
                        <th class=" text-right">Haber</th>
                        <th class=" text-right">Saldo</th>
                    </tr>
                </thead>
                <tbody>
                    
                    @foreach ($journals as $item)
                        @php
                            $total_debe += $item['debit_value'];
                            $total_haber += $item['credit_value'];
                        @endphp
                        <tr>
                            <td class=" w-28">{{ \Carbon\Carbon::parse($item['date'])->format('d-m-Y') }}</td>
                            <td class=" lowercase">{{ $item['reference'] }}</td>
                            <td class="row text-right whitespace-nowrap">
                                @if ($item['debit_value'] > 0)
                                    $ {{ number_format($item['debit_value'], 2) }}   
                                @else
                                    -
                                @endif
                            </td>
                            <td class="row text-right whitespace-nowrap">                                       
                                @if ($item['credit_value'] > 0)
                                    $ {{ number_format($item['credit_value'], 2) }}   
                                @else
                                    -
                                @endif                                     
                            </td> 
                            <td class=" row text-right whitespace-nowrap"></td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td class="font-semibold">Totales</td>
                        <td class="row text-right font-semibold"> $ @php echo number_format($total_debe, 2); @endphp </td>
                        <td class="row text-right font-semibold"> $ @php echo number_format($total_haber, 2); @endphp </td>
                        <td class="row text-right font-semibold"> $ @php echo number_format($total_debe - $total_haber, 2); @endphp</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div></div>    
    </div>    
</body>
</html>