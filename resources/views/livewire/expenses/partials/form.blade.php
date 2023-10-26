@if ($errors->any())
    <ol class=" bg-red-600 text-rose-300 p-2 rounded-md">
        @foreach ($errors->all() as $error)
            <li>- {{ $error }}</li>
        @endforeach
    </ol>
@endif

<div class="grid grid-cols-2 gap-2 mb-4">
    <!-- Name -->
    <div class="mb-0 gap-2" wire:ignore>
        <x-jet-label value="Numero *" />
        <input type="text" class="block sm:py-0 rounded border-gray-400 w-full text-gray-600" id="expense_number" wire:model.defer="expense.number">
        
        <x-jet-input-error for="expense.number" class="mt-2" />
    </div>

    <!-- Auth number -->
    <div class="mb-0">
        <x-jet-label value="No. Autorización" />
        <input type="text" class="block sm:py-0 rounded border-gray-400 w-full text-gray-600" wire:model.defer="expense.auth_number">
        <x-jet-input-error for="expense.auth_number" class="mt-2" />
    </div>

    <div class="mb-0">
        <x-jet-label  value="Fecha *" />
        <input type="date" class="block sm:py-0 rounded border-gray-400 w-full text-gray-600" wire:model.defer="expense.date">
        <x-jet-input-error for="expense.date" class="mt-2" />
    </div>

    <div class="mb-0">
        <x-jet-label value="Proveedor *" />
        <select name="" class="py-0 rounded border-gray-400 w-full text-gray-600" wire:model.defer="expense.supplier_id">
            <option value="-1"></option>
            @foreach ($suppliers as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endforeach
        </select>
        <x-jet-input-error for="expense.supplier_id" class="mt-2" />
    </div>

    <div class="" >
        <x-jet-label value="Referencia (glosa)" />
        <textarea name="" id="" rows="2" style="resize:none" class="block sm:py-0 rounded border-gray-400 w-full text-gray-600" wire:model.defer="expense.reference"></textarea>
        <x-jet-input-error for="expense.reference" class="mt-2" />
    </div>

    <div class="grid grid-cols-2 gap-2">
        <div>
            <x-jet-label value="Total a pagar" />
            <input type="text" class="block sm:py-0 rounded border-gray-400 w-full text-gray-600 bg-gray-200" disabled wire:model.defer="expense.total">
            <x-jet-input-error for="expense.total" class="mt-2" />
        </div>
        <div class="mb-0">
            <x-jet-label value="IVA" />
            <input type="text" class="block sm:py-0 rounded border-gray-400 w-full text-gray-600 bg-gray-200" disabled wire:model.defer="expense.tax">
            <x-jet-input-error for="expense.tax" class="mt-2" />
        </div>
    </div>   

    <div class="mb-0">        
        <x-jet-input id="id" type="hidden" name="id" value="{{ old('id', $expense->id) }}" />        
    </div>
</div>      
<div class="">
    ( * ) Obligatorio
</div>