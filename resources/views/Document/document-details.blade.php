<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cetak Dokumen') }}
        </h2>
    </x-slot>
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- MAIN WINDOW --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6 text-gray-900 flex items-center flex-col">
                    <div class="flex justify-between mb-3">
                        <span class="text-xl">{{ __("Additonal Informations") }}</span>
                    </div>
                    <div class="my-3 w-full overflow-auto">
                        <div class="text-lg text-gray-900 font-semibold">{{ __("Ordered Items") }}</div>
                        <div class="text-sm text-gray-600" id="item-summary">{{ count($documents) . __(" items ordered") }}</div>
                        <div class="mt-3 w-[1100px] m-auto" id="row">
                            <table class="w-full table-fixed">
                                <tbody id="ordered-item">
                                    @foreach ($sales_order->orders as $order)
                                        <tr class="text-center">
                                            @foreach ($documents as $document)
                                                @if ($order->id == $document->order_id)
                                                <td class="w-24"><input type="checkbox" name="order[{{ $loop ->index}}][order_id]" class="rounded-sm cursor-pointer" value="{{ $order->id }}" checked disabled></td>
                                                @else
                                                <td class="w-24"><input type="checkbox" name="order[{{ $loop ->index}}][order_id]" class="rounded-sm cursor-pointer" value="{{ $order->id }}" disabled></td>
                                                @endif
                                            @endforeach
                                            <td id="product" class="text-left w-80">
                                                <div class="text-lg font-semibold">{{ $order->product->name }}</div>
                                                <div class="text-sm">{{ $order->product->dimension }}</div>
                                                <span class="text-gray-600 text-sm">{{ $order->product->code }}</span>
                                            </td>
                                            <td id="price">Rp. {{ number_format($order->price, 2, ',', '.') }}</td>
                                            <td id="quantity">{{ $order->qty }}</td>
                                            <td id="disc">disc {{ $order->discount }}%<i class="fa-solid fa-tag ml-2"></i></td>
                                            <td id="total">Rp. {{ number_format($order->amount, 2, ',', '.') }}</td>
                                            <td>
                                                <input type="text" placeholder="keterangan" class="w-full border-none focus:ring-0" name="keterangan[{{ $loop->index }}]" value="{{ $order->keterangan }}">
                                                <input type="hidden" name="id[{{ $loop->index }}]" value="{{ $order->id }}">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody> 
                            </table>
                            <table class="w-full table-fixed">
                                <tbody>
                                    <tr class="text-center bg-slate-100">
                                        <td class="font-semibold" colspan="4">Sub-total</td>
                                        <td id="sub-total">Rp. {{ number_format($subtotal, 2, ',', '.') }}</td>
                                        <td></td>
                                    </tr>
                                    <tr class="text-center">
                                        <td class="font-semibold" colspan="4">PPN</td>
                                        <td id="ppn">{{ $sales_order->ppn }}%</td>
                                        <td></td>
                                    </tr>
                                    <tr class="text-center bg-slate-100">
                                        <td class="font-semibold" colspan="4">Amount</td>
                                        <td id="amount">Rp. {{ number_format($sales_order->ppn * 0.01 * $subtotal + $subtotal, 2, ',', '.') }}</td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="my-3 w-full">
                        <div class="text-lg text-gray-900 font-semibold">{{ __("Date Input") }}</div>
                        <div class="text-sm text-gray-600" id="item-summary">{{ __("Tanggal cetak surat jalan dan invoice") }}</div>
                        
                        <div class="mx-4 mt-4 w-1/2 min-w-min">
                            <x-text-input id="print_date" class="block mt-1 w-full" type="date" name="print_date" value="{{ old('print_date') ? old('print_date') : (isset($sales_order['print_date']) ? $sales_order['print_date'] : '') }}" required autofocus/>
                            <x-input-error :messages="$errors->get('print_date')" class="mt-2" />
                        </div>
                    </div>
                </div>
                <x-secondary-link href="" class="w-1/3 relative left-1/2 -translate-x-1/2 mb-4" id="generate-button">
                    {{ __('Edit Document') }}
                </x-secondary-link>
            </div>
        </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- MAIN WINDOW --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6 text-gray-900 flex items-center flex-col">
                    <div class="flex justify-between mb-6">
                        <span class="text-xl">{{ __("PDF Preview") }}</span>
                    </div>
                    {{-- PREVIEW PDF --}}
                    <div class="flex w-full gap-4">
                        <iframe id="surat-jalan-preview" width="50%" src="" height="700px" class=" rounded-md"></iframe>
                        <iframe id="invoice-preview" width="50%" src="" height="700px" class=" rounded-md"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>