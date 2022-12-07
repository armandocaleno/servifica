<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Administración | Editar usuario
        </h2>
    </x-slot>

    @section('body')        
       <div class="grid grid-cols-3">
            <div class="col-span-3 sm:col-span-2 xl:col-span-1">
               
                {!! Form::model($user, ['route' => ['users.update', $user], 'method' => 'put']) !!}
                     <!-- Name -->
                     <div class="mb-4">
                        <x-jet-label value="Nombre" />
                        {!! Form::text('name', null, ['class' => 'mt-1 block w-full rounded border-gray-400 text-gray-700']) !!}
                        <x-jet-input-error for="name" class="mt-2" />
                    </div>

                    {{-- Email --}}
                    <div class="mb-4">
                        <x-jet-label value="Correo electrónico" />
                        {!! Form::email('email', null, ['class' => 'mt-1 block w-full rounded border-gray-400 text-gray-700']) !!}                        
                        <x-jet-input-error for="email" class="mt-2" />
                    </div>

                    {{-- roles --}}
                    <div class="mt-4">
                        <x-jet-label value="Roles"/>                                 
                
                        @foreach ($roles as $item)
                            <div class="my-2">
                                <label class="block font-medium text-sm text-gray-700">                                    
                                    {!! Form::checkbox('roles[]', $item->id, null, ['class' => 'mr-2 mb-1']) !!}
                                    {{ $item->name }}
                                </label>
                            </div>                    
                        @endforeach                                            
                    </div>

                    {!! Form::submit('Actualizar', ['class' => 'bg-orange-600 items-center px-3 py-1 cursor-pointer rounded-md font-semibold text-white shadow-md hover:bg-opacity-80 focus:outline-none focus:ring active:text-gray-700 disabled:opacity-25 transition']) !!}

                {!! Form::close() !!}
            </div>
       </div>
    @endsection
</x-app-layout>