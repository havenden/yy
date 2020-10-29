<?php

namespace App\Http\Controllers;

use App\Aiden;
use App\Imports\SwtsImport;
use App\Jobs\ExcelImport;
use App\Swt;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class SwtController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->id==1||Auth::user()->hasPermissionTo('swts_read')){
            return view('swt.index',
                [
                    'memberTypes' => Swt::getMemberType(),
                    'chatTypes' => Swt::getChatType(),
                    'msgTypes' => Swt::getMsgType(),
                    'accounts' => Swt::getAccount(),
                    'areas' => Swt::getAreas(),
                    'authors' => Swt::getAuthors(),
                    'engineFroms' => Swt::getEngineFroms(),
                    'devices' => Swt::getDevices(),
                    'swts' => Swt::orderBy('start_time','desc')->paginate(18),
                ]
            );
        }
        return abort(401,'权限不足');
    }
    public function list()
    {
        return view('swt.list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function import(Request $request)
    {
        if ($request->hasFile('swt_excel')){
            $file=$request->file('swt_excel');
            $fileextension=$file->getClientOriginalExtension();
            if (in_array($fileextension,['xls','xlsx'])){
                $filename=date('Y').'_'.date('m').'_'.date('d').'_'.date('H').'_'.date('i').'_'.date('s').'.'.$fileextension;
                $path = $file->storeAs('uploadfiles',$filename);
                ExcelImport::dispatch($path,Aiden::getActiveHospitalId(Auth::user()))->onConnection('database');
//                ExcelImport::dispatchNow($path,Aiden::getActiveHospitalId(Auth::user()));
                return redirect()->back()->with('success','文件上传成功，已进入任务队列，请稍后刷新！');
            }else{
                return redirect()->back()->with('error','文件格式错误');
            }
        }else{
            return redirect()->back()->with('error','请按规定上传文件');
        }
    }

    public function search(Request $request)
    {
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('swts_read')) {
            $key = $request->input('key');
            $model = $request->input('model');
            if (!empty($key)) {
                return view('swt.index', [
                    'memberTypes' => Swt::getMemberType(),
                    'chatTypes' => Swt::getChatType(),
                    'msgTypes' => Swt::getMsgType(),
                    'accounts' => Swt::getAccount(),
                    'areas' => Swt::getAreas(),
                    'authors' => Swt::getAuthors(),
                    'swts' => Swt::where('sid', 'like', '%' . $key . '%')
                        ->orWhere('swt_id', 'like', '%' . $key . '%')
                        ->orWhere('author', 'like', '%' . $key . '%')
                        ->orWhere('authors', 'like', '%' . $key . '%')
                        ->orWhere('member_type', 'like', '%' . $key . '%')
                        ->orWhere('msg_type', 'like', '%' . $key . '%')
                        ->orWhere('chat_type', 'like', '%' . $key . '%')
                        ->orWhere('url', 'like', '%' . $key . '%')
                        ->orWhere('keyword', 'like', '%' . $key . '%')
                        ->orWhere('title', 'like', '%' . $key . '%')
                        ->orWhere('area', 'like', '%' . $key . '%')
                        ->orWhere('account', 'like', '%' . $key . '%')
                        ->orderBy('start_time','desc')
                        ->paginate(12)
                ]);
            }elseif ($model=='modal'){
                $options=[];
                if (!empty($request->input('member_type'))){array_push($options,['member_type','=',$request->input('member_type')]);}
                if (!empty($request->input('chat_type'))){array_push($options,['chat_type','=',$request->input('chat_type')]);}
                if (!empty($request->input('is_effective'))){array_push($options,['is_effective','=',$request->input('is_effective')]);}
                if (!empty($request->input('account'))){array_push($options,['account','like','%'.$request->input('account').'%']);}
                if (!empty($request->input('msg_type'))){array_push($options,['msg_type','=',$request->input('msg_type')]);}
                if (!empty($request->input('area'))){array_push($options,['area','=',$request->input('area')]);}
                if (!empty($request->input('is_contact'))){array_push($options,['is_contact','=',$request->input('is_contact')]);}
                if (!empty($request->input('author'))){array_push($options,['author','=',$request->input('author')]);}
                if (!empty($request->input('engine_from'))){array_push($options,['engine_from','=',$request->input('engine_from')]);}
                if (!empty($request->input('device'))){array_push($options,['device','=',$request->input('device')]);}
                if (!empty($request->input('keyword'))){array_push($options,['keyword','like','%'.$request->input('keyword').'%']);}
                if (!empty($request->input('title'))){array_push($options,['title','like','%'.$request->input('title').'%']);}
                if (!empty($request->input('url'))){array_push($options,['url','like','%'.$request->input('url').'%']);}
                if (!empty($request->input('swt_id'))){array_push($options,['swt_id','like','%'.$request->input('swt_id').'%']);}

                if (!empty($request->input('time_start'))){array_push($options,['start_time','>=',Carbon::createFromFormat('Y-m-d',$request->input('time_start'))->startOfDay()]);}
                if (!empty($request->input('time_end'))){array_push($options,['start_time','<=',Carbon::createFromFormat('Y-m-d',$request->input('time_end'))->endOfDay()]);}

                $members= Swt::where($options);
                return view('swt.index', [
                    'memberTypes' => Swt::getMemberType(),
                    'chatTypes' => Swt::getChatType(),
                    'msgTypes' => Swt::getMsgType(),
                    'accounts' => Swt::getAccount(),
                    'areas' => Swt::getAreas(),
                    'authors' => Swt::getAuthors(),
                    'engineFroms' => Swt::getEngineFroms(),
                    'devices' => Swt::getDevices(),
                    'swts' => $members->orderBy('start_time','desc')->paginate(12)
                ]);
            }else {
                return redirect()->route('swt.index');
            }
        }
        abort(403);

    }
    public function getInfos(Request $request)
    {
        $mid = $request->input('mid');
        $member = Swt::find($mid);
        $data = [
            'status' => 0,
        ];
        if ($member) {
            $data['swt']['id'] = $member->id;
            $data['swt']['sid'] = $member->sid;
            $data['swt']['swt_id'] = $member->swt_id;
            $data['swt']['start_time'] = !empty($member->start_time)?$member->start_time:'';
            $data['swt']['author'] = $member->author;
            $data['swt']['authors'] = $member->authors;
            $data['swt']['msg_num'] = $member->msg_num;
            $data['swt']['member_type'] = $member->member_type;
            $data['swt']['msg_type'] = $member->msg_type;
            $data['swt']['chat_type'] = $member->chat_type;
            $data['swt']['url'] = $member->url;
            $data['swt']['keyword'] = $member->keyword;
            $data['swt']['area'] = $member->area;
            $data['swt']['title'] = $member->title;
            $data['swt']['engine'] = $member->engine;
            $data['swt']['engine_from'] = $member->engine_from;
            $data['swt']['os'] = $member->os;
            $data['swt']['device'] = $member->device=='mobile'?'移动':'PC';
            $data['swt']['account'] = $member->account;
            $data['swt']['is_effective'] = $member->is_effective==1?'有效对话':'无效对话';
            $data['swt']['is_contact'] = $member->is_contact==1?'留联':'';
            $data['status'] = 1;
        }
        return response()->json($data);
    }
}
