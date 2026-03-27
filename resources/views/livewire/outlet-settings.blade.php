<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-[#111111] dark:text-white transition-colors duration-300">Pengaturan Outlet & Profil</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Kelola identitas outlet, profil pengguna, dan keamanan akun Anda.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- KARTU PROFIL & OUTLET -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden transition-colors duration-300">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                <h2 class="text-lg font-bold text-[#111111] dark:text-white">Informasi Dasar</h2>
            </div>
            
            <form wire:submit.prevent="updateProfile" class="p-6 space-y-5">
                @if (session()->has('success_profile'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="p-3 bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400 rounded-lg text-sm font-bold border border-green-200 dark:border-green-500/20">
                        {{ session('success_profile') }}
                    </div>
                @endif

                <!-- Upload Foto -->
                <div class="flex items-center gap-5">
                    <div class="relative w-20 h-20 rounded-full bg-gray-100 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 flex items-center justify-center overflow-hidden shrink-0 shadow-sm">
                        @if ($photo)
                            <img src="{{ $photo->temporaryUrl() }}" class="w-full h-full object-cover">
                        @elseif (auth()->user()->profile_photo)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-2xl font-bold text-gray-400">{{ substr(auth()->user()->name, 0, 1) }}</span>
                        @endif
                        
                        <div wire:loading wire:target="photo" class="absolute inset-0 bg-black/50 flex items-center justify-center">
                            <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </div>
                    </div>
                    <div>
                        <input type="file" wire:model="photo" id="photo-upload" class="hidden" accept="image/*">
                        <label for="photo-upload" class="cursor-pointer px-4 py-2 bg-gray-100 dark:bg-gray-700 text-[#111111] dark:text-white rounded-lg text-xs font-bold hover:bg-gray-200 dark:hover:bg-gray-600 transition border border-gray-200 dark:border-gray-600">
                            Pilih Foto Baru
                        </label>
                        <p class="text-[10px] text-gray-500 mt-2">Format: JPG, PNG. Maksimal 2MB.</p>
                        @error('photo') <span class="text-red-500 text-[10px] block mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                @if(auth()->user()->role === 'owner')
                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1">Nama Outlet / Cafe</label>
                    <input type="text" wire:model="tenantName" class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-sm text-[#111111] dark:text-white outline-none focus:ring-2 focus:ring-[#111111]">
                    @error('tenantName') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                </div>
                @endif

                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1">Nama Lengkap Pengguna</label>
                    <input type="text" wire:model="name" class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-sm text-[#111111] dark:text-white outline-none focus:ring-2 focus:ring-[#111111]">
                    @error('name') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1">Email Log in</label>
                    <input type="email" wire:model="email" class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-sm text-[#111111] dark:text-white outline-none focus:ring-2 focus:ring-[#111111]">
                    @error('email') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3 bg-[#111111] dark:bg-white text-white dark:text-[#111111] rounded-xl font-bold hover:opacity-80 transition-colors shadow-md">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        <!-- KARTU GANTI PASSWORD -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden transition-colors duration-300 h-fit">
            <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                <h2 class="text-lg font-bold text-[#111111] dark:text-white">Keamanan Akun</h2>
            </div>
            
            <form wire:submit.prevent="updatePassword" class="p-6 space-y-5">
                @if (session()->has('success_password'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="p-3 bg-green-50 text-green-700 dark:bg-green-500/10 dark:text-green-400 rounded-lg text-sm font-bold border border-green-200 dark:border-green-500/20">
                        {{ session('success_password') }}
                    </div>
                @endif

                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1">Password Saat Ini</label>
                    <input type="password" wire:model="currentPassword" class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-sm text-[#111111] dark:text-white outline-none focus:ring-2 focus:ring-[#111111]" placeholder="••••••••">
                    @error('currentPassword') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1">Password Baru</label>
                    <input type="password" wire:model="newPassword" class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-sm text-[#111111] dark:text-white outline-none focus:ring-2 focus:ring-[#111111]" placeholder="••••••••">
                    @error('newPassword') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 mb-1">Konfirmasi Password Baru</label>
                    <input type="password" wire:model="newPassword_confirmation" class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-sm text-[#111111] dark:text-white outline-none focus:ring-2 focus:ring-[#111111]" placeholder="••••••••">
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3 bg-gray-100 dark:bg-gray-700 text-[#111111] dark:text-white border border-gray-200 dark:border-gray-600 rounded-xl font-bold hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors shadow-sm">
                        Perbarui Password
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>