<div x-data="otpTimer()" class="w-full max-w-md mx-auto p-8 bg-[#fcfcfc] border border-gray-200 rounded-2xl shadow-sm text-center">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-[#111111]">Verifikasi Email</h2>
        <p class="text-gray-500 text-sm mt-2">Masukkan 6 digit kode OTP yang telah kami kirimkan ke email Anda.</p>
    </div>

    <form wire:submit.prevent="verify" class="space-y-6">
        <div>
            <input type="text" wire:model="otp" maxlength="6" class="w-full text-center tracking-[0.75em] text-3xl font-bold px-4 py-4 bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#111111] outline-none transition" placeholder="------">
            @error('otp') <span class="text-red-500 text-xs block mt-2 text-left">{{ $message }}</span> @enderror
        </div>

        <div class="text-sm font-medium text-gray-500">
            Kode akan dikirim ulang otomatis dalam <span class="text-red-500" x-text="timeLeft"></span> detik
        </div>

        <button type="submit" class="w-full py-3 px-4 rounded-lg text-sm font-medium text-white bg-[#111111] hover:bg-black transition">
            Verifikasi OTP
        </button>
    </form>
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
                        // Jika waktu habis, panggil fungsi Livewire di backend
                        this.$wire.autoResend();
                        this.timeLeft = 60; // Reset timer ke 60
                    }
                }, 1000);
            }
        }));
    });
</script>