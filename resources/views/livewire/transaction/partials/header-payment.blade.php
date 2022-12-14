<div class=" grid grid-cols-1 sm:grid-cols-3 md:grid-cols-4 gap-4 xl:grid-cols-6 bg-gray-50 border-2 px-4 pt-2 pb-6">    

    <div class="">
        <x-jet-label for="number" value="Número:" />        
        <input type="text" class="block sm:py-0 rounded border-gray-400 w-full text-gray-600" wire:model="transaction.number" disabled>        
        <x-jet-input-error for="transaction.number" class="mt-2" />           
    </div>       
    
    <div class="">
        <x-jet-label for="date" value="Fecha:" />        
        <input type="date" class="block sm:py-0 rounded border-gray-400  w-full text-gray-700" wire:model="transaction.date">        
        <x-jet-input-error for="transaction.date" class="mt-2" />                
    </div>      
    
    <div class="xl:col-span-2">
        <x-jet-label for="partner_select" value="Socio:"/>
        <div wire:ignore class="flex-1">
            <select id="partner_select" class="shadow-md z-10 w-full" wire:model="transaction.partner_id">
                <option value="-1"></option>
                @foreach ($partners as $partner)
                    <option value="{{ $partner->id }}">{{ $partner->name }}</option>
                @endforeach
            </select>                     
        </div>
        <x-jet-input-error for="transaction.partner_id" class="mt-2" /> 
    </div> 

    <div class="xl:col-span-2">
        <x-jet-label for="reference" value="Referencia:" />        
        <input type="text" class="block sm:py-0 rounded border-gray-400  w-full sm:w-44 xl:w-full text-gray-600" wire:model="transaction.reference">                
        <x-jet-input-error for="transaction.reference" class="mt-2" />                   
    </div>    
    
    <div class="xl:col-span-2">
        <x-jet-label for="bank_select" value="Banco:"/>
        <div class="flex-1">            
            <select class="block sm:py-0 rounded border-gray-400  w-full sm:w-44 xl:w-full text-gray-600" wire:model="bank_account_id">
                <option value="-1"></option>
                @foreach ($bank_accounts as $item)
                    <option value="{{ $item->id }}">{{ $item->number }} - {{ $item->bank->name }}</option>                    
                @endforeach
            </select>                     
        </div>
        <x-jet-input-error for="bank_account_id" class="mt-2" /> 
    </div> 

    <div class="xl:col-span-2">
        <x-jet-label for="check_number" value="No. cheque:" />        
        <input type="text" class="block sm:py-0 rounded border-gray-400  w-full sm:w-44 xl:w-full text-gray-600" wire:model="check_number">                
        <x-jet-input-error for="check_number" class="mt-2" />                   
    </div>

</div>