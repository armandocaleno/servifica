<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Inicio
        </h2>
    </x-slot>

    @section('body')        
        <div class="flex flex-col align-middle text-center items-center bg-white">
            {{-- <span class=" font-bold text-2xl mb-4">SERVIFICA SYSTEM</span> --}}            
            <img src="{{ asset('/images/fondo/fondo.png') }}" style="width: 80%" alt="">
        </div>
    @endsection
</x-app-layout>