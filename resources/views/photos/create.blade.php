<x-app-layout>
    <x-slot name="hideNav">true</x-slot>

    <x-slot name="header">
        Upload Documentation
    </x-slot>

    <x-slot name="headerActions">
        <a href="{{ route('photos.index') }}" class="flex items-center justify-center rounded-full w-10 h-10 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
            <span class="material-symbols-outlined">close</span>
        </a>
    </x-slot>

    <div class="flex flex-col flex-1 pb-40">
        <form action="{{ route('photos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <!-- EmptyState / Upload Area -->
            <div class="flex flex-col p-4 pt-6">
                <label for="photos-input" class="group flex flex-col items-center gap-8 rounded-[32px] border-2 border-dashed border-primary/20 bg-primary/5 px-6 py-12 dark:border-primary/20 dark:bg-primary/5 cursor-pointer hover:bg-primary/10 transition-all">
                    <div class="flex flex-col items-center gap-4">
                        <div class="size-20 rounded-3xl bg-primary shadow-xl shadow-primary/30 flex items-center justify-center text-white group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-5xl">add_a_photo</span>
                        </div>
                        <div class="flex flex-col items-center gap-1">
                            <p class="text-slate-900 dark:text-white text-xl font-bold leading-tight text-center">Capture Photos</p>
                            <p class="text-slate-500 dark:text-gray-400 text-sm font-medium text-center">Tap to open camera or browse</p>
                        </div>
                    </div>
                    <div class="flex min-w-[160px] items-center justify-center rounded-2xl h-14 px-8 bg-white dark:bg-slate-800 text-primary border border-primary/20 text-sm font-bold tracking-widest uppercase shadow-sm">
                        Select Files
                    </div>
                    <input id="photos-input" name="photos[]" type="file" multiple class="hidden" accept="image/*" />
                </label>
            </div>

            <!-- MetaText -->
            <div class="px-4 pb-6">
                <div class="flex items-center justify-center gap-6 text-slate-400">
                    <div class="flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-lg">filter_none</span>
                        <p class="text-[10px] font-bold uppercase tracking-wider">Max 10 photos</p>
                    </div>
                    <div class="h-1 w-1 rounded-full bg-slate-300"></div>
                    <div class="flex items-center gap-1.5 text-green-600">
                        <span class="material-symbols-outlined text-lg">verified_user</span>
                        <p class="text-[10px] font-bold uppercase tracking-wider">SECURE UPLOAD</p>
                    </div>
                </div>
            </div>

            <!-- Form Section -->
            <div class="flex flex-col gap-4 px-4">
                <!-- Date -->
                <div class="flex flex-col gap-2">
                    <p class="text-slate-900 dark:text-white text-[10px] font-black uppercase tracking-[0.2em] px-2 pl-4">Record Date</p>
                    <input name="photo_date" type="date" value="{{ date('Y-m-d') }}" class="w-full rounded-2xl border-none bg-white dark:bg-slate-900 shadow-xl shadow-slate-200/50 dark:shadow-none h-16 px-6 font-bold text-slate-900 dark:text-white focus:ring-2 focus:ring-primary/50 transition-all"/>
                </div>

                <!-- Location -->
                <div class="flex flex-col gap-2">
                    <p class="text-slate-900 dark:text-white text-[10px] font-black uppercase tracking-[0.2em] px-2 pl-4">Factory Zone</p>
                    <select name="location" required class="w-full rounded-2xl border-none bg-white dark:bg-slate-900 shadow-xl shadow-slate-200/50 dark:shadow-none h-16 px-6 font-bold text-slate-900 dark:text-white focus:ring-2 focus:ring-primary/50 transition-all appearance-none">
                        <option disabled selected value="">Select location</option>
                        <option value="Production Line 1">Production Line 1</option>
                        <option value="Warehouse A">Warehouse A</option>
                        <option value="Shipping">Shipping</option>
                        <option value="Maintenance">Maintenance</option>
                    </select>
                </div>

                <!-- Department -->
                <div class="flex flex-col gap-2">
                    <p class="text-slate-900 dark:text-white text-[10px] font-black uppercase tracking-[0.2em] px-2 pl-4">Department</p>
                    <select name="department" required class="w-full rounded-2xl border-none bg-white dark:bg-slate-900 shadow-xl shadow-slate-200/50 dark:shadow-none h-16 px-6 font-bold text-slate-900 dark:text-white focus:ring-2 focus:ring-primary/50 transition-all appearance-none">
                        <option disabled selected value="">Select department</option>
                        <option value="Production">Production</option>
                        <option value="Safety">Safety</option>
                        <option value="Maintenance">Maintenance</option>
                        <option value="Quality Control">Quality Control</option>
                        <option value="Inspections">Inspections</option>
                        <option value="Damages">Damages</option>
                    </select>
                </div>

                <!-- Notes -->
                <div class="flex flex-col gap-2">
                    <p class="text-slate-900 dark:text-white text-[10px] font-black uppercase tracking-[0.2em] px-2 pl-4">Analysis Notes</p>
                    <textarea name="notes" class="w-full min-h-[140px] rounded-2xl border-none bg-white dark:bg-slate-900 shadow-xl shadow-slate-200/50 dark:shadow-none p-6 font-medium text-slate-600 dark:text-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-primary/50 transition-all" placeholder="Describe findings, asset condition, or safety concerns..."></textarea>
                </div>
            </div>

            <!-- Sticky Submit Button -->
            <div class="fixed bottom-0 left-0 right-0 max-w-7xl mx-auto bg-white/90 dark:bg-background-dark/95 backdrop-blur-md border-t border-slate-100 dark:border-slate-800 p-4 z-50 md:rounded-b-3xl">
                <button type="submit" class="flex w-full items-center justify-center rounded-2xl h-16 bg-primary text-white text-sm font-bold tracking-[0.2em] shadow-2xl shadow-primary/40 transition-all hover:scale-[1.01] active:scale-95 uppercase">
                    <span class="material-symbols-outlined mr-3 text-xl">file_upload</span>
                    Finalize Documentation
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
