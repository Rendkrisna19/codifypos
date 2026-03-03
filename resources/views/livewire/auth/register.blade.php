<div class="w-full max-w-md mx-auto p-8 bg-[#fcfcfc] border border-gray-200 rounded-2xl shadow-sm">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-[#111111] tracking-tight">Daftar CodifyPOS</h1>
        <p class="text-gray-500 text-sm mt-2">Mulai trial 14 hari Anda sekarang.</p>
    </div>

    <form wire:submit="register" class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-[#111111] mb-1">Nama Lengkap</label>
            <input type="text" wire:model="name" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#111111] outline-none transition">
            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-[#111111] mb-1">Nama Cafe / Bisnis</label>
            <input type="text" wire:model="cafe_name" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#111111] outline-none transition">
            @error('cafe_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-[#111111] mb-1">Email</label>
            <input type="email" wire:model="email" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#111111] outline-none transition">
            @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-[#111111] mb-1">Password</label>
            <input type="password" wire:model="password" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#111111] outline-none transition">
            @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-[#111111] mb-1">Konfirmasi Password</label>
            <input type="password" wire:model="password_confirmation" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#111111] outline-none transition">
        </div>

        <button type="submit" class="w-full py-3 px-4 rounded-lg shadow-sm text-sm font-medium text-white bg-[#111111] hover:bg-black transition mt-4">
            Daftar & Lanjutkan
        </button>

        <p class="text-center text-sm text-gray-600 mt-4">
            Sudah punya akun? <a href="{{ route('login') }}" wire:navigate class="font-medium text-[#111111] hover:underline">Masuk di sini</a>
        </p>
    </form>
</div>