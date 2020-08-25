<?php

namespace App;

use App\Http\Requests\storeConsultRequest;
use Illuminate\Database\Eloquent\Model;

class Consult extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'display_name'
    ];

    /**
     * @param storeConsultRequest $request
     * @return Consult
     */
    public static function createConsult(Http\Requests\storeConsultRequest $request)
    {
        $consult=new Consult();
        $consult->name=$request->name;
        $consult->display_name=$request->display_name;
        $consult->save();
        return $consult;
    }

    /**
     * @param storeConsultRequest $request
     * @param int $id
     * @return mixed
     */
    public static function updateConsult(Http\Requests\storeConsultRequest $request, int $id)
    {
        $consult=Consult::findOrFail($id);
        $consult->display_name=$request->input('display_name');
        return $consult->save();
    }
}
