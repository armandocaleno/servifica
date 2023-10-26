<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Administración | Editar rol de usuario
        </h2>
    </x-slot>
    
    @section('body')
       <div class="">                
            {!! Form::model($role, ['route' => ['roles.update', $role], 'method' => 'put']) !!}
                
                @include('roles.partials.form')

                {!! Form::submit('Actualizar', ['class' => 'bg-orange-600 items-center px-3 py-1 cursor-pointer rounded-md font-semibold text-white shadow-md hover:bg-opacity-80 focus:outline-none focus:ring active:text-gray-700 disabled:opacity-25 transition']) !!}

            {!! Form::close() !!}            
       </div>
    @endsection
</x-app-layout>