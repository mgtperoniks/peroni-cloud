<x-app-layout>
    <x-slot name="header">
        User Profile
    </x-slot>

    <x-slot name="headerActions">
        <a href="{{ route('photos.index') }}" class="text-primary flex size-10 items-center justify-center">
            <span class="material-symbols-outlined">settings</span>
        </a>
    </x-slot>

    <div class="flex-1 overflow-y-auto pb-32">
        <!-- Profile Header -->
        <div class="flex p-6 @container bg-white dark:bg-background-dark border-b border-gray-100 dark:border-gray-800">
            <div class="flex w-full flex-col gap-4 items-center">
                <div class="flex gap-4 flex-col items-center">
                    <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full border-4 border-primary/10 min-h-32 w-32 shadow-xl flex items-center justify-center bg-gray-100 dark:bg-slate-800">
                        <span class="material-symbols-outlined text-primary text-6xl">account_circle</span>
                    </div>
                    <div class="flex flex-col items-center justify-center">
                        <p class="text-[#111318] dark:text-white text-2xl font-bold leading-tight tracking-[-0.015em] text-center">{{ auth()->user()->name }}</p>
                        <p class="text-primary font-semibold text-base mt-1">Factory Personnel</p>
                        <p class="text-[#616f89] dark:text-gray-400 text-sm font-medium mt-1">Employee ID: #P-{{ str_pad(auth()->user()->id, 4, '0', STR_PAD_LEFT) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Organizational Details Section -->
        <div class="mt-4">
            <h3 class="text-[#111318] dark:text-white text-[10px] font-black uppercase tracking-widest px-6 pb-2 pt-4">Organizational Details</h3>
            <div class="bg-white dark:bg-background-dark divide-y divide-gray-50 dark:divide-gray-800">
                <!-- Department Item -->
                <div class="flex items-center gap-4 px-6 min-h-[72px] py-2">
                    <div class="text-primary flex items-center justify-center rounded-xl bg-primary/10 shrink-0 size-12">
                        <span class="material-symbols-outlined">factory</span>
                    </div>
                    <div class="flex flex-col justify-center">
                        <p class="text-[#111318] dark:text-white text-base font-semibold leading-normal">Department</p>
                        <p class="text-[#616f89] dark:text-gray-400 text-sm font-normal leading-normal">Operations & Production</p>
                    </div>
                </div>
                <!-- Role Item -->
                <div class="flex items-center gap-4 px-6 min-h-[72px] py-2">
                    <div class="text-primary flex items-center justify-center rounded-xl bg-primary/10 shrink-0 size-12">
                        <span class="material-symbols-outlined">assignment_ind</span>
                    </div>
                    <div class="flex flex-col justify-center">
                        <p class="text-[#111318] dark:text-white text-base font-semibold leading-normal">Email Address</p>
                        <p class="text-[#616f89] dark:text-gray-400 text-sm font-normal leading-normal">{{ auth()->user()->email }}</p>
                    </div>
                </div>
                <!-- Location Item -->
                <div class="flex items-center gap-4 px-6 min-h-[72px] py-2">
                    <div class="text-primary flex items-center justify-center rounded-xl bg-primary/10 shrink-0 size-12">
                        <span class="material-symbols-outlined">location_on</span>
                    </div>
                    <div class="flex flex-col justify-center">
                        <p class="text-[#111318] dark:text-white text-base font-semibold leading-normal">Facility</p>
                        <p class="text-[#616f89] dark:text-gray-400 text-sm font-normal leading-normal">Main Processing Plant</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Status -->
        <div class="mt-4">
            <h3 class="text-[#111318] dark:text-white text-[10px] font-black uppercase tracking-widest px-6 pb-2 pt-4">System Status</h3>
            <div class="bg-white dark:bg-background-dark divide-y divide-gray-50 dark:divide-gray-800">
                <div class="flex items-center justify-between px-6 min-h-[60px] py-2">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-green-500">cloud_done</span>
                        <p class="text-[#111318] dark:text-white text-sm font-medium">Data Synchronization</p>
                    </div>
                    <p class="text-[#616f89] dark:text-gray-400 text-[10px] font-bold">ACTIVE</p>
                </div>
                <div class="flex items-center justify-between px-6 min-h-[60px] py-2">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-gray-400">info</span>
                        <p class="text-[#111318] dark:text-white text-sm font-medium">App Version</p>
                    </div>
                    <p class="text-[#616f89] dark:text-gray-400 text-[10px] font-bold tracking-widest">V2.4.1-STABLE</p>
                </div>
            </div>
        </div>

        <!-- Breeze Edit Forms (Optional/Collapsible or just visible at bottom) -->
        <div class="mt-8 px-4 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-slate-900 shadow sm:rounded-3xl border border-slate-100 dark:border-slate-800">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-slate-900 shadow sm:rounded-3xl border border-slate-100 dark:border-slate-800">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-slate-900 shadow sm:rounded-3xl border border-slate-100 dark:border-slate-800">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>

        <!-- Logout Section -->
        <div class="p-6">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full bg-primary hover:bg-primary/90 text-white font-bold py-5 rounded-2xl flex items-center justify-center gap-3 transition-all active:scale-[0.98] shadow-xl shadow-primary/20 uppercase tracking-[0.2em] text-sm">
                    <span class="material-symbols-outlined">logout</span>
                    Logout System
                </button>
            </form>
            <p class="text-center text-[#616f89] dark:text-gray-500 text-[10px] font-bold uppercase tracking-widest mt-6">
                Logged in since {{ auth()->user()->updated_at->format('H:i') }}
            </p>
        </div>
    </div>
</x-app-layout>
