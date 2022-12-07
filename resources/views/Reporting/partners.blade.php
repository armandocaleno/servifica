<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Reporte de socios
        </h2>
    </x-slot>

    @section('body')       

        @livewire('reporting.partners')
        
    @endsection
</x-app-layout>