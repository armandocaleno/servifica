<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Administración | Nuevo usuario
        </h2>
    </x-slot>

    @section('body')        

       <div class="grid grid-cols-3">
            <div class="col-span-3 sm:col-span-2 xl:col-span-1">
                <form method="POST" action="{{ route('users.store') }}">
                    @csrf
                    <!-- Name -->
                    <div class="mb-4">
                        <x-jet-label value="Nombre" />
                        <x-jet-input type="text" name="name" class="mt-1 block w-full" :value="old('name')" autofocus/>
                        <x-jet-input-error for="name" class="mt-2" />
                    </div>
                
                     <!-- Email -->
                     <div class="mb-4">
                        <x-jet-label value="Correo electrónico" />
                        <x-jet-input type="email" name="email" class="mt-1 block w-full" :value="old('email')" />
                        <x-jet-input-error for="email" class="mt-2" />
                    </div>
                
                     <!-- Password -->
                     <div class="mb-4">
                        <x-jet-label value="Contraseña" />
                        <x-jet-input type="password" name="password" class="mt-1 block w-full" />                    
                        <x-jet-input-error for="password" class="mt-2" />
                    </div>
                
                    {{-- password_confirmation --}}
                    <div class="mt-4">
                        <x-jet-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                        <x-jet-input class="block mt-1 w-full" type="password" name="password_confirmation" />
                        <x-jet-input-error for="password_confirmation" class="mt-2" />
                    </div> 
                
                     {{-- Rol --}}
                     <div class="mt-4">
                        <x-jet-label value="Roles"/>                                 
                        <div class="my-2">
                            @foreach ($roles as $item)                                
                                    <label class="block font-medium text-sm text-gray-700">
                                        <input type="checkbox" class="mr-2" name="roles[]" value="{{ $item->id }}"> {{ $item->name }}
                                    </label>                                            
                            @endforeach
                        </div>      
                                            
                    </div>
                
                    <div class="mt-4 text-right">                       
                        <x-jet-button type="submit">
                            Aceptar
                        </x-jet-button>
                    </div>
                </form>
            </div>
       </div>
    @endsection
</x-app-layout>