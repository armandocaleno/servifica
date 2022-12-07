<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Reporte de transacciones
        </h2>
    </x-slot>

    @section('body')       

        @livewire('reporting.transactions')
        
    @endsection
</x-app-layout>