<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Track extends Model
{
    protected $fillable =[
        'uid','mid','content','track_type'
    ];
    protected $table = 'tracks';
//    protected $tracksArray = [
//            '1'=>'例行回访',
//            '2'=>'未来跟进',
//            '3'=>'事后跟踪',
//            '4'=>'其它',
//        ];
//    public function getTrackType()
//    {
//        return $this->tracksArray[$this->track_type];
//    }
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table='tracks_'.Aiden::getActiveHospitalId(Auth::user());
    }

    public static function createTrack(\Illuminate\Http\Request $request)
    {
//        dd($request->all());
        $track=new Track();
        $track->uid=Auth::user()->id;
        $track->mid=$request->input('mid');
        $track->content=$request->input('content');
        $track->track_type=$request->input('track_type');
        $condition=$request->input('condition');
        $pubdate=$request->input('pubdate');
        $member=Member::find($request->input('mid'));
        $member->tell_num=$member->tell_num + 1;
        if (!empty($condition) || !empty($pubdate)){
            if (!empty($condition)){
                $member->condition=$condition;
            }
            if (!empty($pubdate)){
                $member->pubdate=Carbon::createFromFormat('Y-m-d H:i',$pubdate)->toDateTimeString();
                $member->change_log.=Auth::user()->display_name.'于'.Carbon::now()->toDateTimeString().'更新了预约时间<br/>';
            }
        }
        $member->save();
        return $track->save();
    }

    public function member()
    {
        return $this->belongsTo('App\Member','mid');
    }
}
