<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Nueva cuenta contable
        </h2>
    </x-slot>

    @section('body')
        @livewire('accounting.create', ['accounting' => $accounting])
    @endsection
</x-app-layout>