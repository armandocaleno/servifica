<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Proveedores
        </h2>
    </x-slot>

    @section('body')
        @livewire('suppliers.index')
    @endsection
</x-app-layout>