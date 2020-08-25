<?php

namespace App\Http\Controllers;

use App\Aiden;
use App\Disease;
use App\Hospital;
use App\Http\Requests\storeDiseaseRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiseaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('diseases_read')){
            $hospitalId=Aiden::getActiveHospitalId(Auth::user());
            if ($hospitalId!==true&&is_numeric($hospitalId)){
                $diseases=Disease::select('id','hid','name', 'display_name')->where('hid',$hospitalId)->paginate(12);
            }else{
                $diseases=[];
            }
            return view('disease.index',[
                'diseases'=>$diseases
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
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('diseases_create')) {
            $hospitalId=Aiden::getActiveHospitalId(Auth::user());
            if (is_numeric($hospitalId)){
                return view('disease.create',['hospital'=>Hospital::findOrFail($hospitalId)]);
            }else{
                abort(403);
            }
        }
        abort(401,'权限不足');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(storeDiseaseRequest $request)
    {
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('diseases_create')){
            if (Disease::createDisease($request)) {
                return redirect()->route('disease.index')->with('success', '添加成功！');
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
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('diseases_update')){
            $disease=Disease::findOrFail($id);
            $hospital=Hospital::findOrFail($disease->hid);
            return view('disease.update', [
                'disease' =>$disease,
                'hospital' =>$hospital,
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
    public function update(storeDiseaseRequest $request, $id)
    {
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('diseases_update')){
            if (Disease::updateDisease($request, $id)) {
                return redirect()->route('disease.index')->with('success', '更新成功！');
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
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('diseases_delete')){
            if (Disease::destroy($id)) {
                return redirect()->back()->with('success', '删除成功！');
            }
        }
        abort(401);
    }
}
