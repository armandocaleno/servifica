<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Registro de cheque
        </h2>
    </x-slot>

    @section('body')
        @livewire('checks.create', ['check' => $check])
    @endsection
</x-app-layout>