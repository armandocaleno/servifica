 <div class="grid grid-cols-2 gap-4 mb-4">
    <!-- Name -->

    <div class="mb-2">
        <x-jet-label for="identity" value="Identificación *" />
        <x-jet-input id="identity" type="text" class="mt-1 block w-full" name="identity" autocomplete="identity" maxlength="13" value="{{ old('identity', $supplier->identity) }}"/>
        <x-jet-input-error for="identity" class="mt-2" />
    </div>

    <div class="mb-2">
        <x-jet-label for="name" value="Nombre *" />
        <x-jet-input id="name" type="text" class="mt-1 block w-full" name="name" autocomplete="name" value="{{ old('name', $supplier->name) }}"/>
        <x-jet-input-error for="name" class="mt-2" />
    </div>
    
    <div class="mb-2">
        <x-jet-label for="phone" value="Teléfono" />
        <x-jet-input id="phone" type="text" class="mt-1 block w-full" name="phone" autocomplete="phone" value="{{ old('phone', $supplier->phone) }}"/>
        <x-jet-input-error for="phone" class="mt-2" />
    </div>   
    
    <div class="mb-2">
        <x-jet-label for="address" value="Dirección" />
        <x-jet-input id="address" type="text" class="mt-1 block w-full" name="address" autocomplete="address" value="{{ old('address', $supplier->email) }}"/>
        <x-jet-input-error for="address" class="mt-2" />
    </div>

    <div class="mb-2">        
        <x-jet-input id="id" type="hidden" name="id" value="{{ old('id', $supplier->id) }}" />        
    </div>
</div>      
<div class="">
    ( * ) Obligatorio
</div>