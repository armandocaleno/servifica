<div class="grid grid-cols-2 gap-4">
    
    <!-- Ruc -->            
    <div class="mb-2">
        <x-jet-label for="ruc" value="Ruc: *" />
        <x-jet-input id="ruc" type="text" class="mt-1 shadow-md block w-full" name="ruc" autocomplete="ruc" value="{{ old('name', $company->ruc) }}"/>
        <x-jet-input-error for="ruc" class="mt-2" />
    </div>  
    
    <!-- businessname -->            
    <div class="mb-2">
        <x-jet-label for="businessname" value="Razón social: *" />
        <x-jet-input id="businessname" type="text" class="mt-1 shadow-md block w-full" name="businessname" autocomplete="businessname" value="{{ old('businessname', $company->businessname) }}"/>
        <x-jet-input-error for="businessname" class="mt-2" />
    </div> 

    <!-- tradename -->            
    <div class="mb-2">
        <x-jet-label for="tradename" value="Nombre comercial:" />
        <x-jet-input id="tradename" type="text" class="mt-1 shadow-md block w-full" name="tradename" autocomplete="tradename" value="{{ old('tradename', $company->tradename) }}"/>
        <x-jet-input-error for="tradename" class="mt-2" />
    </div>

    <!-- address -->            
    <div class="mb-2">
        <x-jet-label for="address" value="Dirección:" />
        <x-jet-input id="address" type="text" class="mt-1 shadow-md block w-full" name="address" autocomplete="address" value="{{ old('address', $company->address) }}"/>
        <x-jet-input-error for="address" class="mt-2" />
    </div> 

    <!-- phone -->            
    <div class="mb-2">
        <x-jet-label for="phone" value="Teléfono:" />
        <x-jet-input id="phone" type="text" class="mt-1 shadow-md block w-full" name="phone" autocomplete="phone" value="{{ old('phone', $company->phone) }}"/>
        <x-jet-input-error for="phone" class="mt-2" />
    </div>    

    {{-- Logo --}}
    <div class="flex">
        <div class="mr-4">
            <x-jet-label for="logo" value="Logo:" />

            <div class="image-wrapper h-20 w-20 bg-cover bg-no-repeat bg-center border" >
                @if ($company->logo)
                    <img src="{{ asset('images/logo/'.$company->ruc.'/'.$company->logo) }}" alt="{{ $company->businessname }}" id="picture">
                @endif
            </div>
        </div>
        
        <div class="flex-1 flex items-center">
            <input type="file" name="logo" id="logo" accept="image/*">
            <x-jet-input-error for="logo" class="mt-2" />   
        </div>
             
    </div>

    <div class="mb-2">        
        <x-jet-input id="id" type="hidden" name="id" value="{{ old('id', $company->id) }}" />        
    </div>
</div>      

<div class="">
    ( * ) Obligatorio
</div>