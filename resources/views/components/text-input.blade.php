@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-[#2C74B3] focus:border-[#144272] focus:ring-[#2C74B3] rounded-md shadow-sm']) !!}>
