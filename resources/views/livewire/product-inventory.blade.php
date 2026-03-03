<div x-data="{ tab: @entangle('activeTab'), showCategoryModal: false, showProductModal: false }">
    
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#111111] dark:text-white transition-colors">Produk & Inventori</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Kelola master data menu dan kategori cafe Anda.</p>
        </div>
        
        <div class="flex bg-gray-200 dark:bg-gray-800 p-1 rounded-xl transition-colors">
            <button @click="tab = 'products'" :class="tab === 'products' ? 'bg-white dark:bg-[#111111] text-[#111111] dark:text-white shadow-sm' : 'text-gray-500 dark:text-gray-400'" class="px-5 py-2 rounded-lg text-sm font-semibold transition-all">
                Daftar Produk
            </button>
            <button @click="tab = 'categories'" :class="tab === 'categories' ? 'bg-white dark:bg-[#111111] text-[#111111] dark:text-white shadow-sm' : 'text-gray-500 dark:text-gray-400'" class="px-5 py-2 rounded-lg text-sm font-semibold transition-all">
                Kategori
            </button>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="mb-4 p-3 bg-green-50 dark:bg-green-500/10 text-green-700 dark:text-green-400 rounded-lg border border-green-200 dark:border-green-500/20 text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    <div x-show="tab === 'products'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden transition-colors w-full">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h3 class="font-bold text-[#111111] dark:text-white">Data Produk</h3>
                <button @click="showProductModal = true; $wire.resetProductForm()" class="px-4 py-2 bg-[#111111] dark:bg-white text-white dark:text-[#111111] text-sm font-bold rounded-lg hover:bg-gray-800 dark:hover:bg-gray-200 transition-colors">
                    + Tambah Produk
                </button>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                    <thead class="bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 text-xs uppercase font-semibold">
                        <tr>
                            <th class="px-4 py-3">Produk</th>
                            <th class="px-4 py-3">Kategori</th>
                            <th class="px-4 py-3">Harga Modal</th>
                            <th class="px-4 py-3">Harga Jual</th>
                            <th class="px-4 py-3">Stok</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($products as $prod)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                            <td class="px-4 py-3 flex items-center gap-3">
                                @if($prod->image)
                                    <img src="{{ asset('storage/' . $prod->image) }}" class="w-10 h-10 rounded-lg object-cover border border-gray-200 dark:border-gray-600">
                                @else
                                    <div class="w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-400">🖼️</div>
                                @endif
                                <div>
                                    <p class="font-bold text-[#111111] dark:text-white">{{ $prod->name }}</p>
                                    <p class="text-xs text-gray-400">SKU: {{ $prod->sku ?? '-' }}</p>
                                </div>
                            </td>
                            <td class="px-4 py-3">{{ $prod->category->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-red-500 dark:text-red-400">Rp {{ number_format($prod->cost_price, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 font-bold text-green-600 dark:text-green-400">Rp {{ number_format($prod->selling_price, 0, ',', '.') }}</td>
                            <td class="px-4 py-3">{{ $prod->stock }}</td>
                            <td class="px-4 py-3 text-right space-x-2">
                                <button @click="showProductModal = true; $wire.editProduct({{ $prod->id }})" class="text-blue-500 hover:text-blue-700 font-medium">Edit</button>
                                <button wire:click="deleteProduct({{ $prod->id }})" wire:confirm="Yakin ingin menghapus produk ini?" class="text-red-500 hover:text-red-700 font-medium">Hapus</button>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">Belum ada produk.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div x-show="tab === 'categories'" style="display: none;" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden transition-colors w-full">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h3 class="font-bold text-[#111111] dark:text-white">Data Kategori</h3>
                <button @click="showCategoryModal = true; $wire.resetCategoryForm()" class="px-4 py-2 bg-[#111111] dark:bg-white text-white dark:text-[#111111] text-sm font-bold rounded-lg hover:bg-gray-800 dark:hover:bg-gray-200 transition-colors">
                    + Tambah Kategori
                </button>
            </div>
            <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                <thead class="bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 text-xs uppercase font-semibold">
                    <tr>
                        <th class="px-4 py-3">Nama Kategori</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($categories as $cat)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                        <td class="px-4 py-3 font-medium text-[#111111] dark:text-white">{{ $cat->name }}</td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <button @click="showCategoryModal = true; $wire.editCategory({{ $cat->id }})" class="text-blue-500 hover:text-blue-700 font-medium">Edit</button>
                            <button wire:click="deleteCategory({{ $cat->id }})" wire:confirm="Hapus kategori?" class="text-red-500 hover:text-red-700 font-medium">Hapus</button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="2" class="px-4 py-8 text-center text-gray-400">Belum ada kategori.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div x-show="showProductModal" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="showProductModal" @click="showProductModal = false" x-transition.opacity class="fixed inset-0 bg-gray-900/80 transition-opacity" aria-hidden="true"></div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div x-show="showProductModal" x-transition class="relative z-10 inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl w-full border border-gray-200 dark:border-gray-700">
                <form wire:submit.prevent="saveProduct" @submit="showProductModal = false">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-[#111111] dark:text-white mb-4">{{ $productId ? 'Edit Produk' : 'Tambah Produk Baru' }}</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Produk</label>
                                    <input type="text" wire:model="productName" class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-[#111111] dark:text-white focus:outline-none focus:ring-2 focus:ring-[#111111] dark:focus:ring-gray-500" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kategori</label>
                                    <select wire:model="categoryIdForProduct" class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-[#111111] dark:text-white focus:outline-none focus:ring-2 focus:ring-[#111111]" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">SKU (Opsional)</label>
                                    <input type="text" wire:model="sku" class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-[#111111] dark:text-white">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Foto Produk (Max 2MB)</label>
                                    <input type="file" wire:model="newImage" accept="image/*" class="w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#111111] file:text-white dark:file:bg-white dark:file:text-[#111111] hover:file:bg-gray-800">
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Harga Modal (HPP)</label>
                                    <input type="number" wire:model="costPrice" class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-[#111111] dark:text-white" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Harga Jual</label>
                                    <input type="number" wire:model="sellingPrice" class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-[#111111] dark:text-white" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Stok Awal</label>
                                    <input type="number" wire:model="stock" class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-[#111111] dark:text-white" required>
                                </div>
                                <div class="flex items-center mt-6">
                                    <input type="checkbox" wire:model="isActive" class="w-4 h-4 text-[#111111] bg-gray-100 border-gray-300 rounded focus:ring-[#111111]">
                                    <label class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Aktifkan Produk</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-3">
                        <button type="button" @click="showProductModal = false" class="px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 font-medium">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-[#111111] dark:bg-white text-white dark:text-[#111111] rounded-lg hover:bg-gray-800 dark:hover:bg-gray-200 font-bold">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div x-show="showCategoryModal" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div x-show="showCategoryModal" @click="showCategoryModal = false" class="fixed inset-0 bg-gray-900/80 transition-opacity" aria-hidden="true"></div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div x-show="showCategoryModal" x-transition class="relative z-10 inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-xl sm:my-8 sm:align-middle sm:max-w-md w-full border border-gray-200 dark:border-gray-700">
                <form wire:submit.prevent="saveCategory" @submit="showCategoryModal = false">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-[#111111] dark:text-white mb-4">{{ $categoryId ? 'Edit Kategori' : 'Tambah Kategori' }}</h3>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Kategori</label>
                            <input type="text" wire:model="categoryName" class="w-full px-3 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-[#111111] dark:text-white focus:outline-none focus:ring-2 focus:ring-[#111111]" required>
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-3">
                        <button type="button" @click="showCategoryModal = false" class="px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 font-medium">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-[#111111] dark:bg-white text-white dark:text-[#111111] hover:bg-gray-800 dark:hover:bg-gray-200 rounded-lg font-bold">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>