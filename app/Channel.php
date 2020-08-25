<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'display_name','status'
    ];

    /**
     * @param Http\Requests\storeChannelRequest $request
     * @return Channel
     */
    public static function createChannel(Http\Requests\storeChannelRequest $request)
    {
        $channel=new Channel();
        $channel->name=$request->name;
        $channel->display_name=$request->display_name;
        $channel->status=$request->status;
        $channel->save();
        return $channel;
    }

    /**
     * @param Http\Requests\storeChannelRequest $request
     * @param int $id
     * @return mixed
     */
    public static function updateChannel(Http\Requests\storeChannelRequest $request, int $id)
    {
        $channel=Channel::findOrFail($id);
        $channel->display_name=$request->input('display_name');
        $channel->status=$request->input('status');
        return $channel->save();
    }

}
