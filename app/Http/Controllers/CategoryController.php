<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;


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

//update
public function update(Request $request, $id){

    $data = $request->all();
    $category = Category::findOrFail($id);

    $category->update($data) ;
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
