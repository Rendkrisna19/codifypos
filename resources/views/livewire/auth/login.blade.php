<div class="w-full max-w-md mx-auto p-8 bg-[#fcfcfc] border border-gray-200 rounded-2xl shadow-sm">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-[#111111] tracking-tight">Masuk CodifyPOS</h1>
        <p class="text-gray-500 text-sm mt-2">Selamat datang kembali, silakan masuk.</p>
    </div>

    <form wire:submit="login" class="space-y-5">
        <div>
            <label class="block text-sm font-medium text-[#111111] mb-1">Email</label>
            <input type="email" wire:model="email" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#111111] outline-none transition" required>
            @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-[#111111] mb-1">Password</label>
            <input type="password" wire:model="password" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#111111] outline-none transition" required>
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center">
                <input type="checkbox" wire:model="remember" class="w-4 h-4 text-[#111111] border-gray-300 rounded focus:ring-[#111111]">
                <span class="ml-2 text-sm text-gray-600">Ingat Saya</span>
            </label>
        </div>

        <button type="submit" class="w-full py-3 px-4 rounded-lg shadow-sm text-sm font-medium text-white bg-[#111111] hover:bg-black transition">
            Masuk
        </button>

        <p class="text-center text-sm text-gray-600 mt-4">
            Belum punya akun? <a href="{{ route('register') }}" wire:navigate class="font-medium text-[#111111] hover:underline">Daftar Sekarang</a>
        </p>
    </form>
</div>