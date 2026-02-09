<x-app-layout>
    <x-slot name="header">
        {{ $folder->name }}
    </x-slot>

    <x-slot name="headerActions">
        <a href="{{ route('folders.index') }}"
            class="flex items-center justify-center rounded-full w-10 h-10 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
    </x-slot>

    <div class="p-4">
        <div class="flex items-center gap-3 mb-6 px-2">
            <span class="material-symbols-outlined text-amber-500">folder_open</span>
            <div class="flex flex-col">
                <h2 class="text-xs font-black uppercase tracking-[0.2em] text-slate-400">Folder Content</h2>
                <p class="text-[10px] font-bold text-slate-500">{{ count($photos) }} Documentation(s)</p>
            </div>
        </div>

        @if($photos->isEmpty())
            <div class="flex flex-col items-center justify-center py-32 text-slate-300">
                <span class="material-symbols-outlined text-7xl mb-4 opacity-20">no_photography</span>
                <p class="font-black uppercase tracking-[0.2em] text-xs">Folder is empty</p>
                <a href="{{ route('photos.create', ['folder_id' => $folder->id]) }}"
                    class="mt-4 px-6 py-3 bg-primary text-white text-[10px] font-black uppercase tracking-widest rounded-xl shadow-lg shadow-primary/20">Upload
                    Now</a>
            </div>
        @else
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                @foreach($photos as $photo)
                    <div class="group flex flex-col gap-3">
                        <a href="{{ route('photos.show', $photo) }}"
                            class="relative w-full aspect-square rounded-[24px] shadow-sm overflow-hidden bg-slate-100 dark:bg-slate-800">
                            <img src="{{ asset('storage/' . ($photo->thumbnail ?? $photo->filename)) }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                                alt="Documentation" loading="lazy">

                            <div
                                class="absolute inset-0 bg-primary/20 opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-center justify-center backdrop-blur-[2px]">
                                <div
                                    class="size-12 rounded-full bg-white text-primary flex items-center justify-center shadow-2xl scale-50 group-hover:scale-100 transition-transform duration-300">
                                    <span class="material-symbols-outlined font-bold">visibility</span>
                                </div>
                            </div>
                        </a>
                        <div class="px-2">
                            <p
                                class="text-slate-900 dark:text-white text-sm font-bold leading-tight truncate group-hover:text-primary transition-colors">
                                {{ $photo->location }}</p>
                            <div
                                class="flex items-center justify-between mt-1 pt-1 border-t border-slate-50 dark:border-gray-800">
                                <p class="text-slate-400 dark:text-gray-500 text-[9px] font-black uppercase tracking-widest">
                                    {{ \Carbon\Carbon::parse($photo->photo_date)->format('d M y') }}</p>
                                <p class="text-slate-400 dark:text-gray-500 text-[9px] font-bold">{{ $photo->user->name }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>