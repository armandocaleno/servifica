<div class="grid grid-cols-2 gap-2 mb-2">


    <div class=" grid grid-cols-2 gap-2">
        <div>
            <!-- Date -->
            <div class="mb-0">
                <x-jet-label for="date" value="Fecha *" />
                <input type="date" class="block sm:py-0 rounded border-gray-400 w-full text-gray-600"
                    wire:model.defer="payment.date">
                <x-jet-input-error for="payment.date" class="mt-2" />
            </div>
        </div>

        <div>
            <!-- paymentMethods -->
            <x-jet-label for="payment_method" value="Forma de pago *" />
            <div class="flex-1">
                <select class="block sm:py-0 rounded border-gray-400  w-full sm:w-44 xl:w-full text-gray-600"
                    wire:model.defer="payment.payment_method_id">
                    <option value="-1"></option>
                    @foreach ($paymentmethods as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
            <x-jet-input-error for="payment.payment_method_id" class="mt-2" />
        </div>
    </div>

    <!-- Name -->
    <div class="mb-0 gap-2">
        <x-jet-label for="number" value="Numero (cheque / transferencia)" />
        <input type="text" class="block sm:py-0 rounded border-gray-400 w-full text-gray-600"
            wire:model.defer="payment.number" onkeypress="return valideKey(event);">
        <x-jet-input-error for="payment.number" class="mt-2" />
    </div>

    <!-- Emisor -->
    {{-- <div class="mb-0">
        <x-jet-label for="supplier" value="Emisor" />
        <input type="text" class="block sm:py-0 rounded border-gray-400 w-full text-gray-600"
            wire:model.defer="payment.emisor">
        <x-jet-input-error for="payment.emisor" class="mt-2" />
    </div> --}}

    <!-- Beneficiary -->
    <div class="mb-0">
        <x-jet-label for="beneficiary" value="Beneficiario" />
        <input type="text" class="block sm:py-0 rounded border-gray-400 w-full text-gray-600"
            wire:model.defer="payment.beneficiary">
        <x-jet-input-error for="payment.beneficiary" class="mt-2" />
    </div>

    <!-- Reference -->
    <div class="">
        <x-jet-label for="reference" value="Referencia" />
        <textarea name="" id="" rows="2" style="resize:none"
            class="block sm:py-0 rounded border-gray-400 w-full text-gray-600" wire:model.defer="payment.reference"></textarea>
        <x-jet-input-error for="payment.reference" class="mt-2" />
    </div>

    <div class=" grid grid-cols-2 gap-2">
        <div>
            <x-jet-label for="bank_account_select" value="Cuenta bancaria *" />
            <div class="flex-1">
                <select class="block sm:py-0 rounded border-gray-400  w-full sm:w-44 xl:w-full text-gray-600"
                    wire:model.defer="payment.bank_account_id">
                    <option value="-1"></option>
                    @foreach ($bankaccounts as $item)
                        <option value="{{ $item->id }}">{{ $item->reference }}</option>
                    @endforeach
                </select>
            </div>
            <x-jet-input-error for="payment.bank_account_id" class="mt-2" />
        </div>

        <div>
            <x-jet-label for="total" value="Total *" />
            <input type="text" class="block sm:py-0 rounded border-gray-400 w-full text-gray-600" disabled
                wire:model.defer="payment.total">
            <x-jet-input-error for="payment.total" class="mt-2" />
        </div>
    </div>

    <div class="mb-0">
        <x-jet-input id="id" type="hidden" name="id" value="{{ old('id', $payment->id) }}" />
    </div>
</div>
<div class="">
    ( * ) Obligatorio
</div>
