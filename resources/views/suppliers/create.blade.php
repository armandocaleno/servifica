<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Nuevo proveedor
        </h2>
    </x-slot>

    @section('body')
        <form action="{{ route('suppliers.store') }}" method="post">
            @csrf
            
            @include('suppliers.partials.form')

            <x-jet-button class="mt-4" type="submit">
                Guardar
            </x-jet-button>
        </form>
    @endsection
</x-app-layout>