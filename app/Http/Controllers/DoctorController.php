<?php

namespace App\Http\Controllers;

use App\Aiden;
use App\Doctor;
use App\Hospital;
use App\Http\Requests\storeDoctorRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('doctors_read')){
            $hospitalId=Aiden::getActiveHospitalId(Auth::user());
            if ($hospitalId!==true&&is_numeric($hospitalId)){
                $doctors=Doctor::select('id','hid','name', 'num')->where('hid',$hospitalId)->paginate(12);
            }else{
                $doctors=[];
            }
            return view('doctor.index',[
                'doctors'=>$doctors
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
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('doctors_create')) {
            $hospitalId=Aiden::getActiveHospitalId(Auth::user());
            if (is_numeric($hospitalId)){
                return view('doctor.create',['hospital'=>Hospital::findOrFail($hospitalId)]);
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
    public function store(storeDoctorRequest $request)
    {
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('doctors_create')){
            if (Doctor::createDoctor($request)) {
                return redirect()->route('doctor.index')->with('success', '添加成功！');
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
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('doctors_update')){
            $doctor=Doctor::findOrFail($id);
            $hospital=Hospital::findOrFail($doctor->hid);
            return view('doctor.update', [
                'doctor' =>$doctor,
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
    public function update(storeDoctorRequest $request, $id)
    {
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('doctors_update')){
            if (Doctor::updateDoctor($request, $id)) {
                return redirect()->route('doctor.index')->with('success', '更新成功！');
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
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('doctors_delete')){
            if (Doctor::destroy($id)) {
                return redirect()->back()->with('success', '删除成功！');
            }
        }
        abort(401);
    }
}
