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

    /**
     * 商务通客服
     * @return mixed
     */
    public static function getAuthors()
    {
        return Swt::select('author')->distinct()->pluck('author')->toArray();
    }

    /**
     * 对话类型
     * @return array
     */
    public static function getMsgType()
    {
        return Swt::select('msg_type')->distinct()->pluck('msg_type')->toArray();
    }
    /**
     * 客人类别
     * @return array
     */
    public static function getMemberType()
    {
        return Swt::select('member_type')->distinct()->pluck('member_type')->toArray();
    }
    /**
     * 对话类别
     * @return array
     */
    public static function getChatType()
    {
        $types=Swt::select('chat_type')->distinct()->pluck('chat_type')->toArray();
        $typesArray=implode(',',$types);
        $res=array_unique(explode(',',$typesArray));
        return $res;
    }
    /**
     * 地域
     * @return array
     */
    public static function getAreas()
    {
        return Swt::select('area')->distinct()->pluck('area')->toArray();
    }
    /**
     * 账户后缀
     * @return array
     */
    public static function getAccount()
    {
        return Swt::select('account')->distinct()->pluck('account')->toArray();
    }

    /**
     * 搜索来源
     * @return mixed
     */
    public static function getEngineFroms()
    {
        return Swt::select('engine_from')->distinct()->pluck('engine_from')->toArray();
    }

    /**
     * 设备为移动设备或pc
     * @return mixed
     */
    public static function getDevices()
    {
        return Swt::select('device')->distinct()->pluck('device')->toArray();
    }
}
