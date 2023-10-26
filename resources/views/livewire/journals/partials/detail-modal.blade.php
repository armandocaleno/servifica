<x-jet-dialog-modal wire:model="openDetailModal">
    <x-slot name="title">
        Detalle de asiento contable
    </x-slot>

    <x-slot name="content">
        <div class="flex flex-col">
            <div class="-my-2 py-2 overflow-x-auto lg:-mx-8 lg:px-8">
                <div class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr>                             
                                <th class="thead">Cuenta</th>         
                                <th class="thead">Debe</th> 
                                <th class="thead">Haber</th>                                                             
                            </tr>
                        </thead>
                        <tbody>
                            @if ($detail)
                                @foreach ($detail as $item)                                     
                                    <tr>
                                        <td class="row lowercase">{{ $item->accounting->code }} - {{ $item->accounting->name }}</td>
                                        <td class="row whitespace-nowrap">$ {{ $item->debit_value }}</td>                               
                                        <td class="row whitespace-nowrap">$ {{ $item->credit_value }}</td>                                                                                                                        
                                    </tr>                                    
                                @endforeach  
                            @endif
                        </tbody>   
                        {{-- <tfoot>
                            <tr>       
                                <td colspan="4" class="row text-right">Total: </td>
                            </tr>
                        </tfoot>                          --}}
                    </table>                        
                </div>
            </div>
        </div>                       
    </x-slot>

    <x-slot name="footer">
        <div class=" space-x-4">
            <x-jet-secondary-button wire:click="$set('openDetailModal', false)">
                Cerrar
            </x-jet-secondary-button>               
        </div>
    </x-slot>
</x-jet-dialog-modal>