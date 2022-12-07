 <div class="grid grid-cols-2 gap-4 mb-4">
    <!-- Name -->
    

    <div class="mb-2">
        <x-jet-label for="code" value="Código *" />
        <x-jet-input id="code" type="text" class="mt-1 block w-full" name="code" autocomplete="code" value="{{ old('code', $partner->code) }}" />
        <x-jet-input-error for="code" class="mt-2" />
    </div>

    <div class="mb-2">
        <x-jet-label for="identity" value="Identificación *" />
        <x-jet-input id="identity" type="text" class="mt-1 block w-full" name="identity" autocomplete="identity" value="{{ old('identity', $partner->identity) }}"/>
        <x-jet-input-error for="identity" class="mt-2" />
    </div>

    <div class="mb-2">
        <x-jet-label for="name" value="Nombres *" />
        <x-jet-input id="name" type="text" class="mt-1 block w-full" name="name" autocomplete="name" value="{{ old('name', $partner->name) }}"/>
        <x-jet-input-error for="name" class="mt-2" />
    </div>

    <div class="mb-2">
        <x-jet-label for="lastname" value="Apellidos *" />
        <x-jet-input id="lastname" type="text" class="mt-1 block w-full" name="lastname" autocomplete="lastname" value="{{ old('lastname', $partner->lastname) }}"/>
        <x-jet-input-error for="lastname" class="mt-2" />
    </div>
    
    <div class="mb-2">
        <x-jet-label for="phone" value="Teléfono" />
        <x-jet-input id="phone" type="text" class="mt-1 block w-full" name="phone" autocomplete="phone" value="{{ old('phone', $partner->phone) }}"/>
        <x-jet-input-error for="phone" class="mt-2" />
    </div>   
    
    <div class="mb-2">
        <x-jet-label for="email" value="Email" />
        <x-jet-input id="email" type="email" class="mt-1 block w-full" name="email" autocomplete="email" value="{{ old('email', $partner->email) }}"/>
        <x-jet-input-error for="email" class="mt-2" />
    </div>

    <div class="mb-2">        
        <x-jet-input id="id" type="hidden" name="id" value="{{ old('id', $partner->id) }}" />        
    </div>
</div>      
<div class="">
    ( * ) Obligatorio
</div>