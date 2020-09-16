<?php

namespace App;

use App\Http\Requests\storeHospitalRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Hospital extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'display_name','status'
    ];

    /**
     * @param storeHospitalRequest $request
     * @return Hospital
     */
    public static function createHospital(storeHospitalRequest $request)
    {
        $hospital=new Hospital();
        $hospital->name=$request->name;
        $hospital->display_name=$request->display_name;
        $hospital->status=$request->status;
        $hospital->save();
        //创建患者表，患者附加表，回访表，挂号表
        TableHandle::copyTableStructure('members','members_'.$hospital->id);
        TableHandle::copyTableStructure('addons','addons_'.$hospital->id);
        TableHandle::copyTableStructure('tracks','tracks_'.$hospital->id);
        TableHandle::copyTableStructure('ghs','ghs_'.$hospital->id);
        TableHandle::copyTableStructure('swts','swts_'.$hospital->id);
        //关联到超级管理员
        $admin=User::findOrFail(1);
        $admin->hospitals()->attach($hospital);
        return $hospital;
    }

    /**
     * @param storeHospitalRequest $request
     * @param int $id
     * @return mixed
     */
    public static function updateHospital(storeHospitalRequest $request, int $id)
    {
        $hospital=Hospital::findOrFail($id);
        $hospital->display_name=$request->input('display_name');
        $hospital->status=$request->input('status');
        return $hospital->save();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('App\User','user_hospitals','hospital_id','user_id');
    }
}
