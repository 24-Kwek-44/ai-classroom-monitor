@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'w-full bg-white text-black placeholder:text-[#ACACAC] border-2 border-transparent focus:border-[#01F5D1] focus:ring-0 rounded-full']) !!}>
