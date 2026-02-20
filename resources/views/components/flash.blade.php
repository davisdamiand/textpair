@props(['type' => 'message', 'timeout' => 5000])

@php
    $isError = $type === 'error';
    $sessionKey = $isError ? 'error' : 'message';
    $bgColor = $isError ? 'bg-red-500' : 'bg-green-500';
@endphp

@if(session()->has($sessionKey))
    <div x-data="{ show: true }" x-show="show" @if(!$isError) x-init="setTimeout(() => show = false, {{ $timeout }})" @endif
        x-transition:leave="transition ease-in duration-1000" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" {{ $attributes->merge(['class' => "p-4 $bgColor text-white rounded-2xl shadow-lg flex items-center justify-between mb-4"]) }}>
        <div class="flex items-center">
            @if($isError)
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            @else
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            @endif

            <span class="font-medium">{{ session($sessionKey) }}</span>
        </div>

        {{-- Always show close button for error, optional for success --}}
        <button @click="show = false" class="ml-4 p-1 hover:bg-white/20 rounded-full transition-colors focus:outline-none">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
@endif