<div class="w-full max-w-2xl mx-auto p-8 sm:p-10 bg-white border border-slate-200 rounded-[2rem] shadow-2xl relative overflow-hidden font-sans">
    
    <div class="absolute -top-20 -right-20 w-40 h-40 bg-slate-900 rounded-full blur-3xl opacity-5 pointer-events-none"></div>

    <div class="text-center mb-10 relative z-10">
        <h1 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tighter uppercase mb-2">Buat Akun Bisnis</h1>
        <p class="text-slate-500 text-sm font-medium">Mulai trial 14 hari Anda sekarang. Tanpa kartu kredit.</p>
    </div>

    @if ($errors->any())
        <div class="mb-8 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-xl">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-bold text-red-800">Mohon perbaiki kesalahan berikut:</h3>
                </div>
            </div>
        </div>
    @endif

    <form wire:submit.prevent="register" class="space-y-5 relative z-10">
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Nama Lengkap Owner</label>
                <input type="text" wire:model="name" placeholder="John Doe" class="w-full px-4 py-3.5 bg-slate-50 border-2 border-slate-200 rounded-xl focus:bg-white focus:border-slate-900 outline-none transition-all text-sm font-bold text-slate-900 {{ $errors->has('name') ? 'border-red-400 bg-red-50' : '' }}">
                @error('name') <span class="text-red-500 text-[10px] font-bold uppercase mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Nama Bisnis / Cafe</label>
                <input type="text" wire:model="cafe_name" placeholder="Cafe Senja" class="w-full px-4 py-3.5 bg-slate-50 border-2 border-slate-200 rounded-xl focus:bg-white focus:border-slate-900 outline-none transition-all text-sm font-bold text-slate-900 {{ $errors->has('cafe_name') ? 'border-red-400 bg-red-50' : '' }}">
                @error('cafe_name') <span class="text-red-500 text-[10px] font-bold uppercase mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Alamat Email</label>
                <input type="email" wire:model="email" placeholder="owner@cafesenja.com" class="w-full px-4 py-3.5 bg-slate-50 border-2 border-slate-200 rounded-xl focus:bg-white focus:border-slate-900 outline-none transition-all text-sm font-bold text-slate-900 {{ $errors->has('email') ? 'border-red-400 bg-red-50' : '' }}">
                @error('email') <span class="text-red-500 text-[10px] font-bold uppercase mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Nomor WhatsApp Aktif</label>
                <input type="tel" wire:model="phone" placeholder="081234567890" class="w-full px-4 py-3.5 bg-slate-50 border-2 border-slate-200 rounded-xl focus:bg-white focus:border-slate-900 outline-none transition-all text-sm font-bold text-slate-900 {{ $errors->has('phone') ? 'border-red-400 bg-red-50' : '' }}">
                @error('phone') <span class="text-red-500 text-[10px] font-bold uppercase mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Password Sistem</label>
                <input type="password" wire:model="password" placeholder="Minimal 8 Karakter" class="w-full px-4 py-3.5 bg-slate-50 border-2 border-slate-200 rounded-xl focus:bg-white focus:border-slate-900 outline-none transition-all text-sm font-bold text-slate-900 {{ $errors->has('password') ? 'border-red-400 bg-red-50' : '' }}">
                @error('password') <span class="text-red-500 text-[10px] font-bold uppercase mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Konfirmasi Password</label>
                <input type="password" wire:model="password_confirmation" placeholder="Ulangi Password" class="w-full px-4 py-3.5 bg-slate-50 border-2 border-slate-200 rounded-xl focus:bg-white focus:border-slate-900 outline-none transition-all text-sm font-bold text-slate-900">
            </div>
        </div>

        <div class="pt-6 border-t border-slate-100">
            <button type="submit" class="w-full py-4 px-4 rounded-xl shadow-xl text-sm font-black uppercase tracking-widest text-white bg-slate-900 hover:bg-slate-800 transition-all hover:-translate-y-0.5 transform flex justify-center items-center">
                <span wire:loading.remove wire:target="register">Daftar & Lanjutkan &rarr;</span>
                <span wire:loading wire:target="register" class="flex items-center">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Memproses...
                </span>
            </button>
        </div>

        <p class="text-center text-sm font-bold text-slate-400 mt-6">
            Sudah punya akun? 
            <a href="{{ route('login') }}" wire:navigate class="text-slate-900 hover:text-blue-600 transition-colors underline decoration-2 underline-offset-4">Login di sini</a>
        </p>
    </form>
</div>