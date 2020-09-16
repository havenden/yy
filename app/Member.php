<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Member extends Model
{

    protected $fillable =[
        'hid','grade','age','consult','sex','channel','name','wechat','qq','yy_num','disease','writer','area','tell_num','order_date','condition','edit_log','change_log','created_at','updated_at'
    ];
    protected $table = 'members';
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table='members_'.Aiden::getActiveHospitalId(Auth::user());
    }

    public function addon()
    {
        return $this->hasOne('App\Addon','mid');
    }

    public function tracks()
    {
        return $this->hasMany('App\Track','mid');
    }

    public static function createMember(Http\Requests\storeMemberRequest $request)
    {
        $member=new Member();
        $member->name=$request->input('name');
        $member->uid=Auth::user()->id;
        $member->tel=$request->input('tel');
        if (!empty($request->input('order_date'))){
            $member->order_date=Carbon::createFromFormat('Y-m-d H:i',$request->input('order_date') )->toDateTimeString();
        }
        $member->hid=$request->input('hospital');
        $member->grade=$request->input('grade');
        $member->channel=$request->input('channel');
        $member->consult=$request->input('consult');
        $member->condition=$request->input('condition');
        $member->disease=implode(',',$request->input('disease'));
        if (!empty($request->input('doctor'))){
            $member->doctor=$request->input('doctor');
        }
        $member->yy_num=$request->input('yy_num')?$request->input('yy_num'):Str::random(7);
        $member->description=$request->input('description');
        $member->age=$request->input('age');
        $member->sex=$request->input('sex');
        $member->cfz=$request->input('cfz');
        $member->wechat=$request->input('wechat');
        $member->area=$request->input('area');
        $member->url=$request->input('url');
        $member->keywords=$request->input('keywords');
        if (!empty($request->input('ok_date'))){
            $member->okdate=Carbon::createFromFormat('Y-m-d H:i',$request->input('ok_date') )->toDateTimeString();
        }


            $member->save();
            $addon=Addon::create([
                'mid'=>$member->id,
                'body'=>$request->input('body')?$request->input('body'):'',
            ]);
            $member->addon()->save($addon);

        return $member;
    }

    public static function updateMember(Http\Requests\storeMemberRequest $request, int $id)
    {
        $user=Auth::user();
        $member=Member::findOrFail($id);
        $member->name=$request->input('name');
        $member->tell=$request->input('tell');
        if (!empty($request->input('order_date'))){
            $oldorderdate=$member->order_date;
            if (Carbon::create($oldorderdate)->format('Y-m-d H:i')!=$request->input('order_date')){
                $member->order_date=Carbon::create($request->input('order_date') )->toDateTimeString();
                $member->change_log.=$user->display_name.'于'.Carbon::now()->toDateTimeString().'更新了预约时间<br/>';
            }
        }
        $member->hid=$request->input('hospital');
        $member->grade=$request->input('grade');
        $member->channel=$request->input('channel');
        $member->consult=$request->input('consult');
        $member->condition=$request->input('condition');
        $member->disease=implode(',',$request->input('disease'));
        if (!empty($request->input('doctor'))){
            $member->doctor=$request->input('doctor');
        }
        $member->yy_num=$request->input('yy_num')?$request->input('yy_num'):Str::random(7);
        $member->description=$request->input('description');
        $member->age=$request->input('age');
        $member->sex=$request->input('sex');
        $member->cfz=$request->input('cfz');
        $member->wechat=$request->input('wechat');
        $member->area=$request->input('area');
        $member->url=$request->input('url');
        $member->keywords=$request->input('keywords');
        if (!empty($request->input('ok_date'))){
            $oldokdate=$member->ok_date;
            if (Carbon::create($oldokdate)->format('Y-m-d H:i')!=$request->input('order_date')){
                $member->ok_date=Carbon::create($request->input('ok_date') )->toDateTimeString();
                $member->condition=1;
                $member->change_log.=$user->display_name.'于'.Carbon::now()->toDateTimeString().'更新了到诊时间<br/>';
            }
        }
        $member->edit_log.=$user->display_name.'于'.Carbon::now()->toDateTimeString().'更新了客户资料<br/>';

            $member->save();
            $addon=$member->addon;
            $addon->body=$request->input('body');
            $member->addon()->save($addon);
        return $member;
    }


}
