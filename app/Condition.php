<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Condition extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','name', 'display_name'
    ];

    public static function createCondition(Http\Requests\storeConditionRequest $request)
    {
        $condition=new Condition();
        $condition->name=$request->name;
        $condition->display_name=$request->display_name;
        $condition->save();
        return $condition;
    }

    public static function updateCondition(Http\Requests\storeConditionRequest $request, int $id)
    {
        $condition=Condition::findOrFail($id);
        $condition->display_name=$request->input('display_name');
        return $condition->save();
    }
}
