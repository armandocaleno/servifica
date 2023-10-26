<div class="grid grid-cols-2 gap-2 mb-4">
    <!-- Name -->
    <div class="mb-0 gap-2">
        <x-jet-label for="number" value="Numero *" />
        {{-- <x-jet-input id="number" type="text" class="mt-1 block w-full" name="number" value="{{ old('number', $expense->number) }}" /> --}}
        <input type="text" class="block sm:py-0 rounded border-gray-400 w-full text-gray-600" wire:model="expense.number">
        <x-jet-input-error for="number" class="mt-2" />
    </div>

    <div class="mb-0">
        <x-jet-label for="date" value="Fecha *" />
        {{-- <x-jet-input id="date" type="date" class="mt-1 block w-full" name="date" value="{{ old('date', $expense->date) }}"/> --}}
        <input type="date" class="block sm:py-0 rounded border-gray-400 w-full text-gray-600" wire:model="expense.date">
        <x-jet-input-error for="date" class="mt-2" />
    </div>

    <div class="mb-0">
        <x-jet-label for="supplier" value="Proveedor *" />
        {{-- <x-jet-input id="supplier" type="text" class="mt-1 block w-full" name="supplier" autocomplete="supplier" value="{{ old('supplier', $expense->supplier) }}"/> --}}
        <input type="text" class="block sm:py-0 rounded border-gray-400 w-full text-gray-600" wire:model="expense.supplier">
        <x-jet-input-error for="supplier" class="mt-2" />
    </div>

    <div class="mb-0">
        <x-jet-label for="reference" value="Referencia (glosa)" />
        {{-- <x-jet-input id="reference" type="text" class="mt-1 block w-full" name="reference" autocomplete="reference" value="{{ old('reference', $expense->reference) }}"/> --}}
        {{-- <input type="text" class="block sm:py-0 rounded border-gray-400 w-full text-gray-600" wire:model="expense.reference"> --}}
        <textarea name="" id="" cols="30" rows="5" wire:model="expense.reference"></textarea>
        <x-jet-input-error for="reference" class="mt-2" />
    </div>
    
    <div class="mb-0">
        <x-jet-label for="total" value="Total *" />
        {{-- <x-jet-input id="total" type="text" class="mt-1 block w-full" name="total" autocomplete="total" value="{{ old('total', $expense->total) }}"/> --}}
        <input type="text" class="block sm:py-0 rounded border-gray-400 w-full text-gray-600" wire:model="expense.total">
        <x-jet-input-error for="total" class="mt-2" />
    </div>   
    
    <div class="mb-0">
        <x-jet-label for="tax" value="IVA *" />
        {{-- <x-jet-input id="tax" type="email" class="mt-1 block w-full" name="tax" autocomplete="tax" value="{{ old('tax', $expense->tax) }}"/> --}}
        <input type="text" class="block sm:py-0 rounded border-gray-400 w-full text-gray-600" wire:model="expense.tax">
        <x-jet-input-error for="tax" class="mt-2" />
    </div>

    <div class="mb-0">        
        {{-- <x-jet-input id="id" type="hidden" name="id" value="{{ old('id', $expense->id) }}" />         --}}
    </div>
</div>      
<div class="">
    ( * ) Obligatorio
</div>