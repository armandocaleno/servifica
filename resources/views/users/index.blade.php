<x-app-layout>      
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Administración | Usuarios
        </h2>
    </x-slot>

    @section('body')    
       <div class=" flex items-center justify-end mb-4">
           
           
            {{-- New user button --}}
            {{-- @can('admin.users.create') --}}
                <a href="{{ route('users.create') }}" class="bg-orange-600 inline-flex items-center px-4 py-2 rounded-md font-semibold text-white shadow-md hover:bg-opacity-80 focus:outline-none focus:ring active:text-gray-700 disabled:opacity-25 transition">Nuevo usuario</a>
            {{-- @endcan --}}
       </div>

         {{-- Tabla --}}
        {{-- <div class=""> --}}
            <div class="flex flex-col">
                <div class="-my-2 py-2 overflow-x-auto lg:-mx-8 lg:px-8">
                    <div class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">
                        <table class="min-w-full">
                            <thead>
                                <tr>                                    
                                    <th class="thead">ID</th>
                                    <th class="thead">Nombre</th>
                                    <th class="thead">Correo electrónico</th>
                                    <th class="thead">Roles</th>
                                    <th class="thead"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                @if ($users->count())
                                    @foreach ($users as $item)                                                                           
                                        <tr>                                            
                                            <td class="row">{{ $item->id }}</td>
                                            <td class="row whitespace-nowrap">{{ $item->name }}</td>
                                            <td class="row">{{ $item->email }}</td>
                                            <td class="row whitespace-nowrap">                                                
                                                <ul>                                                
                                                    @foreach ($item->getRoleNames() as $rol)
                                                        <li>{{ $rol }}</li>
                                                    @endforeach                                                
                                                </ul>
                                            </td>
                                            <td class="row">
                                                <div class="flex space-x-4">
                                                    {{-- @can('admin.users.edit') --}}
                                                        <a href="{{ route('users.edit', $item) }}" class="text-gray-600 hover:opacity-50"><i class="fa-solid fa-pen-to-square"></i></a>
                                                    {{-- @endcan --}}

                                                    {{-- @can('admin.users.delete') --}}
                                                        <form action="{{ route('users.destroy', $item) }}" method="post">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-orange-500 hover:opacity-50"><i class="fa-solid fa-trash-can"></i></button>
                                                        </form>
                                                    {{-- @endcan --}}
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="row" colspan="5">No se encontraron registros</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Pagination --}}
            @if ($users->hasPages())
                <div class="px-4 py-4 bg-white border border-gray-200 mt-2 rounded-md shadow-lg">
                    {{ $users->links() }}   
                </div>
            @endif
        {{-- </div> --}}
    @endsection     
</x-app-layout>
