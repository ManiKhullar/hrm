<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /*Category curd opration*/

    public function category_add(){
        $categorys = DB::table('category')->paginate(15);
        return view('category.category',['categorys'=> $categorys])->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function category_save(Request $request)
    {
        $request->validate([
            'category_name' => 'required',
            'status' =>  'required',
        ]);
        Category::create([
            'category_name' => $request->category_name,
            'status' =>  $request->status,
        ]);
        return redirect()->route('category')->with('success','Category Created Successfully.');
    }

    public function category_update(Request $request, $id, $status)
    {
        $category = Category::where('id', $id)->firstOrFail();
        $category->update([
            'status' =>  $status,
        ]);
  
        return redirect()->route('category')->with('success','Category Updated Successfully');
    }
}
