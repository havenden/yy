<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class Aiden extends Model
{

    /**
     * @param $user
     * @return mixed
     */
    public static function getActiveHospitalId($user)
    {
        $key='user_'.$user->id.'_recent_hospital';
        $id = Cache::remember($key, config('session.lifetime'), function() use ($user){
            if (empty($user->hid)){
                if (empty($user->hospital->first())){
                    if ($user->id==1){
                        return true;
                    }else{
                        abort('401','请先获得项目权限');
                        return 0;
                    }
                }else{
                    $user->hid=$user->hospital->first()->id;
                    $user->save();
                    return $user->hid;
                }
            }else{
                return $user->hid;
            }
        });
        return $id;
    }

    /**
     * @param $hospital_id
     * @return mixed
     */
    public static function setActiveHospitalId($hospital_id)
    {
        $user=Auth::user();
        $user->hid=$hospital_id;
        $user->save();
        $key='user_'.$user->id.'_recent_hospital';
        Cache::put($key,$hospital_id,config('session.lifetime'));
        return $hospital_id;
    }

    public static function getAllHospitalsArray()
    {
        $key='all_hospital_array';
        $id = Cache::remember($key, config('session.lifetime'), function(){
            return Hospital::pluck('display_name','id');
        });

    }

    public static function getAllConsultsArray()
    {
        return Consult::pluck('display_name','id');
    }

    public static function getAllUsersArray()
    {
        return User::pluck('display_name','id');
    }

    public static function getAllChannelsArray()
    {
        return Channel::pluck('display_name','id');
    }

    public static function getAllActiveUsersArray()
    {
        return User::where('is_active','1')->pluck('display_name','id');
    }

    public static function getAllDiseasesArray()
    {
        return Disease::where('hid',static::getActiveHospitalId(Auth::user()))->pluck('display_name','id');
    }

    public static function getAllDoctorsArray()
    {
        return Doctor::where('hid',static::getActiveHospitalId(Auth::user()))->pluck('name','id');
    }

    public static function getAllConditionsArray()
    {
        return Condition::pluck('display_name','id');
    }

    public static function getChannelIdFromName($flag)
    {
        return Channel::where('name',$flag)->first()->id;
    }

    public static function getAllTracksType()
    {
        $tracksArray = [
            '1'=>'例行回访',
            '2'=>'未来跟进',
            '3'=>'事后跟踪',
            '4'=>'其它',
        ];
        return $tracksArray;
    }
}
