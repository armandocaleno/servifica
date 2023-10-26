<div class=" ">
    <div class=" mb-2 flex items-center space-x-4 w-1/2">
        <x-jet-label for="accounting_code" value="Buscar: " />
        <x-jet-input type="text" class="mt-1 shadow-md block w-full" wire:model="search" />
    </div>

    <div class="p-8 h-80 min-h-max border rounded-md bg-white shadow-md overflow-auto">

    <table class="table text-sm">
        <thead>
            <tr>
                <th class="hidden">ID</th>
                <th>Código</th>
                <th>Nombre</th>
                <th>Nivel</th>
                <th>Tipo</th>
                <th>Clase</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($accountings as $item)
                <tr class="{{ $item->group ? 'font-thin' : 'font-semibold' }}">
                    <td class="hidden">{{ $item->id }}</td>
                    <td class="row text-right">{{ $item->code }}</td>
                    <td class="row">{{ $item->name }}</td>
                    {{-- <td class="row">{{ $item->type->name }}</td> --}}
                    <td class="row">{{ $item->level }}</td>
                    <td class="row">{{ $item->class->name }}</td>
                    <td class="row">{{ $item->group ? "Grupo" : "Movimiento" }}</td>
                    <td class="row whitespace-nowrap space-x-2">
                        @can('accounting.accounts.edit') 
                            <a href="{{ route('accounting.edit', $item) }}" class="text-blue-600 "><i class="fa-solid fa-pen-to-square"></i></a>
                        @endcan
                        @can('accounting.accounts.delete') 
                            <button wire:click="destroy({{ $item }})" class="text-red-600 "><i class="fa-solid fa-trash"></i></button>
                        @endcan
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
        
    </div>

    <div class="relative mt-4">
        @can('accounting.accounts.create') 
            <x-jet-button>
                <a href="{{ route('accounting.create') }}" class="">Nueva</a>
            </x-jet-button>
        @endcan
    </div>

    @push('js')
        <script>
            
            $(document).ready(function () {
                listree();
            });

            Livewire.on('searching', function() {
               listree();
            })
        </script>
    @endpush
</div>
