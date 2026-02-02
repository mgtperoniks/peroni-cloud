<x-app-layout>
    <x-slot name="header">
        Gallery
    </x-slot>

    <x-slot name="headerActions">
        <button @click="$store.gallery.toggleFilter()" class="flex items-center justify-center rounded-full w-10 h-10 bg-transparent text-[#111318] dark:text-white hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
            <span class="material-symbols-outlined text-[24px]">filter_list</span>
        </button>
    </x-slot>

    <div>
        <!-- Filter Modal -->
        <div x-show="$store.gallery.showFilters" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-full"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-full"
             class="fixed inset-0 z-[100] flex items-end sm:items-center justify-center p-0 sm:p-4 bg-background-dark/80 backdrop-blur-md"
             style="display: none;">
            
            <div @click.away="$store.gallery.showFilters = false" class="relative w-full max-w-md bg-white dark:bg-[#111318] rounded-t-[32px] sm:rounded-[32px] overflow-hidden shadow-2xl">
                <!-- Header -->
                <header class="sticky top-0 z-10 flex items-center justify-between border-b border-gray-100 dark:border-gray-800 bg-white dark:bg-[#111318] p-4">
                    <div class="flex items-center gap-3">
                        <button @click="$store.gallery.showFilters = false" class="flex size-10 items-center justify-center rounded-full hover:bg-gray-100 dark:hover:bg-gray-800">
                            <span class="material-symbols-outlined text-[#111318] dark:text-white">close</span>
                        </button>
                        <h2 class="text-[#111318] dark:text-white text-lg font-bold leading-tight tracking-[-0.015em]">Filter & Sort</h2>
                    </div>
                    <a href="{{ route('photos.index') }}" class="text-primary text-sm font-semibold">Reset All</a>
                </header>

                <!-- Content -->
                <div class="overflow-y-auto max-h-[80vh] pb-32 no-scrollbar">
                    <form action="{{ route('photos.index') }}" method="GET">
                        <!-- Date Range -->
                        <section class="p-4 pt-6">
                            <h3 class="text-[#111318] dark:text-white text-lg font-bold leading-tight mb-4 px-1">Date Range</h3>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="space-y-1">
                                    <label class="text-[10px] font-bold uppercase tracking-wider text-slate-400 pl-1">From</label>
                                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full bg-gray-50 dark:bg-gray-800 border-gray-100 dark:border-gray-700 rounded-xl h-12 px-3 font-medium text-sm">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[10px] font-bold uppercase tracking-wider text-slate-400 pl-1">To</label>
                                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full bg-gray-50 dark:bg-gray-800 border-gray-100 dark:border-gray-700 rounded-xl h-12 px-3 font-medium text-sm">
                                </div>
                            </div>
                        </section>

                        <!-- Location -->
                        <section class="p-4">
                            <h3 class="text-[#111318] dark:text-white text-lg font-bold leading-tight mb-4 px-1">Location</h3>
                            <div class="flex gap-3 flex-wrap">
                                @foreach(['Kantor', 'Bahan Baku', 'Cor Flange', 'Netto Flange', 'Produksi Bubut', 'Cetak/Cor Fitting', 'Netto Fitting', 'Gudang Jadi FL', 'Gudang Jadi PF', 'Aluminium', 'Produksi Besi', 'Satpam', 'Sparepart dan BP'] as $loc)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="location" value="{{ $loc }}" class="hidden peer" {{ request('location') == $loc ? 'checked' : '' }}>
                                        <span class="flex h-11 items-center justify-center rounded-lg px-5 text-sm font-medium border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 peer-checked:bg-primary peer-checked:text-white peer-checked:border-primary transition-all">
                                            {{ $loc }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </section>

                        <!-- Department -->
                        <section class="p-4">
                            <h3 class="text-[#111318] dark:text-white text-lg font-bold leading-tight mb-4 px-1">Department</h3>
                            <div class="flex gap-3 flex-wrap">
                                @foreach(['PPIC', 'QC Fitting', 'QC Flange', 'QC Inspektor PD', 'QC Inspector FL', 'K3', 'Sales'] as $dept)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="department" value="{{ $dept }}" class="hidden peer" {{ request('department') == $dept ? 'checked' : '' }}>
                                        <span class="flex h-11 items-center justify-center rounded-lg px-5 text-sm font-medium border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 peer-checked:bg-primary peer-checked:text-white peer-checked:border-primary transition-all">
                                            {{ $dept }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </section>
                    </form>
                </div>

                <!-- Footer -->
                <footer class="absolute bottom-0 left-0 right-0 flex gap-4 bg-white dark:bg-[#111318] p-4 border-t border-gray-100 dark:border-gray-800">
                    <button @click="$store.gallery.showFilters = false" class="flex-1 h-14 rounded-xl border border-gray-200 dark:border-gray-700 text-[#111318] dark:text-white font-bold">
                        Close
                    </button>
                    <button @click="document.querySelector('form').submit()" class="flex-[2] h-14 rounded-xl bg-primary text-white font-bold shadow-lg shadow-primary/20">
                        Apply Filters
                    </button>
                </footer>
            </div>
        </div>

        <!-- Filter Pills / Categories -->
        <div class="flex gap-4 p-4 py-2 overflow-x-auto no-scrollbar bg-white dark:bg-background-dark/50 backdrop-blur-sm sticky top-14 z-20">
            <a href="{{ route('photos.index') }}" class="px-5 py-2 {{ !request('department') ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'bg-slate-100 dark:bg-slate-800 text-slate-500' }} text-[10px] font-black uppercase tracking-widest rounded-xl whitespace-nowrap transition-all">All Gallery</a>
            @foreach(['PPIC', 'QC Fitting', 'QC Flange', 'QC Inspektor PD', 'QC Inspector FL', 'K3', 'Sales'] as $cat)
                <a href="{{ route('photos.index', array_merge(request()->except('department'), ['department' => $cat])) }}" class="px-5 py-2 {{ request('department') == $cat ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'bg-slate-100 dark:bg-slate-800 text-slate-500' }} text-[10px] font-black uppercase tracking-widest rounded-xl whitespace-nowrap transition-all">{{ $cat }}</a>
            @endforeach
        </div>

        <!-- ImageGrid - Responsive Grid -->
        <div class="p-4 pt-2">
            @if($photos->isEmpty())
                <div class="flex flex-col items-center justify-center py-32 text-slate-300">
                    <span class="material-symbols-outlined text-7xl mb-4 opacity-20">inventory_2</span>
                    <p class="font-black uppercase tracking-[0.2em] text-xs">No documentation found</p>
                    <p class="text-[10px] mt-1 font-bold">Try adjusting filters or upload new entry</p>
                </div>
            @else
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                    @foreach($photos as $photo)
                        <div class="group flex flex-col gap-3">
                            <a href="{{ route('photos.show', $photo) }}" class="relative w-full aspect-square rounded-[24px] shadow-sm overflow-hidden bg-slate-100 dark:bg-slate-800">
                                <img src="{{ asset('storage/' . ($photo->thumbnail ?? $photo->filename)) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="Documentation" loading="lazy">
                                
                                <!-- Status Badge -->
                                <div class="absolute top-3 right-3 flex flex-col gap-1 items-end">
                                    @if($photo->department == 'Damages' || $photo->department == 'Safety')
                                        <div class="px-2 py-1 {{ $photo->department == 'Damages' ? 'bg-red-500' : 'bg-amber-500' }} text-white text-[8px] font-black rounded-lg uppercase tracking-wider shadow-lg">
                                            {{ $photo->department }}
                                        </div>
                                    @endif
                                </div>

                                <div class="absolute inset-0 bg-primary/20 opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-center justify-center backdrop-blur-[2px]">
                                    <div class="size-12 rounded-full bg-white text-primary flex items-center justify-center shadow-2xl scale-50 group-hover:scale-100 transition-transform duration-300">
                                        <span class="material-symbols-outlined font-bold">visibility</span>
                                    </div>
                                </div>
                            </a>
                            <div class="px-2">
                                <p class="text-slate-900 dark:text-white text-sm font-bold leading-tight truncate group-hover:text-primary transition-colors">{{ $photo->location }}</p>
                                <div class="flex items-center justify-between mt-1 pt-1 border-t border-slate-50 dark:border-gray-800">
                                    <p class="text-slate-400 dark:text-gray-500 text-[9px] font-black uppercase tracking-widest">{{ \Carbon\Carbon::parse($photo->photo_date)->format('d M y') }}</p>
                                    <p class="text-slate-400 dark:text-gray-500 text-[9px] font-bold">{{ $photo->user->name }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        
        <!-- Bottom Padding -->
        <div class="h-32"></div>
    </div>
</x-app-layout>
