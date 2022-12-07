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
            <option value="1">Ingreso</option>
            <option value="2">Egreso</option>
        </select> 
    </div>

    <div class="mb-2">        
        <x-jet-input id="id" type="hidden" name="id" value="{{ old('id', $account->id) }}" />        
    </div>
</div>      
<div class="">
    ( * ) Obligatorio
</div>