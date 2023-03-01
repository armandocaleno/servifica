<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Plan de Cuentas
        </h2>
    </x-slot>

    @section('body')
        @livewire('accounting.index')
    @endsection

    @push('js')
        <script src="{{ asset('js/plan.js') }}"></script>
        <script>
            // alert(JSON.stringify(list.innerHTML)); //obtener todo el contenido html
            // alert(list.dataset.id); // obtiene el valor del atributo personalizado

           
        </script>
    @endpush
</x-app-layout>
