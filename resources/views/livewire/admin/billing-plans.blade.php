<div class="space-y-8 font-sans">
    
    @if (session()->has('success'))
        <div class="bg-slate-900 text-white p-4 rounded-xl flex items-center justify-between shadow-lg">
            <span class="text-sm font-bold">{{ session('success') }}</span>
            <button wire:click="$set('success', null)" class="text-slate-400 hover:text-white">✕</button>
        </div>
    @endif

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tighter uppercase">Billing Plans</h1>
            <p class="text-slate-500 text-sm mt-1 font-medium">Kelola harga paket langganan untuk integrasi Midtrans.</p>
        </div>
        
        <button wire:click="openModal" class="flex items-center gap-2 px-5 py-3 bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-black text-sm rounded-xl hover:opacity-80 transition-all shadow-xl">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
            TAMBAH PAKET BARU
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($packages as $pkg)
        <div class="bg-white dark:bg-slate-900 border-2 border-slate-100 dark:border-slate-800 rounded-3xl p-8 flex flex-col justify-between shadow-sm hover:border-slate-900 dark:hover:border-white transition-all group" wire:key="pkg-{{ $pkg->id }}">
            
            <div>
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-xl font-black text-slate-900 dark:text-white uppercase">{{ $pkg->name }}</h3>
                    <span class="px-3 py-1 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-full text-[10px] font-bold uppercase tracking-widest">{{ $pkg->duration_days }} Hari</span>
                </div>
                
                <div class="mb-6">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1">Harga Final (IDR)</p>
                    <h2 class="text-4xl font-black text-slate-900 dark:text-white tracking-tighter">Rp {{ number_format($pkg->price, 0, ',', '.') }}</h2>
                </div>

                <div class="space-y-2 mb-8">
                    @php $featureList = explode("\n", str_replace("\r", "", $pkg->features)); @endphp
                    @foreach($featureList as $feature)
                        @if(trim($feature) !== '')
                        <div class="flex items-start gap-2 text-sm text-slate-600 dark:text-slate-400 font-medium">
                            <svg class="w-5 h-5 text-slate-900 dark:text-white shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>{{ $feature }}</span>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <div class="flex gap-2 border-t border-slate-100 dark:border-slate-800 pt-6 mt-auto">
                <button wire:click="edit({{ $pkg->id }})" class="flex-1 py-2 bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white font-bold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-all text-xs uppercase tracking-widest">
                    Edit Data
                </button>
                <button wire:click="delete({{ $pkg->id }})" wire:confirm="Yakin ingin menghapus paket {{ $pkg->name }}? Ini tidak bisa dibatalkan." class="px-4 py-2 bg-red-50 text-red-600 font-bold rounded-xl hover:bg-red-100 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-slate-50 dark:bg-slate-800/50 rounded-3xl p-12 text-center border-2 border-dashed border-slate-200 dark:border-slate-700">
            <p class="text-slate-400 font-bold uppercase tracking-widest text-sm">Belum ada paket berlangganan.</p>
            <p class="text-slate-500 text-xs mt-2">Klik "Tambah Paket Baru" untuk memulai.</p>
        </div>
        @endforelse
    </div>

    @if($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center">
        <div wire:click="closeModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity"></div>
        
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-8 w-full max-w-lg z-10 shadow-2xl mx-4 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-black text-slate-900 dark:text-white uppercase tracking-tighter">
                    {{ $isEditMode ? 'Edit Paket' : 'Buat Paket Baru' }}
                </h2>
                <button wire:click="closeModal" class="text-slate-400 hover:text-slate-900 dark:hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form wire:submit.prevent="{{ $isEditMode ? 'update' : 'store' }}" class="space-y-5">
                
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Nama Paket</label>
                    <input type="text" wire:model="name" placeholder="Misal: Paket Pro 1 Tahun" class="w-full bg-slate-50 dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm font-bold text-slate-900 dark:text-white focus:border-slate-900 dark:focus:border-white outline-none transition-all">
                    @error('name') <span class="text-red-500 text-xs font-bold mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Durasi (Hari)</label>
                        <input type="number" wire:model="duration_days" placeholder="Misal: 30" class="w-full bg-slate-50 dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm font-bold text-slate-900 dark:text-white focus:border-slate-900 dark:focus:border-white outline-none transition-all">
                        @error('duration_days') <span class="text-red-500 text-xs font-bold mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Harga (Rp)</label>
                        <input type="number" wire:model="price" placeholder="Misal: 150000" class="w-full bg-slate-50 dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm font-bold text-slate-900 dark:text-white focus:border-slate-900 dark:focus:border-white outline-none transition-all">
                        @error('price') <span class="text-red-500 text-xs font-bold mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Daftar Fitur (Pisahkan dengan Enter)</label>
                    <textarea wire:model="features" rows="4" placeholder="Kasir Tanpa Batas&#10;Laporan Keuangan Lanjut&#10;Dukungan Prioritas" class="w-full bg-slate-50 dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm font-medium text-slate-900 dark:text-white focus:border-slate-900 dark:focus:border-white outline-none transition-all resize-none"></textarea>
                    @error('features') <span class="text-red-500 text-xs font-bold mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="pt-4 mt-6 border-t border-slate-100 dark:border-slate-800">
                    <button type="submit" class="w-full py-4 bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-black rounded-xl hover:opacity-80 transition-all uppercase tracking-widest text-xs shadow-xl">
                        {{ $isEditMode ? 'Simpan Perubahan' : 'Buat Paket Sekarang' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>