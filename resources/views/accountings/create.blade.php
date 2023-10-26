<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @if ($accounting->id)
                Editar cuenta contable: {{ $accounting->name }}
            @else
                Nueva cuenta contable
            @endif
           
        </h2>
    </x-slot>

    @section('body')
        @livewire('accounting.create', ['accounting' => $accounting, 'global_level' => $level])
    @endsection
</x-app-layout>