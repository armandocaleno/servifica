<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Configuracion de cuentas contables
        </h2>
    </x-slot>

    @section('body')
       @livewire('accounting-config.index')
    @endsection
</x-app-layout>