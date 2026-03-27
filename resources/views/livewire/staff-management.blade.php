<div x-data="{ showModal: @entangle('showModal') }">
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#111111] dark:text-white transition-colors">Staff & Pengguna</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Kelola akun staff dan hak akses pegawai cafe Anda.</p>
        </div>
        <button @click="showModal = true; $wire.resetForm()" class="px-5 py-2.5 bg-[#111111] dark:bg-white text-white dark:text-[#111111] text-sm font-bold rounded-xl hover:bg-gray-800 dark:hover:bg-gray-200 transition-colors shadow-sm">
            + Tambah Pegawai
        </button>
    </div>

    @if (session()->has('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" x-transition.duration.500ms class="mb-4 p-4 bg-green-50 dark:bg-green-500/10 text-green-700 dark:text-green-400 rounded-xl border border-green-200 dark:border-green-500/20 text-sm font-bold flex items-center gap-2 shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" x-transition.duration.500ms class="mb-4 p-4 bg-red-50 dark:bg-red-500/10 text-red-700 dark:text-red-400 rounded-xl border border-red-200 dark:border-red-500/20 text-sm font-bold flex items-center gap-2 shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden transition-colors w-full">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                <thead class="bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 text-xs uppercase font-semibold">
                    <tr>
                        <th class="px-6 py-4">Nama Pegawai</th>
                        <th class="px-6 py-4">Email Login</th>
                        <th class="px-6 py-4">Role Akses</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($staffList as $staff)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                        <td class="px-6 py-4 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-[#111111] dark:bg-white text-white dark:text-[#111111] flex items-center justify-center font-bold text-xs uppercase">
                                {{ substr($staff->name, 0, 2) }}
                            </div>
                            <span class="font-bold text-[#111111] dark:text-white">{{ $staff->name }}</span>
                        </td>
                        <td class="px-6 py-4">{{ $staff->email }}</td>
                        <td class="px-6 py-4">
                            @if($staff->role === 'owner')
                                <span class="px-3 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 rounded-full text-xs font-bold uppercase tracking-wide">Owner</span>
                            @else
                                <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded-full text-xs font-bold uppercase tracking-wide">Staff</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right space-x-3">
                            <button wire:click="edit({{ $staff->id }})" class="text-blue-500 hover:text-blue-700 font-medium">Edit</button>
                            @if($staff->role !== 'owner')
                            <button wire:click="delete({{ $staff->id }})" wire:confirm="Yakin cabut akses login pegawai ini?" class="text-red-500 hover:text-red-700 font-medium">Hapus</button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-6 py-8 text-center text-gray-400">Belum ada staff tambahan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div x-show="showModal" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="showModal" @click="showModal = false" class="fixed inset-0 bg-gray-900/80 transition-opacity"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            
            <div x-show="showModal" x-transition class="relative z-10 inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-xl sm:my-8 sm:align-middle sm:max-w-md w-full border border-gray-200 dark:border-gray-700">
                <form wire:submit.prevent="save">
                    <div class="p-6 space-y-4">
                        <h3 class="text-lg font-bold text-[#111111] dark:text-white mb-2">{{ $staffId ? 'Edit Akun Pegawai' : 'Buat Akun Pegawai' }}</h3>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Lengkap</label>
                            <input type="text" wire:model="name" class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-[#111111] dark:text-white focus:outline-none focus:ring-2 focus:ring-[#111111]" required>
                            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email (Untuk Login)</label>
                            <input type="email" wire:model="email" class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-[#111111] dark:text-white focus:outline-none focus:ring-2 focus:ring-[#111111]" required>
                            @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password Baru {{ $staffId ? '(Kosongkan jika tidak diubah)' : '' }}</label>
                            <input type="password" wire:model="password" class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-[#111111] dark:text-white focus:outline-none focus:ring-2 focus:ring-[#111111]" {{ $staffId ? '' : 'required' }}>
                            @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Hak Akses (Role)</label>
                            <select wire:model="role" class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-[#111111] dark:text-white focus:outline-none focus:ring-2 focus:ring-[#111111]" required>
                                <option value="staff">Staff (Hanya akses POS)</option>
                            </select>
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-3">
                        <button type="button" @click="showModal = false" class="px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 font-medium">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-[#111111] dark:bg-white text-white dark:text-[#111111] hover:bg-gray-800 dark:hover:bg-gray-200 rounded-lg font-bold">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>