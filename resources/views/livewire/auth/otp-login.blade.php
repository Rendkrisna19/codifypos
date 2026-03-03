<div class="w-full max-w-md mx-auto p-8 bg-white border border-gray-200 rounded-2xl shadow-sm">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-codify-black tracking-tight">CodifyPOS</h1>
        <p class="text-gray-500 text-sm mt-2">Masuk atau daftar untuk melanjutkan</p>
    </div>

    @if($step == 1)
        <form wire:submit="sendOtp" class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-codify-black mb-1">Email Aktif</label>
                <input type="email" wire:model="email" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-codify-black focus:border-codify-black transition outline-none" placeholder="pemilik@cafe.com" required>
                @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            @if($isNewUser || strlen($email) > 5)
                <div x-data="{ show: @entangle('isNewUser') }" x-show="show" x-transition>
                    <label class="block text-sm font-medium text-codify-black mb-1">Nama Lengkap (Untuk Akun Baru)</label>
                    <input type="text" wire:model="name" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-codify-black focus:border-codify-black transition outline-none" placeholder="Nama Anda">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            @endif

            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-codify-black hover:bg-gray-800 transition">
                <span wire:loading.remove wire:target="sendOtp">Kirim Kode OTP</span>
                <span wire:loading wire:target="sendOtp">Memproses...</span>
            </button>
        </form>
    @else
        <form wire:submit="verifyOtp" class="space-y-5">
            <div class="text-center bg-gray-50 p-4 rounded-lg border border-gray-100 mb-4">
                <p class="text-sm text-gray-600">Kode 6 digit telah dikirim ke:</p>
                <p class="font-semibold text-codify-black">{{ $email }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-codify-black mb-1">Masukkan Kode OTP</label>
                <input type="text" wire:model="otp" maxlength="6" class="w-full text-center tracking-[0.5em] text-2xl px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-codify-black focus:border-codify-black transition outline-none" placeholder="••••••" required>
                @error('otp') <span class="text-red-500 text-xs mt-1 block text-left">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-codify-black hover:bg-gray-800 transition">
                Verifikasi & Masuk
            </button>

            <div class="text-center mt-4">
                <button type="button" wire:click="$set('step', 1)" class="text-sm text-gray-500 hover:text-codify-black underline">
                    Ganti Email
                </button>
            </div>
        </form>
    @endif
</div>