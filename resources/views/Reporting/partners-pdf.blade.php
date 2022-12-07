<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de socios</title>        
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
                
        <h1 class="font-bold text-lg text-center">REPORTE DE SOCIOS</h1>        
    </header>        
    
    <div id="content" class="align-middle min-w-full mt-4">
        <table class="w-full text-sm">
            <thead class="head text-sm rounded">                            
                <th class="px-2 py-2 text-left leading-4">Código</th>
                <th class="px-2 py-2 text-left leading-4">Identificación</th>
                <th class="px-2 py-2 text-left leading-4">Nombres</th>
                <th class="px-2 py-2 text-left leading-4">Apellidos</th>                                                
                <th class="px-2 py-2 text-left leading-4">Teléfono</th>                                 
                <th class="px-2 py-2 text-left leading-4">Email</th>
            </thead>
            <tbody class="border">
                @foreach ($partners as $item)
                    <tr class="border">                                            
                        <td class="px-2 whitespace-no-wrap">{{ $item->code }}</td>
                        <td class="px-2 whitespace-no-wrap">{{ $item->identity }}</td>
                        <td class="px-2 whitespace-no-wrap">{{ $item->name }}</td>
                        <td class="px-2 whitespace-no-wrap">{{ $item->lastname }}</td>                                             
                        <td class="px-2 whitespace-no-wrap">{{ $item->phone }}</td>                           
                        <td class="px-2 whitespace-no-wrap">{{ $item->email }}</td>
                    </tr>
                @endforeach
            </tbody>        
        </table>    
    </div>    
</body>
</html>