<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Http\Requests\storeChannelRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChannelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('channels_read')){
            return view('channel.index',[
                'channels'=>Channel::select('id','name', 'display_name','status')->paginate(12)
            ]);
        }
        abort(401,'权限不足');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('channels_create')) {
            return view('channel.create');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(storeChannelRequest $request)
    {
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('channels_create')){
            if (Channel::createChannel($request)) {
                return redirect()->route('channel.index')->with('success', '添加成功！');
            }
        }
        abort(401);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('channels_update')){
            return view('channel.update', [
                'channel' => Channel::findOrFail($id)
            ]);
        }
        abort(401);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(storeChannelRequest $request, $id)
    {
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('channels_update')){
            if (Channel::updateChannel($request, $id)) {
                return redirect()->route('channel.index')->with('success', '更新成功！');
            }
        }
        abort(401);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('channels_delete')){
            if (Channel::destroy($id)) {
                return redirect()->back()->with('success', '删除成功！');
            }
        }
        abort(401);
    }
}
