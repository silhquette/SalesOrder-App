<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($message = Session::get('deleteSuccess'))
                <div class="bg-green-200 overflow-hidden shadow-sm sm:rounded-lg mb-8 py-4 px-6 text-green-800 flex justify-between" id="flash">
                    <span>
                        <i class="fa-solid fa-circle-check inline-block"></i>
                        <p class="font-bold inline-block pl-2">Awesome!</p>
                        <p class="inline-block pl-2">{{ $message }}</p>
                    </span>
                    <a href="#"><i class="fa-solid fa-xmark text-2xl" id="flash-close"></i></a>
                </div>   
            @endif
            {{-- MAIN WINDOW --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center">
                        <span class="text-lg">{{ __("Sales Order List") }}</span>
                        
                        {{-- SEARCH BAR --}}
                        <div class="text-right mr-8 inline-block" id="search">
                            <form action="">
                                <input type="text" class="border-x-0 border-t-0 outline-none focus:ring-0 px-3 pb-1 pt-0 pr-9" id="keyword" placeholder="cari order atau kode">
                                <i class="fa-solid fa-magnifying-glass ml-[-28px]"></i>
                            </form>
                        </div>
                    </div>

                    {{-- TABLE --}}
                    <div class="container overflow-auto rounded-xl relative shadow-sm rounded-t-lg">
                        <table class="table-auto w-full border border-collapse mt-4 px-3">
                            <thead>
                                <tr class="bg-gradient-to-r from-slate-200 to-slate-200/80">
                                    <th class="p-2">No.</th>
                                    <th class="p-2">Order Number (ID)</th>
                                    <th class="p-2">No. PO Cust</th>
                                    <th class="p-2">Tangal PO Cust</th>
                                    <th class="p-2">Customer</th>
                                    {{-- <th class="p-2">Address</th> --}}
                                    <th class="p-2">Amount</th>
                                    <th class="p-2">Due On</th>
                                    <th class="p-2">Action</th>
                                </tr>
                            </thead>
                            <tbody id="main-table">
                                @if ($PO->isEmpty())
                                    <tr>
                                        <td colspan="8" class="text-center py-3 text-gray-600">No data.</td>
                                    </tr>
                                @endif
                                @foreach ($PO as $purchaseOrder)
                                <tr class="border-t border-b text-center">
                                    <td class="p-2">{{ $loop->iteration }}</td>
                                    <td class="p-2">{{ $purchaseOrder->order_code }}</td>
                                    <td class="p-2">{{ $purchaseOrder->nomor_po }}</td>
                                    <td class="p-2">{{ date_format(date_create($purchaseOrder->tanggal_po),"d M Y") }}</td>
                                    <td class="p-2">{{ $purchaseOrder->customer->name }}</td>
                                    <td class="p-2">Rp. {{ number_format($purchaseOrder['total'], 2, ',', '.') }}</td>
                                    <td class="p-2">{{ date_format(date_create($purchaseOrder->due_time),"d M Y") }}</td>
                                    <td class="p-2">
                                        <button title="details" value="{{ $purchaseOrder->order_code }}" class="show-button"><i class="fa-solid fa-eye hover:text-[#144272] text-[#2C74B3] text-lg px-3"></i></button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8 w-fit">
                <div class="p-6 text-gray-900 py-4 w-fit flex items-center hover:text-[#144272]">
                    <a href="{{ route('order.create') }}">Create Sales Order</a> <i class="fa-solid fa-arrow-right ml-3"></i></a>
                </div>
            </div>
            
        </div>
    </div>

    {{-- DETAILS --}}
    <div id="detail-wrap" class="hidden">
        <div class="fixed top-0 left-0 right-0 bottom-0 bg-black/70"></div>
        <div class="fixed top-1/2 left-1/2 bg-white overflow-hidden shadow-sm sm:rounded-lg -translate-x-1/2 -translate-y-1/2 wk-[56rem] 2xl:w-2/3 w-5/6">
            <div class="p-6">
                <div class="mb-3">
                    <div class="flex justify-between items-center">
                        <div>
                            <div class="text-xl text-gray-900 font-semibold">{{ __("Order ") }}<span class="text-[#144272] hover:text-[#2C74B3]">#<span id="order-number-customer">ASKD12SD</span></span></span></div>
                            <div class="text-md text-gray-600" id="term-customer">{{ __("Created at 17 Jul 2023 | Due on 17 Aug 2023") }}</div>
                        </div>
                        <a href="#" id="detail-close"><i class="fa-solid fa-xmark text-2xl mr-6" id="edit-close"></i></a>
                    </div>
                </div>
                <hr>
                <div class="my-3">
                    <div class="text-lg text-gray-900 font-semibold">{{ __("Ordered Items") }}</div>
                    <div class="text-sm text-gray-600" id="item-summary">{{ __("4 items ordered") }}</div>
                    <div class="mt-3" id="row">
                        <table class="w-full table-fixed">
                            <tbody id="ordered-item">
                                <tr class="text-center">
                                    <td id="product" class="text-left pl-16">
                                        <div class="text-lg font-semibold">Nama produk</div>
                                        <div class="text-sm">12 x 12 mm</div>
                                        <span class="text-gray-600 text-sm">PR-ASD1KJH</span>
                                    </td>
                                    <td id="price">Rp. 2000</td>
                                    <td id="quantity">2</td>
                                    <td id="disc">disc 10%<i class="fa-solid fa-tag ml-2"></i></td>
                                    <td id="total">Rp. 3600</td>
                                </tr>
                            </tbody> 
                        </table>
                        <table class="w-full table-fixed">
                            <tbody>
                                <tr class="text-center bg-slate-100">
                                    <td class="font-semibold" colspan="4">Sub-total</td>
                                    <td id="sub-total">360000</td>
                                </tr>
                                <tr class="text-center">
                                    <td class="font-semibold" colspan="4">PPN</td>
                                    <td id="ppn">11%</td>
                                </tr>
                                <tr class="text-center bg-slate-100">
                                    <td class="font-semibold" colspan="4">Amount</td>
                                    <td id="amount">360000</td>
                                </tr>
                            </tbody>   
                        </table>
                    </div>
                </div>
                <hr>
                <div class="my-3">
                    <div class="text-lg text-gray-900 font-semibold">{{ __("Customer Details") }}</div>
                    <div class="flex">
                        <div class="grid grid-cols-3 gap-2 mt-2 w-2/3">
                            <div>
                                <span class="text-sm text-slate-600">Customer Name</span>
                                <div class="flex items-start">
                                    <i class="text-[#144272] inline-block w-8 pt-1 fa-solid fa-user"></i>
                                    <span id="nama-customer">Nama Customer</span>
                                </div>
                            </div>
                            <div>
                                <span class="text-sm text-slate-600">Address</span>
                                <div class="flex items-start">
                                    <i class="text-[#144272] inline-block w-8 pt-1 fa-sharp fa-solid fa-location-dot"></i>
                                    <span id="alamat-customer" class="basis-full">Jalan Perumahan Baru</span>
                                </div>
                            </div>
                            <div>
                                <span class="text-sm text-slate-600">Email</span>
                                <div class="flex items-start">
                                    <i class="text-[#144272] inline-block w-8 pt-1 fa-solid fa-envelope"></i>
                                    <span id="email-customer">Perusahaan@gmail.com</span>
                                </div>
                            </div>
                            <div>
                                <span class="text-sm text-slate-600">Customer NPWP</span>
                                <div class="flex items-start">
                                    <i class="text-[#144272] inline-block w-8 pt-1 fa-solid fa-credit-card"></i>
                                    <span id="npwp-customer">1231231</span>
                                </div>
                            </div>
                            <div>
                                <span class="text-sm text-slate-600">Customer Phone</span>
                                <div class="flex items-start">
                                    <i class="text-[#144272] inline-block w-8 pt-1 fa-solid fa-phone"></i>
                                    <span id="kontak-customer">08123091283</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col items-center w-1/3 gap-6 justify-center">
                            {{-- BUTTON CETAK --}}
                            <a href="#" id="print-document" target="blank" class="w-full inline-flex justify-center items-center mx-5 py-2 bg-gradient-to-r from-[#144272] to-[#2C74B3] border border-transparent rounded-md text-xs text-white tracking-widest hover:bg-[#144272] focus:bg-[#144272] active:bg-[#144272] focus:outline-none focus:ring-2 focus:ring-[#144272] focus:ring-offset-2 transition ease-in-out duration-150">
                                <i class="fa-solid fa-print mr-2"></i>
                                {{ __('Cetak Doumen') }}
                            </a>
                            {{-- DELETE BUTTON --}}
                            <x-secondary-button class="w-full mx-5" id="delete-button">
                                {{ __('Delete') }}
                                <i class="fa-solid fa-trash hover:text-[#144272] text-[#2C74B3] text-lg pl-3"></i>
                            </x-secondary-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- DELETE CONFIRMATION --}}
    <div id="delete-wrap" class="hidden">
        <div class="fixed top-0 left-0 right-0 bottom-0 bg-black/70"></div>
        <div class="fixed top-1/2 left-1/2 bg-white overflow-hidden shadow-sm sm:rounded-lg -translate-x-1/2 -translate-y-1/2">
            <div class="p-6">
                <span class="text-gray-900 font-bold">Warning!</span>
                <span class="text-gray-700">Apakah anda yakin akan menghapus Data Sales Order dari database?</span>
                <form method="POST" id="delete-form">
                    @csrf
                    @method('DELETE')
                    <div class="mt-4 flex gap-4 justify-end">
                        <button id="delete-close" class="cancel-button inline-flex justiy-center items-cefnter px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-[#144272] uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#144272] focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">Cancel</button>
                        <button type="submit" class="delete-confirm inline-flex justify-center items-center px-4 py-2 bg-gradient-to-r from-[#144272] to-[#2C74B3] border border-transparent rounded-md text-xs text-white uppercase tracking-widest hover:bg-[#144272] focus:bg-[#144272] active:bg-[#144272] focus:outline-none focus:ring-2 focus:ring-[#144272] focus:ring-offset-2 transition ease-in-out duration-150">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
</x-app-layout>
