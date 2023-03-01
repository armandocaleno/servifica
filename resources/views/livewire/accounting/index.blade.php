<div class=" ">
    <div class=" mb-2 flex items-center space-x-4 w-1/2">
        <x-jet-label for="accounting_code" value="Buscar: " />
        <x-jet-input type="text" class="mt-1 shadow-md block w-full" wire:model="search" />
    </div>

    <div class="p-8 h-80 min-h-max border rounded-md bg-white shadow-md overflow-auto">


        <ul id="main_accounting" class="listree">
            {{-- nivel 1 --}}
            @foreach ($accountings as $item)
                @if ($item->parent_id == null)
                    <li class="listree-submenu-heading" id="list{{ $item->id }}"
                        data-id="{{ $item->account_class_id }}">
                        
                        <span class="accounting-code">{{ $item->code }}</span>
                        {{ $item->name }}
                        <x-jet-input type="checkbox" class=" ml-4"/>
                        {{-- <a href="{{ route('accounting.edit',$item) }}" class=" lowercase">Editar</a> --}}
                    </li>

                    {{-- nivel 2 --}}
                    <ul class="listree-submenu-items">
                        @foreach ($accountings as $item2)
                            @if ($item2->parent_id == $item->id)
                                <li class="listree-submenu-heading" id="list{{ $item->id }}"
                                    data-id="{{ $item2->account_class_id }}">
                                    <span class="accounting-code">{{ $item2->code }} </span>{{ $item2->name }} <x-jet-input type="checkbox" class=" ml-4"/>
                                </li>

                                {{-- nivel 3 --}}
                                <ul class="listree-submenu-items">
                                    @foreach ($accountings as $item3)
                                        @if ($item3->parent_id == $item2->id)
                                            <li class="listree-submenu-heading" id="list{{ $item->id }}"
                                                data-id="{{ $item3->account_class_id }}">
                                                <span class="accounting-code">{{ $item3->code }} </span>
                                                {{ $item3->name }} <x-jet-input type="checkbox" class=" ml-4"/>
                                            </li>

                                            {{-- nivel 4 --}}
                                            <ul class="listree-submenu-items">
                                                @foreach ($accountings as $item4)
                                                    @if ($item4->parent_id == $item3->id)
                                                        <li id="list{{ $item->id }}"
                                                            data-id="{{ $item4->account_class_id }}">
                                                            <span class="accounting-code">{{ $item4->code }} </span>
                                                            {{ $item4->name }}
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        @endif
                                    @endforeach
                                </ul>
                            @endif
                        @endforeach
                    </ul>
                @endif
            @endforeach
        </ul>
    </div>

    <div class="relative mt-4">
        <x-jet-button>
            <a href="{{ route('accounting.create') }}" class="">Nueva</a>
        </x-jet-button>

        <x-jet-button class=" bg-blue-500">
            <a href="{{ route('accounting.create') }}" class="">Editar</a>
        </x-jet-button>

        <x-jet-button class=" bg-red-500">
            <a href="{{ route('accounting.create') }}" class="">Eliminar</a>
        </x-jet-button>

        <x-jet-button class=" bg-green-500">
            <a href="{{ route('accounting.create') }}" class="">Importar</a>
        </x-jet-button>
    </div>

    @push('js')
        <script>
            
            $(document).ready(function () {
                listree();
            });

            Livewire.on('searching', function() {
               listree();
            })
        </script>
    @endpush
</div>
