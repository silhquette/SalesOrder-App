<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($message = Session::get('success'))
                <div class="bg-green-200 overflow-hidden shadow-sm sm:rounded-lg mb-8 py-4 px-6 text-green-800 flex justify-between" id="flash">
                    <span>
                        <i class="fa-solid fa-circle-check inline-block"></i>
                        <p class="font-bold inline-block pl-2">Awesome!</p>
                        <p class="inline-block pl-2">{{ $message }}</p>
                    </span>
                    <a href="#"><i class="fa-solid fa-xmark text-2xl" id="flash-close"></i></a>
                </div>   
            @endif
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between">
                        <span class="text-lg">{{ __("Create Sales Order") }}</span>
                    </div>

                    {{-- form --}}
                    <form method="POST" action="{{ route('order.store') }}" id="input-form">
                        <div class="flex flex-col items-center justify-center mt-4">
                            <div class=" grid grid-cols-2 gap-6 w-2/3 mb-14">
                                @csrf
                                <!-- Nomor PO -->
                                <div class="mx-4">
                                    <x-input-label for="nomor_po" :value="__('Nomor PO')" class="after:content-['*'] after:ml-0.5 after:text-red-500"/>
                                    <x-text-input id="nomor_po" class="block mt-1 w-full" type="text" name="nomor_po" value="{{ old('nomor_po') }}" required autofocus/>
                                    <x-input-error :messages="$errors->get('nomor_po')" class="mt-2" />
                                </div>
                                    
                                <!-- Tanggal PO -->
                                <div class="mx-4">
                                    <x-input-label for="tanggal_po" :value="__('Tanggal PO')" class="after:content-['*'] after:ml-0.5 after:text-red-500"/>
                                    <x-text-input id="tanggal_po" class="block mt-1 w-full" type="date" name="tanggal_po" value="{{ old('tanggal_po') }}" required autofocus/>
                                    <x-input-error :messages="$errors->get('tanggal_po')" class="mt-2" />
                                </div>

                                <!-- Order Code -->
                                <div class="mx-4">
                                    <x-input-label for="order_code" :value="__('Order Code')" />
                                    <x-text-input id="order_code" class="block mt-1 w-full text-[#205295]" type="text" value="{{ $uuid }}" :readonly=true name="order_code"/>
                                    <x-input-error :messages="$errors->get('order_code')" class="mt-2" />
                                </div>

                                <!-- Customer -->
                                <div class="mx-4">
                                    <x-input-label for="customer_id" :value="__('Customer')" class="after:content-['*'] after:ml-0.5 after:text-red-500" />
                                    <x-text-input list="customer-list" id="customer_id" class="block mt-1 w-full" type="text" name="customer_id" value="{{ old('customer_id') }}" required autofocus autocomplete=false/>
                                    <datalist id="customer-list" class="">
                                        @foreach ($customers as $customer)
                                        <option value="{{ $customer->name }} - {{ $customer->address }}">{{ $customer->code }} - {{ $customer->name }} - {{ $customer->address }}</option>   
                                        @endforeach
                                    </datalist>
                                    <x-input-error :messages="$errors->get('customer_id')" class="mt-2" />
                                </div>
                            </div>

                            <div class="flex flex-col w-2/3 mb-10 gap-6">
                                @for ($i = 0; $i < 7; $i++)
                                <div class="grid-cols-8 hidden" id="{{ $i }}">
                                    <!-- product -->
                                    <div class="mx-4 col-span-2">
                                        <x-input-label for="product_id" :value="__('product')" class="after:content-['*'] after:ml-0.5 after:text-red-500" />
                                        <x-text-input list="product-list" id="product_id" class="block mt-1 w-full" type="text" name="order[{{ $i }}][product_id]" value="{{ old('product_id') }}" required autofocus autocomplete=false :disabled=true/>
                                        <datalist id="product-list" class="">
                                            @foreach ($products as $product)
                                            <option value="{{ $product->name }}">{{ $product->code }} - {{ $product->name }} - {{ $product->dimension }} mm/{{ $product->unit }}</option>   
                                            @endforeach
                                        </datalist>
                                        <x-input-error :messages="$errors->get('product_id')" class="mt-2" />
                                    </div>

                                    <!-- Price per Unit -->
                                    <div class="mx-4 col-span-2">
                                        <x-input-label for="price" :value="__('Price per Unit')" class="after:content-['*'] after:ml-0.5 after:text-red-500"/>
                                        <x-text-input id="price" class="block mt-1 w-full price" type="number" name="order[{{ $i }}][price]" value="{{ old('price') }}" min=0 required autofocus :disabled=true/>
                                        <x-input-error :messages="$errors->get('price')" class="mt-2" />
                                    </div>

                                    <!-- Qty -->
                                    <div class="mx-4">
                                        <x-input-label for="qty" :value="__('Qty')" class="after:content-['*'] after:ml-0.5 after:text-red-500"/>
                                        <x-text-input id="qty" class="block mt-1 w-full quantity" type="number" name="order[{{ $i }}][qty]" value="{{ old('qty') }}" min=0 required autofocus :disabled=true/>
                                        <x-input-error :messages="$errors->get('qty')" class="mt-2" />
                                    </div>

                                    <!-- Disc (%) -->
                                    <div class="mx-4 ">
                                        <x-input-label for="discount" :value="__('Disc (%)')" class="after:content-['*'] after:ml-0.5 after:text-red-500"/>
                                        <x-text-input id="discount" class="block mt-1 w-full discount" type="number" name="order[{{ $i }}][discount]" value="{{ old('discount') ? old('discount') : 0 }}" min=0 max=100 required autofocus :disabled=true/>
                                        <x-input-error :messages="$errors->get('discount')" class="mt-2" />
                                    </div>

                                    <!-- Amount -->
                                    <div class="mx-4 col-span-2">
                                        <x-input-label for="amount" :value="__('Amount')"/>
                                        <x-text-input id="amount" class="block mt-1 w-full amount text-[#205295]" type="number" name="order[{{ $i }}][amount]" value="0" min=0 required autofocus :disabled=true/>
                                        <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                                    </div>
                                </div>    
                                @endfor
                                <div class="flex justify-center items-center">
                                    <a href="#"><i class=" mx-4 fa-solid fa-square-minus text-3xl text-[#144272] hover:scale-105"></i></a>
                                    <a href="#"><i class=" mx-4 fa-solid fa-square-plus text-3xl text-[#2C74B3] hover:scale-105"></i></a>
                                </div>
                            </div>

                            <div class="flex flex-col w-1/2 mb-10 gap-6">
                                <!-- Subtotal -->
                                <div class="mx-4 col-span-2">
                                    <x-input-label for="subtotal" :value="__('Subtotal')"/>
                                    <x-text-input id="subtotal" class="block mt-1 w-full subtotal text-[#205295]" type="number" value="0" min=0 :disabled=true/>
                                </div>
                                <!-- PPN -->
                                <div class="mx-4 col-span-2">
                                    <x-input-label for="ppn" :value="__('PPN (%)')" class="after:content-['*'] after:ml-0.5 after:text-red-500"/>
                                    <x-text-input id="ppn" class="block mt-1 w-full ppn" type="number" name="ppn" value="{{ old('ppn') ? old('ppn') : 11 }}" min=0 max=100 required autofocus/>
                                    <x-input-error :messages="$errors->get('ppn')" class="mt-2" />
                                </div>
                                <!-- Total -->
                                <div class="mx-4 col-span-2">
                                    <x-input-label for="total" :value="__('Total')"/>
                                    <x-text-input id="total" class="block mt-1 w-full total text-[#205295]" type="number" name="total" value="0" min=0 required autofocus/>
                                    <x-input-error :messages="$errors->get('total')" class="mt-2" />
                                </div>
                            </div>
                        </div>
                        <!-- Submit Button -->
                        <div class="flex items-center flex-col gap-4 justify-end mt-6">
                            <x-primary-button class="w-1/2 min-w-[200px]">
                                {{ __('Submit') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8 w-fit">
                <div class="p-6 text-gray-900 py-4 w-fit flex items-center hover:text-[#144272]">
                    <a href="{{ route('order.index') }}"><i class="fa-solid fa-arrow-left mr-3"></i>Sales Order</a></a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
