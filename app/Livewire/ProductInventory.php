<?php
namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class ProductInventory extends Component
{
    use WithFileUploads; // Wajib untuk fitur upload foto

    // State Tab & Modal (Dikontrol dari Alpine juga agar ringan)
    public $activeTab = 'products'; // 'products' atau 'categories'
    
    // Form Kategori
    public $categoryId, $categoryName;
    
    // Form Produk
    public $productId, $productName, $categoryIdForProduct, $sku, $costPrice, $sellingPrice, $stock, $isActive = true;
    public $image, $newImage; // $image untuk nampilin foto lama, $newImage file upload baru

    // --- CRUD KATEGORI ---
    public function saveCategory()
    {
        $this->validate(['categoryName' => 'required|string|max:255']);
        
        Category::updateOrCreate(
            ['id' => $this->categoryId],
            [
                'tenant_id' => auth()->user()->tenant_id,
                'name' => $this->categoryName
            ]
        );
        $this->resetCategoryForm();
        session()->flash('success', 'Kategori berhasil disimpan!');
    }

    public function editCategory($id)
    {
        $category = Category::findOrFail($id);
        $this->categoryId = $category->id;
        $this->categoryName = $category->name;
    }

    public function deleteCategory($id)
    {
        Category::findOrFail($id)->delete();
        session()->flash('success', 'Kategori dihapus!');
    }

    public function resetCategoryForm() {
        $this->reset(['categoryId', 'categoryName']);
    }

    // --- CRUD PRODUK ---
    public function saveProduct()
    {
        $this->validate([
            'productName' => 'required|string|max:255',
            'categoryIdForProduct' => 'required|exists:categories,id',
            'costPrice' => 'required|numeric|min:0',
            'sellingPrice' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:0',
            'newImage' => 'nullable|image|max:2048', // Max 2MB
        ]);

        $imagePath = $this->image; // Path lama defaultnya

        // Jika upload foto baru
        if ($this->newImage) {
            if ($this->image) {
                Storage::disk('public')->delete($this->image); // Hapus foto lama
            }
            $imagePath = $this->newImage->store('products', 'public');
        }

        Product::updateOrCreate(
            ['id' => $this->productId],
            [
                'tenant_id' => auth()->user()->tenant_id,
                'category_id' => $this->categoryIdForProduct,
                'name' => $this->productName,
                'sku' => $this->sku,
                'cost_price' => $this->costPrice,
                'selling_price' => $this->sellingPrice,
                'stock' => $this->stock,
                'is_active' => $this->isActive,
                'image' => $imagePath,
            ]
        );

        $this->resetProductForm();
        session()->flash('success', 'Produk berhasil disimpan!');
    }

    public function editProduct($id)
    {
        $product = Product::findOrFail($id);
        $this->productId = $product->id;
        $this->productName = $product->name;
        $this->categoryIdForProduct = $product->category_id;
        $this->sku = $product->sku;
        $this->costPrice = $product->cost_price;
        $this->sellingPrice = $product->selling_price;
        $this->stock = $product->stock;
        $this->isActive = $product->is_active;
        $this->image = $product->image;
    }

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();
        session()->flash('success', 'Produk dihapus!');
    }

    public function resetProductForm() {
        $this->reset(['productId', 'productName', 'categoryIdForProduct', 'sku', 'costPrice', 'sellingPrice', 'stock', 'isActive', 'image', 'newImage']);
    }

    public function render()
    {
        return view('livewire.product-inventory', [
            'categories' => Category::latest()->get(),
            'products' => Product::with('category')->latest()->get(),
        ])->layout('components.layouts.app');
    }
}