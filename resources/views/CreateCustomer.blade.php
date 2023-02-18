<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($message = Session::get('customerSuccess'))
                <div class="bg-green-200 overflow-hidden shadow-sm sm:rounded-lg mb-8 py-4 px-6 text-green-800 flex justify-between" id="flash">
                    <span>
                        <i class="fa-solid fa-circle-check inline-block"></i>
                        <p class="font-bold inline-block pl-2">Awesome!</p>
                        <p class="inline-block pl-2">{{ $message }}</p>
                    </span>
                    <a href="#"><i class="fa-solid fa-xmark text-2xl" id="flash-close"></i></a>
                </div>   
            @endif
            @if ($message = Session::get('editSuccess'))
                <div class="bg-green-200 overflow-hidden shadow-sm sm:rounded-lg mb-8 py-4 px-6 text-green-800 flex justify-between" id="flash">
                    <span>
                        <i class="fa-solid fa-circle-check inline-block"></i>
                        <p class="font-bold inline-block pl-2">Great!</p>
                        <p class="inline-block pl-2">{{ $message }}</p>
                    </span>
                    <a href="#"><i class="fa-solid fa-xmark text-2xl" id="flash-close"></i></a>
                </div>   
            @endif
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between">
                        @if ($status == 'edit')
                            <span class="text-lg">{{ __("Customer Edit Form") }}</span>
                        @else
                            <span class="text-lg">{{ __("Customer Registration Form") }}</span>
                        @endif
                    </div>

                    {{-- form --}}
                    <form method="POST" action="{{ isset($create) ? route('customer.store') : route('customer.update', $customer->code) }}" id="input-form">
                        <div class="flex justify-center mt-4">
                            <div class=" grid grid-cols-6 gap-6 w-2/3">
                                @csrf
                                @if (!isset($create))
                                    @method('PATCH')
                                @endif
                                <!-- Customer Code -->
                                <div class="mx-4 col-span-3">
                                    <x-input-label for="code" :value="__('Customer Code')" />
                                    <x-text-input id="code" class="block mt-1 w-full text-[#144272]" type="text" value="{{ isset($uuid) ? $uuid : $customer->code }}" :disabled=true/>
                                        <input type="hidden" value="{{ (isset($uuid)) ? $uuid : $customer->code }}" name="code">
                                        <x-input-error :messages="$errors->get('code')" class="mt-2" />
                                </div>

                                <!-- Term Of Payment -->
                                <div class="mx-4 col-span-3">
                                    <x-input-label for="term" :value="__('Term Of Payment (Day)')" class="after:content-['*'] after:ml-0.5 after:text-red-500" />
                                    <x-text-input id="term" class="block mt-1 w-full" type="number" name="term" value="{{ old('term') ? old('term') : (isset($customer->term) ? $customer->term : '') }}" min="0" required autofocus/>
                                    <x-input-error :messages="$errors->get('term')" class="mt-2" />
                                </div>
                                
                                <!-- Customer Name -->
                                <div class="mx-4 col-span-3">
                                    <x-input-label for="name" :value="__('Customer name')" class="after:content-['*'] after:ml-0.5 after:text-red-500"/>
                                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" value="{{ old('name') ? old('name') : (isset($customer->name) ? $customer->name : '') }}" required autofocus/>
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>
                            
                                <!-- Delivery address -->
                                <div class="mx-4 col-span-3">
                                    <x-input-label for="address" :value="__('Delivery address')" class="after:content-['*'] after:ml-0.5 after:text-red-500" />
                                    <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" value="{{ old('address') ? old('address') : (isset($customer->address) ? $customer->address : '') }}" required autofocus/>
                                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
                                </div>
                                    
                                <!-- NPWP -->
                                <div class="mx-4 col-span-3">
                                    <x-input-label for="npwp" :value="__('NPWP')" class="after:content-['*'] after:ml-0.5 after:text-red-500"/>
                                    <x-text-input id="npwp" class="block mt-1 w-full" type="text" name="npwp" value="{{ old('npwp') ? old('npwp') : (isset($customer->npwp) ? $customer->npwp : '') }}" required autofocus placeholder="XX.XXX.XXX-X.XXX.XXX"/>
                                    <x-input-error :messages="$errors->get('npwp')" class="mt-2" />
                                </div>

                                <!-- NPWP Address -->
                                <div class="mx-4 col-span-3">
                                    <x-input-label for="npwp_add" :value="__('NPWP Address')" />
                                    <x-text-input id="npwp_add" class="block mt-1 w-full" type="text" name="npwp_add" value="{{ old('npwp_add') ? old('npwp_add') : (isset($customer->npwp_add) ? $customer->npwp_add : '') }}" autofocus/>
                                    <x-input-error :messages="$errors->get('npwp_add')" class="mt-2" />
                                </div>

                                <!-- Phone -->
                                <div class="mx-4 col-span-2">
                                    <x-input-label for="phone" :value="__('Phone')" />
                                    <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" value="{{ old('phone') ? old('phone') : (isset($customer->phone) ? $customer->phone : '') }}" autofocus placeholder="08XX XXXX XXXX"/>
                                    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                                </div>

                                <!-- Contact Person -->
                                <div class="mx-4 col-span-2">
                                    <x-input-label for="contact" :value="__('Contact Person')" />
                                    <x-text-input id="contact" class="block mt-1 w-full" type="text" name="contact" value="{{ old('contact') ? old('contact') : (isset($customer->contact) ? $customer->contact : '') }}" autofocus/>
                                    <x-input-error :messages="$errors->get('contact')" class="mt-2" />
                                </div>
                        
                                <!-- Email -->
                                <div class="mx-4 col-span-2">
                                    <x-input-label for="email" :value="__('Email')" class="after:content-['*'] after:ml-0.5 after:text-red-500"/>
                                    <x-text-input id="email" class="block mt-1 w-full normal-case" type="text" name="email" value="{{ old('email') ? old('email') : (isset($customer->email) ? $customer->email : '') }}" required autofocus placeholder="your.mail@gmail.com"/>
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
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
                    <a href="{{ route('customer.index') }}"><i class="fa-solid fa-arrow-left mr-3"></i>Customer List</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
