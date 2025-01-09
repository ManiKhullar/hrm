<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    /*Role curd opration*/

    public function role_add(){
        $roles = DB::select("select role.id, role.status, department.department_name  from role left join department on role.department=department.id  where role.status=1");
        $deparement = DB::select("select * from department where status=1");
        $menuData = DB::select("select * from menus where status=1 or status=2");
        return view('role.role',['roles'=> $roles,'menuData'=>$menuData,'deparement'=>$deparement])->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function role_save(Request $request)
    {
        $request->validate([
            'department' => 'required',
            'access' =>  'required',
            'status' =>  'required',
        ]);
        Role::create([
            'department' => $request->department,
            'access' =>  implode(",",$request->access),
            'status' =>  $request->status,
        ]);
        return redirect()->route('role')->with('success','Role Created Successfully.');
    }

    public function role_edit($id)
    {
        $role = DB::select("select *  from role where id=".$id);
        $menu = DB::select("select *  from menus where status=1 or status=2");
        $deparement = DB::select("select *  from department where status=1");
        return view('role.roleedit', ['menu'=>$menu,'role'=>$role,'deparement'=>$deparement]);
    }

    public function role_update(Request $request, $id)
    {
        $request->validate([
            'department' => 'required',
            'access' => 'required',
            'status' => 'required',
        ]);
        $role = Role::where('id', $id)->firstOrFail();
        $role->update([
            'department' => $request->department,
            'access' =>  implode(",",$request->access),
            'status' =>  $request->status,
        ]);
  
        return redirect()->route('role')->with('success','Role Updated Successfully');
    }

    public function role_delete($del_id)
    {
        $role=Role::find($del_id);
        $role->delete($role->id);
        return redirect()->route('role')->with('success','Role Deleted Successfully'); 
    }

    public function menu_add(){
        $menu = DB::table('menus')->orderBy('name', 'ASC') ->paginate(15);
        return view('menu.add',['menu'=> $menu])->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function menu_save(Request $request){
        $request->validate([
            'name' => 'required',
            'routes_name' =>  'required',
            'status' =>  'required',
        ]);
        Menu::create([
            'name' => $request->name,
            'class_name' =>  $request->class_name,
            'routes_name' =>  $request->routes_name,
            'status' =>  $request->status,
        ]);
        return redirect()->route('menu_add')->with('success','Menu Created Successfully.');
    }

    public function menu_edit($id){

        $menu = Menu::where('id', $id)->firstOrFail();

        return view('menu.edit', compact('menu'));
    }

    public function menu_update(Request $request, $id){
        $request->validate([
            'name' => 'required',
            'routes_name' => 'required',
            'status' => 'required',
        ]);
        $menu = Menu::where('id', $id)->firstOrFail();
        $menu->update([
            'name' => $request->name,
            'class_name' =>  $request->class_name,
            'routes_name' =>  $request->routes_name,
            'status' =>  $request->status,
        ]);
  
        return redirect()->route('menu_add')->with('success','Menu Updated Successfully');
    }

    public function menu_delete($id)
    {
        $menu=Menu::find($id);
        $menu->delete($menu->id);
        return redirect()->route('menu_add')->with('success','Menu Deleted Successfully'); 
    }
}
