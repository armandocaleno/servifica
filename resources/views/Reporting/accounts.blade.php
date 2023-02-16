<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Reporte de cuentas
        </h2>
    </x-slot>

    @section('body')       

        @livewire('reporting.total-accounts')
        
    @endsection
</x-app-layout>