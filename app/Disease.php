<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Disease extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'hid','display_name'
    ];

    public static function createDisease(Http\Requests\storeDiseaseRequest $request)
    {
        $disease=new Disease();
        $disease->name=$request->name;
        $disease->hid=$request->hid;
        $disease->display_name=$request->display_name;
        $disease->save();
        return $disease;
    }

    public static function updateDisease(Http\Requests\storeDiseaseRequest $request, int $id)
    {
        $disease=Disease::findOrFail($id);
        $disease->display_name=$request->input('display_name');
        return $disease->save();
    }
}
