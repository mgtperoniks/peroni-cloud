<x-app-layout>
    <x-slot name="header">
        Create Folder
    </x-slot>

    <x-slot name="headerActions">
        <a href="{{ route('folders.index') }}"
            class="flex items-center justify-center rounded-full w-10 h-10 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
            <span class="material-symbols-outlined">close</span>
        </a>
    </x-slot>

    <div class="p-6 max-w-md mx-auto">
        <form action="{{ route('folders.store') }}" method="POST" class="flex flex-col gap-6">
            @csrf
            <div class="flex flex-col gap-2">
                <label
                    class="text-slate-900 dark:text-white text-[10px] font-black uppercase tracking-[0.2em] px-2 pl-4">Folder
                    Name</label>
                <input name="name" type="text" placeholder="e.g. Proyek A, Maintenance Feb..." required autofocus
                    class="w-full rounded-2xl border-none bg-white dark:bg-slate-900 shadow-xl shadow-slate-200/50 dark:shadow-none h-16 px-6 font-bold text-slate-900 dark:text-white focus:ring-2 focus:ring-primary/50 transition-all">
            </div>

            <button type="submit"
                class="flex w-full items-center justify-center rounded-2xl h-16 bg-primary text-white text-sm font-bold tracking-[0.2em] shadow-2xl shadow-primary/40 transition-all hover:scale-[1.01] active:scale-95 uppercase">
                <span class="material-symbols-outlined mr-3">create_new_folder</span>
                Create Folder
            </button>

            <p class="text-[10px] text-center text-slate-400 font-bold uppercase tracking-widest">Folder ini akan
                digunakan untuk mengelompokkan dokumentasi foto Anda.</p>
        </form>
    </div>
</x-app-layout>