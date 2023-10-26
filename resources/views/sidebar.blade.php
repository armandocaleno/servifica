<div x-cloak :class="sidebarOpen ? 'block' : 'hidden'" @click="sidebarOpen = false"
    class="fixed inset-0 z-20 transition-opacity bg-blue-900 opacity-50 lg:hidden"></div>

<div :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'"
    class="fixed text-sm inset-y-0 left-0 z-30 w-60 overflow-y-auto transition duration-300 transform bg-blue-950 lg:translate-x-0 lg:static lg:inset-0">
    {{-- x-cloak --}}

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
    <nav class="mt-4 text-sm">

        <a class="flex items-center py-2 mt-4 px-2 text-gray-100 bg-gray-700 bg-opacity-25"
            href="{{ route('welcome') }}">
            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
            </svg>
            <span class="mx-3">Inicio</span>
        </a>
        {{-- Menú trasacciones --}}
        <div class="flex flex-col">
            <a onclick="showMenu1(true)"
                class="flex items-center justify-between py-2 mt-2 px-2 text-gray-300 hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100"
                href="#">
                <div class="flex">
                    <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24"
                        viewBox="0 0 172 172">
                        <g transform="translate(0.516,0.516) scale(0.994,0.994)">
                            <g fill="none" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt"
                                stroke-linejoin="none" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0"
                                font-family="none" font-weight="none" font-size="none" text-anchor="none"
                                style="mix-blend-mode: normal">
                                <g fill="currentColor" stroke="currentColor" stroke-linejoin="round">
                                    <path
                                        d="M43,100.33333c0,4.04938 3.11728,7.16667 7.16667,7.16667h100.33333v14.33333h-100.33333c-11.78895,0 -21.5,-9.71105 -21.5,-21.5v-71.63867l-14.23535,0.06999l-0.06999,-14.33333l28.63867,-0.12598zM143.33333,21.5v43h-7.16667v28.66667h-78.83333v-43h14.33333v-28.66667zM86,50.16667h43v-14.33333h-43zM71.66667,78.83333h50.16667v-14.33333h-50.16667zM71.66667,143.33333c0,7.91608 -6.41725,14.33333 -14.33333,14.33333c-7.91608,0 -14.33333,-6.41725 -14.33333,-14.33333c0,-7.91608 6.41725,-14.33333 14.33333,-14.33333c7.91608,0 14.33333,6.41725 14.33333,14.33333zM143.33333,143.33333c0,7.91608 -6.41725,14.33333 -14.33333,14.33333c-7.91608,0 -14.33333,-6.41725 -14.33333,-14.33333c0,-7.91608 6.41725,-14.33333 14.33333,-14.33333c7.91608,0 14.33333,6.41725 14.33333,14.33333z">
                                    </path>
                                </g>
                                <path d="M0,172v-172h172v172z" fill="none" stroke="none" stroke-linejoin="miter">
                                </path>
                                <g fill="currentColor" stroke="none" stroke-linejoin="miter">
                                    <path
                                        d="M43,14.30534l-28.63867,0.12598l0.06999,14.33333l14.23535,-0.06999v71.63867c0,11.78895 9.71105,21.5 21.5,21.5h100.33333v-14.33333h-100.33333c-4.04938,0 -7.16667,-3.11728 -7.16667,-7.16667zM71.66667,21.5v28.66667h-14.33333v43h78.83333v-28.66667h7.16667v-43zM86,35.83333h43v14.33333h-43zM71.66667,64.5h50.16667v14.33333h-50.16667zM57.33333,129c-7.91608,0 -14.33333,6.41725 -14.33333,14.33333c0,7.91608 6.41725,14.33333 14.33333,14.33333c7.91608,0 14.33333,-6.41725 14.33333,-14.33333c0,-7.91608 -6.41725,-14.33333 -14.33333,-14.33333zM129,129c-7.91608,0 -14.33333,6.41725 -14.33333,14.33333c0,7.91608 6.41725,14.33333 14.33333,14.33333c7.91608,0 14.33333,-6.41725 14.33333,-14.33333c0,-7.91608 -6.41725,-14.33333 -14.33333,-14.33333z">
                                    </path>
                                </g>
                                <path d="" fill="currentColor" stroke="none" stroke-linejoin="miter"></path>
                            </g>
                        </g>
                    </svg>
                    <span class="mx-3">Transacciones</span>
                </div>

                <svg id="icon1" class="transform rotate-180" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M18 15L12 9L6 15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </a>

            <div id="menu1"
                class="hidden flex flex-col w-full pb-1 bg-gray-700 border-r-4 bg-opacity-40 border-orange-400">
                <div class="">
                    @can('transactions.index')
                        <a href="{{ route('transactions.index') }}"
                            class="flex items-center space-x-6 pl-10 hover:text-white focus:bg-gray-700 focus:text-white hover:bg-gray-700 hover:bg-opacity-25 text-gray-300 rounded py-1 w-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 512 512"
                                style=" fill:currentColor;">
                                <path
                                    d="M40 48C26.7 48 16 58.7 16 72v48c0 13.3 10.7 24 24 24H88c13.3 0 24-10.7 24-24V72c0-13.3-10.7-24-24-24H40zM192 64c-17.7 0-32 14.3-32 32s14.3 32 32 32H480c17.7 0 32-14.3 32-32s-14.3-32-32-32H192zm0 160c-17.7 0-32 14.3-32 32s14.3 32 32 32H480c17.7 0 32-14.3 32-32s-14.3-32-32-32H192zm0 160c-17.7 0-32 14.3-32 32s14.3 32 32 32H480c17.7 0 32-14.3 32-32s-14.3-32-32-32H192zM16 232v48c0 13.3 10.7 24 24 24H88c13.3 0 24-10.7 24-24V232c0-13.3-10.7-24-24-24H40c-13.3 0-24 10.7-24 24zM40 368c-13.3 0-24 10.7-24 24v48c0 13.3 10.7 24 24 24H88c13.3 0 24-10.7 24-24V392c0-13.3-10.7-24-24-24H40z" />
                            </svg>
                            <p class=" leading-4">Listado de transacciones</p>
                        </a>
                    @endcan
                    
                    @can('transactions.charge.create')
                        <a href="{{ route('transactions.lotes') }}"
                            class="flex items-center space-x-6 pl-10 hover:text-white focus:bg-gray-700 focus:text-white hover:bg-gray-700 hover:bg-opacity-25 text-gray-300 rounded px-3 py-1 w-full">
                            <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24"
                                viewBox="0 0 172 172" style=" fill:currentColor;">
                                <path
                                    d="M93.16667,21.5v86h14.33333l-21.5,21.5l-21.5,-21.5h14.33333v-86zM50.16667,47.19922v31.63411h14.33333l-21.5,21.5l-21.5,-21.5h14.33333v-25.69922l-19.40039,-19.40039l10.13411,-10.13411zM155.56706,33.73372l-19.40039,19.40039v25.69922h14.33333l-21.5,21.5l-21.5,-21.5h14.33333v-31.63411l23.59961,-23.59961zM28.66667,121.83333v21.5h114.66667v-21.5h14.33333v21.5c0,7.83362 -6.49972,14.33333 -14.33333,14.33333h-114.66667c-7.83362,0 -14.33333,-6.49972 -14.33333,-14.33333v-21.5z">
                                </path>
                            </svg>
                            <p class=" leading-4">Cargos</p>
                        </a>
                    @endcan

                    @can('transactions.payment.create')
                        <a href="{{ route('transactions.payment.create') }}"
                            class="flex items-center space-x-6 pl-10 hover:text-white focus:bg-gray-700 focus:text-white hover:bg-gray-700 hover:bg-opacity-25 text-gray-300 rounded px-3 py-1 w-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 576 512"
                                style=" fill:currentColor;">
                                <path
                                    d="M64 64C28.7 64 0 92.7 0 128V384c0 35.3 28.7 64 64 64H512c35.3 0 64-28.7 64-64V128c0-35.3-28.7-64-64-64H64zm64 320H64V320c35.3 0 64 28.7 64 64zM64 192V128h64c0 35.3-28.7 64-64 64zM448 384c0-35.3 28.7-64 64-64v64H448zm64-192c-35.3 0-64-28.7-64-64h64v64zM400 256c0 61.9-50.1 112-112 112s-112-50.1-112-112s50.1-112 112-112s112 50.1 112 112zM252 208c0 9.7 6.9 17.7 16 19.6V276h-4c-11 0-20 9-20 20s9 20 20 20h24 24c11 0 20-9 20-20s-9-20-20-20h-4V208c0-11-9-20-20-20H272c-11 0-20 9-20 20z" />
                            </svg>
                            <p class=" leading-4">Devolución de ahorros</p>
                        </a>
                    @endcan

                </div>
            </div>
        </div>

        {{-- Menú cuentas por pagar --}}
        <div class="flex flex-col">
            <a onclick="showMenu2(true)"
                class="flex items-center justify-between py-2 mt-2 px-2 text-gray-300 hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100"
                href="#">
                <div class="flex">
                    <i class="fa-solid fa-money-bill-transfer"></i>
                    <span class="mx-3">Cuentas por pagar</span>
                </div>

                <svg id="icon2" class="transform rotate-180" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M18 15L12 9L6 15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </a>

            <div id="menu2"
                class="hidden flex flex-col w-full pb-1 bg-gray-700 border-r-4 bg-opacity-40 border-orange-400">
                <div class="">
                    @can('transactions.expenses.create')
                        <a href="{{ route('expenses.index') }}"
                            class="flex items-center space-x-6 pl-10 hover:text-white focus:bg-gray-700 focus:text-white hover:bg-gray-700 hover:bg-opacity-25 text-gray-300 rounded py-1 w-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                viewBox="0 0 512 512">
                                <path
                                    d="M512 80c0 18-14.3 34.6-38.4 48c-29.1 16.1-72.5 27.5-122.3 30.9c-3.7-1.8-7.4-3.5-11.3-5C300.6 137.4 248.2 128 192 128c-8.3 0-16.4 .2-24.5 .6l-1.1-.6C142.3 114.6 128 98 128 80c0-44.2 86-80 192-80S512 35.8 512 80zM160.7 161.1c10.2-.7 20.7-1.1 31.3-1.1c62.2 0 117.4 12.3 152.5 31.4C369.3 204.9 384 221.7 384 240c0 4-.7 7.9-2.1 11.7c-4.6 13.2-17 25.3-35 35.5c0 0 0 0 0 0c-.1 .1-.3 .1-.4 .2l0 0 0 0c-.3 .2-.6 .3-.9 .5c-35 19.4-90.8 32-153.6 32c-59.6 0-112.9-11.3-148.2-29.1c-1.9-.9-3.7-1.9-5.5-2.9C14.3 274.6 0 258 0 240c0-34.8 53.4-64.5 128-75.4c10.5-1.5 21.4-2.7 32.7-3.5zM416 240c0-21.9-10.6-39.9-24.1-53.4c28.3-4.4 54.2-11.4 76.2-20.5c16.3-6.8 31.5-15.2 43.9-25.5V176c0 19.3-16.5 37.1-43.8 50.9c-14.6 7.4-32.4 13.7-52.4 18.5c.1-1.8 .2-3.5 .2-5.3zm-32 96c0 18-14.3 34.6-38.4 48c-1.8 1-3.6 1.9-5.5 2.9C304.9 404.7 251.6 416 192 416c-62.8 0-118.6-12.6-153.6-32C14.3 370.6 0 354 0 336V300.6c12.5 10.3 27.6 18.7 43.9 25.5C83.4 342.6 135.8 352 192 352s108.6-9.4 148.1-25.9c7.8-3.2 15.3-6.9 22.4-10.9c6.1-3.4 11.8-7.2 17.2-11.2c1.5-1.1 2.9-2.3 4.3-3.4V304v5.7V336zm32 0V304 278.1c19-4.2 36.5-9.5 52.1-16c16.3-6.8 31.5-15.2 43.9-25.5V272c0 10.5-5 21-14.9 30.9c-16.3 16.3-45 29.7-81.3 38.4c.1-1.7 .2-3.5 .2-5.3zM192 448c56.2 0 108.6-9.4 148.1-25.9c16.3-6.8 31.5-15.2 43.9-25.5V432c0 44.2-86 80-192 80S0 476.2 0 432V396.6c12.5 10.3 27.6 18.7 43.9 25.5C83.4 438.6 135.8 448 192 448z" />
                            </svg>
                            <p class=" leading-4">Gastos</p>
                        </a>
                    @endcan
                </div>
            </div>
        </div>

        {{-- Menú bancos --}}
        <div class="flex flex-col">
            <a onclick="showMenu6(true)"
                class="flex items-center justify-between py-2 mt-2 px-2 text-gray-300 hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100"
                href="#">
                <div class="flex">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                        viewBox="0 0 576 512">
                        <path
                            d="M400 96l0 .7c-5.3-.4-10.6-.7-16-.7H256c-16.5 0-32.5 2.1-47.8 6c-.1-2-.2-4-.2-6c0-53 43-96 96-96s96 43 96 96zm-16 32c3.5 0 7 .1 10.4 .3c4.2 .3 8.4 .7 12.6 1.3C424.6 109.1 450.8 96 480 96h11.5c10.4 0 18 9.8 15.5 19.9l-13.8 55.2c15.8 14.8 28.7 32.8 37.5 52.9H544c17.7 0 32 14.3 32 32v96c0 17.7-14.3 32-32 32H512c-9.1 12.1-19.9 22.9-32 32v64c0 17.7-14.3 32-32 32H416c-17.7 0-32-14.3-32-32V448H256v32c0 17.7-14.3 32-32 32H192c-17.7 0-32-14.3-32-32V416c-34.9-26.2-58.7-66.3-63.2-112H68c-37.6 0-68-30.4-68-68s30.4-68 68-68h4c13.3 0 24 10.7 24 24s-10.7 24-24 24H68c-11 0-20 9-20 20s9 20 20 20H99.2c12.1-59.8 57.7-107.5 116.3-122.8c12.9-3.4 26.5-5.2 40.5-5.2H384zm64 136a24 24 0 1 0 -48 0 24 24 0 1 0 48 0z" />
                    </svg>
                    <span class="mx-3">Bancos</span>
                </div>

                <svg id="icon6" class="transform rotate-180" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M18 15L12 9L6 15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </a>

            <div id="menu6"
                class="hidden flex flex-col w-full pb-1 bg-gray-700 border-r-4 bg-opacity-40 border-orange-400">
                <div class="">

                    @can('banks.checks.index')
                        <a href="{{ route('checks.index') }}"
                            class="flex items-center space-x-6 pl-10 hover:text-white focus:bg-gray-700 focus:text-white hover:bg-gray-700 hover:bg-opacity-25 text-gray-300 rounded py-1 w-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 640 512"
                                fill="currentColor">
                                <path
                                    d="M535 41c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l64 64c4.5 4.5 7 10.6 7 17s-2.5 12.5-7 17l-64 64c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l23-23L384 112c-13.3 0-24-10.7-24-24s10.7-24 24-24l174.1 0L535 41zM105 377l-23 23L256 400c13.3 0 24 10.7 24 24s-10.7 24-24 24L81.9 448l23 23c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0L7 441c-4.5-4.5-7-10.6-7-17s2.5-12.5 7-17l64-64c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9zM96 64H337.9c-3.7 7.2-5.9 15.3-5.9 24c0 28.7 23.3 52 52 52l117.4 0c-4 17 .6 35.5 13.8 48.8c20.3 20.3 53.2 20.3 73.5 0L608 169.5V384c0 35.3-28.7 64-64 64H302.1c3.7-7.2 5.9-15.3 5.9-24c0-28.7-23.3-52-52-52l-117.4 0c4-17-.6-35.5-13.8-48.8c-20.3-20.3-53.2-20.3-73.5 0L32 342.5V128c0-35.3 28.7-64 64-64zm64 64H96v64c35.3 0 64-28.7 64-64zM544 320c-35.3 0-64 28.7-64 64h64V320zM320 352a96 96 0 1 0 0-192 96 96 0 1 0 0 192z" />
                            </svg>
                            <p class=" leading-4">Movimientos bancarios</p>
                        </a>
                    @endcan

                    @can('banks.income.create')
                        <a href="{{ route('checks.income') }}"
                            class="flex items-center space-x-6 pl-10 hover:text-white focus:bg-gray-700 focus:text-white hover:bg-gray-700 hover:bg-opacity-25 text-gray-300 rounded py-1 w-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                viewBox="0 0 576 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                <path
                                    d="M400 96l0 .7c-5.3-.4-10.6-.7-16-.7H256c-16.5 0-32.5 2.1-47.8 6c-.1-2-.2-4-.2-6c0-53 43-96 96-96s96 43 96 96zm-16 32c3.5 0 7 .1 10.4 .3c4.2 .3 8.4 .7 12.6 1.3C424.6 109.1 450.8 96 480 96h11.5c10.4 0 18 9.8 15.5 19.9l-13.8 55.2c15.8 14.8 28.7 32.8 37.5 52.9H544c17.7 0 32 14.3 32 32v96c0 17.7-14.3 32-32 32H512c-9.1 12.1-19.9 22.9-32 32v64c0 17.7-14.3 32-32 32H416c-17.7 0-32-14.3-32-32V448H256v32c0 17.7-14.3 32-32 32H192c-17.7 0-32-14.3-32-32V416c-34.9-26.2-58.7-66.3-63.2-112H68c-37.6 0-68-30.4-68-68s30.4-68 68-68h4c13.3 0 24 10.7 24 24s-10.7 24-24 24H68c-11 0-20 9-20 20s9 20 20 20H99.2c12.1-59.8 57.7-107.5 116.3-122.8c12.9-3.4 26.5-5.2 40.5-5.2H384zm64 136a24 24 0 1 0 -48 0 24 24 0 1 0 48 0z" />
                            </svg>
                            <p class=" leading-4">Ingresos</p>
                        </a>
                    @endcan

                    @can('banks.checks.create')
                        <a href="{{ route('checks.create') }}"
                            class="flex items-center space-x-6 pl-10 hover:text-white focus:bg-gray-700 focus:text-white hover:bg-gray-700 hover:bg-opacity-25 text-gray-300 rounded py-1 w-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                <path
                                    d="M320 96H192L144.6 24.9C137.5 14.2 145.1 0 157.9 0H354.1c12.8 0 20.4 14.2 13.3 24.9L320 96zM192 128H320c3.8 2.5 8.1 5.3 13 8.4C389.7 172.7 512 250.9 512 416c0 53-43 96-96 96H96c-53 0-96-43-96-96C0 250.9 122.3 172.7 179 136.4l0 0 0 0c4.8-3.1 9.2-5.9 13-8.4zm84 88c0-11-9-20-20-20s-20 9-20 20v14c-7.6 1.7-15.2 4.4-22.2 8.5c-13.9 8.3-25.9 22.8-25.8 43.9c.1 20.3 12 33.1 24.7 40.7c11 6.6 24.7 10.8 35.6 14l1.7 .5c12.6 3.8 21.8 6.8 28 10.7c5.1 3.2 5.8 5.4 5.9 8.2c.1 5-1.8 8-5.9 10.5c-5 3.1-12.9 5-21.4 4.7c-11.1-.4-21.5-3.9-35.1-8.5c-2.3-.8-4.7-1.6-7.2-2.4c-10.5-3.5-21.8 2.2-25.3 12.6s2.2 21.8 12.6 25.3c1.9 .6 4 1.3 6.1 2.1l0 0 0 0c8.3 2.9 17.9 6.2 28.2 8.4V424c0 11 9 20 20 20s20-9 20-20V410.2c8-1.7 16-4.5 23.2-9c14.3-8.9 25.1-24.1 24.8-45c-.3-20.3-11.7-33.4-24.6-41.6c-11.5-7.2-25.9-11.6-37.1-15l0 0-.7-.2c-12.8-3.9-21.9-6.7-28.3-10.5c-5.2-3.1-5.3-4.9-5.3-6.7c0-3.7 1.4-6.5 6.2-9.3c5.4-3.2 13.6-5.1 21.5-5c9.6 .1 20.2 2.2 31.2 5.2c10.7 2.8 21.6-3.5 24.5-14.2s-3.5-21.6-14.2-24.5c-6.5-1.7-13.7-3.4-21.1-4.7V216z" />
                            </svg>
                            <p class=" leading-4">Egresos</p>
                        </a>
                    @endcan
                </div>
            </div>
        </div>

        {{-- Menú contabilidad --}}
        <div class="flex flex-col">
            <a onclick="showMenu5(true)"
                class="flex items-center justify-between py-2 mt-2 px-2 text-gray-300 hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100"
                href="#">
                <div class="flex">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" width="24" height="24"
                        fill="currentColor">
                        <path
                            d="M384 32H512c17.7 0 32 14.3 32 32s-14.3 32-32 32H398.4c-5.2 25.8-22.9 47.1-46.4 57.3V448H512c17.7 0 32 14.3 32 32s-14.3 32-32 32H320 128c-17.7 0-32-14.3-32-32s14.3-32 32-32H288V153.3c-23.5-10.3-41.2-31.6-46.4-57.3H128c-17.7 0-32-14.3-32-32s14.3-32 32-32H256c14.6-19.4 37.8-32 64-32s49.4 12.6 64 32zm55.6 288H584.4L512 195.8 439.6 320zM512 416c-62.9 0-115.2-34-126-78.9c-2.6-11 1-22.3 6.7-32.1l95.2-163.2c5-8.6 14.2-13.8 24.1-13.8s19.1 5.3 24.1 13.8l95.2 163.2c5.7 9.8 9.3 21.1 6.7 32.1C627.2 382 574.9 416 512 416zM126.8 195.8L54.4 320H199.3L126.8 195.8zM.9 337.1c-2.6-11 1-22.3 6.7-32.1l95.2-163.2c5-8.6 14.2-13.8 24.1-13.8s19.1 5.3 24.1 13.8l95.2 163.2c5.7 9.8 9.3 21.1 6.7 32.1C242 382 189.7 416 126.8 416S11.7 382 .9 337.1z" />
                    </svg>

                    <span class="mx-3">Contabilidad</span>
                </div>

                <svg id="icon5" class="transform rotate-180" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M18 15L12 9L6 15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>

            </a>

            <div id="menu5"
                class="hidden flex flex-col w-full pb-1 bg-gray-700 border-r-4 bg-opacity-40 border-orange-400">
                <div class="">
                    @can('accounting.accounts.index')
                        <a href="{{ route('accounting.index') }}"
                            class="flex items-center space-x-6 pl-10 hover:text-white focus:bg-gray-700 focus:text-white hover:bg-gray-700 hover:bg-opacity-25 text-gray-300 rounded py-1 w-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 512 512"
                                style=" fill:currentColor;">
                                <path
                                    d="M40 48C26.7 48 16 58.7 16 72v48c0 13.3 10.7 24 24 24H88c13.3 0 24-10.7 24-24V72c0-13.3-10.7-24-24-24H40zM192 64c-17.7 0-32 14.3-32 32s14.3 32 32 32H480c17.7 0 32-14.3 32-32s-14.3-32-32-32H192zm0 160c-17.7 0-32 14.3-32 32s14.3 32 32 32H480c17.7 0 32-14.3 32-32s-14.3-32-32-32H192zm0 160c-17.7 0-32 14.3-32 32s14.3 32 32 32H480c17.7 0 32-14.3 32-32s-14.3-32-32-32H192zM16 232v48c0 13.3 10.7 24 24 24H88c13.3 0 24-10.7 24-24V232c0-13.3-10.7-24-24-24H40c-13.3 0-24 10.7-24 24zM40 368c-13.3 0-24 10.7-24 24v48c0 13.3 10.7 24 24 24H88c13.3 0 24-10.7 24-24V392c0-13.3-10.7-24-24-24H40z" />
                            </svg>
                            <p class=" leading-4">Plan de cuentas</p>
                        </a>
                    @endcan

                    @can('accounting.accounts.index')
                        <a href="{{ route('journals.index') }}"
                            class="flex items-center space-x-6 pl-10 hover:text-white focus:bg-gray-700 focus:text-white hover:bg-gray-700 hover:bg-opacity-25 text-gray-300 rounded px-3 py-1 w-full">
                            <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24"
                                viewBox="0 0 172 172" style=" fill:currentColor;">
                                <path
                                    d="M93.16667,21.5v86h14.33333l-21.5,21.5l-21.5,-21.5h14.33333v-86zM50.16667,47.19922v31.63411h14.33333l-21.5,21.5l-21.5,-21.5h14.33333v-25.69922l-19.40039,-19.40039l10.13411,-10.13411zM155.56706,33.73372l-19.40039,19.40039v25.69922h14.33333l-21.5,21.5l-21.5,-21.5h14.33333v-31.63411l23.59961,-23.59961zM28.66667,121.83333v21.5h114.66667v-21.5h14.33333v21.5c0,7.83362 -6.49972,14.33333 -14.33333,14.33333h-114.66667c-7.83362,0 -14.33333,-6.49972 -14.33333,-14.33333v-21.5z">
                                </path>
                            </svg>
                            <p class=" leading-4">Asientos contables</p>
                        </a>
                    @endcan

                    @can('accounting.banks.index')
                        <a href="{{ route('banks.index') }}"
                            class="flex items-center space-x-6 pl-10 hover:text-white focus:bg-gray-700 focus:text-white hover:bg-gray-700 hover:bg-opacity-25 text-gray-300 rounded px-3 py-1 w-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 512 512"
                                style=" fill:currentColor;"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                <path
                                    d="M243.4 2.6l-224 96c-14 6-21.8 21-18.7 35.8S16.8 160 32 160v8c0 13.3 10.7 24 24 24H456c13.3 0 24-10.7 24-24v-8c15.2 0 28.3-10.7 31.3-25.6s-4.8-29.9-18.7-35.8l-224-96c-8-3.4-17.2-3.4-25.2 0zM128 224H64V420.3c-.6 .3-1.2 .7-1.8 1.1l-48 32c-11.7 7.8-17 22.4-12.9 35.9S17.9 512 32 512H480c14.1 0 26.5-9.2 30.6-22.7s-1.1-28.1-12.9-35.9l-48-32c-.6-.4-1.2-.7-1.8-1.1V224H384V416H344V224H280V416H232V224H168V416H128V224zM256 64a32 32 0 1 1 0 64 32 32 0 1 1 0-64z" />
                            </svg>
                            <p class=" leading-4">Bancos</p>
                        </a>
                    @endcan

                    @can('accounting.bankaccounts.index')
                        <a href="{{ route('bank-accounts.index') }}"
                            class="flex items-center space-x-6 pl-10 hover:text-white focus:bg-gray-700 focus:text-white hover:bg-gray-700 hover:bg-opacity-25 text-gray-300 rounded px-3 py-1 w-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 512 512"
                                style=" fill:currentColor;"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                <path
                                    d="M320 96H192L144.6 24.9C137.5 14.2 145.1 0 157.9 0H354.1c12.8 0 20.4 14.2 13.3 24.9L320 96zM192 128H320c3.8 2.5 8.1 5.3 13 8.4C389.7 172.7 512 250.9 512 416c0 53-43 96-96 96H96c-53 0-96-43-96-96C0 250.9 122.3 172.7 179 136.4l0 0 0 0c4.8-3.1 9.2-5.9 13-8.4zm84 88c0-11-9-20-20-20s-20 9-20 20v14c-7.6 1.7-15.2 4.4-22.2 8.5c-13.9 8.3-25.9 22.8-25.8 43.9c.1 20.3 12 33.1 24.7 40.7c11 6.6 24.7 10.8 35.6 14l1.7 .5c12.6 3.8 21.8 6.8 28 10.7c5.1 3.2 5.8 5.4 5.9 8.2c.1 5-1.8 8-5.9 10.5c-5 3.1-12.9 5-21.4 4.7c-11.1-.4-21.5-3.9-35.1-8.5c-2.3-.8-4.7-1.6-7.2-2.4c-10.5-3.5-21.8 2.2-25.3 12.6s2.2 21.8 12.6 25.3c1.9 .6 4 1.3 6.1 2.1l0 0 0 0c8.3 2.9 17.9 6.2 28.2 8.4V424c0 11 9 20 20 20s20-9 20-20V410.2c8-1.7 16-4.5 23.2-9c14.3-8.9 25.1-24.1 24.8-45c-.3-20.3-11.7-33.4-24.6-41.6c-11.5-7.2-25.9-11.6-37.1-15l0 0-.7-.2c-12.8-3.9-21.9-6.7-28.3-10.5c-5.2-3.1-5.3-4.9-5.3-6.7c0-3.7 1.4-6.5 6.2-9.3c5.4-3.2 13.6-5.1 21.5-5c9.6 .1 20.2 2.2 31.2 5.2c10.7 2.8 21.6-3.5 24.5-14.2s-3.5-21.6-14.2-24.5c-6.5-1.7-13.7-3.4-21.1-4.7V216z" />
                            </svg>
                            <p class=" leading-4">Cuentas Bancarias</p>
                        </a>
                    @endcan

                    @can('accounting.sfp.show')
                        <a href="{{ route('reporting.balance') }}"
                            class="flex items-center space-x-6 pl-10 hover:text-white focus:bg-gray-700 focus:text-white hover:bg-gray-700 hover:bg-opacity-25 text-gray-300 rounded px-3 py-1 w-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 576 512"
                                style=" fill:currentColor;">
                                <path
                                    d="M64 64C28.7 64 0 92.7 0 128V384c0 35.3 28.7 64 64 64H512c35.3 0 64-28.7 64-64V128c0-35.3-28.7-64-64-64H64zm64 320H64V320c35.3 0 64 28.7 64 64zM64 192V128h64c0 35.3-28.7 64-64 64zM448 384c0-35.3 28.7-64 64-64v64H448zm64-192c-35.3 0-64-28.7-64-64h64v64zM400 256c0 61.9-50.1 112-112 112s-112-50.1-112-112s50.1-112 112-112s112 50.1 112 112zM252 208c0 9.7 6.9 17.7 16 19.6V276h-4c-11 0-20 9-20 20s9 20 20 20h24 24c11 0 20-9 20-20s-9-20-20-20h-4V208c0-11-9-20-20-20H272c-11 0-20 9-20 20z" />
                            </svg>
                            <p class=" leading-4">E. de Situacion F.</p>
                        </a>
                    @endcan

                    @can('accounting.balance.show')
                        <a href="{{ route('reporting.results') }}"
                            class="flex items-center space-x-6 pl-10 hover:text-white focus:bg-gray-700 focus:text-white hover:bg-gray-700 hover:bg-opacity-25 text-gray-300 rounded px-3 py-1 w-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 640 512"
                                style=" fill:currentColor;"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                <path
                                    d="M522.1 62.4c16.8-5.6 25.8-23.7 20.2-40.5S518.6-3.9 501.9 1.6l-113 37.7C375 15.8 349.3 0 320 0c-44.2 0-80 35.8-80 80c0 3 .2 5.9 .5 8.8L117.9 129.6c-16.8 5.6-25.8 23.7-20.2 40.5s23.7 25.8 40.5 20.2l135.5-45.2c4.5 3.2 9.3 5.9 14.4 8.2V480c0 17.7 14.3 32 32 32H512c17.7 0 32-14.3 32-32s-14.3-32-32-32H352V153.3c21-9.2 37.2-27 44.2-49l125.9-42zM439.6 288L512 163.8 584.4 288H439.6zM512 384c62.9 0 115.2-34 126-78.9c2.6-11-1-22.3-6.7-32.1L536.1 109.8c-5-8.6-14.2-13.8-24.1-13.8s-19.1 5.3-24.1 13.8L392.7 273.1c-5.7 9.8-9.3 21.1-6.7 32.1C396.8 350 449.1 384 512 384zM129.2 291.8L201.6 416H56.7l72.4-124.2zM3.2 433.1C14 478 66.3 512 129.2 512s115.2-34 126-78.9c2.6-11-1-22.3-6.7-32.1L153.2 237.8c-5-8.6-14.2-13.8-24.1-13.8s-19.1 5.3-24.1 13.8L9.9 401.1c-5.7 9.8-9.3 21.1-6.7 32.1z" />
                            </svg>
                            <p class=" leading-4">E. de Resultados</p>
                        </a>
                    @endcan

                    @can('accounting.ledger.show')
                        <a href="{{ route('reporting.ledger') }}"
                            class="flex items-center space-x-6 pl-10 hover:text-white focus:bg-gray-700 focus:text-white hover:bg-gray-700 hover:bg-opacity-25 text-gray-300 rounded px-3 py-1 w-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 384 512"
                                style=" fill:currentColor;"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                <path
                                    d="M64 0C28.7 0 0 28.7 0 64V448c0 35.3 28.7 64 64 64H320c35.3 0 64-28.7 64-64V160H256c-17.7 0-32-14.3-32-32V0H64zM256 0V128H384L256 0zM64 80c0-8.8 7.2-16 16-16h64c8.8 0 16 7.2 16 16s-7.2 16-16 16H80c-8.8 0-16-7.2-16-16zm0 64c0-8.8 7.2-16 16-16h64c8.8 0 16 7.2 16 16s-7.2 16-16 16H80c-8.8 0-16-7.2-16-16zm128 72c8.8 0 16 7.2 16 16v17.3c8.5 1.2 16.7 3.1 24.1 5.1c8.5 2.3 13.6 11 11.3 19.6s-11 13.6-19.6 11.3c-11.1-3-22-5.2-32.1-5.3c-8.4-.1-17.4 1.8-23.6 5.5c-5.7 3.4-8.1 7.3-8.1 12.8c0 3.7 1.3 6.5 7.3 10.1c6.9 4.1 16.6 7.1 29.2 10.9l.5 .1 0 0 0 0c11.3 3.4 25.3 7.6 36.3 14.6c12.1 7.6 22.4 19.7 22.7 38.2c.3 19.3-9.6 33.3-22.9 41.6c-7.7 4.8-16.4 7.6-25.1 9.1V440c0 8.8-7.2 16-16 16s-16-7.2-16-16V422.2c-11.2-2.1-21.7-5.7-30.9-8.9l0 0c-2.1-.7-4.2-1.4-6.2-2.1c-8.4-2.8-12.9-11.9-10.1-20.2s11.9-12.9 20.2-10.1c2.5 .8 4.8 1.6 7.1 2.4l0 0 0 0 0 0c13.6 4.6 24.6 8.4 36.3 8.7c9.1 .3 17.9-1.7 23.7-5.3c5.1-3.2 7.9-7.3 7.8-14c-.1-4.6-1.8-7.8-7.7-11.6c-6.8-4.3-16.5-7.4-29-11.2l-1.6-.5 0 0c-11-3.3-24.3-7.3-34.8-13.7c-12-7.2-22.6-18.9-22.7-37.3c-.1-19.4 10.8-32.8 23.8-40.5c7.5-4.4 15.8-7.2 24.1-8.7V232c0-8.8 7.2-16 16-16z" />
                            </svg>
                            <p class=" leading-4">Libro mayor</p>
                        </a>
                    @endcan
                </div>
            </div>
        </div>

        {{-- Menú reportes --}}
        <div class="flex flex-col">
            <a onclick="showMenu3(true)"
                class="flex items-center justify-between py-2 mt-2 px-2 text-gray-300 hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100"
                href="#">
                <div class="flex">
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <span class="mx-3">Reportes</span>
                </div>

                <svg id="icon3" class="transform rotate-180" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M18 15L12 9L6 15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </a>

            <div id="menu3"
                class="hidden flex flex-col w-full pb-1 bg-gray-700 border-r-4 bg-opacity-40 border-orange-400">
                <div class="">
                    @can('reporting.partners.balance.show')
                        <a href="{{ route('reporting.partners.balances') }}"
                            class="flex items-center space-x-6 pl-10 hover:text-white focus:bg-gray-700 focus:text-white hover:bg-gray-700 hover:bg-opacity-25 text-gray-300 rounded px-3 py-2 w-full">
                            <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24"
                                viewBox="0 0 172 172" style=" fill:currentColor;">
                                <path
                                    d="M129,14.33333c7.83362,0 14.33333,6.49972 14.33333,14.33333v114.66667c0,7.83362 -6.49972,14.33333 -14.33333,14.33333h-86c-7.83362,0 -14.33333,-6.49972 -14.33333,-14.33333v-7.16667h-7.16667c-3.956,0 -7.16667,-3.21067 -7.16667,-7.16667c0,-3.956 3.21067,-7.16667 7.16667,-7.16667h7.16667v-14.33333h-7.16667c-3.956,0 -7.16667,-3.21067 -7.16667,-7.16667c0,-3.956 3.21067,-7.16667 7.16667,-7.16667h7.16667v-14.33333h-7.16667c-3.956,0 -7.16667,-3.21067 -7.16667,-7.16667c0,-3.956 3.21067,-7.16667 7.16667,-7.16667h7.16667v-14.33333h-7.16667c-3.956,0 -7.16667,-3.21067 -7.16667,-7.16667c0,-3.956 3.21067,-7.16667 7.16667,-7.16667h7.16667v-7.16667c0,-7.83362 6.49972,-14.33333 14.33333,-14.33333zM43,143.33333h86v-114.66667h-86zM114.66667,50.16667v21.5h-57.33333v-21.5zM114.66667,86v14.33333h-57.33333v-14.33333zM114.66667,107.5v14.33333h-57.33333v-14.33333z">
                                </path>
                            </svg>

                            <p class=" leading-4">Saldos de socios</p>
                        </a>
                    @endcan

                    @can('reporting.transactions.show')
                        <a href="{{ route('reporting.transactions') }}"
                            class="flex items-center space-x-6 pl-10 hover:text-white focus:bg-gray-700 focus:text-white hover:bg-gray-700 hover:bg-opacity-25 text-gray-300 rounded px-3 py-2 w-full">
                            <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24"
                                viewBox="0 0 172 172" style=" fill:currentColor;">
                                <path
                                    d="M129,14.33333c7.83362,0 14.33333,6.49972 14.33333,14.33333v114.66667c0,7.83362 -6.49972,14.33333 -14.33333,14.33333h-86c-7.83362,0 -14.33333,-6.49972 -14.33333,-14.33333v-7.16667h-7.16667c-3.956,0 -7.16667,-3.21067 -7.16667,-7.16667c0,-3.956 3.21067,-7.16667 7.16667,-7.16667h7.16667v-14.33333h-7.16667c-3.956,0 -7.16667,-3.21067 -7.16667,-7.16667c0,-3.956 3.21067,-7.16667 7.16667,-7.16667h7.16667v-14.33333h-7.16667c-3.956,0 -7.16667,-3.21067 -7.16667,-7.16667c0,-3.956 3.21067,-7.16667 7.16667,-7.16667h7.16667v-14.33333h-7.16667c-3.956,0 -7.16667,-3.21067 -7.16667,-7.16667c0,-3.956 3.21067,-7.16667 7.16667,-7.16667h7.16667v-7.16667c0,-7.83362 6.49972,-14.33333 14.33333,-14.33333zM43,143.33333h86v-114.66667h-86zM114.66667,50.16667v21.5h-57.33333v-21.5zM114.66667,86v14.33333h-57.33333v-14.33333zM114.66667,107.5v14.33333h-57.33333v-14.33333z">
                                </path>
                            </svg>

                            <p class=" leading-4">Transacciones</p>
                        </a>
                    @endcan

                    @can('reporting.partners.show')
                        <a href="{{ route('reporting.partners') }}"
                            class="flex items-center space-x-6 pl-10 hover:text-white focus:bg-gray-700 focus:text-white hover:bg-gray-700 hover:bg-opacity-25 text-gray-300 rounded px-3 py-2 w-full">
                            <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24"
                                viewBox="0 0 172 172" style=" fill:currentColor;">
                                <path
                                    d="M129,14.33333c7.83362,0 14.33333,6.49972 14.33333,14.33333v114.66667c0,7.83362 -6.49972,14.33333 -14.33333,14.33333h-86c-7.83362,0 -14.33333,-6.49972 -14.33333,-14.33333v-7.16667h-7.16667c-3.956,0 -7.16667,-3.21067 -7.16667,-7.16667c0,-3.956 3.21067,-7.16667 7.16667,-7.16667h7.16667v-14.33333h-7.16667c-3.956,0 -7.16667,-3.21067 -7.16667,-7.16667c0,-3.956 3.21067,-7.16667 7.16667,-7.16667h7.16667v-14.33333h-7.16667c-3.956,0 -7.16667,-3.21067 -7.16667,-7.16667c0,-3.956 3.21067,-7.16667 7.16667,-7.16667h7.16667v-14.33333h-7.16667c-3.956,0 -7.16667,-3.21067 -7.16667,-7.16667c0,-3.956 3.21067,-7.16667 7.16667,-7.16667h7.16667v-7.16667c0,-7.83362 6.49972,-14.33333 14.33333,-14.33333zM43,143.33333h86v-114.66667h-86zM114.66667,50.16667v21.5h-57.33333v-21.5zM114.66667,86v14.33333h-57.33333v-14.33333zM114.66667,107.5v14.33333h-57.33333v-14.33333z">
                                </path>
                            </svg>

                            <p class=" leading-4">Socios</p>
                        </a>
                    @endcan

                    @can('reporting.accounts.show')
                        <a href="{{ route('reporting.accounts') }}"
                            class="flex items-center space-x-6 pl-10 hover:text-white focus:bg-gray-700 focus:text-white hover:bg-gray-700 hover:bg-opacity-25 text-gray-300 rounded px-3 py-2 w-full">
                            <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24"
                                viewBox="0 0 172 172" style=" fill:currentColor;">
                                <path
                                    d="M129,14.33333c7.83362,0 14.33333,6.49972 14.33333,14.33333v114.66667c0,7.83362 -6.49972,14.33333 -14.33333,14.33333h-86c-7.83362,0 -14.33333,-6.49972 -14.33333,-14.33333v-7.16667h-7.16667c-3.956,0 -7.16667,-3.21067 -7.16667,-7.16667c0,-3.956 3.21067,-7.16667 7.16667,-7.16667h7.16667v-14.33333h-7.16667c-3.956,0 -7.16667,-3.21067 -7.16667,-7.16667c0,-3.956 3.21067,-7.16667 7.16667,-7.16667h7.16667v-14.33333h-7.16667c-3.956,0 -7.16667,-3.21067 -7.16667,-7.16667c0,-3.956 3.21067,-7.16667 7.16667,-7.16667h7.16667v-14.33333h-7.16667c-3.956,0 -7.16667,-3.21067 -7.16667,-7.16667c0,-3.956 3.21067,-7.16667 7.16667,-7.16667h7.16667v-7.16667c0,-7.83362 6.49972,-14.33333 14.33333,-14.33333zM43,143.33333h86v-114.66667h-86zM114.66667,50.16667v21.5h-57.33333v-21.5zM114.66667,86v14.33333h-57.33333v-14.33333zM114.66667,107.5v14.33333h-57.33333v-14.33333z">
                                </path>
                            </svg>

                            <p class=" leading-4">Cuentas</p>
                        </a>
                    @endcan

                    @can('reporting.expenses.show')
                        <a href="{{ route('reporting.expenses') }}"
                            class="flex items-center space-x-6 pl-10 hover:text-white focus:bg-gray-700 focus:text-white hover:bg-gray-700 hover:bg-opacity-25 text-gray-300 rounded px-3 py-2 w-full">
                            <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24"
                                viewBox="0 0 172 172" style=" fill:currentColor;">
                                <path
                                    d="M129,14.33333c7.83362,0 14.33333,6.49972 14.33333,14.33333v114.66667c0,7.83362 -6.49972,14.33333 -14.33333,14.33333h-86c-7.83362,0 -14.33333,-6.49972 -14.33333,-14.33333v-7.16667h-7.16667c-3.956,0 -7.16667,-3.21067 -7.16667,-7.16667c0,-3.956 3.21067,-7.16667 7.16667,-7.16667h7.16667v-14.33333h-7.16667c-3.956,0 -7.16667,-3.21067 -7.16667,-7.16667c0,-3.956 3.21067,-7.16667 7.16667,-7.16667h7.16667v-14.33333h-7.16667c-3.956,0 -7.16667,-3.21067 -7.16667,-7.16667c0,-3.956 3.21067,-7.16667 7.16667,-7.16667h7.16667v-14.33333h-7.16667c-3.956,0 -7.16667,-3.21067 -7.16667,-7.16667c0,-3.956 3.21067,-7.16667 7.16667,-7.16667h7.16667v-7.16667c0,-7.83362 6.49972,-14.33333 14.33333,-14.33333zM43,143.33333h86v-114.66667h-86zM114.66667,50.16667v21.5h-57.33333v-21.5zM114.66667,86v14.33333h-57.33333v-14.33333zM114.66667,107.5v14.33333h-57.33333v-14.33333z">
                                </path>
                            </svg>

                            <p class=" leading-4">Compras</p>
                        </a>
                    @endcan
                </div>
            </div>
        </div>

        {{-- Menú administración --}}
        <div class="flex flex-col">
            <a onclick="showMenu4(true)"
                class="flex items-center justify-between py-2 mt-2 px-2 text-gray-300 hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100"
                href="#">
                <div class="flex">
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span class="mx-3">Administración</span>
                </div>

                <svg id="icon4" class="transform rotate-180" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M18 15L12 9L6 15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </a>

            <div id="menu4"
                class="hidden flex flex-col w-full pb-1 bg-gray-700 bg-opacity-40 border-r-4 border-orange-400">
                <div class="">

                    <a href="{{ route('partners.index') }}"
                        class="flex items-center space-x-6 pl-10 hover:text-white focus:bg-gray-700 focus:text-white hover:bg-gray-700 hover:bg-opacity-25 text-gray-300 rounded px-3 py-1 w-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 448 512"
                            style=" fill:currentColor;"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                            <path
                                d="M224 256A128 128 0 1 1 224 0a128 128 0 1 1 0 256zM209.1 359.2l-18.6-31c-6.4-10.7 1.3-24.2 13.7-24.2H224h19.7c12.4 0 20.1 13.6 13.7 24.2l-18.6 31 33.4 123.9 36-146.9c2-8.1 9.8-13.4 17.9-11.3c70.1 17.6 121.9 81 121.9 156.4c0 17-13.8 30.7-30.7 30.7H285.5c-2.1 0-4-.4-5.8-1.1l.3 1.1H168l.3-1.1c-1.8 .7-3.8 1.1-5.8 1.1H30.7C13.8 512 0 498.2 0 481.3c0-75.5 51.9-138.9 121.9-156.4c8.1-2 15.9 3.3 17.9 11.3l36 146.9 33.4-123.9z" />
                        </svg>

                        <p class=" leading-4">Socios</p>
                    </a>
                    @can('admin.suppliers.index')
                        <a href="{{ route('suppliers.index') }}"
                            class="flex items-center space-x-6 pl-10 hover:text-white focus:bg-gray-700 focus:text-white hover:bg-gray-700 hover:bg-opacity-25 text-gray-300 rounded px-3 py-1 w-full">
                            <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24"
                                viewBox="0 0 172 172" style=" fill:currentColor;">
                                <path
                                    d="M68.4775,15.1575c7.51156,0 14.01531,1.76031 17.63,6.45c2.83531,0.57781 5.36156,1.55875 7.525,2.9025c4.74344,-3.69531 11.22031,-5.76469 19.565,-5.9125c6.06031,0 11.23375,1.62594 14.5125,5.2675c5.0525,0.90031 9.05688,3.38625 11.5025,6.7725c2.58,3.56094 3.64156,7.82063 3.9775,12.04c0.59125,7.65938 -1.15562,15.00969 -2.58,19.6725c1.29,1.73344 2.31125,3.84313 1.935,6.9875v0.1075c-0.44344,3.62813 -1.42437,6.18125 -2.9025,8.0625c-0.72562,0.92719 -1.67969,1.00781 -2.58,1.505c-0.52406,3.01 -1.35719,5.97969 -2.58,8.4925c-0.72562,1.47813 -1.54531,2.84875 -2.365,3.9775c-0.30906,0.43 -0.84656,0.69875 -1.1825,1.075c-0.02687,3.44 0.04031,6.38281 0.3225,9.9975c0.87344,2.08281 2.84875,3.84313 6.3425,5.59c3.61469,1.81406 8.37156,3.38625 13.2225,5.4825c4.85094,2.09625 9.86313,4.63594 13.8675,8.7075c4.00438,4.07156 6.86656,9.76906 7.31,17.0925l0.215,3.655h-39.2375c2.58,4.05813 4.25969,8.98969 4.6225,14.9425l0.215,3.655h-138.03l0.215,-3.655c0.51063,-8.55969 3.81625,-15.05 8.4925,-19.78c4.67625,-4.73 10.58875,-7.74 16.34,-10.2125c5.75125,-2.4725 11.43531,-4.48812 15.8025,-6.665c4.23281,-2.10969 6.83969,-4.28656 7.955,-6.9875c0.36281,-4.47469 0.34938,-8.00875 0.3225,-12.255c-0.45687,-0.48375 -1.075,-0.81969 -1.505,-1.3975c-0.95406,-1.29 -1.935,-2.88906 -2.795,-4.6225c-1.505,-3.01 -2.51281,-6.61125 -3.1175,-10.2125c-1.11531,-0.56437 -2.31125,-0.67187 -3.225,-1.8275c-1.65281,-2.10969 -2.82187,-5.11969 -3.3325,-9.3525c-0.47031,-3.88344 0.83313,-6.46344 2.4725,-8.385c-3.05031,-12.55062 -3.99094,-24.68469 0.215,-34.2925c4.4075,-10.09156 14.67375,-16.58187 30.745,-16.8775zM43.9675,34.83c-3.37281,7.69969 -2.80844,19.0275 0.43,31.4975l0.645,2.4725l-2.25015,1.39295c0.07893,-0.04592 -1.30357,0.96636 -0.97485,3.76705c0.40313,3.37281 1.20938,5.11969 1.8275,5.9125c0.61813,0.79281 0.94063,0.7525 0.9675,0.7525l2.9025,0.215l0.3225,2.795c0.30906,2.94281 1.47813,6.61125 2.9025,9.46c0.71219,1.42438 1.505,2.67406 2.15,3.5475c0.645,0.87344 1.38406,1.34375 1.075,1.1825l1.8275,0.9675v2.0425c0,4.98531 0.20156,9.07031 -0.3225,14.835l-0.215,0.86c-1.96187,5.28094 -6.59781,8.57313 -11.61,11.0725c-5.01219,2.49938 -10.70969,4.34031 -16.125,6.665c-5.41531,2.32469 -10.41406,5.10625 -14.0825,8.815c-2.91594,2.94281 -4.73,6.85313 -5.6975,11.7175h122.12c-0.9675,-4.87781 -2.795,-8.77469 -5.6975,-11.7175c-0.1075,-0.1075 -0.215,-0.215 -0.3225,-0.3225c-0.01344,-0.01344 -0.09406,0.01344 -0.1075,0c-0.28219,-0.17469 -0.5375,-0.38969 -0.7525,-0.645c-0.18812,-0.12094 -0.37625,-0.26875 -0.5375,-0.43c-0.01344,-0.01344 0.01344,-0.09406 0,-0.1075c-3.42656,-2.95625 -7.64594,-5.32125 -12.255,-7.31c-5.38844,-2.32469 -11.12625,-4.16562 -16.125,-6.665c-4.99875,-2.49937 -9.54062,-5.79156 -11.5025,-11.0725l-0.215,-0.43v-0.43c-0.52406,-5.76469 -0.3225,-9.84969 -0.3225,-14.835v-2.0425l1.8275,-0.9675c-0.33594,0.17469 0.33594,-0.30906 0.9675,-1.1825c0.63156,-0.87344 1.34375,-2.12312 2.0425,-3.5475c1.38406,-2.84875 2.58,-6.47687 2.9025,-9.46l0.3225,-2.795l2.9025,-0.215c0.02688,0 0.34938,0.04031 0.9675,-0.7525c0.61813,-0.79281 1.42438,-2.53969 1.8275,-5.9125c0.33574,-2.75044 -1.00371,-3.77607 -0.97764,-3.76849l-2.35486,-1.39151l0.86,-2.6875c1.49156,-4.50156 3.87,-14.10937 3.225,-22.36c-0.3225,-4.12531 -1.3975,-7.86094 -3.3325,-10.535c-1.935,-2.67406 -4.67625,-4.60906 -9.46,-5.2675l-1.6125,-0.215l-0.86,-1.3975c-1.27656,-2.24406 -5.76469,-4.27312 -12.685,-4.3c-0.04031,0 -0.06719,0 -0.1075,0c-14.2975,0.29563 -21.15062,5.11969 -24.51,12.7925zM98.7925,29.1325c0.01344,0.02688 -0.01344,0.08063 0,0.1075c2.96969,4.09844 4.23281,9.03 4.6225,13.975c0.71219,9.05688 -1.43781,17.87188 -3.1175,23.3275c1.55875,1.92156 2.82188,4.4075 2.365,8.17c-0.51062,4.23281 -1.67969,7.24281 -3.3325,9.3525c-0.88687,1.12875 -2.0425,1.26313 -3.1175,1.8275c-0.59125,3.58781 -1.65281,7.2025 -3.1175,10.2125c-0.84656,1.73344 -1.74687,3.31906 -2.6875,4.6225c-0.41656,0.57781 -1.06156,0.91375 -1.505,1.3975c-0.02687,4.28656 -0.05375,7.83406 0.3225,12.3625c1.14219,2.66063 3.77594,4.78375 7.955,6.88c4.35375,2.17688 9.97063,4.1925 15.695,6.665c4.85094,2.09625 9.74219,4.58219 13.975,8.17h37.41c-0.84656,-3.68187 -2.2575,-6.73219 -4.515,-9.03c-2.99656,-3.03687 -7.095,-5.25406 -11.61,-7.2025c-4.515,-1.94844 -9.28531,-3.56094 -13.545,-5.6975c-4.25969,-2.13656 -8.26406,-4.93156 -9.9975,-9.5675l-0.215,-0.43v-0.43c-0.44344,-4.89125 -0.3225,-8.39844 -0.3225,-12.5775v-2.0425l1.8275,-0.86c0,0 -0.01344,-0.09406 0,-0.1075h0.1075c0.12094,-0.13437 0.33594,-0.44344 0.645,-0.86c0.51063,-0.69875 1.03469,-1.73344 1.6125,-2.9025c1.14219,-2.35156 2.21719,-5.32125 2.4725,-7.74l0.215,-2.795l2.9025,-0.215c-0.12094,0.01344 -0.01344,0.13438 0.43,-0.43c0.44344,-0.56437 1.16906,-1.94844 1.505,-4.73c0.2355,-1.94287 -0.67431,-2.68883 -0.69509,-2.7171l-2.31491,-1.3679l0.86,-2.58c1.23625,-3.74906 3.225,-11.87875 2.6875,-18.705c-0.26875,-3.41312 -1.14219,-6.46344 -2.6875,-8.6c-1.54531,-2.13656 -3.72219,-3.5475 -7.6325,-4.085l-1.6125,-0.3225l-0.86,-1.3975c-0.91375,-1.6125 -4.48812,-3.30562 -10.2125,-3.3325c-0.04031,0 -0.06719,0 -0.1075,0c-6.5575,0.13438 -11.16656,1.505 -14.405,3.655z">
                                </path>
                            </svg>

                            <p class=" leading-4">Proveedores</p>
                        </a>
                    @endcan
                    @can('admin.accounts.index')
                        <a href="{{ route('accounts.index') }}"
                            class="flex items-center space-x-6 pl-10 hover:text-white focus:bg-gray-700 focus:text-white hover:bg-gray-700 hover:bg-opacity-25 text-gray-300 rounded px-3 py-1 w-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 448 512"
                                style=" fill:currentColor;"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                <path
                                    d="M0 80V229.5c0 17 6.7 33.3 18.7 45.3l176 176c25 25 65.5 25 90.5 0L418.7 317.3c25-25 25-65.5 0-90.5l-176-176c-12-12-28.3-18.7-45.3-18.7H48C21.5 32 0 53.5 0 80zm112 32a32 32 0 1 1 0 64 32 32 0 1 1 0-64z" />
                            </svg>

                            <p class=" leading-4">Cuentas</p>
                        </a>
                    @endcan

                    @can('admin.users.index')
                        <a href="{{ route('users.index') }}"
                            class="flex items-center space-x-6 pl-10 hover:text-white focus:bg-gray-700 focus:text-white hover:bg-gray-700 hover:bg-opacity-25 text-gray-300 rounded px-3 py-1 w-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 640 512"
                                style=" fill:currentColor;">
                                <path
                                    d="M352 128c0 70.7-57.3 128-128 128s-128-57.3-128-128S153.3 0 224 0s128 57.3 128 128zM0 482.3C0 383.8 79.8 304 178.3 304h91.4C368.2 304 448 383.8 448 482.3c0 16.4-13.3 29.7-29.7 29.7H29.7C13.3 512 0 498.7 0 482.3zM609.3 512H471.4c5.4-9.4 8.6-20.3 8.6-32v-8c0-60.7-27.1-115.2-69.8-151.8c2.4-.1 4.7-.2 7.1-.2h61.4C567.8 320 640 392.2 640 481.3c0 17-13.8 30.7-30.7 30.7zM432 256c-31 0-59-12.6-79.3-32.9C372.4 196.5 384 163.6 384 128c0-26.8-6.6-52.1-18.3-74.3C384.3 40.1 407.2 32 432 32c61.9 0 112 50.1 112 112s-50.1 112-112 112z" />
                            </svg>

                            <p class=" leading-4">Usuarios</p>
                        </a>
                    @endcan

                    @can('admin.roles.index')
                        <a href="{{ route('roles.index') }}"
                            class="flex items-center space-x-6 pl-10 hover:text-white focus:bg-gray-700 focus:text-white hover:bg-gray-700 hover:bg-opacity-25 text-gray-300 rounded px-3 py-1 w-full">
                            <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24"
                                viewBox="0 0 24 24" style=" fill:currentColor;">
                                <path
                                    d="M 7 5 C 3.1545455 5 0 8.1545455 0 12 C 0 15.845455 3.1545455 19 7 19 C 9.7749912 19 12.089412 17.314701 13.271484 15 L 16 15 L 16 18 L 22 18 L 22 15 L 24 15 L 24 9 L 23 9 L 13.287109 9 C 12.172597 6.6755615 9.8391582 5 7 5 z M 7 7 C 9.2802469 7 11.092512 8.4210017 11.755859 10.328125 L 11.988281 11 L 22 11 L 22 13 L 20 13 L 20 16 L 18 16 L 18 13 L 12.017578 13 L 11.769531 13.634766 C 11.010114 15.575499 9.1641026 17 7 17 C 4.2454545 17 2 14.754545 2 12 C 2 9.2454545 4.2454545 7 7 7 z M 7 9 C 5.3549904 9 4 10.35499 4 12 C 4 13.64501 5.3549904 15 7 15 C 8.6450096 15 10 13.64501 10 12 C 10 10.35499 8.6450096 9 7 9 z M 7 11 C 7.5641294 11 8 11.435871 8 12 C 8 12.564129 7.5641294 13 7 13 C 6.4358706 13 6 12.564129 6 12 C 6 11.435871 6.4358706 11 7 11 z">
                                </path>
                            </svg>

                            <p class=" leading-4">Roles y permisos</p>
                        </a>
                    @endcan

                    @can('admin.companies.index')
                        <a href="{{ route('companies.index') }}"
                            class="flex items-center space-x-6 pl-10 hover:text-white focus:bg-gray-700 focus:text-white hover:bg-gray-700 hover:bg-opacity-25 text-gray-300 rounded px-3 py-1 w-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 384 512"
                                style=" fill:currentColor;">
                                <path
                                    d="M88 104C88 95.16 95.16 88 104 88H152C160.8 88 168 95.16 168 104V152C168 160.8 160.8 168 152 168H104C95.16 168 88 160.8 88 152V104zM280 88C288.8 88 296 95.16 296 104V152C296 160.8 288.8 168 280 168H232C223.2 168 216 160.8 216 152V104C216 95.16 223.2 88 232 88H280zM88 232C88 223.2 95.16 216 104 216H152C160.8 216 168 223.2 168 232V280C168 288.8 160.8 296 152 296H104C95.16 296 88 288.8 88 280V232zM280 216C288.8 216 296 223.2 296 232V280C296 288.8 288.8 296 280 296H232C223.2 296 216 288.8 216 280V232C216 223.2 223.2 216 232 216H280zM0 64C0 28.65 28.65 0 64 0H320C355.3 0 384 28.65 384 64V448C384 483.3 355.3 512 320 512H64C28.65 512 0 483.3 0 448V64zM48 64V448C48 456.8 55.16 464 64 464H144V400C144 373.5 165.5 352 192 352C218.5 352 240 373.5 240 400V464H320C328.8 464 336 456.8 336 448V64C336 55.16 328.8 48 320 48H64C55.16 48 48 55.16 48 64z" />
                            </svg>

                            <p class=" leading-4">Empresas</p>
                        </a>
                    @endcan

                    @can('admin.config.accounting.index')
                        <a href="{{ route('accounting.config.index') }}"
                            class="flex items-center space-x-6 pl-10 hover:text-white focus:bg-gray-700 focus:text-white hover:bg-gray-700 hover:bg-opacity-25 text-gray-300 rounded px-3 py-1 w-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 384 512"
                                style=" fill:currentColor;">
                                <path
                                    d="M64 0C28.7 0 0 28.7 0 64V448c0 35.3 28.7 64 64 64H320c35.3 0 64-28.7 64-64V160H256c-17.7 0-32-14.3-32-32V0H64zM256 0V128H384L256 0zM64 80c0-8.8 7.2-16 16-16h64c8.8 0 16 7.2 16 16s-7.2 16-16 16H80c-8.8 0-16-7.2-16-16zm0 64c0-8.8 7.2-16 16-16h64c8.8 0 16 7.2 16 16s-7.2 16-16 16H80c-8.8 0-16-7.2-16-16zm128 72c8.8 0 16 7.2 16 16v17.3c8.5 1.2 16.7 3.1 24.1 5.1c8.5 2.3 13.6 11 11.3 19.6s-11 13.6-19.6 11.3c-11.1-3-22-5.2-32.1-5.3c-8.4-.1-17.4 1.8-23.6 5.5c-5.7 3.4-8.1 7.3-8.1 12.8c0 3.7 1.3 6.5 7.3 10.1c6.9 4.1 16.6 7.1 29.2 10.9l.5 .1 0 0 0 0c11.3 3.4 25.3 7.6 36.3 14.6c12.1 7.6 22.4 19.7 22.7 38.2c.3 19.3-9.6 33.3-22.9 41.6c-7.7 4.8-16.4 7.6-25.1 9.1V440c0 8.8-7.2 16-16 16s-16-7.2-16-16V422.2c-11.2-2.1-21.7-5.7-30.9-8.9l0 0c-2.1-.7-4.2-1.4-6.2-2.1c-8.4-2.8-12.9-11.9-10.1-20.2s11.9-12.9 20.2-10.1c2.5 .8 4.8 1.6 7.1 2.4l0 0 0 0 0 0c13.6 4.6 24.6 8.4 36.3 8.7c9.1 .3 17.9-1.7 23.7-5.3c5.1-3.2 7.9-7.3 7.8-14c-.1-4.6-1.8-7.8-7.7-11.6c-6.8-4.3-16.5-7.4-29-11.2l-1.6-.5 0 0c-11-3.3-24.3-7.3-34.8-13.7c-12-7.2-22.6-18.9-22.7-37.3c-.1-19.4 10.8-32.8 23.8-40.5c7.5-4.4 15.8-7.2 24.1-8.7V232c0-8.8 7.2-16 16-16z" />
                            </svg>

                            <p class=" leading-4">Contabilidad</p>
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
                if (menu2 !== null) {
                    menu2.classList.add("hidden");
                }
                if (menu3 !== null) {
                    menu3.classList.add("hidden");
                }
                if (menu4 !== null) {
                    menu4.classList.add("hidden");
                }
                if (menu5 !== null) {
                    menu5.classList.add("hidden");
                }
            }
        };

        let icon2 = document.getElementById("icon2");
        let menu2 = document.getElementById("menu2");
        const showMenu2 = (flag) => {
            if (flag) {
                icon2.classList.toggle("rotate-180");
                menu2.classList.toggle("hidden");

                // Oculta los demás submenús abiertos
                if (menu1 !== null) {
                    menu1.classList.add("hidden");
                }
                if (menu3 !== null) {
                    menu3.classList.add("hidden");
                }
                if (menu4 !== null) {
                    menu4.classList.add("hidden");
                }
                if (menu5 !== null) {
                    menu5.classList.add("hidden");
                }
            }
        };

        let icon3 = document.getElementById("icon3");
        let menu3 = document.getElementById("menu3");
        const showMenu3 = (flag) => {
            if (flag) {
                icon3.classList.toggle("rotate-180");
                menu3.classList.toggle("hidden");

                // Oculta los demás submenús abiertos
                if (menu2 !== null) {
                    menu2.classList.add("hidden");
                }
                if (menu4 !== null) {
                    menu4.classList.add("hidden");
                }
                if (menu1 !== null) {
                    menu1.classList.add("hidden");
                }
                if (menu5 !== null) {
                    menu5.classList.add("hidden");
                }
            }
        };

        let icon4 = document.getElementById("icon4");
        let menu4 = document.getElementById("menu4");
        const showMenu4 = (flag) => {
            if (flag) {
                icon4.classList.toggle("rotate-180");
                menu4.classList.toggle("hidden");

                // Oculta los demás submenús abiertos
                if (menu2 !== null) {
                    menu2.classList.add("hidden");
                }
                if (menu3 !== null) {
                    menu3.classList.add("hidden");
                }
                if (menu1 !== null) {
                    menu1.classList.add("hidden");
                }
                if (menu5 !== null) {
                    menu5.classList.add("hidden");
                }
            }
        };

        let icon5 = document.getElementById("icon5");
        let menu5 = document.getElementById("menu5");
        const showMenu5 = (flag) => {
            if (flag) {
                icon5.classList.toggle("rotate-180");
                menu5.classList.toggle("hidden");

                // Oculta los demás submenús abiertos
                if (menu2 !== null) {
                    menu2.classList.add("hidden");
                }
                if (menu3 !== null) {
                    menu3.classList.add("hidden");
                }
                if (menu1 !== null) {
                    menu1.classList.add("hidden");
                }
                if (menu4 !== null) {
                    menu4.classList.add("hidden");
                }
            }
        };

        let icon6 = document.getElementById("icon6");
        let menu6 = document.getElementById("menu6");
        const showMenu6 = (flag) => {
            if (flag) {
                icon6.classList.toggle("rotate-180");
                menu6.classList.toggle("hidden");

                // Oculta los demás submenús abiertos
                if (menu2 !== null) {
                    menu2.classList.add("hidden");
                }
                if (menu3 !== null) {
                    menu3.classList.add("hidden");
                }
                if (menu1 !== null) {
                    menu1.classList.add("hidden");
                }
                if (menu4 !== null) {
                    menu4.classList.add("hidden");
                }
                if (menu5 !== null) {
                    menu5.classList.add("hidden");
                }
            }
        };
    </script>
</div>
