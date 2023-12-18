<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Comprobante</title>        
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>       
        .text{
            color: #344454;
        }

        .head{
            background-color: #344454;
            color: #f3f4f6;            
        }             

        #subtitle{
            margin-bottom: 5px;
            margin-top: -8px;         
        }        
        
        #logo2{
            position: fixed;
            top: -30px;
            width: 10%;
            right: 4px;
        }

        #logo1{
            position: fixed;
            top: -40px;
            width: 20%;   
            left: -5px;   
            margin-bottom: 10px;      
        }       

        #header_table{
            margin-top: 15px;
            border-collapse: separate;
            border-spacing: 10px 0px;
        }

        #signatures{
            margin-top: 60px !important;
        }

        #signatures tr td{
            width: 50%;
            margin-top: 80px !important;
        }

    </style>
</head>
<body>
    <header>
        <div class="text-left">
            <img src="{{ asset('/images/fondo/fondo.jpg') }}" id="logo1">
        </div>
        {{-- <div class="text-right">
            @php
            $fecha_actual = date("d-m-Y h:i:s");
                echo  $fecha_actual;
            @endphp
        </div> --}}

        <div class="text-right">   
            @php                
                $path = '/images/logo/'. $company->ruc.'/'.$company->logo;
            @endphp
            @if ($company->logo)
                @if (file_exists(public_path($path)))
                    <img src="{{ asset($path) }}" id="logo2">           
                @endif
            @endif
            
        </div>

        <table class=" text-sm mb-2" id="header_table">
            <tr>
                <td class="font-bold">Asiento:</td>
                <td> {{ $journal->number }}</td>             
                <td class="font-bold">Concepto:</td>
                <td>{{ $journal->refence }}</td>
            </tr>
            <tr>
                <td class="font-bold">Fecha:</td>
                <td>{{ $journal->date }}</td>
            </tr>
        </table>
    </header>        
    
    <div id="content" class="align-middle min-w-full mt-2">
        <table class="w-full text-sm">
            <thead class="head text-sm rounded">                            
                <th class="px-2 py-2 text-left leading-4">Código</th>
                <th class="px-2 py-2 text-left leading-4">Cuenta</th>
                <th class="px-2 py-2 text-right leading-4">Debe</th>                                                
                <th class="px-2 py-2 text-right leading-4">Haber</th>    
            </thead>
            <tbody class="border">
                @php
                    $total_debe = 0;
                    $total_haber = 0;
                @endphp
                @foreach ($journal->details as $item)
                    <tr>                                            
                        <td class="px-2 border">{{ $item->accounting->code }}</td>  
                        <td class="px-2 whitespace-no-wrap border">{{ $item->accounting->name }}</td>
                        <td class="px-2 whitespace-no-wrap border text-right">                            
                            @if ($item->debit_value == 0)
                                -
                            @else
                                $ {{ number_format($item->debit_value, 2) }}
                                @php $total_debe += $item->debit_value; @endphp
                            @endif
                        </td>                        
                        <td class="px-2 whitespace-no-wrap border text-right">
                            @if ($item->credit_value == 0)
                            -
                        @else
                            $ {{ number_format($item->credit_value, 2) }}
                            @php $total_haber += $item->credit_value; @endphp
                        @endif
                    </tr>
                @endforeach
                
            </tbody>
            <tfoot>
                <tr>
                    <td class="font-bold px-2 border" colspan="2">TOTALES DE MOVIMIENTOS:</td>                                                          
                    {{-- <td class="border"></td> --}}
                    <td class="font-bold text-right px-2 border">$ {{ number_format($total_debe, 2) }}</td>    
                    <td class="font-bold text-right px-2 border">$ {{ number_format($total_haber, 2) }}</td> 
                </tr>
            </tfoot>
        </table>

        <table class="w-full mt-10" id="signatures">
            <tr>                
                <td class="text-center px-2 border-t">PRESIDENTE</td>
                <td class="text-center px-2 border-t">GERENTE</td>   
                <td class="text-center px-2 border-t">PRES. CONC. VIG.</td>
                <td class="text-center px-2 border-t">RECIBI CONFORME</td>             
            </tr>
        </table>
    </div>    
</body>
</html>