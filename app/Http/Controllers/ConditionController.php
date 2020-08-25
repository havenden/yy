<?php

namespace App\Http\Controllers;

use App\Condition;
use App\Http\Requests\storeConditionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConditionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('conditions_read')){
            return view('condition.index',[
                'conditions'=>Condition::select('id','name', 'display_name')->paginate(12)
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
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('conditions_create')) {
            return view('condition.create');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(storeConditionRequest $request)
    {
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('conditions_create')){
            if (Condition::createCondition($request)) {
                return redirect()->route('condition.index')->with('success', '添加成功！');
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
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('conditions_update')){
            return view('condition.update', [
                'condition' => Condition::findOrFail($id)
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
    public function update(storeConditionRequest $request, $id)
    {
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('conditions_update')){
            if (Condition::updateCondition($request, $id)) {
                return redirect()->route('condition.index')->with('success', '更新成功！');
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
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('conditions_delete')){
            if (Condition::destroy($id)) {
                return redirect()->back()->with('success', '删除成功！');
            }
        }
        abort(401);
    }
}
