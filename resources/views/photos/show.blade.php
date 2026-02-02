<x-app-layout>
    <x-slot name="hideNav">true</x-slot>

    <x-slot name="header">
        Photo Details
    </x-slot>

    <x-slot name="headerActions">
        <a href="{{ route('photos.index') }}"
            class="flex size-10 items-center justify-center rounded-full hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
            <span class="material-symbols-outlined text-slate-800 dark:text-white">arrow_back</span>
        </a>
    </x-slot>

    <main x-data="{ showDownloadOptions: false }" class="flex-1 pb-32">
        <!-- Image Preview Component -->
        <div class="flex w-full bg-white dark:bg-slate-900 @container border-b border-slate-200 dark:border-slate-800">
            <div class="w-full gap-1 overflow-hidden aspect-[4/3] flex relative">
                <!-- Main Image - Start with thumbnail, then swap to full if needed (or just show full here since it's one page) -->
                <div class="w-full bg-center bg-no-repeat bg-cover aspect-auto flex-1 transition-opacity duration-500"
                    style='background-image: url("{{ asset('storage/' . ($photo->thumbnail ?? $photo->filename)) }}");'>
                </div>
                <!-- Zoom Button Overlay -->
                <a href="{{ asset('storage/' . $photo->filename) }}" target="_blank"
                    class="absolute bottom-4 right-4 bg-black/50 backdrop-blur-sm text-white p-2 rounded-lg flex items-center justify-center cursor-pointer shadow-lg">
                    <span class="material-symbols-outlined text-xl">zoom_out_map</span>
                </a>
            </div>
        </div>

        <!-- Download Options Modal -->
        <div x-show="showDownloadOptions" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-full" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-full"
            class="fixed inset-0 z-[60] flex items-end justify-center p-0 bg-background-dark/80 backdrop-blur-sm"
            style="display: none;">

            <div @click.away="showDownloadOptions = false"
                class="relative w-full max-w-md bg-white dark:bg-[#111318] rounded-t-[40px] overflow-hidden shadow-2xl p-6 pb-12">
                <div class="w-12 h-1 bg-slate-200 dark:bg-slate-800 rounded-full mx-auto mb-6"></div>

                <h3 class="text-xl font-bold text-[#111318] dark:text-white mb-2 text-center">Ready to Download</h3>
                <p class="text-slate-500 text-sm text-center mb-8">Choose the quality that fits your current needs.</p>

                <div class="grid gap-4">
                    <!-- High Res -->
                    <a href="{{ asset('storage/' . $photo->filename) }}" download
                        class="flex items-center gap-4 p-5 bg-primary/5 border border-primary/20 rounded-3xl hover:bg-primary/10 transition-colors group">
                        <div
                            class="size-14 rounded-2xl bg-primary text-white flex items-center justify-center shadow-lg group-active:scale-95 transition-transform">
                            <span class="material-symbols-outlined text-2xl">high_quality</span>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-[#111318] dark:text-white">High Resolution</h4>
                            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">Original Quality
                                (~2-5 MB)</p>
                        </div>
                        <span class="material-symbols-outlined text-primary">arrow_forward</span>
                    </a>

                    <!-- Compressed -->
                    @if($photo->thumbnail)
                        <a href="{{ asset('storage/' . $photo->thumbnail) }}" download
                            class="flex items-center gap-4 p-5 bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 rounded-3xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors group">
                            <div
                                class="size-14 rounded-2xl bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300 flex items-center justify-center group-active:scale-95 transition-transform">
                                <span class="material-symbols-outlined text-2xl">speed</span>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-[#111318] dark:text-white">Optimized (Low-Data)</h4>
                                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">Small size
                                    (~300-400 KB)</p>
                            </div>
                            <span class="material-symbols-outlined text-slate-400">arrow_forward</span>
                        </a>
                    @endif
                </div>

                <button @click="showDownloadOptions = false"
                    class="w-full mt-6 py-4 text-sm font-black uppercase tracking-widest text-slate-400">Cancel</button>
            </div>
        </div>

        <!-- Metadata Section Header -->
        <div class="flex items-center px-4 pt-6 pb-2">
            <span class="material-symbols-outlined text-primary mr-2 text-2xl font-bold">database</span>
            <h3 class="text-slate-900 dark:text-white text-lg font-bold leading-tight tracking-tight">Metadata</h3>
        </div>

        <!-- DescriptionList Component -->
        <div
            class="mx-4 mb-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-xl overflow-hidden">
            <div class="p-5 space-y-0 divide-y divide-slate-50 dark:divide-gray-800">
                <div class="flex justify-between gap-x-6 py-4">
                    <p
                        class="text-slate-500 dark:text-gray-400 text-sm font-semibold uppercase tracking-widest text-[10px]">
                        Photo Date</p>
                    <p class="text-slate-900 dark:text-white text-sm font-bold">
                        {{ \Carbon\Carbon::parse($photo->photo_date)->format('M d, Y') }}</p>
                </div>
                <div class="flex justify-between gap-x-6 py-4">
                    <p
                        class="text-slate-500 dark:text-gray-400 text-sm font-semibold uppercase tracking-widest text-[10px]">
                        Location</p>
                    <div class="text-right">
                        <p class="text-slate-900 dark:text-white text-sm font-bold">{{ $photo->location }}</p>
                        <p class="text-slate-400 dark:text-gray-500 text-[10px] font-medium">Factory Asset Registry</p>
                    </div>
                </div>
                <div class="flex justify-between gap-x-6 py-4">
                    <p
                        class="text-slate-500 dark:text-gray-400 text-sm font-semibold uppercase tracking-widest text-[10px]">
                        Department</p>
                    <p class="text-slate-900 dark:text-white text-sm font-bold">{{ $photo->department }}</p>
                </div>
                <div class="flex justify-between gap-x-6 py-4">
                    <p
                        class="text-slate-500 dark:text-gray-400 text-sm font-semibold uppercase tracking-widest text-[10px]">
                        Uploader</p>
                    <div class="flex items-center gap-2">
                        <p class="text-slate-900 dark:text-white text-sm font-bold">{{ $photo->user->name }}</p>
                        <span
                            class="text-[10px] bg-primary/10 text-primary px-2 py-0.5 rounded-full font-bold">STAFF</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notes Section Header -->
        <div class="flex items-center px-4 pt-4 pb-2">
            <span class="material-symbols-outlined text-primary mr-2 text-2xl font-bold">notes</span>
            <h3 class="text-slate-900 dark:text-white text-lg font-bold leading-tight tracking-tight">Notes</h3>
        </div>

        <!-- Notes Content -->
        <div
            class="mx-4 mb-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 shadow-xl p-5">
            <p class="text-slate-600 dark:text-slate-300 text-sm leading-relaxed font-medium">
                {{ $photo->notes ?: 'No specific observations recorded for this documentation.' }}
            </p>
        </div>

        <!-- Danger Zone Section Header -->
        <div class="flex items-center px-4 pt-4 pb-2">
            <span class="material-symbols-outlined text-red-500 mr-2 text-2xl font-bold">warning</span>
            <h3 class="text-slate-900 dark:text-white text-lg font-bold leading-tight tracking-tight text-red-500">
                Danger Zone</h3>
        </div>

        <!-- Archive/Delete Section -->
        <div class="mx-4 mb-10">
            <form action="{{ route('photos.destroy', $photo) }}" method="POST"
                onsubmit="return confirm('Archive this documentation forever?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="w-full flex items-center justify-center gap-2 text-red-500 font-bold text-xs uppercase tracking-[0.2em] py-4 border-2 border-red-50 dark:border-red-900/10 rounded-2xl hover:bg-red-50 dark:hover:bg-red-900/20 transition-all">
                    <span class="material-symbols-outlined text-sm">delete_forever</span>
                    PURGE RECORD
                </button>
            </form>
        </div>

        <!-- Fixed Action Button at the Bottom -->
        <div
            class="fixed bottom-0 left-0 right-0 p-4 bg-white/90 dark:bg-background-dark/95 backdrop-blur-md border-t border-slate-200 dark:border-slate-800 z-50 max-w-7xl mx-auto md:rounded-b-3xl">
            <button @click="showDownloadOptions = true"
                class="w-full bg-primary hover:bg-primary/90 text-white font-bold py-5 px-6 rounded-2xl flex items-center justify-center gap-3 transition-all active:scale-[0.98] shadow-2xl shadow-primary/40 uppercase tracking-[0.2em] text-sm">
                <span class="material-symbols-outlined text-xl">cloud_download</span>
                <span>Download Assets</span>
            </button>
        </div>
    </main>
</x-app-layout>