<?php

namespace App\Http\Controllers;

use App\Aiden;
use App\Hospital;
use App\Member;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $counts=[];
        return view('home',[
            'counts'=>$counts,
            'hospitalName'=>Hospital::findOrFail(Aiden::getActiveHospitalId(Auth::user()))->display_name
        ]);
    }
}
