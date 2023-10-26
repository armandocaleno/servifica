<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Estado de Situacion Financiera
        </h2>
    </x-slot>

    @section('body')       
        @livewire('reporting.balance')
    @endsection
</x-app-layout>