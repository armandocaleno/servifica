<div class=" grid grid-cols-1 sm:grid-cols-3 md:grid-cols-4 gap-4 xl:grid-cols-6 bg-gray-50 border-2 px-4 pt-2 pb-6">    

    <div class="">
        <x-jet-label for="number" value="Número:" />        
        <input type="text" class="block sm:py-0 rounded border-gray-400 w-full text-gray-600" wire:model.defer="journal.number" disabled>        
        <x-jet-input-error for="journal.number" class="mt-2" />           
    </div>       
    
    <div class="">
        <x-jet-label for="date" value="Fecha:" />        
        <input type="date" class="block sm:py-0 rounded border-gray-400  w-full text-gray-700" wire:model.defer="journal.date">        
        <x-jet-input-error for="journal.date" class="mt-2" />                
    </div>      

    <div class="xl:col-span-4">
        <x-jet-label for="reference" value="Referencia:" />        
        <input type="text" class="block sm:py-0 rounded border-gray-400  w-full sm:w-44 xl:w-full text-gray-600" wire:model.defer="journal.refence">                
        <x-jet-input-error for="journal.refence" class="mt-2" />                   
    </div>      

</div>