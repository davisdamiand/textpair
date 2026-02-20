@props(['savedPairs'])

<div class="mt-10 space-y-4">
    <h3 class="text-[10px] font-black tracking-widest text-zinc-400 uppercase px-2">Saved Combinations</h3>

    <div class="grid grid-cols-1 gap-3">
        @foreach($savedPairs as $pair)
            {{-- 1. Changed from <button> to <div> --}}
                    <div @click="loadSavedPair({{ $pair->toJson() }})"
                        :class="darkMode ? 'bg-zinc-900 border-zinc-800 hover:border-zinc-500' : 'bg-white border-zinc-100 hover:border-zinc-900'"
                        class="group w-full text-left p-4 rounded-2xl border transition-all hover:shadow-md cursor-pointer relative">

                        <div class="flex justify-between items-start mb-2">
                            <span :class="darkMode ? 'text-zinc-100' : 'text-zinc-900'"
                                class="text-[10px] font-bold uppercase truncate pr-4">
                                {{ $pair->name }}
                            </span>

                            {{-- 2. Right side column for Time + Delete Button --}}
                            <div class="flex flex-col items-end gap-2">
                                <span class="text-[8px] text-zinc-400 whitespace-nowrap">
                                    {{ $pair->created_at->diffForHumans() }}
                                </span>

                                <form action="{{ route('fontpair.delete', $pair->id) }}" method="POST" @click.stop>
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        :class="darkMode ? 'border-zinc-700 hover:bg-red-900/20 hover:border-red-500 hover:text-red-500' : 'border-zinc-200 hover:bg-red-50 hover:border-red-500 hover:text-red-500'"
                                        class="text-[10px] uppercase tracking-tighter px-2 py-1 rounded-md border transition-colors font-bold text-zinc-400">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="space-y-1">
                            <p :class="darkMode ? 'text-zinc-100' : 'text-zinc-900'" class="text-lg leading-none truncate"
                                style="font-family: '{{ $pair->heading->name }}'">
                                {{ $pair->heading->name }}
                            </p>

                            <p class="text-[10px] text-zinc-500 truncate" style="font-family: '{{ $pair->body->name }}'">
                                & {{ $pair->body->name }}
                            </p>
                        </div>
                    </div>
        @endforeach
            </div>