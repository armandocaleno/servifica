<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Nueva transacción por lotes
        </h2>
    </x-slot>

    @section('body')       

        @livewire('transaction.lotes') 
      
    @endsection
</x-app-layout>