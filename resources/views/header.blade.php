<header class="flex items-center justify-between px-6 py-4 bg-white border-b-2 border-gray-600 shadow-md">
    <div class="flex items-center">
        <button @click="sidebarOpen = true" class="text-gray-500 focus:outline-none lg:hidden">
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4 6H20M4 12H20M4 18H11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>        
    </div>    

    <div class="hidden sm:flex items-center space-x-4 font-semibold text-lg text-gray-700">
        <p>{{ session('company')->businessname }}</p>
        
        @php                
            $path = '/images/logo/'.session('company')->ruc.'/'.session('company')->logo;
        @endphp
        @if (session('company')->logo)                   
            @if (file_exists(public_path($path)))
                <img src="{{ asset($path) }}" alt="{{ session('company')->businessname }}" id="logo2" class="rounded-full h-8 w-8 object-cover">                                         
            @endif 
        @endif
    </div>
    
    <div class="flex items-center">      
        <div x-data="{ dropdownOpen: false }"  class="relative">
            <div class="flex items-center space-x-2">
                <div class=" text-right">
                    <div class="text-gray-700">{{ auth()->user()->name }}</div>                    
                </div>
                <button @click="dropdownOpen = ! dropdownOpen" class="relative block w-8 h-8 overflow-hidden rounded-full shadow focus:outline-none">                   
                    <img class="object-cover w-full h-full" src="{{ Auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}">
                </button>
            </div>

            <div x-cloak x-show="dropdownOpen" @click="dropdownOpen = false" class="fixed inset-0 z-10 w-full h-full"></div>

            <template x-if="true">
                <div x-show="dropdownOpen" class="absolute right-0 z-10 w-48 mt-2 overflow-hidden bg-white rounded-md shadow-xl">
                    <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-700 hover:text-white">Perfil</a>
                   
                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf                   
                        <a href="{{ route('logout') }}"  onclick="event.preventDefault(); this.closest('form').submit();" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-700 hover:text-white">Cerrar sesión</a>
                    </form>
                </div>
            </template>
        </div>
    </div>
</header>