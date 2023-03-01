<div>
    <div class=" w-8/12">
        <form wire:submit.prevent="store">
            @csrf
        
                @include('accountings.partials.form')
        
            <x-jet-button class="mt-4" type="submit">
                Guardar
            </x-jet-button>
        </form>
    </div>
</div>
