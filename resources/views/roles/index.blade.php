<x-app-layout>    
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Administración | Roles
        </h2>
    </x-slot>

    @section('body')    
        {{-- New role button --}}
        @can('admin.roles.create')
            <a href="{{ route('roles.create') }}" class="bg-orange-600 inline-flex items-center px-4 py-2 rounded-md font-semibold text-white shadow-md hover:bg-opacity-80 focus:outline-none focus:ring active:text-gray-700 disabled:opacity-25 transition">Nuevo rol</a>
        @endcan
       </div>

         {{-- Tabla --}}
        <div class="col-span-3 p-4">
            <div class="flex flex-col">
                <div class="-my-2 py-2 overflow-x-auto lg:-mx-8 lg:px-8">
                    <div class="align-middle inrole-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">
                        <table class="min-w-full">
                            <thead>
                                <tr>                                    
                                    <th class="thead">ID</th>
                                    <th class="thead">Nombre</th>                                   
                                    <th class="thead"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                @if ($roles->count())
                                    @foreach ($roles as $item)                                                                           
                                        <tr>                                            
                                            <td class="row">{{ $item->id }}</td>
                                            <td class="row">{{ $item->name }}</td>                                                                                      
                                            <td class="row">
                                                <div class="flex space-x-4">
                                                    @can('admin.roles.edit')
                                                        <a href="{{ route('roles.edit', $item) }}" class="text-gray-600 hover:opacity-50"><i class="fa-solid fa-pen-to-square"></i></a>
                                                    @endcan

                                                    @can('admin.roles.delete')
                                                        <form action="{{ route('roles.destroy', $item) }}" method="post">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-orange-500 hover:opacity-50"><i class="fa-solid fa-trash-can"></i></button>
                                                        </form>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="row" colspan="3">No se encontraron registros</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>       
    @endsection     
</x-app-layout>