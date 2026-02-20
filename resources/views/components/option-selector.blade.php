@props(['label', 'model', 'options'])

<div class="flex flex-col">
    <label class="text-[11px] font-bold text-zinc-500 uppercase mb-2 tracking-wider">
        {{ $label }}
    </label>

    <div class="flex p-1 rounded-xl border transition-colors overflow-hidden"
        :class="darkMode ? 'bg-zinc-900 border-zinc-700' : 'bg-zinc-50 border-zinc-200'">

        @foreach($options as $value => $display)
            <button type="button" @click="{{ $model }} = '{{ $value }}'"
                class="flex-1 py-2 px-1 text-[10px] font-bold rounded-lg transition-all duration-200" :class="{{ $model }} == '{{ $value }}' 
                        ? (darkMode ? 'bg-zinc-700 text-white shadow-sm' : 'bg-white text-zinc-900 shadow-sm') 
                        : (darkMode ? 'text-zinc-500 hover:text-zinc-300' : 'text-zinc-400 hover:text-zinc-600')">

                <div class="flex flex-col items-center">
                    <span>{{ $value }}</span>
                    <span class="opacity-60 font-medium text-[8px] uppercase tracking-tighter">{{ $display }}</span>
                </div>
            </button>
        @endforeach
    </div>

    <input type="hidden" name="{{ $model }}" :value="{{ $model }}">
</div>