<x-jet-dialog-modal wire:model="openDetailModal">
    <x-slot name="title">
        Detalle
    </x-slot>

    <x-slot name="content">
        <div class=" grid grid-cols-2 mb-4 text-gray-500">
            @if (isset($aditional_info))
                <div>
                    <span class=" font-bold">Banco: </span> <span> {{ $aditional_info['bank'] }}</span>
                </div>

                <div>
                    <span class=" font-bold">No. cheque: </span> <span>{{ $aditional_info['check_number'] }}</span>
                </div>                
            @endif
        </div>
        <div class="flex flex-col">
            <div class="-my-2 py-2 overflow-x-auto lg:-mx-8 lg:px-8">
                <div class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">
                    <table class="min-w-full">
                        <thead>
                            <tr>                             
                                <th class="thead">Cuenta</th>         
                                <th class="thead">Monto</th>                                                              
                            </tr>
                        </thead>
                        <tbody>
                            @if ($detail)
                                @foreach ($detail as $item)                                     
                                    <tr>
                                        <td class="row">{{ $item['name'] }}</td>                               
                                        <td class="row">${{ $item['price'] }}</td>                                                                                                                        
                                    </tr>                                    
                                @endforeach  
                            @else
                                <tr>
                                    <td colspan="2" class="text-center">Esta transacción no tiene cuentas</td>
                                </tr>
                            @endif
                        </tbody>   
                        <tfoot>
                            <tr>
                                @if ($detail)
                                    <td colspan="4" class="row text-right">Total de items: {{ count($detail) }}</td>
                                @endif
                            </tr>
                        </tfoot>                         
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