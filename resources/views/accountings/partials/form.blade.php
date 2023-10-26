<div class="grid grid-cols-2 gap-4 mb-4">
    <!-- level -->
    {{-- <div class="mb-2">
        <x-jet-label for="accounting_level" value="Nivel:" />
        <select id="accounting_level" class="mt-1 shadow-md rounded w-full border-gray-400 text-gray-700"
            wire:model="accounting.level">

            @for ($i = 1; $i <= $global_level; $i++)
                <option value="{{ $i }}">{{ $i }}</option>
            @endfor
        </select>
        <x-jet-input-error for="accounting.level" class="mt-2" />
    </div> --}}

     <!-- Class -->
     <div class="mb-2">
        <x-jet-label for="accounting_class" value="Clase:" />
        <select id="accounting_class" class="mt-1 shadow-md rounded w-full border-gray-400 text-gray-700"
            wire:model="accounting.account_class_id">
            @foreach ($accounting_class as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endforeach
        </select>
        <x-jet-input-error for="accounting.account_class_id" class="mt-2" />
    </div>

    <!-- Type -->
    <div class="mb-2">
        <x-jet-label for="accounting_type" value="Naturaleza:" />
        <select id="accounting_type" class="mt-1 shadow-md rounded w-full border-gray-400 text-gray-700" disabled
            wire:model="accounting.account_type_id">
            @foreach ($accounting_types as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endforeach
        </select>
        <x-jet-input-error for="accounting.account_type_id" class="mt-2" />
    </div>

    <!-- Parent -->
    <div class="mb-2">
        <x-jet-label for="accounting_parent" value="Padre:" />
        <select id="accounting_parent" class="mt-1 shadow-md rounded w-full border-gray-400 text-gray-700"
            wire:model="accounting.parent_id">

            <option value=""></option>

            @foreach ($accounting_parents as $item)
                <option value="{{ $item->id }}">
                    <span class=" font-semibold text-blue-950">{{ $item->code }}</span> -> {{ $item->name }}
                </option>
            @endforeach
        </select>
        <x-jet-input-error for="accounting.parent_id" class="mt-2" />
    </div>

     <!-- Subclass -->
     <div class="mb-2">
        <x-jet-label for="account_subclass" value="Subclase:" />
        <select id="account_subclass" class="mt-1 shadow-md rounded w-full border-gray-400 text-gray-700"
            wire:model="accounting.account_subclass_id">
            <option value=""></option>
            @foreach ($accounting_subclasses as $item)
                <option value="{{ $item->id }}">{{ $item->description }}</option>
            @endforeach
        </select>
        <x-jet-input-error for="accounting.account_subclass_id" class="mt-2" />
    </div>

    <!-- Group -->
    <div class="mb-2">
        <x-jet-label for="accounting_group" value="Tipo:" />
        <select id="accounting_group" class="mt-1 shadow-md rounded w-full border-gray-400 text-gray-700"
            wire:model="accounting.group">
            <option value="1">Grupo</option>
            <option value="0">Movimiento</option>
        </select>
        <x-jet-input-error for="accounting.group" class="mt-2" />
    </div>

     <!-- Codigo -->
     <div class="mb-2">
        <x-jet-label for="accounting_code" value="Codigo: *" />
        <x-jet-input id="accounting_code" type="text" class="mt-1 shadow-md block w-full" 
            autocomplete="code" value="{{ old('code', $accounting->code) }}" wire:model="accounting.code"/>
        <x-jet-input-error for="accounting.code" class="mt-2" />
    </div>

    <!-- Name -->
    <div class="mb-2 col-span-2">
        <x-jet-label for="accounting_name" value="Nombre: *" />
        <x-jet-input id="accounting_name" type="text" class="mt-1 shadow-md block w-full"
            autocomplete="name" value="{{ old('name', $accounting->name) }}" wire:model="accounting.name"/>
        <x-jet-input-error for="accounting.name" class="mt-2" />
    </div>

    <!-- Id -->
    <div class="mb-2">
        <x-jet-input id="accounting_group_id" type="hidden" value="{{ old('id', $accounting->id) }}" />
    </div>
</div>

<div class="">
    ( * ) Obligatorio
</div>


