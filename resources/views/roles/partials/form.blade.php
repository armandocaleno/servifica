{{-- Name --}}
<div class="mb-4">
    <x-jet-label value="Nombre" />
    {!! Form::text('name', null, ['class' => 'mt-1 block w-full rounded border-gray-400 text-gray-700']) !!}
    <x-jet-input-error for="name" class="mt-2" />
</div>

<p class=" text-xl text-gray-700">Listado de permisos</p>
<div class="my-4 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3">                        
    <div>
        <label>Transacciones</label>
        @foreach ($permissions as $item)                                                           
            @if (Str::startsWith($item->name, 'transactions'))                                
                <div class="my-2 w-full">
                    <label class="block font-medium text-sm text-gray-700 w-full">                                
                        {!! Form::checkbox('permissions[]', $item->id, null, ['class' => 'mr-2 mb-1']) !!}
                        {{ $item->description }}
                    </label>
                </div> 
            @endif                                                   
        @endforeach
    </div>

    <div>
        <label>Bancos</label>
        @foreach ($permissions as $item)                                                           
            @if (Str::startsWith($item->name, 'banks'))                                
                <div class="my-2 w-full">
                    <label class="block font-medium text-sm text-gray-700 w-full">                                
                        {!! Form::checkbox('permissions[]', $item->id, null, ['class' => 'mr-2 mb-1']) !!}
                        {{ $item->description }}
                    </label>
                </div> 
            @endif                                                   
        @endforeach
    </div>
    
    <div>                                                
        <label>Contabilidad</label>
        @foreach ($permissions as $item)                                                           
            @if (Str::startsWith($item->name, 'accounting'))                                
                <div class="my-2 w-full">
                    <label class="block font-medium text-sm text-gray-700 w-full">                                
                        {!! Form::checkbox('permissions[]', $item->id, null, ['class' => 'mr-2 mb-1']) !!}
                        {{ $item->description }}
                    </label>
                </div> 
            @endif                                                   
        @endforeach
    </div>

    <div>                                                
        <label>Administración</label>
        @foreach ($permissions as $item)                                                           
            @if (Str::startsWith($item->name, 'admin'))                                
                <div class="my-2 w-full">
                    <label class="block font-medium text-sm text-gray-700 w-full">                                
                        {!! Form::checkbox('permissions[]', $item->id, null, ['class' => 'mr-2 mb-1']) !!}
                        {{ $item->description }}
                    </label>
                </div> 
            @endif                                                   
        @endforeach
    </div>

    <div>                                                
        <label>Reportes</label>
        @foreach ($permissions as $item)                                                           
            @if (Str::startsWith($item->name, 'reporting'))                                
                <div class="my-2 w-full">
                    <label class="block font-medium text-sm text-gray-700 w-full">                                
                        {!! Form::checkbox('permissions[]', $item->id, null, ['class' => 'mr-2 mb-1']) !!}
                        {{ $item->description }}
                    </label>
                </div> 
            @endif                                                   
        @endforeach
    </div>
</div>