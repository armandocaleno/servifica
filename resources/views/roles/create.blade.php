<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Administración | Nuevo rol
        </h2>
    </x-slot>

    @section('body')
       <div class="">
            <form method="POST" action="{{ route('roles.store') }}">
                @csrf                                       

                
            
                <div class="mt-4 text-right">                       
                    <x-jet-button type="submit">
                        Crear rol
                    </x-jet-button>
                </div>

                @include('roles.partials.form')
            </form>            
       </div>
    @endsection
</x-app-layout>