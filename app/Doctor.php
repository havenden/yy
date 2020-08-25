<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'hid','num'
    ];

    /**
     * @param Http\Requests\storeDoctorRequest $request
     * @return Doctor
     */
    public static function createDoctor(Http\Requests\storeDoctorRequest $request)
    {
        $doctor=new Doctor();
        $doctor->name=$request->name;
        $doctor->hid=$request->hid;
        $doctor->num=$request->num;
        $doctor->save();
        return $doctor;
    }

    /**
     * @param Http\Requests\storeDoctorRequest $request
     * @param int $id
     * @return mixed
     */
    public static function updateDoctor(Http\Requests\storeDoctorRequest $request, int $id)
    {
        $doctor=Doctor::findOrFail($id);
        $doctor->name=$request->input('name');
        $doctor->num=$request->input('num');
        return $doctor->save();
    }
}
