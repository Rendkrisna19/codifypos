<div class="min-h-screen flex items-center justify-center bg-gray-50 px-4" x-data="otpTimer()">
    <div class="w-full max-w-md p-10 bg-white border border-gray-100 rounded-3xl shadow-xl shadow-gray-200/50 text-center">
        
        <div class="mb-8">
            <div class="w-16 h-16 bg-black text-white rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <h2 class="text-3xl font-black text-[#111111] tracking-tight">Verifikasi Email</h2>
            <p class="text-gray-500 text-sm mt-3 leading-relaxed">
                Kami telah mengirimkan 6 digit kode keamanan ke email Anda. Silakan masukkan di bawah ini.
            </p>
        </div>

        <form wire:submit.prevent="verify" class="space-y-8">
            <div>
                <input type="text" 
                       wire:model="otp" 
                       maxlength="6" 
                       autofocus
                       class="w-full text-center tracking-[0.5em] text-4xl font-black px-4 py-5 bg-gray-50 border-2 border-transparent rounded-2xl focus:border-black focus:bg-white focus:ring-0 outline-none transition-all duration-300" 
                       placeholder="000000">
                
                @error('otp') 
                    <div class="flex items-center justify-center mt-3 text-red-500 text-xs font-bold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        {{ $message }}
                    </div> 
                @enderror
            </div>

            <div class="bg-gray-50 rounded-xl py-3 px-4 flex items-center justify-center space-x-2">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Kirim Ulang Dalam</span>
                <span class="text-sm font-black text-black" x-text="timeLeft + 's'"></span>
            </div>

            <button type="submit" class="group relative w-full py-4 px-6 rounded-2xl text-sm font-bold text-white bg-[#111111] hover:bg-black transition-all duration-300 shadow-lg shadow-gray-300 overflow-hidden">
                <span class="relative z-10 flex items-center justify-center">
                    Verifikasi OTP
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </span>
            </button>
        </form>

        <div class="mt-8 text-xs text-gray-400 font-medium">
            Tidak menerima email? Periksa folder <b>Spam</b> atau tunggu timer berakhir.
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('otpTimer', () => ({
            timeLeft: 60,
            init() {
                setInterval(() => {
                    if (this.timeLeft > 0) {
                        this.timeLeft--;
                    } else {
                        this.$wire.autoResend();
                        this.timeLeft = 60;
                    }
                }, 1000);
            }
        }));
    });
</script>