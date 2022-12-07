<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Recibo</title>        
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
        }       

        #header_table tr td{
            margin: 0;
        }

        #signatures tr td{
            width: 50%;
        }

    </style>
</head>
<body>
    <header>
        <div class="text-left">
            <img src="{{ asset('/images/fondo/fondo.jpg') }}" id="logo1">
        </div>

        <div class="text-right">   
            @php                
                $path = '/images/logo/'.$transaction->company->ruc.'/'.$transaction->company->logo;
            @endphp
            @if ($transaction->company->logo)
                @if (file_exists(public_path($path)))
                    <img src="{{ asset($path) }}" id="logo2">           
                @endif
            @endif
                        
        </div>
                
        <h1 class="font-bold text-lg text-center uppercase">{{ $transaction->company->businessname }}</h1>  
        @if ($transaction->type == 3)
            <h2 class="font-bold text-center uppercase" id="subtitle"> Comprobante de egreso </h2>
        @endif

        <table class="w-full text-sm" id="header_table">
            <tr>
                <td class="font-bold">Recibo No.:</td>
                <td>{{ $transaction->number }}</td>                
                <td class="font-bold">Referencia:</td>
                <td>{{ $transaction->reference }}</td>
            </tr>
            <tr>
                <td class="font-bold">Fecha:</td>
                <td>                    
                    {{ $transaction->date }}
                </td>
                <td class="font-bold">Socio:</td>
                <td>{{ $transaction->partner->code }} - {{ $transaction->partner->name }} {{ $transaction->partner->lastname }} </td>
            </tr>

            @if ($transaction->aditional_info)
                <tr>
                    <td class="font-bold">Banco:</td>
                    <td>                    
                        {{ $transaction->aditional_info['bank'] }}
                    </td>
                    <td class="font-bold">No. cheque:</td>
                    <td>{{ $transaction->aditional_info['check_number'] }}</td>
                </tr>
            @endif
        </table>
    </header>        
    
    <div id="content" class="align-middle min-w-full mt-2">
        <table class="w-full text-sm">
            <thead class="head text-sm rounded">                            
                <th class="px-2 py-2 text-left leading-4">ID</th>
                <th class="px-2 py-2 text-left leading-4">Cuenta</th>
                <th class="px-2 py-2 text-right leading-4">Anterior</th>                                                
                <th class="px-2 py-2 text-right leading-4">Monto</th>   
                <th class="px-2 py-2 text-right leading-4">Actual</th>   
            </thead>
            <tbody class="border">
                @foreach ($transaction->voucher as $item)
                    <tr>                                            
                        <td class="px-2 whitespace-no-wrap border text-center">{{ $item['id'] }}</td>  
                        <td class="px-2 whitespace-no-wrap border">{{ $item['name'] }}</td>
                        <td class="px-2 whitespace-no-wrap border text-right">                            
                            @if ($item['options']['previus'] == 0)
                                -
                            @else
                                $ {{ number_format($item['options']['previus'], 2) }}
                            @endif
                        </td>                        
                        <td class="px-2 whitespace-no-wrap border text-right">
                            @if ($item['price'] == 0)
                                -
                            @else
                                $ {{ number_format($item['price'], 2) }}
                            @endif
                        </td>   
                        <td class="px-2 whitespace-no-wrap border text-right">
                            @if ($item['options']['new'] == 0)
                                -
                            @else
                                $ {{ number_format($item['options']['new'], 2) }}
                            @endif
                        </td>   
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td class="border"></td>                                                          
                    <td class="font-bold px-2 border">TOTAL A PAGAR  =============></td>
                    <td class="border"></td>        
                    <td class="font-bold text-right px-2 border">$ {{ $transaction->total }}</td>
                    <td class="border"></td>  
                </tr>
            </tfoot>
        </table>

        <table class="w-full mt-10" id="signatures">
            <tr>                
                <td class="text-center px-2 border-t">RECAUDADOR</td>
                <td class="text-center px-2 border-t">RECIBÍ CONFORME</td>                
            </tr>
        </table>
    </div>    
</body>
</html>