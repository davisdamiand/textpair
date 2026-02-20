@props(['label', 'model'])

<div>
    <label class="text-[11px] font-bold text-zinc-500 uppercase mb-2 block">{{ $label }}</label>

    <div x-data="{ open: false, search: '' }" class="relative font-sans">

        <div @click="open = !open"
            class="w-full p-3 rounded-xl border flex justify-between items-center cursor-pointer transition-colors"
            :class="darkMode ? 'bg-zinc-800 border-zinc-700 text-white' : 'bg-white border-zinc-200 text-zinc-900'">

            <span x-text="{{ $model }}" :style="`font-family: '${{{ $model }}}', sans-serif;`"
                class="font-medium text-sm"></span>

            <div class="flex items-center space-x-2 text-zinc-400">
                <button type="button" @click.stop="{{ $model }} = 'Inter'; loadFont('Inter')"
                    class="hover:text-zinc-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </div>

        <div x-show="open" @click.outside="open = false" x-transition
            class="absolute z-50 w-full mt-2 border rounded-xl shadow-xl max-h-72 overflow-y-auto hide-scrollbar"
            :class="darkMode ? 'bg-zinc-800 border-zinc-700' : 'bg-white border-zinc-200'" style="display: none;">

            <div class="p-2 sticky top-0" :class="darkMode ? 'bg-zinc-800' : 'bg-white'">
                <input type="text" x-model="search" placeholder="Search fonts..."
                    class="w-full p-2 text-xs rounded-lg outline-none border"
                    :class="darkMode ? 'bg-zinc-900 border-zinc-700 text-white' : 'bg-zinc-50 border-zinc-200'">
            </div>

            <ul class="p-2 space-y-1">
                <template x-for="font in fonts.filter(f => f.name.toLowerCase().includes(search.toLowerCase()))"
                    :key="font.name">
                    <li @click="{{ $model }} = font.name; loadFont(font.name); open = false; search = ''"
                        class="flex justify-between items-center p-2 rounded-lg cursor-pointer transition-colors"
                        :class="darkMode ? 'hover:bg-zinc-700 text-zinc-200' : 'hover:bg-zinc-100 text-zinc-800'">

                        <span x-text="font.name" :style="`font-family: '${font.name}', sans-serif;`"
                            class="text-base"></span>

                        <span x-text="font.category" class="text-[10px] px-2 py-0.5 rounded-full"
                            :class="darkMode ? 'bg-zinc-900 text-zinc-400' : 'bg-zinc-200 text-zinc-500'">
                        </span>
                    </li>
                </template>
            </ul>
        </div>
    </div>

    <input type="hidden" name="{{ $model }}" :value="{{ $model }}">
</div>