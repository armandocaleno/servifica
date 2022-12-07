<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Modificar Socio
        </h2>
    </x-slot>

    @section('body')
        <form action="{{ route('partners.update') }}" method="post">
            @csrf
            
            @include('partners.partials.form')

            <x-jet-button class="mt-4" type="submit">
                Guardar
            </x-jet-button>
        </form>
    @endsection
</x-app-layout>