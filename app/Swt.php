<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Swt extends Model
{
    protected $fillable =[
        'sid','swt_id','start_time','author','authors','msg_num','member_type','msg_type','chat_type','url','addr','keyword','area','title','account','is_contact','is_effective'
    ];
    protected $table = 'swts';
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        if (isset($attributes['hid'])&&!empty($attributes['hid'])&&is_numeric($attributes['hid'])){
            $this->table='swts_'.$attributes['hid'];
        }else{
            $this->table='swts_'.Aiden::getActiveHospitalId(Auth::user());
        }

    }
}
