<div class="grid grid-cols-2 gap-2 mb-2">
    <!-- Name -->
    <div class="mb-0 gap-2">
        <x-jet-label for="number" value="Numero *" />
        <input type="text" class="block sm:py-0 rounded border-gray-400 w-full text-gray-600" wire:model.defer="check.number" onkeypress="return valideKey(event);">
        <x-jet-input-error for="check.number" class="mt-2" />
    </div>

    <div class="mb-0">
        <x-jet-label for="date" value="Fecha *" />
        <input type="date" class="block sm:py-0 rounded border-gray-400 w-full text-gray-600" wire:model.defer="check.date">
        <x-jet-input-error for="check.date" class="mt-2" />
    </div>

    <div class="mb-0">
        <x-jet-label for="supplier" value="Beneficiario *" />
        <input type="text" class="block sm:py-0 rounded border-gray-400 w-full text-gray-600" wire:model.defer="check.beneficiary">
        <x-jet-input-error for="check.supplier" class="mt-2" />
    </div>

    <div class=" grid grid-cols-2 gap-2">
        <div>
            <x-jet-label for="bank_select" value="Cuenta bancaria *"/>
            <div class="flex-1">            
                <select class="block sm:py-0 rounded border-gray-400  w-full sm:w-44 xl:w-full text-gray-600" wire:model.defer="check.bank_account_id">
                    <option value="-1"></option>
                    @foreach ($bankaccounts as $item)
                        <option value="{{ $item->id }}">{{ $item->reference }}</option>                    
                    @endforeach
                </select>                     
            </div>
            <x-jet-input-error for="check.bank_account_id" class="mt-2" /> 
        </div>

        <div>
            <x-jet-label for="total" value="Total *" />
            <input type="text" class="block sm:py-0 rounded border-gray-400 w-full text-gray-600" disabled wire:model.defer="check.total">
            <x-jet-input-error for="check.total" class="mt-2" />
        </div>
    </div>

    <div class="" >
        <x-jet-label for="reference" value="Referencia" />
        <textarea name="" id="" rows="2" style="resize:none" class="block sm:py-0 rounded border-gray-400 w-full text-gray-600" wire:model.defer="check.reference"></textarea>
        <x-jet-input-error for="check.reference" class="mt-2" />
    </div>

    <div class="mb-0">        
        <x-jet-input id="id" type="hidden" name="id" value="{{ old('id', $check->id) }}" />        
    </div>
</div>      
<div class="">
    ( * ) Obligatorio
</div>