<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Estado de Resultados
        </h2>
    </x-slot>

    @section('body')       

        @livewire('reporting.results')
        
    @endsection
</x-app-layout>