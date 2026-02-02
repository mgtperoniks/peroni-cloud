<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="@container mb-4">
        <div class="@[480px]:px-4">
            <div class="w-24 h-24 mx-auto bg-primary/10 rounded-2xl flex items-center justify-center mb-6">
                <span class="material-symbols-outlined text-primary text-5xl">precision_manufacturing</span>
            </div>
        </div>
    </div>
    
    <h2 class="text-[#111318] dark:text-white tracking-light text-[28px] font-bold leading-tight px-4 text-center pb-2">Employee Login</h2>
    <p class="text-[#616f89] dark:text-slate-400 text-base font-normal leading-normal pb-8 px-4 text-center">Please sign in to document assets, damages, and production logs.</p>

    <form method="POST" action="{{ route('login') }}" class="space-y-4 max-w-md mx-auto w-full">
        @csrf

        <div class="flex flex-col px-4">
            <label class="flex flex-col w-full">
                <p class="text-[#111318] dark:text-slate-200 text-sm font-medium leading-normal pb-2">Employee ID or Username</p>
                <div class="relative">
                    <input name="email" value="{{ old('email') }}" class="form-input flex w-full rounded-lg text-[#111318] dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-[#dbdfe6] dark:border-slate-700 bg-white dark:bg-slate-800 h-14 placeholder:text-[#616f89] p-[15px] pl-12 text-lg font-normal leading-normal" placeholder="Enter your ID" type="email" required autofocus/>
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-[#616f89]">person</span>
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </label>
        </div>

        <div class="flex flex-col px-4">
            <label class="flex flex-col w-full">
                <p class="text-[#111318] dark:text-slate-200 text-sm font-medium leading-normal pb-2">Password</p>
                <div class="relative">
                    <input name="password" class="form-input flex w-full rounded-lg text-[#111318] dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-[#dbdfe6] dark:border-slate-700 bg-white dark:bg-slate-800 h-14 placeholder:text-[#616f89] p-[15px] pl-12 text-lg font-normal leading-normal" placeholder="••••••••" type="password" required autocomplete="current-password"/>
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-[#616f89]">lock</span>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </label>
        </div>

        <div class="px-4 pt-4">
            <button class="w-full bg-primary hover:bg-primary/90 text-white text-lg font-bold h-14 rounded-lg shadow-lg shadow-primary/20 transition-colors flex items-center justify-center gap-2 uppercase tracking-widest">
                <span>LOGIN</span>
                <span class="material-symbols-outlined">login</span>
            </button>
        </div>

        <div class="flex flex-col items-center gap-4 pt-6">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-primary text-sm font-semibold hover:underline">Forgot Password?</a>
            @endif
            <div class="flex items-center gap-2 text-[#616f89] dark:text-slate-500 text-xs">
                <span class="material-symbols-outlined text-sm">support_agent</span>
                <span>Contact IT Support</span>
            </div>
        </div>
    </form>
</x-guest-layout>
