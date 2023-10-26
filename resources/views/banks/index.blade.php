<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Bancos
        </h2>
    </x-slot>

    @section('body')
        @livewire('banks.index')
    @endsection
</x-app-layout>