<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Nueva empresa
        </h2>
    </x-slot>

    @section('body')
        <form action="{{ route('companies.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            
            @include('companies.partials.form')

            <x-jet-button class="mt-4" type="submit">
                Guardar
            </x-jet-button>
        </form>
    @endsection
</x-app-layout>