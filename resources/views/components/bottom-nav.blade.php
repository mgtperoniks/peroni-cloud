<nav
    class="fixed bottom-0 left-0 right-0 z-50 flex gap-4 border-t border-slate-100 dark:border-gray-800 bg-white/80 dark:bg-background-dark/90 backdrop-blur-xl px-6 pb-8 pt-4 max-w-7xl mx-auto md:rounded-b-[32px] shadow-[0_-10px_40px_rgba(0,0,0,0.05)]">
    <a href="{{ route('photos.index') }}"
        class="flex flex-1 flex-col items-center justify-center gap-1.5 transition-all duration-300 {{ request()->routeIs('photos.index') ? 'text-primary scale-110' : 'text-slate-400 hover:text-slate-600' }}">
        <div class="relative">
            <span
                class="material-symbols-outlined text-[28px] {{ request()->routeIs('photos.index') ? 'filled-icon' : '' }}">grid_view</span>
            @if(request()->routeIs('photos.index'))
                <span class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-primary rounded-full"></span>
            @endif
        </div>
        <p class="text-[10px] font-black leading-normal tracking-[0.1em] uppercase">Gallery</p>
    </a>

    <a href="{{ route('photos.timeline') }}"
        class="flex flex-1 flex-col items-center justify-center gap-1.5 transition-all duration-300 {{ request()->routeIs('photos.timeline') ? 'text-primary scale-110' : 'text-slate-400 hover:text-slate-600' }}">
        <div class="relative">
            <span
                class="material-symbols-outlined text-[28px] {{ request()->routeIs('photos.timeline') ? 'filled-icon' : '' }}">calendar_view_day</span>
            @if(request()->routeIs('photos.timeline'))
                <span class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-primary rounded-full"></span>
            @endif
        </div>
        <p class="text-[10px] font-black leading-normal tracking-[0.1em] uppercase">Timeline</p>
    </a>

    <div class="flex-1 flex flex-col items-center justify-center -mt-12">
        <a href="{{ route('photos.create') }}"
            class="flex items-center justify-center rounded-2xl size-14 bg-primary text-white shadow-2xl shadow-primary/40 hover:scale-110 active:scale-90 transition-all border-4 border-white dark:border-background-dark relative group">
            <span class="material-symbols-outlined text-[32px]">add</span>
            <div
                class="absolute -top-12 left-1/2 -translate-x-1/2 bg-slate-900 text-white text-[10px] font-bold py-1 px-3 rounded-full opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">
                NEW ENTRY</div>
        </a>
    </div>

    <button type="button" @click="$store.gallery.toggleFilter()"
        class="flex flex-1 flex-col items-center justify-center gap-1.5 {{ request()->routeIs('photos.index') && request()->hasAny(['location', 'department', 'date']) ? 'text-primary' : 'text-slate-400' }} hover:text-slate-600 transition-all">
        <span
            class="material-symbols-outlined text-[28px] {{ request()->hasAny(['location', 'department', 'date']) ? 'filled-icon' : '' }}">search_insights</span>
        <p class="text-[10px] font-black leading-normal tracking-[0.1em] uppercase">Sort</p>
    </button>

    <a href="{{ route('profile.edit') }}"
        class="flex flex-1 flex-col items-center justify-center gap-1.5 transition-all duration-300 {{ request()->routeIs('profile.edit') ? 'text-primary scale-110' : 'text-slate-400 hover:text-slate-600' }}">
        <div class="relative">
            <span
                class="material-symbols-outlined text-[28px] {{ request()->routeIs('profile.edit') ? 'filled-icon' : '' }}">person_4</span>
            @if(request()->routeIs('profile.edit'))
                <span class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-primary rounded-full"></span>
            @endif
        </div>
        <p class="text-[10px] font-black leading-normal tracking-[0.1em] uppercase">User</p>
    </a>
</nav>