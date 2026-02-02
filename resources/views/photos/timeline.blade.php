<x-app-layout>
    <x-slot name="header">
        Timeline
    </x-slot>

    <div class="p-6 pt-8 max-w-5xl mx-auto">
        @if($groupedPhotos->isEmpty())
            <div class="flex flex-col items-center justify-center py-32 text-slate-300">
                <span class="material-symbols-outlined text-7xl mb-4 opacity-20">history</span>
                <p class="font-black uppercase tracking-[0.2em] text-xs">No history found</p>
                <p class="text-[10px] mt-1 font-bold">Upload entries to see your timeline</p>
            </div>
        @else
            @foreach($groupedPhotos as $date => $photos)
                <!-- Timeline Section -->
                <div class="relative pl-10 pb-12 border-l-[3px] border-slate-100 dark:border-gray-700 ml-2 last:border-l-0">
                    <!-- Timeline Dot -->
                    <div
                        class="absolute -left-[11px] top-0 size-[22px] rounded-full bg-white dark:bg-slate-900 border-[5px] border-primary shadow-lg z-10">
                    </div>

                    <!-- Date Header -->
                    <div class="flex items-center mb-6 -mt-2">
                        <span
                            class="bg-primary/5 text-primary dark:bg-primary/20 dark:text-primary-300 px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-widest leading-none">
                            {{ $date }}
                        </span>
                    </div>

                    <!-- Items Grid -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-y-8 gap-x-4">
                        @foreach($photos as $photo)
                            <div class="group flex flex-col gap-2">
                                <a href="{{ route('photos.show', $photo) }}"
                                    class="relative w-full aspect-[4/5] rounded-2xl shadow-sm overflow-hidden bg-slate-100 dark:bg-slate-800">
                                    <img src="{{ asset('storage/' . ($photo->thumbnail ?? $photo->filename)) }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                        loading="lazy">

                                    <!-- Overlay -->
                                    <div class="absolute inset-0 bg-black/10 group-hover:bg-black/20 transition-colors"></div>

                                    <!-- Floating Date Badge inside image -->
                                    <div
                                        class="absolute bottom-2 left-2 px-2 py-1 bg-black/30 backdrop-blur-sm rounded-lg text-white text-[9px] font-bold">
                                        {{ \Carbon\Carbon::parse($photo->photo_date)->format('d') }}
                                    </div>
                                </a>
                                <div>
                                    <p class="text-xs font-bold truncate text-slate-700 dark:text-slate-200">{{ $photo->location }}
                                    </p>
                                    <p
                                        class="text-[10px] items-center text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider flex gap-1">
                                        {{ $photo->department }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @endif

        <!-- Bottom Padding -->
        <div class="h-32"></div>
    </div>
</x-app-layout>