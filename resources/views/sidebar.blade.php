<div x-cloak :class="sidebarOpen ? 'block' : 'hidden'" @click="sidebarOpen = false" class="fixed inset-0 z-20 transition-opacity bg-blue-900 opacity-50 lg:hidden"></div>
    
<div :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'" class="fixed inset-y-0 left-0 z-30 w-60 overflow-y-auto transition duration-300 transform bg-blue-950 lg:translate-x-0 lg:static lg:inset-0"> {{-- x-cloak --}}
                    
    {{-- Logo section --}}
    <div class="flex items-center justify-center mb-4 mt-16 bg-white">
        <div class="flex items-center justify-center">
            {{-- logo --}}
            <img src="{{ asset('/images/fondo/fondo.png') }}" style="width: 60%" alt="logo">
            
        </div>
    </div>
    
    {{-- User information --}}
    {{-- <div class="mb-4">
        <div class="flex items-center">
            <div class="w-12 h-12 overflow-hidden rounded-full shadow focus:outline-none border-2 border-gray-100 mx-2">
                <img class="object-cover w-full h-full" src="{{ auth()->user()->profile_photo_url }}" alt="Your avatar">        
            </div>
            <div class="flex flex-col text-sm">
                <a class="text-gray-400">Bienvenido (a)</a>
                <a class="text-gray-100">{{ auth()->user()->name }}</a>
            </div>        
        </div>
    </div> --}}

    {{-- Menu --}}
    <nav class="mt-4 px-2">
                         
        <a class="flex items-center py-2 mt-4 text-gray-100 bg-gray-700 bg-opacity-25" href="{{ route('welcome') }}">
            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
            </svg>            
            <span class="mx-3">Inicio</span>
        </a>        
        {{-- Menú trasacciones --}}
        <div class="flex flex-col">
            <a onclick="showMenu1(true)" class="flex items-center justify-between py-2 mt-2 text-gray-300 hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100" href="#">
                <div class="flex">                                       
                    <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="0 0 172 172">
                        <g transform="translate(0.516,0.516) scale(0.994,0.994)">
                        <g fill="none" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="none" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal">
                        <g fill="currentColor" stroke="currentColor" stroke-linejoin="round">
                            <path d="M43,100.33333c0,4.04938 3.11728,7.16667 7.16667,7.16667h100.33333v14.33333h-100.33333c-11.78895,0 -21.5,-9.71105 -21.5,-21.5v-71.63867l-14.23535,0.06999l-0.06999,-14.33333l28.63867,-0.12598zM143.33333,21.5v43h-7.16667v28.66667h-78.83333v-43h14.33333v-28.66667zM86,50.16667h43v-14.33333h-43zM71.66667,78.83333h50.16667v-14.33333h-50.16667zM71.66667,143.33333c0,7.91608 -6.41725,14.33333 -14.33333,14.33333c-7.91608,0 -14.33333,-6.41725 -14.33333,-14.33333c0,-7.91608 6.41725,-14.33333 14.33333,-14.33333c7.91608,0 14.33333,6.41725 14.33333,14.33333zM143.33333,143.33333c0,7.91608 -6.41725,14.33333 -14.33333,14.33333c-7.91608,0 -14.33333,-6.41725 -14.33333,-14.33333c0,-7.91608 6.41725,-14.33333 14.33333,-14.33333c7.91608,0 14.33333,6.41725 14.33333,14.33333z"></path></g><path d="M0,172v-172h172v172z" fill="none" stroke="none" stroke-linejoin="miter"></path><g fill="currentColor" stroke="none" stroke-linejoin="miter"><path d="M43,14.30534l-28.63867,0.12598l0.06999,14.33333l14.23535,-0.06999v71.63867c0,11.78895 9.71105,21.5 21.5,21.5h100.33333v-14.33333h-100.33333c-4.04938,0 -7.16667,-3.11728 -7.16667,-7.16667zM71.66667,21.5v28.66667h-14.33333v43h78.83333v-28.66667h7.16667v-43zM86,35.83333h43v14.33333h-43zM71.66667,64.5h50.16667v14.33333h-50.16667zM57.33333,129c-7.91608,0 -14.33333,6.41725 -14.33333,14.33333c0,7.91608 6.41725,14.33333 14.33333,14.33333c7.91608,0 14.33333,-6.41725 14.33333,-14.33333c0,-7.91608 -6.41725,-14.33333 -14.33333,-14.33333zM129,129c-7.91608,0 -14.33333,6.41725 -14.33333,14.33333c0,7.91608 6.41725,14.33333 14.33333,14.33333c7.91608,0 14.33333,-6.41725 14.33333,-14.33333c0,-7.91608 -6.41725,-14.33333 -14.33333,-14.33333z"></path></g><path d="" fill="currentColor" stroke="none" stroke-linejoin="miter"></path>
                        </g>
                        </g>
                    </svg>                    
                    <span class="mx-3">Transacciones</span>
                </div>
            
                <svg id="icon1" class="transform rotate-180" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M18 15L12 9L6 15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </a>
            
            <div id="menu1" class="hidden flex flex-col w-full pb-1 bg-gray-700 border-r-4 bg-opacity-40 border-orange-400">
                <div class="">
                                                          
                    <a href="{{ route('transactions.index') }}" class="flex items-center space-x-6 pl-10 hover:text-white focus:bg-gray-700 focus:text-white hover:bg-gray-700 hover:bg-opacity-25 text-gray-300 rounded py-1 w-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 512 512" style=" fill:currentColor;">                            
                            <path d="M40 48C26.7 48 16 58.7 16 72v48c0 13.3 10.7 24 24 24H88c13.3 0 24-10.7 24-24V72c0-13.3-10.7-24-24-24H40zM192 64c-17.7 0-32 14.3-32 32s14.3 32 32 32H480c17.7 0 32-14.3 32-32s-14.3-32-32-32H192zm0 160c-17.7 0-32 14.3-32 32s14.3 32 32 32H480c17.7 0 32-14.3 32-32s-14.3-32-32-32H192zm0 160c-17.7 0-32 14.3-32 32s14.3 32 32 32H480c17.7 0 32-14.3 32-32s-14.3-32-32-32H192zM16 232v48c0 13.3 10.7 24 24 24H88c13.3 0 24-10.7 24-24V232c0-13.3-10.7-24-24-24H40c-13.3 0-24 10.7-24 24zM40 368c-13.3 0-24 10.7-24 24v48c0 13.3 10.7 24 24 24H88c13.3 0 24-10.7 24-24V392c0-13.3-10.7-24-24-24H40z"/>
                        </svg>
                        <p class="text-base leading-4">Listado de transacciones</p>
                    </a>                             
                                                                                                              
                    <a href="{{ route('transactions.lotes') }}" class="flex items-center space-x-6 pl-10 hover:text-white focus:bg-gray-700 focus:text-white hover:bg-gray-700 hover:bg-opacity-25 text-gray-300 rounded px-3 py-1 w-full">
                        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="0 0 172 172" style=" fill:currentColor;">
                            <path d="M93.16667,21.5v86h14.33333l-21.5,21.5l-21.5,-21.5h14.33333v-86zM50.16667,47.19922v31.63411h14.33333l-21.5,21.5l-21.5,-21.5h14.33333v-25.69922l-19.40039,-19.40039l10.13411,-10.13411zM155.56706,33.73372l-19.40039,19.40039v25.69922h14.33333l-21.5,21.5l-21.5,-21.5h14.33333v-31.63411l23.59961,-23.59961zM28.66667,121.83333v21.5h114.66667v-21.5h14.33333v21.5c0,7.83362 -6.49972,14.33333 -14.33333,14.33333h-114.66667c-7.83362,0 -14.33333,-6.49972 -14.33333,-14.33333v-21.5z"></path>
                        </svg>
                        <p class="text-base leading-4">Por lotes</p>
                    </a>   
                    
                    <a href="{{ route('transactions.payment.create') }}" class="flex items-center space-x-6 pl-10 hover:text-white focus:bg-gray-700 focus:text-white hover:bg-gray-700 hover:bg-opacity-25 text-gray-300 rounded px-3 py-1 w-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 576 512" style=" fill:currentColor;">
                            <path d="M64 64C28.7 64 0 92.7 0 128V384c0 35.3 28.7 64 64 64H512c35.3 0 64-28.7 64-64V128c0-35.3-28.7-64-64-64H64zm64 320H64V320c35.3 0 64 28.7 64 64zM64 192V128h64c0 35.3-28.7 64-64 64zM448 384c0-35.3 28.7-64 64-64v64H448zm64-192c-35.3 0-64-28.7-64-64h64v64zM400 256c0 61.9-50.1 112-112 112s-112-50.1-112-112s50.1-112 112-112s112 50.1 112 112zM252 208c0 9.7 6.9 17.7 16 19.6V276h-4c-11 0-20 9-20 20s9 20 20 20h24 24c11 0 20-9 20-20s-9-20-20-20h-4V208c0-11-9-20-20-20H272c-11 0-20 9-20 20z"/>
                        </svg>
                        <p class="text-base leading-4">Pagos</p>
                    </a> 
                </div>
            </div>
        </div>       

        {{-- Menú reportes --}}
        <div class="flex flex-col">
            <a onclick="showMenu3(true)" class="flex items-center justify-between py-2 mt-2 text-gray-300 hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100" href="#">
                <div class="flex">                                       
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>                  
                    <span class="mx-3">Reportes</span>
                </div>
            
                <svg id="icon3" class="transform rotate-180" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M18 15L12 9L6 15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </a>
            
            <div id="menu3" class="hidden flex flex-col w-full pb-1 bg-gray-600 border-r-4 border-orange-400">
                <div class="">    
                                                                        
                    <a href="{{ route('reporting.partners.balances') }}" class="flex items-center space-x-6 pl-10 hover:text-white focus:bg-gray-700 focus:text-white hover:bg-gray-500 text-gray-400 rounded px-3 py-2 w-full">                        
                        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="0 0 172 172" style=" fill:currentColor;">
                            <path d="M129,14.33333c7.83362,0 14.33333,6.49972 14.33333,14.33333v114.66667c0,7.83362 -6.49972,14.33333 -14.33333,14.33333h-86c-7.83362,0 -14.33333,-6.49972 -14.33333,-14.33333v-7.16667h-7.16667c-3.956,0 -7.16667,-3.21067 -7.16667,-7.16667c0,-3.956 3.21067,-7.16667 7.16667,-7.16667h7.16667v-14.33333h-7.16667c-3.956,0 -7.16667,-3.21067 -7.16667,-7.16667c0,-3.956 3.21067,-7.16667 7.16667,-7.16667h7.16667v-14.33333h-7.16667c-3.956,0 -7.16667,-3.21067 -7.16667,-7.16667c0,-3.956 3.21067,-7.16667 7.16667,-7.16667h7.16667v-14.33333h-7.16667c-3.956,0 -7.16667,-3.21067 -7.16667,-7.16667c0,-3.956 3.21067,-7.16667 7.16667,-7.16667h7.16667v-7.16667c0,-7.83362 6.49972,-14.33333 14.33333,-14.33333zM43,143.33333h86v-114.66667h-86zM114.66667,50.16667v21.5h-57.33333v-21.5zM114.66667,86v14.33333h-57.33333v-14.33333zM114.66667,107.5v14.33333h-57.33333v-14.33333z"></path>
                        </svg>

                        <p class="text-base leading-4">Saldos de socios</p>
                    </a>    
                                                                                  
                    <a href="{{ route('reporting.transactions') }}" class="flex items-center space-x-6 pl-10 hover:text-white focus:bg-gray-700 focus:text-white hover:bg-gray-500 text-gray-400 rounded px-3 py-2 w-full">                        
                        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="0 0 172 172" style=" fill:currentColor;">
                            <path d="M129,14.33333c7.83362,0 14.33333,6.49972 14.33333,14.33333v114.66667c0,7.83362 -6.49972,14.33333 -14.33333,14.33333h-86c-7.83362,0 -14.33333,-6.49972 -14.33333,-14.33333v-7.16667h-7.16667c-3.956,0 -7.16667,-3.21067 -7.16667,-7.16667c0,-3.956 3.21067,-7.16667 7.16667,-7.16667h7.16667v-14.33333h-7.16667c-3.956,0 -7.16667,-3.21067 -7.16667,-7.16667c0,-3.956 3.21067,-7.16667 7.16667,-7.16667h7.16667v-14.33333h-7.16667c-3.956,0 -7.16667,-3.21067 -7.16667,-7.16667c0,-3.956 3.21067,-7.16667 7.16667,-7.16667h7.16667v-14.33333h-7.16667c-3.956,0 -7.16667,-3.21067 -7.16667,-7.16667c0,-3.956 3.21067,-7.16667 7.16667,-7.16667h7.16667v-7.16667c0,-7.83362 6.49972,-14.33333 14.33333,-14.33333zM43,143.33333h86v-114.66667h-86zM114.66667,50.16667v21.5h-57.33333v-21.5zM114.66667,86v14.33333h-57.33333v-14.33333zM114.66667,107.5v14.33333h-57.33333v-14.33333z"></path>
                        </svg>

                        <p class="text-base leading-4">Transacciones</p>
                    </a>   
                    
                    <a href="{{ route('reporting.partners') }}" class="flex items-center space-x-6 pl-10 hover:text-white focus:bg-gray-700 focus:text-white hover:bg-gray-500 text-gray-400 rounded px-3 py-2 w-full">                        
                        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="0 0 172 172" style=" fill:currentColor;">
                            <path d="M129,14.33333c7.83362,0 14.33333,6.49972 14.33333,14.33333v114.66667c0,7.83362 -6.49972,14.33333 -14.33333,14.33333h-86c-7.83362,0 -14.33333,-6.49972 -14.33333,-14.33333v-7.16667h-7.16667c-3.956,0 -7.16667,-3.21067 -7.16667,-7.16667c0,-3.956 3.21067,-7.16667 7.16667,-7.16667h7.16667v-14.33333h-7.16667c-3.956,0 -7.16667,-3.21067 -7.16667,-7.16667c0,-3.956 3.21067,-7.16667 7.16667,-7.16667h7.16667v-14.33333h-7.16667c-3.956,0 -7.16667,-3.21067 -7.16667,-7.16667c0,-3.956 3.21067,-7.16667 7.16667,-7.16667h7.16667v-14.33333h-7.16667c-3.956,0 -7.16667,-3.21067 -7.16667,-7.16667c0,-3.956 3.21067,-7.16667 7.16667,-7.16667h7.16667v-7.16667c0,-7.83362 6.49972,-14.33333 14.33333,-14.33333zM43,143.33333h86v-114.66667h-86zM114.66667,50.16667v21.5h-57.33333v-21.5zM114.66667,86v14.33333h-57.33333v-14.33333zM114.66667,107.5v14.33333h-57.33333v-14.33333z"></path>
                        </svg>

                        <p class="text-base leading-4">Socios</p>
                    </a>

                    <a href="{{ route('reporting.accounts')}}" class="flex items-center space-x-6 pl-10 hover:text-white focus:bg-gray-700 focus:text-white hover:bg-gray-500 text-gray-400 rounded px-3 py-2 w-full">                        
                        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="0 0 172 172" style=" fill:currentColor;">
                            <path d="M129,14.33333c7.83362,0 14.33333,6.49972 14.33333,14.33333v114.66667c0,7.83362 -6.49972,14.33333 -14.33333,14.33333h-86c-7.83362,0 -14.33333,-6.49972 -14.33333,-14.33333v-7.16667h-7.16667c-3.956,0 -7.16667,-3.21067 -7.16667,-7.16667c0,-3.956 3.21067,-7.16667 7.16667,-7.16667h7.16667v-14.33333h-7.16667c-3.956,0 -7.16667,-3.21067 -7.16667,-7.16667c0,-3.956 3.21067,-7.16667 7.16667,-7.16667h7.16667v-14.33333h-7.16667c-3.956,0 -7.16667,-3.21067 -7.16667,-7.16667c0,-3.956 3.21067,-7.16667 7.16667,-7.16667h7.16667v-14.33333h-7.16667c-3.956,0 -7.16667,-3.21067 -7.16667,-7.16667c0,-3.956 3.21067,-7.16667 7.16667,-7.16667h7.16667v-7.16667c0,-7.83362 6.49972,-14.33333 14.33333,-14.33333zM43,143.33333h86v-114.66667h-86zM114.66667,50.16667v21.5h-57.33333v-21.5zM114.66667,86v14.33333h-57.33333v-14.33333zM114.66667,107.5v14.33333h-57.33333v-14.33333z"></path>
                        </svg>

                        <p class="text-base leading-4">Cuentas</p>
                    </a>
                </div>
            </div>
        </div>            
        
         {{-- Menú administración --}}
         <div class="flex flex-col">
            <a onclick="showMenu4(true)" class="flex items-center justify-between py-2 mt-2 text-gray-300 hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100" href="#">
                <div class="flex">                                       
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>                  
                    <span class="mx-3">Administración</span>
                </div>
            
                <svg id="icon4" class="transform rotate-180" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M18 15L12 9L6 15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </a>
            
            <div id="menu4" class="hidden flex flex-col w-full pb-1 bg-gray-600 border-r-4 border-orange-400">
                <div class="">   
                    
                    <a href="{{ route('partners.index') }}" class="flex items-center space-x-6 pl-10 hover:text-white focus:bg-gray-700 focus:text-white hover:bg-gray-500 text-gray-400 rounded px-3 py-1 w-full">                        
                        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="0 0 172 172" style=" fill:currentColor;">
                        <path d="M68.4775,15.1575c7.51156,0 14.01531,1.76031 17.63,6.45c2.83531,0.57781 5.36156,1.55875 7.525,2.9025c4.74344,-3.69531 11.22031,-5.76469 19.565,-5.9125c6.06031,0 11.23375,1.62594 14.5125,5.2675c5.0525,0.90031 9.05688,3.38625 11.5025,6.7725c2.58,3.56094 3.64156,7.82063 3.9775,12.04c0.59125,7.65938 -1.15562,15.00969 -2.58,19.6725c1.29,1.73344 2.31125,3.84313 1.935,6.9875v0.1075c-0.44344,3.62813 -1.42437,6.18125 -2.9025,8.0625c-0.72562,0.92719 -1.67969,1.00781 -2.58,1.505c-0.52406,3.01 -1.35719,5.97969 -2.58,8.4925c-0.72562,1.47813 -1.54531,2.84875 -2.365,3.9775c-0.30906,0.43 -0.84656,0.69875 -1.1825,1.075c-0.02687,3.44 0.04031,6.38281 0.3225,9.9975c0.87344,2.08281 2.84875,3.84313 6.3425,5.59c3.61469,1.81406 8.37156,3.38625 13.2225,5.4825c4.85094,2.09625 9.86313,4.63594 13.8675,8.7075c4.00438,4.07156 6.86656,9.76906 7.31,17.0925l0.215,3.655h-39.2375c2.58,4.05813 4.25969,8.98969 4.6225,14.9425l0.215,3.655h-138.03l0.215,-3.655c0.51063,-8.55969 3.81625,-15.05 8.4925,-19.78c4.67625,-4.73 10.58875,-7.74 16.34,-10.2125c5.75125,-2.4725 11.43531,-4.48812 15.8025,-6.665c4.23281,-2.10969 6.83969,-4.28656 7.955,-6.9875c0.36281,-4.47469 0.34938,-8.00875 0.3225,-12.255c-0.45687,-0.48375 -1.075,-0.81969 -1.505,-1.3975c-0.95406,-1.29 -1.935,-2.88906 -2.795,-4.6225c-1.505,-3.01 -2.51281,-6.61125 -3.1175,-10.2125c-1.11531,-0.56437 -2.31125,-0.67187 -3.225,-1.8275c-1.65281,-2.10969 -2.82187,-5.11969 -3.3325,-9.3525c-0.47031,-3.88344 0.83313,-6.46344 2.4725,-8.385c-3.05031,-12.55062 -3.99094,-24.68469 0.215,-34.2925c4.4075,-10.09156 14.67375,-16.58187 30.745,-16.8775zM43.9675,34.83c-3.37281,7.69969 -2.80844,19.0275 0.43,31.4975l0.645,2.4725l-2.25015,1.39295c0.07893,-0.04592 -1.30357,0.96636 -0.97485,3.76705c0.40313,3.37281 1.20938,5.11969 1.8275,5.9125c0.61813,0.79281 0.94063,0.7525 0.9675,0.7525l2.9025,0.215l0.3225,2.795c0.30906,2.94281 1.47813,6.61125 2.9025,9.46c0.71219,1.42438 1.505,2.67406 2.15,3.5475c0.645,0.87344 1.38406,1.34375 1.075,1.1825l1.8275,0.9675v2.0425c0,4.98531 0.20156,9.07031 -0.3225,14.835l-0.215,0.86c-1.96187,5.28094 -6.59781,8.57313 -11.61,11.0725c-5.01219,2.49938 -10.70969,4.34031 -16.125,6.665c-5.41531,2.32469 -10.41406,5.10625 -14.0825,8.815c-2.91594,2.94281 -4.73,6.85313 -5.6975,11.7175h122.12c-0.9675,-4.87781 -2.795,-8.77469 -5.6975,-11.7175c-0.1075,-0.1075 -0.215,-0.215 -0.3225,-0.3225c-0.01344,-0.01344 -0.09406,0.01344 -0.1075,0c-0.28219,-0.17469 -0.5375,-0.38969 -0.7525,-0.645c-0.18812,-0.12094 -0.37625,-0.26875 -0.5375,-0.43c-0.01344,-0.01344 0.01344,-0.09406 0,-0.1075c-3.42656,-2.95625 -7.64594,-5.32125 -12.255,-7.31c-5.38844,-2.32469 -11.12625,-4.16562 -16.125,-6.665c-4.99875,-2.49937 -9.54062,-5.79156 -11.5025,-11.0725l-0.215,-0.43v-0.43c-0.52406,-5.76469 -0.3225,-9.84969 -0.3225,-14.835v-2.0425l1.8275,-0.9675c-0.33594,0.17469 0.33594,-0.30906 0.9675,-1.1825c0.63156,-0.87344 1.34375,-2.12312 2.0425,-3.5475c1.38406,-2.84875 2.58,-6.47687 2.9025,-9.46l0.3225,-2.795l2.9025,-0.215c0.02688,0 0.34938,0.04031 0.9675,-0.7525c0.61813,-0.79281 1.42438,-2.53969 1.8275,-5.9125c0.33574,-2.75044 -1.00371,-3.77607 -0.97764,-3.76849l-2.35486,-1.39151l0.86,-2.6875c1.49156,-4.50156 3.87,-14.10937 3.225,-22.36c-0.3225,-4.12531 -1.3975,-7.86094 -3.3325,-10.535c-1.935,-2.67406 -4.67625,-4.60906 -9.46,-5.2675l-1.6125,-0.215l-0.86,-1.3975c-1.27656,-2.24406 -5.76469,-4.27312 -12.685,-4.3c-0.04031,0 -0.06719,0 -0.1075,0c-14.2975,0.29563 -21.15062,5.11969 -24.51,12.7925zM98.7925,29.1325c0.01344,0.02688 -0.01344,0.08063 0,0.1075c2.96969,4.09844 4.23281,9.03 4.6225,13.975c0.71219,9.05688 -1.43781,17.87188 -3.1175,23.3275c1.55875,1.92156 2.82188,4.4075 2.365,8.17c-0.51062,4.23281 -1.67969,7.24281 -3.3325,9.3525c-0.88687,1.12875 -2.0425,1.26313 -3.1175,1.8275c-0.59125,3.58781 -1.65281,7.2025 -3.1175,10.2125c-0.84656,1.73344 -1.74687,3.31906 -2.6875,4.6225c-0.41656,0.57781 -1.06156,0.91375 -1.505,1.3975c-0.02687,4.28656 -0.05375,7.83406 0.3225,12.3625c1.14219,2.66063 3.77594,4.78375 7.955,6.88c4.35375,2.17688 9.97063,4.1925 15.695,6.665c4.85094,2.09625 9.74219,4.58219 13.975,8.17h37.41c-0.84656,-3.68187 -2.2575,-6.73219 -4.515,-9.03c-2.99656,-3.03687 -7.095,-5.25406 -11.61,-7.2025c-4.515,-1.94844 -9.28531,-3.56094 -13.545,-5.6975c-4.25969,-2.13656 -8.26406,-4.93156 -9.9975,-9.5675l-0.215,-0.43v-0.43c-0.44344,-4.89125 -0.3225,-8.39844 -0.3225,-12.5775v-2.0425l1.8275,-0.86c0,0 -0.01344,-0.09406 0,-0.1075h0.1075c0.12094,-0.13437 0.33594,-0.44344 0.645,-0.86c0.51063,-0.69875 1.03469,-1.73344 1.6125,-2.9025c1.14219,-2.35156 2.21719,-5.32125 2.4725,-7.74l0.215,-2.795l2.9025,-0.215c-0.12094,0.01344 -0.01344,0.13438 0.43,-0.43c0.44344,-0.56437 1.16906,-1.94844 1.505,-4.73c0.2355,-1.94287 -0.67431,-2.68883 -0.69509,-2.7171l-2.31491,-1.3679l0.86,-2.58c1.23625,-3.74906 3.225,-11.87875 2.6875,-18.705c-0.26875,-3.41312 -1.14219,-6.46344 -2.6875,-8.6c-1.54531,-2.13656 -3.72219,-3.5475 -7.6325,-4.085l-1.6125,-0.3225l-0.86,-1.3975c-0.91375,-1.6125 -4.48812,-3.30562 -10.2125,-3.3325c-0.04031,0 -0.06719,0 -0.1075,0c-6.5575,0.13438 -11.16656,1.505 -14.405,3.655z"></path>
                        </svg>

                        <p class="text-base leading-4">Socios</p>
                    </a>   

                    @can('admin.accounts.index')                                             
                        <a href="{{ route('accounts.index') }}" class="flex items-center space-x-6 pl-10 hover:text-white focus:bg-gray-700 focus:text-white hover:bg-gray-500 text-gray-400 rounded px-3 py-1 w-full">                        
                            <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="0 0 24 24" style=" fill:currentColor;">
                                <path d="M 7 5 C 3.1545455 5 0 8.1545455 0 12 C 0 15.845455 3.1545455 19 7 19 C 9.7749912 19 12.089412 17.314701 13.271484 15 L 16 15 L 16 18 L 22 18 L 22 15 L 24 15 L 24 9 L 23 9 L 13.287109 9 C 12.172597 6.6755615 9.8391582 5 7 5 z M 7 7 C 9.2802469 7 11.092512 8.4210017 11.755859 10.328125 L 11.988281 11 L 22 11 L 22 13 L 20 13 L 20 16 L 18 16 L 18 13 L 12.017578 13 L 11.769531 13.634766 C 11.010114 15.575499 9.1641026 17 7 17 C 4.2454545 17 2 14.754545 2 12 C 2 9.2454545 4.2454545 7 7 7 z M 7 9 C 5.3549904 9 4 10.35499 4 12 C 4 13.64501 5.3549904 15 7 15 C 8.6450096 15 10 13.64501 10 12 C 10 10.35499 8.6450096 9 7 9 z M 7 11 C 7.5641294 11 8 11.435871 8 12 C 8 12.564129 7.5641294 13 7 13 C 6.4358706 13 6 12.564129 6 12 C 6 11.435871 6.4358706 11 7 11 z"></path>
                            </svg>

                            <p class="text-base leading-4">Cuentas</p>
                        </a>  
                    @endcan   

                    <a href="{{ route('users.index') }}" class="flex items-center space-x-6 pl-10 hover:text-white focus:bg-gray-700 focus:text-white hover:bg-gray-500 text-gray-400 rounded px-3 py-1 w-full">                        
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 640 512" style=" fill:currentColor;">
                            <path d="M352 128c0 70.7-57.3 128-128 128s-128-57.3-128-128S153.3 0 224 0s128 57.3 128 128zM0 482.3C0 383.8 79.8 304 178.3 304h91.4C368.2 304 448 383.8 448 482.3c0 16.4-13.3 29.7-29.7 29.7H29.7C13.3 512 0 498.7 0 482.3zM609.3 512H471.4c5.4-9.4 8.6-20.3 8.6-32v-8c0-60.7-27.1-115.2-69.8-151.8c2.4-.1 4.7-.2 7.1-.2h61.4C567.8 320 640 392.2 640 481.3c0 17-13.8 30.7-30.7 30.7zM432 256c-31 0-59-12.6-79.3-32.9C372.4 196.5 384 163.6 384 128c0-26.8-6.6-52.1-18.3-74.3C384.3 40.1 407.2 32 432 32c61.9 0 112 50.1 112 112s-50.1 112-112 112z"/>
                        </svg>

                        <p class="text-base leading-4">Usuarios</p>
                    </a> 

                    @can('admin.companies.index')                                            
                        <a href="{{ route('companies.index') }}" class="flex items-center space-x-6 pl-10 hover:text-white focus:bg-gray-700 focus:text-white hover:bg-gray-500 text-gray-400 rounded px-3 py-1 w-full">                        
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 384 512" style=" fill:currentColor;">
                                <path d="M88 104C88 95.16 95.16 88 104 88H152C160.8 88 168 95.16 168 104V152C168 160.8 160.8 168 152 168H104C95.16 168 88 160.8 88 152V104zM280 88C288.8 88 296 95.16 296 104V152C296 160.8 288.8 168 280 168H232C223.2 168 216 160.8 216 152V104C216 95.16 223.2 88 232 88H280zM88 232C88 223.2 95.16 216 104 216H152C160.8 216 168 223.2 168 232V280C168 288.8 160.8 296 152 296H104C95.16 296 88 288.8 88 280V232zM280 216C288.8 216 296 223.2 296 232V280C296 288.8 288.8 296 280 296H232C223.2 296 216 288.8 216 280V232C216 223.2 223.2 216 232 216H280zM0 64C0 28.65 28.65 0 64 0H320C355.3 0 384 28.65 384 64V448C384 483.3 355.3 512 320 512H64C28.65 512 0 483.3 0 448V64zM48 64V448C48 456.8 55.16 464 64 464H144V400C144 373.5 165.5 352 192 352C218.5 352 240 373.5 240 400V464H320C328.8 464 336 456.8 336 448V64C336 55.16 328.8 48 320 48H64C55.16 48 48 55.16 48 64z"/>
                            </svg>

                            <p class="text-base leading-4">Empresas</p>
                        </a> 
                    @endcan            
                </div>
            </div>
        </div>
           
    </nav>                           
    

    <script>
        let icon1 = document.getElementById("icon1");
        let menu1 = document.getElementById("menu1");
        const showMenu1 = (flag) => {
            if (flag) {
                icon1.classList.toggle("rotate-180");
                menu1.classList.toggle("hidden");

                // Oculta los demás submenús abiertos
                menu2.classList.add("hidden");
                menu3.classList.add("hidden");
                menu4.classList.add("hidden");
            }
        };

        let icon2 = document.getElementById("icon2");
        let menu2 = document.getElementById("menu2");
        const showMenu2 = (flag) => {
            if (flag) {
                icon2.classList.toggle("rotate-180");
                menu2.classList.toggle("hidden");

                // Oculta los demás submenús abiertos
                menu1.classList.add("hidden");
                menu3.classList.add("hidden");
                menu4.classList.add("hidden");
            }
        };

        let icon3 = document.getElementById("icon3");
        let menu3 = document.getElementById("menu3");
        const showMenu3 = (flag) => {
            if (flag) {
                icon3.classList.toggle("rotate-180");
                menu3.classList.toggle("hidden");

                // Oculta los demás submenús abiertos
                menu2.classList.add("hidden");
                menu1.classList.add("hidden");
                menu4.classList.add("hidden");
            }
        };

        let icon4 = document.getElementById("icon4");
        let menu4 = document.getElementById("menu4");
        const showMenu4 = (flag) => {
            if (flag) {
                icon4.classList.toggle("rotate-180");
                menu4.classList.toggle("hidden");

                // Oculta los demás submenús abiertos
                menu2.classList.add("hidden");
                menu3.classList.add("hidden");
                menu1.classList.add("hidden");
            }
        };
    </script>
</div>
