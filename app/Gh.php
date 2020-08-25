<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Gh extends Model
{
    protected $fillable =[
        'gh_name','gh_age','gh_sex','gh_tel','gh_ref','gh_office','gh_disease','gh_date','gh_description','yy_num','status','addons'
    ];
    protected $table = 'ghs';
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table='ghs_'.Aiden::getActiveHospitalId(Auth::user());
    }

    /**
     * @param int $id
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public static function updateGh(int $id, \Illuminate\Http\Request $request)
    {
        $gh=Gh::findOrFail($id);
        $gh->addons=$request->input('addons');
        $gh->status=$request->input('status');
        return $gh->save();
    }
}
