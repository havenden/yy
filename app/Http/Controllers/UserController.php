<?php

namespace App\Http\Controllers;

use App\Aiden;
use App\Hospital;
use App\Http\Requests\storeUserRequest;
use App\Member;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->id==1||Auth::user()->hasPermissionTo('users_read')){
            return view('user.index',
                [
                    'users' => User::select('id','name', 'display_name','is_active','tell')->paginate(18),
                ]
            );
        }
        return abort(401,'权限不足');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('users_create')) {
            return view('user.create',[
                'roles'=>Role::all(),
                'hospitals'=>Hospital::pluck('display_name','id'),
            ]);
        }
        abort(401,'权限不足');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(storeUserRequest $request)
    {
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('users_create')){
            $user=User::createUser($request);
            if ($user) {
                return redirect()->route('user.index')->with('success', '添加成功！');
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
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('users_update')){
            return view('user.update',[
                'user'=>User::findOrFail($id),
                'roles'=>Role::all(),
                'hospitals'=>Hospital::pluck('display_name','id'),
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
    public function update(storeUserRequest $request, $id)
    {
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('users_update')){
            if (User::updateUser($request,$id)) {
                return redirect()->route('user.index')->with('success', '更新成功！');
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
    public function destroy(Request $request,$id)
    {
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('users_delete')){
            $user=User::findOrFail($id);
            $user->hospitals()->sync([]);
            if (User::destroy($id)) {
                return redirect($request->headers->get('referer'))->with('success', '删除成功！');
            }
        }
        abort(401);
    }
    public function search(Request $request)
    {
        if (Auth::user()->id===1||Auth::user()->hasPermissionTo('users_read')) {
            $key = $request->input('key');
            if (!empty($key)) {
                return view('user.index', [
                    'key' => $key,
                    'users' => User::select('id','name', 'display_name','is_active','tell')
                        ->where('name', 'like', '%' . $key . '%')
                        ->orWhere('display_name', 'like', '%' . $key . '%')
                        ->orderBy('id','desc')
                        ->paginate(12)
                ]);
            }else {
                return redirect()->route('user.index');
            }
        }
        abort(403);
    }

    /**
     * 客服排行
     * @param string $startTime
     * @param string $endTime
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public function ranking(Request $request)
    {
        if(Auth::user()->id==1||Auth::user()->hasPermissionTo('users_read')){
            $startTime=$request->get('startTime');
            $endTime=$request->get('endTime');
            if($request->getMethod()=='POST'){
                $data['startTime']=$startTime;
                $data['endTime']=$endTime;
            }
            $startTime=empty($startTime)?Carbon::now()->startOfMonth()->startOfDay()->toDateTimeString():Carbon::createFromFormat('Y-m-d',$startTime)->startOfDay()->toDateTimeString();
            $endTime=empty($endTime)?Carbon::now()->endOfDay()->toDateTimeString():Carbon::createFromFormat('Y-m-d',$endTime)->endOfDay()->toDateTimeString();
//            dd($startTime.'-----------'.$endTime);
            $yy=Member::select('uid',DB::raw('count(*) as count'))->where([
                ['order_date', '>=', $startTime],
                ['order_date', '<=', $endTime],
            ])->groupBy('uid')->orderBy('count','desc')->limit(10)->get()->toArray();
            $arrive=Member::select('uid',DB::raw('count(*) as count'))->where([
                ['ok_date', '>=', $startTime],
                ['ok_date', '<=', $endTime],
            ])->groupBy('uid')->orderBy('count','desc')->limit(10)->get()->toArray();
            $lost=Member::select('uid',DB::raw('count(*) as count'))->where('condition','!=',1)->where([
                ['order_date', '>=', $startTime],
                ['order_date', '<=', $endTime],
            ])->groupBy('uid')->orderBy('count','desc')->limit(10)->get()->toArray();
            $data['yy']=$yy;
            $data['arrive']=$arrive;
            $data['lost']=$lost;
            return view('user.ranking',
                [
                    'data' => $data,
                    'users'=>Aiden::getAllUsersArray()
                ]
            );
        }
        return abort(401,'权限不足');
    }
}
