<?php

namespace App\Http\Controllers;

use App\Aiden;
use App\Http\Requests\storeRoleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('roles_read')){
            return view('role.index',[
                'roles'=>Role::select('id','name','display_name')->paginate(12)
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
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('roles_create')) {
            return view('role.create',['permissions'=>Permission::all()]);
        }
        abort(401,'权限不足');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(storeRoleRequest $request)
    {
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('roles_create')){
            $role=Role::create(['name'=>$request->input('name'),'display_name'=>$request->input('display_name')]);
            if ($role) {
                //为角色赋权
                $role->syncPermissions($request->input('permissions'));
                return redirect()->route('role.index')->with('success', '添加成功！');
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
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('roles_update')){
            return view('role.update', [
                'role' =>Role::findOrFail($id),
                'permissions'=>Permission::all()
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
    public function update(storeRoleRequest $request, $id)
    {
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('roles_update')){
            $role=Role::findOrFail($id);
            $role->name=$request->input('name');
            $role->display_name=$request->input('display_name');
            if ($role->save()) {
                //为角色赋权
                $role->syncPermissions($request->input('permissions'));
                return redirect()->route('role.index')->with('success', '更新成功！');
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
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('roles_delete')){
            if (Role::destroy($id)) {
                return redirect()->back()->with('success', '删除成功！');
            }
        }
        abort(401);
    }
}
