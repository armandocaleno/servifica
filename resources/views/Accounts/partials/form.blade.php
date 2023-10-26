<div class="grid grid-cols-2 gap-4 mb-4">
    
    <!-- Name -->            
    <div class="mb-2">
        <x-jet-label for="name" value="Nombre: *" />
        <x-jet-input id="name" type="text" class="mt-1 shadow-md block w-full" name="name" autocomplete="name" value="{{ old('name', $account->name) }}"/>
        <x-jet-input-error for="name" class="mt-2" />
    </div>            

    <div class="mb-2">
        <x-jet-label for="type" value="Tipo:" />
        <select id="type" class="mt-1 shadow-md rounded w-full border-gray-400 text-gray-700" name="type">
            <option value="1" {{ $account->type == '1' ? 'selected' : '' }}>Ingreso</option>
            <option value="2" {{ $account->type == '2' ? 'selected' : '' }}>Egreso</option>
        </select> 
    </div>

    <div class="mb-2">
        <x-jet-label for="type" value="Cuenta contable:" />
        <select id="type" class="mt-1 shadow-md rounded w-full border-gray-400 text-gray-700" name="accounting_id">
            <option value="">Seleccione</option>
            @foreach ($accountings as $item)
                @if ($account->accounting_id == $item->id)
                    <option value="{{ $item->id }}" selected>{{ $item->code }} - {{ $item->name }}</option>
                @else
                    <option value="{{ $item->id }}">{{ $item->code }} - {{ $item->name }}</option>
                @endif
            @endforeach
        </select> 
        <x-jet-input-error for="accounting_id" class="mt-2" />
    </div>

    <div class="mb-2">     
        <x-jet-input id="id" type="hidden" name="id" value="{{ old('id', $account->id) }}" />        
    </div>
</div>      
<div class="">
    ( * ) Obligatorio
</div>