<x-app-layout>
    <x-slot name="hideNav">true</x-slot>

    <x-slot name="header">
        Photo Details
    </x-slot>

    <x-slot name="headerActions">
        <a href="{{ route('photos.index') }}"
            class="flex size-10 items-center justify-center rounded-full bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white shadow-sm ring-1 ring-inset ring-slate-200 dark:ring-slate-700 hover:bg-primary hover:text-white dark:hover:bg-primary transition-all group">
            <span
                class="material-symbols-outlined font-bold group-hover:-translate-x-0.5 transition-transform">arrow_back</span>
        </a>
    </x-slot>

    <main x-data="{ showDownloadOptions: false }"
        class="flex flex-col md:flex-row md:h-[calc(100vh-73px)] md:overflow-hidden bg-white dark:bg-background-dark">
        <!-- Left Column: Image Viewer -->
        <div
            class="flex-1 bg-slate-50 dark:bg-[#0a0c10] flex items-center justify-center relative md:h-full overflow-hidden border-b md:border-b-0 border-slate-200 dark:border-slate-800">
            <div class="w-full h-full flex items-center justify-center relative">
                <!-- Main Image -->
                <img src="{{ asset('storage/' . ($photo->thumbnail ?? $photo->filename)) }}" alt="Photo"
                    class="max-w-full max-h-full object-contain shadow-2xl transition-opacity duration-500">

                <!-- Navigation Buttons -->
                @if($next)
                    <a href="{{ route('photos.show', $next) }}"
                        class="absolute left-4 top-1/2 -translate-y-1/2 p-4 rounded-full bg-black/20 hover:bg-black/50 text-white backdrop-blur-md transition-all active:scale-95 group z-10">
                        <span
                            class="material-symbols-outlined text-3xl group-hover:-translate-x-1 transition-transform">chevron_left</span>
                    </a>
                @endif

                @if($previous)
                    <a href="{{ route('photos.show', $previous) }}"
                        class="absolute right-4 top-1/2 -translate-y-1/2 p-4 rounded-full bg-black/20 hover:bg-black/50 text-white backdrop-blur-md transition-all active:scale-95 group z-10">
                        <span
                            class="material-symbols-outlined text-3xl group-hover:translate-x-1 transition-transform">chevron_right</span>
                    </a>
                @endif

                <!-- Zoom Button Overlay -->
                <a href="{{ asset('storage/' . $photo->filename) }}" target="_blank"
                    class="absolute bottom-6 right-6 bg-black/60 backdrop-blur-md text-white px-4 py-2.5 rounded-xl flex items-center gap-2 hover:bg-black/80 transition-all shadow-lg z-20 font-bold text-xs uppercase tracking-widest">
                    <span class="material-symbols-outlined text-lg">zoom_out_map</span>
                    <span>Full View</span>
                </a>
            </div>
        </div>

        <!-- Right Column: Sidebar Details -->
        <div
            class="w-full md:w-[420px] lg:w-[480px] bg-white dark:bg-slate-900 md:h-full flex flex-col md:border-l border-slate-200 dark:border-slate-800">
            <div class="flex-1 md:overflow-y-auto no-scrollbar">
                <!-- Metadata Section -->
                <div class="p-6 pt-8 space-y-6 pb-32 md:pb-12">
                    <div class="flex items-center">
                        <span class="material-symbols-outlined text-primary mr-3 text-2xl font-bold">database</span>
                        <h3
                            class="text-slate-900 dark:text-white text-lg font-bold leading-tight tracking-tight uppercase tracking-[0.1em]">
                            Metadata</h3>
                    </div>

                    <div
                        class="rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 divide-y divide-slate-100 dark:divide-slate-800 overflow-hidden">
                        <div class="flex justify-between items-center p-4">
                            <p
                                class="text-slate-500 dark:text-gray-400 text-[10px] font-black uppercase tracking-widest">
                                Photo Date</p>
                            <p class="text-slate-900 dark:text-white text-sm font-bold">
                                {{ \Carbon\Carbon::parse($photo->photo_date)->format('M d, Y') }}</p>
                        </div>
                        <div class="flex justify-between items-center p-4">
                            <p
                                class="text-slate-500 dark:text-gray-400 text-[10px] font-black uppercase tracking-widest">
                                Location</p>
                            <p class="text-slate-900 dark:text-white text-sm font-bold text-right">
                                {{ $photo->location }}</p>
                        </div>
                        <div class="flex justify-between items-center p-4">
                            <p
                                class="text-slate-500 dark:text-gray-400 text-[10px] font-black uppercase tracking-widest">
                                Department</p>
                            <p class="text-slate-900 dark:text-white text-sm font-bold">{{ $photo->department }}</p>
                        </div>
                        <div class="flex justify-between items-center p-4">
                            <p
                                class="text-slate-500 dark:text-gray-400 text-[10px] font-black uppercase tracking-widest">
                                Uploader</p>
                            <div class="flex items-center gap-2">
                                <p class="text-slate-900 dark:text-white text-sm font-bold">{{ $photo->user->name }}</p>
                                <span
                                    class="text-[9px] bg-primary/10 text-primary px-2 py-0.5 rounded-full font-black uppercase tracking-tighter">STAFF</span>
                            </div>
                        </div>
                    </div>

                    <!-- Notes Section -->
                    <div class="space-y-4 pt-4">
                        <div class="flex items-center">
                            <span class="material-symbols-outlined text-primary mr-3 text-2xl font-bold">notes</span>
                            <h3
                                class="text-slate-900 dark:text-white text-lg font-bold leading-tight tracking-tight uppercase tracking-[0.1em]">
                                Analysis Notes</h3>
                        </div>
                        <div
                            class="bg-indigo-50/30 dark:bg-indigo-900/10 border border-indigo-100/50 dark:border-indigo-800/30 rounded-2xl p-5">
                            <p class="text-slate-600 dark:text-slate-300 text-sm leading-relaxed font-medium italic">
                                "{{ $photo->notes ?: 'No specific observations recorded for this documentation.' }}"
                            </p>
                        </div>
                    </div>

                    <!-- Danger Zone -->
                    <div class="space-y-4 pt-8">
                        <div class="flex items-center">
                            <span class="material-symbols-outlined text-red-500 mr-3 text-2xl font-bold">warning</span>
                            <h3
                                class="text-red-500 text-lg font-bold leading-tight tracking-tight uppercase tracking-[0.1em]">
                                Danger Zone</h3>
                        </div>
                        <form action="{{ route('photos.destroy', $photo) }}" method="POST"
                            onsubmit="return confirm('Archive this documentation forever?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full flex items-center justify-center gap-2 text-red-500 font-bold text-[10px] uppercase tracking-[0.2em] py-4 border-2 border-red-50 dark:border-red-900/10 rounded-2xl hover:bg-red-50 dark:hover:bg-red-900/20 transition-all">
                                <span class="material-symbols-outlined text-sm">delete_forever</span>
                                PURGE RECORD FROM SYSTEM
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Download Button (Sticky for both mobile and desktop sidebar) -->
            <div
                class="fixed md:relative bottom-0 left-0 right-0 p-4 bg-white/95 dark:bg-slate-900/98 backdrop-blur-md border-t border-slate-200 dark:border-slate-800 z-30">
                <button @click="showDownloadOptions = true"
                    class="w-full bg-primary hover:bg-primary/90 text-white font-black py-5 px-6 rounded-2xl flex items-center justify-center gap-3 transition-all active:scale-[0.98] shadow-2xl shadow-primary/40 uppercase tracking-[0.2em] text-sm">
                    <span class="material-symbols-outlined text-xl">cloud_download</span>
                    <span>Download Assets</span>
                </button>
            </div>
        </div>

        <!-- Download Options Modal (Remains same) -->
        <div x-show="showDownloadOptions" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-full" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-full"
            class="fixed inset-0 z-[100] flex items-end justify-center p-0 bg-background-dark/80 backdrop-blur-sm"
            style="display: none;">

            <div @click.away="showDownloadOptions = false"
                class="relative w-full max-w-md bg-white dark:bg-[#111318] rounded-t-[40px] overflow-hidden shadow-2xl p-6 pb-12">
                <div class="w-12 h-1 bg-slate-200 dark:bg-slate-800 rounded-full mx-auto mb-6"></div>

                <h3 class="text-xl font-bold text-[#111318] dark:text-white mb-2 text-center">Ready to Download</h3>
                <p class="text-slate-500 text-sm text-center mb-8">Choose the quality that fits your current needs.</p>

                <div class="grid gap-4">
                    <a href="{{ asset('storage/' . $photo->filename) }}" download
                        class="flex items-center gap-4 p-5 bg-primary/5 border border-primary/20 rounded-3xl hover:bg-primary/10 transition-colors group">
                        <div
                            class="size-14 rounded-2xl bg-primary text-white flex items-center justify-center shadow-lg group-active:scale-95 transition-transform">
                            <span class="material-symbols-outlined text-2xl">high_quality</span>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-[#111318] dark:text-white">High Resolution</h4>
                            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest text-left">Original
                                Quality (~2-5 MB)</p>
                        </div>
                        <span class="material-symbols-outlined text-primary">arrow_forward</span>
                    </a>

                    @if($photo->thumbnail)
                        <a href="{{ asset('storage/' . $photo->thumbnail) }}" download
                            class="flex items-center gap-4 p-5 bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 rounded-3xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors group">
                            <div
                                class="size-14 rounded-2xl bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300 flex items-center justify-center group-active:scale-95 transition-transform">
                                <span class="material-symbols-outlined text-2xl">speed</span>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-[#111318] dark:text-white">Optimized (Low-Data)</h4>
                                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest text-left">Small
                                    size (~300-400 KB)</p>
                            </div>
                            <span class="material-symbols-outlined text-slate-400">arrow_forward</span>
                        </a>
                    @endif
                </div>

                <button @click="showDownloadOptions = false"
                    class="w-full mt-6 py-4 text-sm font-black uppercase tracking-widest text-slate-400">Cancel</button>
            </div>
        </div>
    </main>
</x-app-layout>