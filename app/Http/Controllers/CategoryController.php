<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class CategoryController extends Controller
{
   // index
   public function index() {

    $categories = \App\Models\Category::paginate(15);
    return view('pages.category.index', compact('categories'));
   }

    //create
    public function create(){
        return view('pages.category.create');
    }

   //show
   public function show($id){
    return view('pages.dashboard');
}

    //edit
    public function edit($id){
        $category = Category::findOrFail($id);

        return view('pages.category.edit', compact('category'));
    }

    // update
    public function update(Request $request, $id){
        // Validasi data dari request
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Sesuaikan dengan kebutuhan Anda
        ]);

        // Ambil kategori berdasarkan ID
        $category = Category::findOrFail($id);

        // Tambahkan logika untuk menyimpan gambar baru jika diunggah
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($category->image) {
                Storage::delete('public/categories/' . $category->image);
            }

            // Simpan gambar baru
            $filename = time() . '.' . $request->image->extension();
            $request->image->storeAs('public/categories', $filename);
            $category->image = $filename;
        }

        // Update data kategori
        $category->name = $request->name;
        $category->description = $request->description;
        $category->save();

        return redirect()->route('category.index');
    }

        //store
        public function store(Request $request){
            $filename = time() . '.' . $request->image->extension();
            $request->image->storeAs('public/categories', $filename);
            $category = new \App\Models\Category;
            $category->name = $request->name;
            $category->description = $request->description;
            $category->image = $filename;
            $category->save();

            return redirect()->route('category.index');
        }

        //destroy
     public function destroy($id){

        $category = Category::findOrFail($id);
        $category->delete();
        return redirect()->route('category.index');
    }


}
