<?php

namespace App\Http\Controllers;

use App\Consult;
use App\Http\Requests\storeConsultRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConsultController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('consults_read')){
            return view('consult.index',[
                'consults'=>Consult::select('id','name', 'display_name')->paginate(12)
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
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('consults_create')) {
            return view('consult.create');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(storeConsultRequest $request)
    {
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('consults_create')){
            if (Consult::createConsult($request)) {
                return redirect()->route('consult.index')->with('success', '添加成功！');
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
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('consults_update')){
            return view('consult.update', [
                'consult' => Consult::findOrFail($id)
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
    public function update(storeConsultRequest $request, $id)
    {
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('consults_update')){
            if (Consult::updateConsult($request, $id)) {
                return redirect()->route('consult.index')->with('success', '更新成功！');
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
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('consults_delete')){
            if (Consult::destroy($id)) {
                return redirect()->back()->with('success', '删除成功！');
            }
        }
        abort(401);
    }
}
