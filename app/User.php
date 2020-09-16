<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',  'password', 'display_name','tell','status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @param Http\Requests\storeUserRequest $request
     * @return User
     */
    public static function createUser(Http\Requests\storeUserRequest $request)
    {
        $user=new User();
        $user->name=$request->input('name');
        $user->display_name=$request->input('display_name');
        $user->tell=$request->input('tell');
        $user->email=$request->input('email');
        $user->password=Hash::make($request->input('password'));
        $user->is_active=$request->input('is_active');
        $user->save();
        $user->hospitals()->sync($request->input('hospitals'));
        $user->syncRoles($request->input('roles'));
        return $user;
    }

    /**
     * @param Http\Requests\storeUserRequest $request
     * @param int $id
     * @return mixed
     */
    public static function updateUser(Http\Requests\storeUserRequest $request, int $id)
    {
        $user=User::findOrFail($id);
        $user->display_name=$request->input('display_name');
        $user->tell=$request->input('tell');
        $user->email=$request->input('email');
        $user->is_active=$request->input('is_active');
        if (!empty($request->password)){
            $user->password=Hash::make($request->input('password'));
        }
        $user->save();
        $user->hospitals()->sync($request->input('hospitals'));
        $user->syncRoles($request->input('roles'));
        return $user;
    }


    public function hospitals()
    {
        return $this->belongsToMany('App\Hospital','user_hospitals','user_id','hospital_id');
    }

    public function userHasHospital($user,$hospitalId)
    {
        $hospitals=$user->hospitals->pluck('id')->toArray();
        return in_array($hospitalId,$hospitals);
    }
}
