<button {{ $attributes->merge(['type' => 'button', 'class' => 'w-full bg-zinc-900 text-white py-4 rounded-2xl font-bold text-xs hover:scale-[1.02] transition-all active:scale-95 shadow-xl shadow-zinc-200']) }}>
    {{ $slot }}
</button>