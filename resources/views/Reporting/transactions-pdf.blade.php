<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte transacciones</title>        
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
            margin-bottom: 20px;                        
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

        {{-- <div class="text-right">
            <img src="{{ asset('/images/fondo/logo2.jpeg') }}" alt="" id="logo2">
        </div> --}}
                
        <h1 class="font-bold text-lg text-center">REPORTE DE TRANSACCIONES</h1>  

        <table class="w-full text-sm">
            <tr>
                <td class="font-bold">Desde:</td>
                <td>{{ $from }}</td>                
                <td class="font-bold">Hasta:</td>
                <td>{{ $to }}</td>
                <td class="font-bold">Socio:</td>
                <td>{{ $partner }}</td>
            </tr>            
        </table>
    </header>        
    
    <div id="content" class="align-middle min-w-full mt-4">
        <table class="w-full text-sm">
            <thead class="head text-sm rounded">                            
                <th class="px-2 py-2 text-left leading-4">Número</th>
                <th class="px-2 py-2 text-left leading-4">Fecha</th>
                <th class="px-2 py-2 text-left leading-4">Tipo</th>
                <th class="px-2 py-2 text-left leading-4">Socio</th>                                                
                <th class="px-2 py-2 text-left leading-4">Rubros</th>
                <th class="px-2 py-2 text-right leading-4">Referencia</th>   
                <th class="px-2 py-2 text-right leading-4">Total</th>   
            </thead>
            <tbody class="border">
                @foreach ($transactions as $item)
                    <tr class="border">                                            
                        <td class="px-2 whitespace-no-wrap">{{ $item->number }}</td>
                        <td class="px-2 whitespace-no-wrap">{{ \Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
                        <td class="px-2 whitespace-no-wrap">
                            @if ($item->type == 1)
                                Individual
                            @elseif ($item->type == 2)
                                Lotes
                            @else
                                Pago
                            @endif
                        </td>
                        <td class="px-2 whitespace-no-wrap">{{ $item->name }} {{ $item->lastname }}</td>    
                        <td class="row whitespace-nowrap">
                            <ul>
                                @foreach ($item->content as $i)
                                    <li>{{ $i['name'] }}</li>
                                @endforeach
                            </ul>
                        </td>                    
                        <td class="px-2 whitespace-no-wrap">{{ $item->reference }}</td>   
                        <td class="px-2 whitespace-no-wrap text-right">$ {{ $item->total }}</td>   
                    </tr>
                @endforeach
            </tbody>        
        </table>    
    </div>    
</body>
</html>