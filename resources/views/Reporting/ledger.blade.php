<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Libro mayor
        </h2>
    </x-slot>

    @section('body')       

        @livewire('reporting.ledger')
        
    @endsection
</x-app-layout>