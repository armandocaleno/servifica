<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Modificar empresa
        </h2>
    </x-slot>

    @section('body')
        <form action="{{ route('companies.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            @include('companies.partials.form')

            <x-jet-button class="mt-4" type="submit">
                Guardar
            </x-jet-button>
        </form>
    @endsection

    @push('js')
        <script>
            document.getElementById("logo").addEventListener('change', cambiarImagen);

            function cambiarImagen(event){
                var file = event.target.files[0];

                var reader = new FileReader();
                reader.onload = (event) => {
                    document.getElementById("picture").setAttribute('src', event.target.result);
                };

                reader.readAsDataURL(file);
            }
        </script>
    @endpush
</x-app-layout>