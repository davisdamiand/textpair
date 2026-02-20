@props([
    'show',      // The Alpine variable controlling visibility (e.g., showConfirmModal)
    'name',      // The Alpine variable for the project name (e.g., projectName)
    'formRef',   // The x-ref of the form to submit (e.g., mainForm)
    'title' => 'Overwrite Project?',
    'type'  => 'warning'
])

<template x-teleport="body">
    <div x-show="{{ $show }}" 
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-zinc-950/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display: none;">
        
        <div @click.away="{{ $show }} = false" 
             class="bg-white dark:bg-zinc-900 w-full max-w-md rounded-[2.5rem] p-10 shadow-2xl border border-zinc-100 dark:border-zinc-800 text-center"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="scale-90 opacity-0"
             x-transition:enter-end="scale-100 opacity-100">
            
            {{-- Icon --}}
            <div class="w-20 h-20 bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>

            <h3 class="text-2xl font-bold mb-2 dark:text-white">{{ $title }}</h3>
            
            <p class="text-zinc-500 dark:text-zinc-400 mb-8 px-4">
                A project named <span class="font-bold text-zinc-900 dark:text-zinc-100" x-text="{{ $name }}"></span> already exists. Do you want to update it with your new settings?
            </p>

            <div class="flex flex-col gap-3">
                {{-- Submit Button --}}
                <button @click="$refs.{{ $formRef }}.submit()" 
                    class="w-full bg-zinc-900 dark:bg-zinc-100 dark:text-zinc-900 text-white py-4 rounded-2xl font-bold hover:scale-[1.02] transition-transform shadow-lg shadow-zinc-200 dark:shadow-none">
                    Yes, Update Existing
                </button>
                
                {{-- Cancel Button --}}
                <button @click="{{ $show }} = false" 
                    class="w-full py-4 text-zinc-400 font-medium hover:text-zinc-900 dark:hover:text-white transition-colors">
                    No, Cancel
                </button>
            </div>
        </div>
    </div>
</template>