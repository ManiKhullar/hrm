<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cms;
use App\Models\CmsImages;
use Illuminate\Support\Facades\DB;

class CmsController extends Controller
{
    public function cms_add(){
        $cmsdata = DB::select("SELECT * FROM cms");
        return view('cms.cms',['cmsdata'=> $cmsdata])->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function cms_save(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);

        $cms = Cms::create([
            'title'=> $request->title,
            'content'=> $request->content,
            'status'=> '1',
        ]);

        CmsImages::create([
            'cms_id'=> $cms->id,
            'file_name'=> $request->file_name,
            'status'=> '1',
        ]);
        // print_r($cms->id);die;
        return redirect()->route('cms')->with('success','Cms Added Successfully.');
    }

    public function cms_edit($id)
    {
        $cmsdata = DB::table('cms')
            ->leftjoin('cms_images', 'cms.id', '=', 'cms_images.cms_id')
            ->where('cms.id','=',$id)
            ->paginate(10,array('cms.id', 'cms.title', 'cms.content', 'cms.status', 'cms_images.file_name'));

        return view('cms.cmsedit', compact('cmsdata'));
    } 

    public function cms_update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'status' => 'required',
        ]);
        $cms = Cms::where('id', $id)->firstOrFail();
        $cms->update([
            'title' => $request->title,
            'content' =>  $request->content,
            'status' =>  $request->status,
        ]);
        return redirect()->route('cms')->with('success','Cms Updated Successfully.');
    }

    public function cms_delete($id)
    {
       $cmsCollection = Cms::where('id', $id)->firstOrFail();
       $cmsImageCollection = CmsImages::where('cms_id', $id)->get();
       foreach ($cmsImageCollection as $cmsImage) {
            $cmsImage->delete();
       }
       $cmsCollection->delete();
       return redirect()->route('cms')->with('success','Cms Deleted Successfully.');
    }
}
