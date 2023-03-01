<div class="grid grid-cols-2 gap-4 mb-4">
    <!-- Group -->
    <div class="mb-2">
        <x-jet-label for="accounting_group" value="Tipo:" />
        <select id="accounting_group" class="mt-1 shadow-md rounded w-full border-gray-400 text-gray-700"
            wire:model="group">
            <option value="0">Grupo</option>
            <option value="1">Movimiento</option>
        </select>
        <x-jet-input-error for="group" class="mt-2" />
    </div>

    <!-- Class -->
    <div class="mb-2">
        <x-jet-label for="accounting_class" value="Clase:" />
        <select id="accounting_class" class="mt-1 shadow-md rounded w-full border-gray-400 text-gray-700"
            wire:model="account_class_id">
            @foreach ($accounting_class as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endforeach
        </select>
        <x-jet-input-error for="account_class_id" class="mt-2" />
    </div>

    <!-- Type -->
    <div class="mb-2">
        <x-jet-label for="accounting_type" value="Naturaleza:" />
        <select id="accounting_type" class="mt-1 shadow-md rounded w-full border-gray-400 text-gray-700" 
            wire:model="account_type_id">
            @foreach ($accounting_types as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endforeach
        </select>
        <x-jet-input-error for="account_type_id" class="mt-2" />
    </div>

    <!-- Parent -->
    <div class="mb-2">
        <x-jet-label for="accounting_parent" value="Padre:" />
        <select id="accounting_parent" class="mt-1 shadow-md rounded w-full border-gray-400 text-gray-700"
            wire:model="parent_id">

            <option value=""></option>

            @foreach ($accounting_parents as $item)
                <option value="{{ $item->id }}">
                    <span class=" font-semibold text-blue-950">{{ $item->code }}</span> -> {{ $item->name }}
                </option>
            @endforeach
        </select>
        <x-jet-input-error for="parent_id" class="mt-2" />
    </div>

     <!-- Codigo -->
     <div class="mb-2">
        <x-jet-label for="accounting_code" value="Codigo: *" />
        <x-jet-input id="accounting_code" type="text" class="mt-1 shadow-md block w-full" 
            autocomplete="code" value="{{ old('code', $accounting->code) }}" wire:model="code"/>
        <x-jet-input-error for="code" class="mt-2" />
    </div>

    <!-- Name -->
    <div class="mb-2">
        <x-jet-label for="accounting_name" value="Nombre: *" />
        <x-jet-input id="accounting_name" type="text" class="mt-1 shadow-md block w-full"
            autocomplete="name" value="{{ old('name', $accounting->name) }}" wire:model="name"/>
        <x-jet-input-error for="name" class="mt-2" />
    </div>

    <!-- Id -->
    <div class="mb-2">
        <x-jet-input id="accounting_group_id" type="hidden" value="{{ old('id', $accounting->id) }}" />
    </div>
</div>

<div class="">
    ( * ) Obligatorio
</div>


