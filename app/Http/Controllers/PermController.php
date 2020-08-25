<?php

namespace App\Http\Controllers;

use App\Http\Requests\storePermissionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('permissions_read')){
            return view('permission.index',[
                'permissions'=>Permission::select('id','name','display_name')->paginate(18)
            ]);
        }
        abort(401,'权限不足');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('permissions_create')) {
            return view('permission.create');
        }
        abort(401,'权限不足');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(storePermissionRequest $request)
    {
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('permissions_create')){
            $permisson=Permission::create(['name'=>$request->input('name'),'display_name'=>$request->input('display_name')]);
            if ($permisson) {
                //为管理员赋权
                $roleAdmin=Role::where('name','admin')->first();
                $roleAdmin->givePermissionTo($permisson);
                return redirect()->route('permission.index')->with('success', '添加成功！');
            }
        }
        abort(401);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('permissions_update')){
            return view('permission.update', [
                'permission' =>Permission::findOrFail($id),
            ]);
        }
        abort(401);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(storePermissionRequest $request, $id)
    {
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('permissions_update')){
            $permission=Permission::findOrFail($id);
            $permission->name=$request->input('name');
            $permission->display_name=$request->input('display_name');
            if ($permission->save()) {
                return redirect()->route('permission.index')->with('success', '更新成功！');
            }
        }
        abort(401);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('permissions_delete')){
            if (Permission::destroy($id)) {
                return redirect()->back()->with('success', '删除成功！');
            }
        }
        abort(401);
    }
}
