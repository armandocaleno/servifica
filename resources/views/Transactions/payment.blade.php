<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Nuevo pago
        </h2>
    </x-slot>

    @section('body')       

        @livewire('transaction.payment', ['transaction'=> $transaction])      
        
    @endsection
</x-app-layout>