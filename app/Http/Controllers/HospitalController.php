<?php

namespace App\Http\Controllers;

use App\Aiden;
use App\Hospital;
use App\Http\Requests\storeHospitalRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HospitalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('hospitals_read')){
            return view('hospital.index',[
                'hospitals'=>Hospital::select('id','name', 'display_name','status')->paginate(12)
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
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('hospitals_create')) {
            return view('hospital.create');
        }
    }

    /**
     * @param storeHospitalRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(storeHospitalRequest $request)
    {
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('hospitals_create')){
            if (Hospital::createHospital($request)) {
                return redirect()->route('hospital.index')->with('success', '添加成功！');
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
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('hospitals_update')){
            return view('hospital.update', [
                'hospital' => Hospital::findOrFail($id)
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
    public function update(storeHospitalRequest $request, $id)
    {
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('hospitals_update')){
            if (Hospital::updateHospital($request, $id)) {
                return redirect()->route('hospital.index')->with('success', '更新成功！');
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
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('hospitals_delete')){
            if (Hospital::destroy($id)) {
                return redirect()->back()->with('success', '删除成功！');
            }
        }
        abort(401);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function change(Request $request)
    {
        $hospital_id = $request->input('hospital_id');
        $res = Aiden::setActiveHospitalId($hospital_id);
        if ($res) {
            return redirect($request->header('referer'));
        }
    }
}
