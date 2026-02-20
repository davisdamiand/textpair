@props([
    'label',
    'model',
    'step' => 1,
    'min' => 0,
    'max' => 1000,
    'value' => 0
])

<div class="flex flex-col">
    <label class="text-[11px] font-bold text-zinc-500 uppercase mb-2">{{ $label }}</label>
    
    <div class="relative flex items-center">
        <button type="button" 
            @click="{{ $model }} = Math.max({{ $min }}, parseFloat({{ $model }}) - {{ $step }})"
            class="absolute left-2 p-1 rounded-md hover:bg-zinc-100 dark:hover:bg-zinc-700 text-zinc-400 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" /></svg>
        </button>

        <input 
            type="number" 
            x-model="{{ $model }}"
            step="{{ $step }}"
            min="{{ $min }}"
            max="{{ $max }}"
            class="w-full p-3 px-10 rounded-xl border font-medium text-sm text-center transition-all outline-none focus:ring-2 focus:ring-blue-500/20 appearance-none"
            :class="darkMode 
                ? 'bg-zinc-800 border-zinc-700 text-white focus:border-blue-500' 
                : 'bg-white border-zinc-200 text-zinc-900 focus:border-blue-400'"
        >

        <button type="button" 
            @click="{{ $model }} = Math.min({{ $max }}, parseFloat({{ $model }}) + {{ $step }})"
            class="absolute right-2 p-1 rounded-md hover:bg-zinc-100 dark:hover:bg-zinc-700 text-zinc-400 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
        </button>
    </div>
</div>