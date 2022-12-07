<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">

        {{-- Fontawesome icons --}}
        <script src="https://kit.fontawesome.com/95cec0f688.js" crossorigin="anonymous"></script>

        {{-- Toast messanges --}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">        

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
        
        {{-- Toast messanges --}}
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

        {{-- Tooltips --}}
        <script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.min.js"></script>
        <script src="https://unpkg.com/tippy.js@6/dist/tippy-bundle.umd.js"></script>

        {{-- Select2 --}}
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> 

    </head>
    <body class="font-sans antialiased">
        <div x-data="{ sidebarOpen: false }" class="flex h-screen bg-gray-100 font-roboto">
            @include('sidebar')
            
            <div class="flex-1 flex flex-col overflow-hidden">
                @include('header')

                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                    <div class="mx-auto px-6 pb-6"> {{-- container --}}
                         <!-- Page Heading -->
                        @if (isset($header))
                            <header>
                                <div class=" mx-auto py-4">
                                    {{ $header }}
                                </div>
                            </header>
                        @endif

                        <!-- Page Content -->
                        @yield('body')
                    </div>
                </main>
            </div>
        </div>

        @stack('modals')

        @livewireScripts

        <script>
            //  Captura un mensaje enviado por el componente y lo muestra en pantalla
            window.addEventListener('success', event => {
                toastr.success(event.detail.message)                                 
            }) 
                        
            window.addEventListener('info', event => {
                // show toast message
                toastr.info(event.detail.message)        
            });  

            // Opciones de mensajes tooltips
            toastr.options = {                                    
                "positionClass": "toast-bottom-right",                                    
                "timeOut": "3000"                  
            }           
        </script>    

        @stack('js')
    </body>
</html>
