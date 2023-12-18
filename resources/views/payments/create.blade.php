<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Nuevo pago varios
        </h2>
    </x-slot>

    @section('body')
        @livewire('payments.create', ['payment' => $payment])
    @endsection
</x-app-layout>


