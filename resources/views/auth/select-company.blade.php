<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <x-jet-authentication-card-logo />
        </x-slot>

        <x-jet-validation-errors class="mb-4" />

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('company.set') }}">
            @csrf

            <div>
                <x-jet-label for="company_id" value="Seleccione la empresa" />
                
                <select name="company_id" id="company_id" class="block mt-1 w-full">
                    @foreach ($companies as $item)
                        <option value="{{ $item->id }}">{{ $item->businessname }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center justify-end mt-4">               
                <x-jet-button class="ml-4">
                    Seleccionar
                </x-jet-button>
            </div>
        </form>
    </x-jet-authentication-card>
</x-guest-layout>
