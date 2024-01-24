<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;



class ProductController extends Controller
{
    //index
    public function index(){
        $products = \App\Models\Product::paginate(5);
        return view('pages.product.index', compact('products'));
    }

    //create
    public function create() {
        $categories = \App\Models\Category::all();
        return view('pages.product.create', compact('categories'));
    }

     //edit
     public function edit($id)
    {
        // Ambil produk berdasarkan ID
        $product = Product::findOrFail($id);

        // Ambil semua kategori untuk digunakan dalam dropdown
        $categories = Category::all();


        return view('pages.product.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        // Validasi data dari request
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Sesuaikan dengan kebutuhan Anda
        ], [
            'category_id.exists' => 'The selected category is invalid.',
        ]);

        // Ambil produk berdasarkan ID
        $product = Product::findOrFail($id);

        // Tambahkan logika untuk menyimpan gambar baru jika diunggah
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($product->image) {
                Storage::delete('public/products/' . $product->image);
            }

            // Simpan gambar baru
            $filename = time() . '.' . $request->image->extension();
            $request->image->storeAs('public/products', $filename);
            $product->image = $filename;
        }

        // Update data produk
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->category_id = $request->category_id;
        $product->save();

        return redirect()->route('product.index');
    }

    //store
    public function store(Request $request){
        $filename = time() . '.' . $request->image->extension();
        $request->image->storeAs('public/products', $filename);
       // $data = $request->all();

        $product = new \App\Models\Product;
        $product->name = $request->name;
        $product->price = (int) $request->price;
        $product->stock = (int) $request->stock;
        $product->category_id = $request->category_id;
        $product->image = $filename;
        $product->save();

        return redirect()->route('product.index');
    }
     //destroy
     public function destroy($id){

        $product = Product::findOrFail($id);
        $product->delete();
        return redirect()->route('product.index');
    }


    // public function update(Request $request, $id){

    //     $data = $request->all();
    //     $product = Product::findOrFail($id);
    //     $product->update($data) ;
    //     return redirect()->route('product.index');
    // }
}
