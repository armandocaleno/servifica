<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Cuentas
        </h2>
    </x-slot>

    @section('body')
        @livewire('accounts.index')
    @endsection
</x-app-layout>