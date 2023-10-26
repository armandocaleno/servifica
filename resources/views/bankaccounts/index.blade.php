<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Cuentas bancarias
        </h2>
    </x-slot>

    @section('body')
        @livewire('bank-accounts.index')
    @endsection
</x-app-layout>