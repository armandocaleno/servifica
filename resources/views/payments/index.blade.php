<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Pagos varios
        </h2>
    </x-slot>

    @section('body')
        @livewire('payments.index')
    @endsection
</x-app-layout>