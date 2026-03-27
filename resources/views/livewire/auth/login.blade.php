<div class="min-h-screen w-full flex bg-white font-space">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap');
        .font-space { font-family: 'Space Grotesk', sans-serif !important; }
        [x-cloak] { display: none !important; }
    </style>

    <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12 lg:p-24">
        <div class="w-full max-w-md transition-all duration-300">
            
            <div class="mb-10">
                <img src="{{ asset('images/login.jpg') }}" alt="CodifyPOS" class="w-32 mb-6 lg:hidden object-contain">
                
                <h1 class="text-3xl sm:text-4xl font-bold text-[#111111] tracking-tight mb-2">Masuk CodifyPOS</h1>
                <p class="text-gray-500 text-sm sm:text-base">Selamat datang kembali, silakan masuk ke sistem kasir Anda.</p>
            </div>

            <form wire:submit="login" class="space-y-6">
                
                <div>
                    <label class="block text-sm font-semibold text-[#111111] mb-2">Email</label>
                    <input type="email" wire:model="email" 
                        class="w-full px-4 py-3.5 bg-[#fcfcfc] border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-[#111111] focus:border-transparent outline-none transition-all placeholder:text-gray-400" 
                        placeholder="nama@email.com" required>
                    @error('email') <span class="text-red-500 text-xs mt-1.5 block font-medium">{{ $message }}</span> @enderror
                </div>

                <div x-data="{ showPassword: false }">
                    <label class="block text-sm font-semibold text-[#111111] mb-2">Password</label>
                    <div class="relative">
                        <input :type="showPassword ? 'text' : 'password'" wire:model="password" 
                            class="w-full pl-4 pr-12 py-3.5 bg-[#fcfcfc] border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-[#111111] focus:border-transparent outline-none transition-all placeholder:text-gray-400" 
                            placeholder="••••••••" required>
                        
                        <button type="button" @click="showPassword = !showPassword" 
                            class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-[#111111] transition-colors focus:outline-none">
                            
                            <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>

                            <svg x-show="showPassword" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 015.458-5.458M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center cursor-pointer group">
                        <input type="checkbox" wire:model="remember" 
                            class="w-4 h-4 text-[#111111] bg-white border-gray-300 rounded focus:ring-[#111111] transition-all cursor-pointer">
                        <span class="ml-2.5 text-sm text-gray-600 group-hover:text-[#111111] transition-colors font-medium">Ingat Saya</span>
                    </label>
                    <a href="#" class="text-sm font-semibold text-[#111111] hover:underline underline-offset-4">Lupa Password?</a>
                </div>

                <button type="submit" 
                    class="w-full py-4 px-4 rounded-xl shadow-lg text-sm font-bold text-white bg-[#111111] hover:bg-black active:scale-[0.98] transition-all duration-200">
                    Masuk ke Sistem
                </button>

                <p class="text-center text-sm text-gray-600 mt-6 font-medium">
                    Belum punya akun? <a href="{{ route('register') }}" wire:navigate class="font-bold text-[#111111] hover:underline underline-offset-4 decoration-2">Daftar Sekarang</a>
                </p>
            </form>
        </div>
    </div>

    <div class="hidden lg:flex lg:w-1/2 bg-[#f4f4f5] items-center justify-center p-12 relative overflow-hidden border-l border-gray-200">
        
        <div class="absolute w-96 h-96 bg-gray-300 rounded-full mix-blend-multiply filter blur-3xl opacity-40 top-10 left-10"></div>
        <div class="absolute w-96 h-96 bg-gray-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40 bottom-10 right-10"></div>
        
        <img src="{{ asset('images/login.png') }}" 
             alt="Ilustrasi POS Desktop" 
             class="relative z-10 w-full max-w-lg object-contain drop-shadow-2xl hover:scale-105 transition-transform duration-500">
             
        <div class="absolute bottom-12 text-center z-10">
            <h3 class="text-xl font-bold text-[#111111]">Sistem Kasir Pintar</h3>
            <p class="text-gray-500 mt-2 font-medium">Kelola tenant, produk, dan laporan dalam satu tempat.</p>
        </div>
    </div>
</div>