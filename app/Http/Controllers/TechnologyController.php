<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Technology;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TechnologyController extends Controller
{
    public function technology_add()
    {
        $technology = DB::table('technology')
            ->orderBy('id', 'ASC')
            ->paginate(15);
        return view('technology.technology',['technology'=> $technology])->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function technology_save(Request $request)
    { 
        $request->validate([
            'technology' => 'required',
            'status' => 'required'
        ]);

        Technology::create([
            'technology' => $request->technology,
            'status' => $request->status
            
        ]);
        return redirect()->route('technology')->with('success','Technology Added Successfully.');          
    }

    public function technology_edit($id)
    {
        $technology = Technology::where('id', $id)->firstOrFail();
        return view('technology.technologyedit', compact('technology'));
    }

    public function technology_update(Request $request, $id)
    {
        $request->validate([
            'technology' => 'required',
            'status' => 'required'
        ]);
        $technology = Technology::where('id', $id)->firstOrFail();
        $technology->update([
            'technology' => $request->technology,
            'status' => $request->status
        ]);
        return redirect()->route('technology')->with('success','Technology Updated Successfully.');
    }

}
