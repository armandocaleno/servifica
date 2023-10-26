<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Reporte de facturas de compra
        </h2>
    </x-slot>

    @section('body')       

        @livewire('reporting.expenses')
        
    @endsection
</x-app-layout>