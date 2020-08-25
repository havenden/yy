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
        $counts['all']['add']=Member::select('id')->count();
        $counts['all']['should_arrive']=Member::select('id')->whereNotNull('pubdate')->count();
        $counts['all']['arrive']=Member::select('id')->where('condition','1')->count();
        $counts['all']['arrive_rate']=$counts['all']['should_arrive']?sprintf('%.2f',($counts['all']['arrive']/$counts['all']['should_arrive'])*100).'%':'0.00%';
        $counts['tomorrow']['should_arrive']=Member::select('id')->where([
            ['pubdate','>=',Carbon::tomorrow()->startOfDay()],
            ['pubdate','<=',Carbon::tomorrow()->endOfDay()]
        ])->count();
        //today
        $counts['today']['add']=Member::select('id')->where([
            ['created_at','>=',Carbon::now()->startOfDay()],
            ['created_at','<=',Carbon::now()->endOfDay()]
        ])->count();
        $counts['today']['yy']=Member::select('id')->where([
            ['created_at','>=',Carbon::now()->startOfDay()],
            ['created_at','<=',Carbon::now()->endOfDay()]
        ])->whereNotNull('pubdate')->count();
        $counts['today']['should_arrive']=Member::select('id')->where([
            ['pubdate','>=',Carbon::now()->startOfDay()],
            ['pubdate','<=',Carbon::now()->endOfDay()]
        ])->count();
        $counts['today']['arrive']=Member::select('id')->where('condition',1)->where([
            ['okdate','>=',Carbon::now()->startOfDay()],
            ['okdate','<=',Carbon::now()->endOfDay()]
        ])->count();
        $counts['today']['not_arrive']=Member::select('id')->where('condition','<>',1)->where([
            ['pubdate','>=',Carbon::now()->startOfDay()],
            ['pubdate','<=',Carbon::now()->endOfDay()]
        ])->count();
        $counts['today']['arrive_rate']=$counts['today']['should_arrive']?sprintf('%.2f',($counts['today']['arrive']/$counts['today']['should_arrive'])*100).'%':'0.00%';
        //yesterday
        $counts['yesterday']['add']=Member::select('id')->where([
            ['created_at','>=',Carbon::yesterday()->startOfDay()],
            ['created_at','<=',Carbon::yesterday()->endOfDay()]
        ])->count();
        $counts['yesterday']['yy']=Member::select('id')->where([
            ['created_at','>=',Carbon::yesterday()->startOfDay()],
            ['created_at','<=',Carbon::yesterday()->endOfDay()]
        ])->whereNotNull('pubdate')->count();
        $counts['yesterday']['should_arrive']=Member::select('id')->where([
            ['pubdate','>=',Carbon::yesterday()->startOfDay()],
            ['pubdate','<=',Carbon::yesterday()->endOfDay()]
        ])->count();
        $counts['yesterday']['arrive']=Member::select('id')->where('condition',1)->where([
            ['okdate','>=',Carbon::yesterday()->startOfDay()],
            ['okdate','<=',Carbon::yesterday()->endOfDay()]
        ])->count();
        $counts['yesterday']['not_arrive']=Member::select('id')->where('condition','<>',1)->where([
            ['pubdate','>=',Carbon::yesterday()->startOfDay()],
            ['pubdate','<=',Carbon::yesterday()->endOfDay()]
        ])->count();
        $counts['yesterday']['arrive_rate']=$counts['yesterday']['should_arrive']?sprintf('%.2f',($counts['yesterday']['arrive']/$counts['yesterday']['should_arrive'])*100).'%':'0.00%';
        //thisweek
        $counts['thisweek']['add']=Member::select('id')->where([
            ['created_at','>=',Carbon::now()->startOfWeek()],
            ['created_at','<=',Carbon::now()->endOfWeek()]
        ])->count();
        $counts['thisweek']['yy']=Member::select('id')->where([
            ['created_at','>=',Carbon::now()->startOfWeek()],
            ['created_at','<=',Carbon::now()->endOfWeek()]
        ])->whereNotNull('pubdate')->count();
        $counts['thisweek']['should_arrive']=Member::select('id')->where([
            ['pubdate','>=',Carbon::now()->startOfWeek()],
            ['pubdate','<=',Carbon::now()->endOfWeek()]
        ])->count();
        $counts['thisweek']['arrive']=Member::select('id')->where('condition',1)->where([
            ['okdate','>=',Carbon::now()->startOfWeek()],
            ['okdate','<=',Carbon::now()->endOfWeek()]
        ])->count();
        $counts['thisweek']['not_arrive']=Member::select('id')->where('condition','<>',1)->where([
            ['pubdate','>=',Carbon::now()->startOfWeek()],
            ['pubdate','<=',Carbon::now()->endOfWeek()]
        ])->count();
        $counts['thisweek']['arrive_rate']=$counts['thisweek']['should_arrive']?sprintf('%.2f',($counts['thisweek']['arrive']/$counts['thisweek']['should_arrive'])*100).'%':'0.00%';
        //thismonth
        $counts['thismonth']['add']=Member::select('id')->where([
            ['created_at','>=',Carbon::now()->startOfMonth()],
            ['created_at','<=',Carbon::now()->endOfMonth()]
        ])->count();
        $counts['thismonth']['yy']=Member::select('id')->where([
            ['created_at','>=',Carbon::now()->startOfMonth()],
            ['created_at','<=',Carbon::now()->endOfMonth()]
        ])->whereNotNull('pubdate')->count();
        $counts['thismonth']['should_arrive']=Member::select('id')->where([
            ['pubdate','>=',Carbon::now()->startOfMonth()],
            ['pubdate','<=',Carbon::now()->endOfMonth()]
        ])->count();
        $counts['thismonth']['arrive']=Member::select('id')->where('condition',1)->where([
            ['okdate','>=',Carbon::now()->startOfMonth()],
            ['okdate','<=',Carbon::now()->endOfMonth()]
        ])->count();
        $counts['thismonth']['not_arrive']=Member::select('id')->where('condition','<>',1)->where([
            ['pubdate','>=',Carbon::now()->startOfMonth()],
            ['pubdate','<=',Carbon::now()->endOfMonth()]
        ])->count();
        $counts['thismonth']['arrive_rate']=$counts['thismonth']['should_arrive']?sprintf('%.2f',($counts['thismonth']['arrive']/$counts['thismonth']['should_arrive'])*100).'%':'0.00%';
        //lastmonthtoday
        $counts['lastmonthtoday']['add']=Member::select('id')->where([
            ['created_at','>=',(new Carbon('last month'))->startOfDay()],
            ['created_at','<=',(new Carbon('last month'))->endOfDay()]
        ])->count();
        $counts['lastmonthtoday']['yy']=Member::select('id')->where([
            ['created_at','>=',(new Carbon('last month'))->startOfDay()],
            ['created_at','<=',(new Carbon('last month'))->endOfDay()]
        ])->whereNotNull('pubdate')->count();
        $counts['lastmonthtoday']['should_arrive']=Member::select('id')->where([
            ['pubdate','>=',(new Carbon('last month'))->startOfDay()],
            ['pubdate','<=',(new Carbon('last month'))->endOfDay()]
        ])->count();
        $counts['lastmonthtoday']['arrive']=Member::select('id')->where('condition',1)->where([
            ['okdate','>=',(new Carbon('last month'))->startOfDay()],
            ['okdate','<=',(new Carbon('last month'))->endOfDay()]
        ])->count();
        $counts['lastmonthtoday']['not_arrive']=Member::select('id')->where('condition','<>',1)->where([
            ['pubdate','>=',(new Carbon('last month'))->startOfDay()],
            ['pubdate','<=',(new Carbon('last month'))->endOfDay()]
        ])->count();
        $counts['lastmonthtoday']['arrive_rate']=$counts['lastmonthtoday']['should_arrive']?sprintf('%.2f',($counts['lastmonthtoday']['arrive']/$counts['lastmonthtoday']['should_arrive'])*100).'%':'0.00%';
        //lastmonth
        $counts['lastmonth']['add']=Member::select('id')->where([
            ['created_at','>=',Carbon::now()->subMonths(1)->startOfMonth()],
            ['created_at','<=',Carbon::now()->subMonths(1)->endOfMonth()]
        ])->count();
        $counts['lastmonth']['yy']=Member::select('id')->where([
            ['created_at','>=',Carbon::now()->subMonths(1)->startOfMonth()],
            ['created_at','<=',Carbon::now()->subMonths(1)->endOfMonth()]
        ])->whereNotNull('pubdate')->count();
        $counts['lastmonth']['should_arrive']=Member::select('id')->where([
            ['pubdate','>=',Carbon::now()->subMonths(1)->startOfMonth()],
            ['pubdate','<=',Carbon::now()->subMonths(1)->endOfMonth()]
        ])->count();
        $counts['lastmonth']['arrive']=Member::select('id')->where('condition',1)->where([
            ['okdate','>=',Carbon::now()->subMonths(1)->startOfMonth()],
            ['okdate','<=',Carbon::now()->subMonths(1)->endOfMonth()]
        ])->count();
        $counts['lastmonth']['not_arrive']=Member::select('id')->where('condition','<>',1)->where([
            ['pubdate','>=',Carbon::now()->subMonths(1)->startOfMonth()],
            ['pubdate','<=',Carbon::now()->subMonths(1)->endOfMonth()]
        ])->count();
        $counts['lastmonth']['arrive_rate']=$counts['lastmonth']['should_arrive']?sprintf('%.2f',($counts['lastmonth']['arrive']/$counts['lastmonth']['should_arrive'])*100).'%':'0.00%';
        return view('home',[
            'counts'=>$counts,
            'hospitalName'=>Hospital::findOrFail(Aiden::getActiveHospitalId(Auth::user()))->display_name
        ]);
    }
}
