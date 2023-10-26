<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Nueva compra - gasto
        </h2>
    </x-slot>

    @section('body')
        @livewire('expenses.create', ['expense' => $expense])
    @endsection

    {{-- <script src="inputmask/dist/jquery.inputmask.js"></script> --}}

    {{-- @push('js')
        <script src="inputmask/dist/jquery.inputmask.js"></script>
    @endpush --}}
   
</x-app-layout>