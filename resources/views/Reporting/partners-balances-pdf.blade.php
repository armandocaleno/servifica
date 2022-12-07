<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte saldos</title>        
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
            margin-bottom: 30px;                    
        }                

        #logo2{
            position: fixed;
            top: -30px;
            width: 5%;
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
                
        <h1 class="font-bold text-lg text-center">REPORTE DE SALDOS DE SOCIOS</h1>         
    </header>        
    
    <div id="content" class="align-middle min-w-full mt-4">
        <table class="w-full text-sm">
            <thead class="head text-sm rounded">                            
                <th class="thead">Código</th>     
                <th class="thead">Socio</th>   
                @foreach ($accounts as $account)
                    <th class="thead">{{ $account->name }}</th>  
                @endforeach    
            </thead>
            <tbody class="border">
                @foreach ($balances as $item)                           
                    <tr>                                                                     
                        <td class="row whitespace-nowrap px-2">{{ $item->code }}</td>
                        <td class="row whitespace-nowrap px-2">{{ $item->name }} {{ $item->lastname }}</td>   
                        @foreach ($accounts as $account)
                            @php
                                $var = "cuenta".$account->id;
                            @endphp
                            @if ($item->$var == null || $item->$var == 0)
                                <td class="row whitespace-nowrap text-center">-</td> 
                            @else
                                <td class="row whitespace-nowrap px-2 text-right">$ {{ $item->$var }}</td> 
                            @endif
                            
                        @endforeach                                                                                                       
                    </tr>                                   
                @endforeach     
            </tbody>            
        </table>
    </div>    
</body>
</html>