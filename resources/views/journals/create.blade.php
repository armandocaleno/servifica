<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Crear Asiento contable
        </h2>
    </x-slot>

    @section('body')
        {{-- @livewire('journals.create') --}}
        @livewire('journals.create', ['journal' => $journal])
    @endsection
</x-app-layout>