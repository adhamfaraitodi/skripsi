<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){
        $datas = Category::select('id','name')
            ->orderBy('created_at')
            ->paginate(10);
        return view('admin/category',compact('datas'));
    }
    public function create(){
        return view('admin/category_create');
    }
    public function store(Request $request){
        $request->validate([
            'categoryName' => 'required|string',
        ]);
        $category = new Category();
        $category->name = $request->input('categoryName');
        $category->save();
        return redirect()->route('category.index')->with('success', 'Category created successfully');
    }
    public function edit($id){
        $data=Category::find($id);
        return view('admin/category_edit',compact('data'));
    }
    public function update(Request $request,$id){
        $request->validate([
            'categoryName'=>'required|string'
        ]);
        $data=Category::find($id);
        $data->name = $request->input('categoryName');
        $data->save();
        return redirect()->route('category.index')->with('success', 'Category edited successfully');
    }
}
