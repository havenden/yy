<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Addon extends Model
{
    protected $fillable =[
        'mid','body'
    ];
    protected $table = 'addons';
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table='addons_'.Aiden::getActiveHospitalId(Auth::user());
    }

    public function member()
    {
        return $this->belongsTo('App\Member','mid','id');
    }
}
