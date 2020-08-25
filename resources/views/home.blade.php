@extends('layouts.app')

@section('content')
<style>
.align-middle{vertical-align: middle !important;}
.text-white{color:#fff !important;}
.bg-yellow{background-color:#f8ac59 !important;}
</style>
    <div class="table-responsive">
        <table class="table table-sm table-bordered table-striped table-center w-100">
            <thead>
            <tr class="text-center">
                <th scope="col" class="align-middle">所有({{ $counts['all']['add'] }})</th>
                <th scope="col" class="align-middle">今天</th>
                <th scope="col" class="align-middle">昨天</th>
                <th scope="col" class="align-middle">本周</th>
                <th scope="col" class="align-middle">本月</th>
                <th scope="col" class="align-middle">同期</th>
                <th scope="col" class="align-middle">上月</th>
            </tr>
            </thead>
            <tbody>
            <tr class="text-center">
                <th class="align-middle">日期</th>
                <td class="align-middle">{{ \Carbon\Carbon::now()->toDateString() }}</td>
                <td class="align-middle">{{ \Carbon\Carbon::yesterday()->toDateString() }}</td>
                <td class="align-middle">{{ \Carbon\Carbon::now()->startOfWeek()->toDateString() }}起</td>
                <td class="align-middle">{{ \Carbon\Carbon::now()->startOfMonth()->format('Y-m') }}</td>
                <td class="align-middle">{{ (new \Carbon\Carbon('last month'))->toDateString() }}</td>
                <td class="align-middle">{{ \Carbon\Carbon::now()->subMonths(1)->startOfMonth()->format('Y-m') }}</td>
            </tr>

            <tr class="text-center">
                <th class="align-middle">登记</th>
                <td class="align-middle"><span class="bg-info px-2">{{ !empty($counts)?$counts['today']['add']:'0' }}</span></td>
                <td class="align-middle"><span class="bg-info px-2">{{ !empty($counts)?$counts['yesterday']['add']:'0' }}</span></td>
                <td class="align-middle"><span class="bg-info px-2">{{ !empty($counts)?$counts['thisweek']['add']:'0' }}</span></td>
                <td class="align-middle"><span class="bg-info px-2">{{ !empty($counts)?$counts['thismonth']['add']:'0' }}</span></td>
                <td class="align-middle"><span class="bg-info px-2">{{ !empty($counts)?$counts['lastmonthtoday']['add']:'0' }}</span></td>
                <td class="align-middle"><span class="bg-info px-2">{{ !empty($counts)?$counts['lastmonth']['add']:'0' }}</span></td>
            </tr>

            <tr class="text-center">
                <th class="align-middle">应到</th>
                <td class="align-middle"><span class="bg-yellow px-2 text-white">{{ !empty($counts)?$counts['today']['should_arrive']:'0' }}</span></td>
                <td class="align-middle"><span class="bg-yellow px-2 text-white">{{ !empty($counts)?$counts['yesterday']['should_arrive']:'0' }}</span></td>
                <td class="align-middle"><span class="bg-yellow px-2 text-white">{{ !empty($counts)?$counts['thisweek']['should_arrive']:'0' }}</span></td>
                <td class="align-middle"><span class="bg-yellow px-2 text-white">{{ !empty($counts)?$counts['thismonth']['should_arrive']:'0' }}</span></td>
                <td class="align-middle"><span class="bg-yellow px-2 text-white">{{ !empty($counts)?$counts['lastmonthtoday']['should_arrive']:'0' }}</span></td>
                <td class="align-middle"><span class="bg-yellow px-2 text-white">{{ !empty($counts)?$counts['lastmonth']['should_arrive']:'0' }}</span></td>
            </tr>

            <tr class="text-center">
                <th class="align-middle">实到</th>
                <td class="align-middle"><span class="bg-danger px-2">{{ !empty($counts)?$counts['today']['arrive']:'0' }}</span></td>
                <td class="align-middle"><span class="bg-danger px-2">{{ !empty($counts)?$counts['yesterday']['arrive']:'0' }}</span></td>
                <td class="align-middle"><span class="bg-danger px-2">{{ !empty($counts)?$counts['thisweek']['arrive']:'0' }}</span></td>
                <td class="align-middle"><span class="bg-danger px-2">{{ !empty($counts)?$counts['thismonth']['arrive']:'0' }}</span></td>
                <td class="align-middle"><span class="bg-danger px-2">{{ !empty($counts)?$counts['lastmonthtoday']['arrive']:'0' }}</span></td>
                <td class="align-middle"><span class="bg-danger px-2">{{ !empty($counts)?$counts['lastmonth']['arrive']:'0' }}</span></td>
            </tr>

            <tr class="text-center">
                <th class="align-middle">未到</th>
                <td class="align-middle"><span class="bg-gray px-2">{{ !empty($counts)?$counts['today']['not_arrive']:'0' }}</span></td>
                <td class="align-middle"><span class="bg-gray px-2">{{ !empty($counts)?$counts['yesterday']['not_arrive']:'0' }}</span></td>
                <td class="align-middle"><span class="bg-gray px-2">{{ !empty($counts)?$counts['thisweek']['not_arrive']:'0' }}</span></td>
                <td class="align-middle"><span class="bg-gray px-2">{{ !empty($counts)?$counts['thismonth']['not_arrive']:'0' }}</span></td>
                <td class="align-middle"><span class="bg-gray px-2">{{ !empty($counts)?$counts['lastmonthtoday']['not_arrive']:'0' }}</span></td>
                <td class="align-middle"><span class="bg-gray px-2">{{ !empty($counts)?$counts['lastmonth']['not_arrive']:'0' }}</span></td>
            </tr>
            <tr class="text-center">
                <th class="align-middle">实到率</th>
                <td class="align-middle">{{ !empty($counts)?$counts['today']['arrive_rate']:'0.00%' }}</td>
                <td class="align-middle">{{ !empty($counts)?$counts['yesterday']['arrive_rate']:'0.00%' }}</td>
                <td class="align-middle">{{ !empty($counts)?$counts['thisweek']['arrive_rate']:'0.00%' }}</td>
                <td class="align-middle">{{ !empty($counts)?$counts['thismonth']['arrive_rate']:'0.00%' }}</td>
                <td class="align-middle">{{ !empty($counts)?$counts['lastmonthtoday']['arrive_rate']:'0.00%' }}</td>
                <td class="align-middle">{{ !empty($counts)?$counts['lastmonth']['arrive_rate']:'0.00%' }}</td>
            </tr>

            </tbody>
        </table>
    </div>
    <div class="box-group row">
        <div class="col-md-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title" style="font-size: 1rem;">登记</h3>
                    <div class="card-tools">
                        <span class="badge badge-success">今日</span>
                    </div>
                </div>
                <div class="card-body text-center h3">
                    <a href="{{ route('member.search',['model'=>'modal','created_at_start'=>\Carbon\Carbon::now()->startOfDay()->toDateString(),'created_at_end'=>\Carbon\Carbon::now()->endOfDay()->toDateString()]) }}" class="text-info">{{ !empty($counts)?$counts['today']['add']:'0' }}</a>
                </div>

            </div>
        </div>
        <div class="col-md-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title" style="font-size: 1rem;">应到</h3>
                    <div class="card-tools">
                        <span class="badge badge-primary">今日</span>
                    </div>
                </div>
                <div class="card-body text-center h3 text-info">
                    <a href="{{ route('member.search',['model'=>'modal','pubdate_start'=>\Carbon\Carbon::now()->startOfDay()->toDateString(),'pubdate_end'=>\Carbon\Carbon::now()->endOfDay()->toDateString()]) }}" class="text-info">{{ !empty($counts)?$counts['today']['should_arrive']:'0' }}</a>
                </div>

            </div>
        </div>
        <div class="col-md-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title" style="font-size: 1rem;">实到</h3>
                    <div class="card-tools">
                        <span class="badge badge-danger">今日</span>
                    </div>
                </div>
                <div class="card-body text-center h3 text-info">
                    <a href="{{ route('member.search',['model'=>'modal','okdate_start'=>\Carbon\Carbon::now()->startOfDay()->toDateString(),'okdate_end'=>\Carbon\Carbon::now()->endOfDay()->toDateString()]) }}" class="text-info">{{ !empty($counts)?$counts['today']['arrive']:'0' }}</a>
                </div>

            </div>
        </div>
        <div class="col-md-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title" style="font-size: 1rem;">预约</h3>
                    <div class="card-tools">
                        <span class="badge badge-success">总</span>
                    </div>
                </div>
                <div class="card-body text-center h3">
                    <a href="{{ route('member.search',['model'=>'modal','pubdate'=>'all']) }}" class="text-info">{{ !empty($counts)?$counts['all']['should_arrive']:'0' }}</a>
                </div>

            </div>
        </div>
        <div class="col-md-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title" style="font-size: 1rem;">实到</h3>
                    <div class="card-tools">
                        <span class="badge badge-info">总</span>
                    </div>
                </div>
                <div class="card-body text-center h3 text-info">
                    <a href="{{ route('member.search',['model'=>'modal','condition'=>1]) }}" class="text-info">{{ !empty($counts)?$counts['all']['arrive']:'0' }}</a>
                </div>

            </div>
        </div>
        <div class="col-md-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title" style="font-size: 1rem;">实到率</h3>
                    <div class="card-tools">
                        <span class="badge badge-danger">总</span>
                    </div>
                </div>
                <div class="card-body text-center h3 text-gray">
                    {{ !empty($counts)?$counts['all']['arrive_rate']:'0.00%' }}
                </div>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header border-transparent">
                    <h3 class="card-title">{{ $hospitalName }}</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table m-0 table-bordered">
                            <thead>
                            <tr class="text-center">
                                <th>日期</th>
                                <th>登记</th>
                                <th>预约</th>
                                <th>应到</th>
                                <th>实到</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr class="text-center">
                                <td>今天</td>
                                <td><a href="{{ route('member.search',['model'=>'modal','created_at_start'=>\Carbon\Carbon::now()->startOfDay()->toDateString(),'created_at_end'=>\Carbon\Carbon::now()->endOfDay()->toDateString()]) }}" class="text-info">{{ !empty($counts)?$counts['today']['add']:0 }}</a></td>
                                <td><a href="{{ route('member.search',['model'=>'modal','created_at_start'=>\Carbon\Carbon::now()->startOfDay()->toDateString(),'created_at_end'=>\Carbon\Carbon::now()->endOfDay()->toDateString(),'pudate'=>'all']) }}" class="text-info">{{ !empty($counts)?$counts['today']['yy']:0 }}</a></td>
                                <td><a href="{{ route('member.search',['model'=>'modal','pubdate_start'=>\Carbon\Carbon::now()->startOfDay()->toDateString(),'pubdate_end'=>\Carbon\Carbon::now()->endOfDay()->toDateString()]) }}" class="text-info">{{ !empty($counts)?$counts['today']['should_arrive']:0 }}</a></td>
                                <td><a href="{{ route('member.search',['model'=>'modal','okdate_start'=>\Carbon\Carbon::now()->startOfDay()->toDateString(),'okdate_end'=>\Carbon\Carbon::now()->endOfDay()->toDateString()]) }}" class="text-info">{{ !empty($counts)?$counts['today']['arrive']:0 }}</a></td>
                            </tr>
                            <tr class="text-center">
                                <td>昨天</td>
                                <td><a href="{{ route('member.search',['model'=>'modal','created_at_start'=>\Carbon\Carbon::yesterday()->startOfDay()->toDateString(),'created_at_end'=>\Carbon\Carbon::yesterday()->endOfDay()->toDateString()]) }}" class="text-info">{{ !empty($counts)?$counts['yesterday']['add']:0 }}</a></td>
                                <td><a href="{{ route('member.search',['model'=>'modal','created_at_start'=>\Carbon\Carbon::yesterday()->startOfDay()->toDateString(),'created_at_end'=>\Carbon\Carbon::yesterday()->endOfDay()->toDateString(),'pudate'=>'all']) }}" class="text-info">{{ !empty($counts)?$counts['yesterday']['yy']:0 }}</a></td>
                                <td><a href="{{ route('member.search',['model'=>'modal','pubdate_start'=>\Carbon\Carbon::yesterday()->startOfDay()->toDateString(),'pubdate_end'=>\Carbon\Carbon::yesterday()->endOfDay()->toDateString()]) }}" class="text-info">{{ !empty($counts)?$counts['yesterday']['should_arrive']:0 }}</a></td>
                                <td><a href="{{ route('member.search',['model'=>'modal','okdate_start'=>\Carbon\Carbon::yesterday()->startOfDay()->toDateString(),'okdate_end'=>\Carbon\Carbon::yesterday()->endOfDay()->toDateString()]) }}" class="text-info">{{ !empty($counts)?$counts['yesterday']['arrive']:0 }}</a></td>
                            </tr>
                            <tr class="text-center">
                                <td>本月</td>
                                <td><a href="{{ route('member.search',['model'=>'modal','created_at_start'=>\Carbon\Carbon::now()->startOfMonth()->toDateString(),'created_at_end'=>\Carbon\Carbon::now()->endOfMonth()->toDateString()]) }}" class="text-info">{{ !empty($counts)?$counts['thismonth']['add']:0 }}</a></td>
                                <td><a href="{{ route('member.search',['model'=>'modal','created_at_start'=>\Carbon\Carbon::now()->startOfMonth()->toDateString(),'created_at_end'=>\Carbon\Carbon::now()->endOfMonth()->toDateString(),'pudate'=>'all']) }}" class="text-info">{{ !empty($counts)?$counts['thismonth']['yy']:0 }}</a></td>
                                <td><a href="{{ route('member.search',['model'=>'modal','pubdate_start'=>\Carbon\Carbon::now()->startOfMonth()->toDateString(),'pubdate_end'=>\Carbon\Carbon::now()->endOfMonth()->toDateString()]) }}" class="text-info">{{ !empty($counts)?$counts['thismonth']['should_arrive']:0 }}</a></td>
                                <td><a href="{{ route('member.search',['model'=>'modal','okdate_start'=>\Carbon\Carbon::now()->startOfMonth()->toDateString(),'okdate_end'=>\Carbon\Carbon::now()->endOfMonth()->toDateString()]) }}" class="text-info">{{ !empty($counts)?$counts['thismonth']['arrive']:0 }}</a></td>
                            </tr>
                            <tr class="text-center">
                                <td>上月</td>
                                <td><a href="{{ route('member.search',['model'=>'modal','created_at_start'=>\Carbon\Carbon::now()->subMonths(1)->startOfMonth()->toDateString(),'created_at_end'=>\Carbon\Carbon::now()->subMonths(1)->endOfMonth()->toDateString()]) }}" class="text-info">{{ !empty($counts)?$counts['lastmonth']['add']:0 }}</a></td>
                                <td><a href="{{ route('member.search',['model'=>'modal','created_at_start'=>\Carbon\Carbon::now()->subMonths(1)->startOfMonth()->toDateString(),'created_at_end'=>\Carbon\Carbon::now()->subMonths(1)->endOfMonth()->toDateString(),'pudate'=>'all']) }}" class="text-info">{{ !empty($counts)?$counts['lastmonth']['yy']:0 }}</a></td>
                                <td><a href="{{ route('member.search',['model'=>'modal','pubdate_start'=>\Carbon\Carbon::now()->subMonths(1)->startOfMonth()->toDateString(),'pubdate_end'=>\Carbon\Carbon::now()->subMonths(1)->endOfMonth()->toDateString()]) }}" class="text-info">{{ !empty($counts)?$counts['lastmonth']['should_arrive']:0 }}</a></td>
                                <td><a href="{{ route('member.search',['model'=>'modal','okdate_start'=>\Carbon\Carbon::now()->subMonths(1)->startOfMonth()->toDateString(),'okdate_end'=>\Carbon\Carbon::now()->subMonths(1)->endOfMonth()->toDateString()]) }}" class="text-info">{{ !empty($counts)?$counts['lastmonth']['arrive']:0 }}</a></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->
                </div>
                <!-- /.card-body -->
                <div class="mt-1 table-responsive">
                    <table class="table m-0 table-bordered">
                        <tr class="text-left">
                            <td>上月环比: <span class="{{ ($counts['thismonth']['arrive']-$counts['lastmonth']['arrive'])>0?'text-info':'text-danger' }}">{{ $counts['thismonth']['arrive']-$counts['lastmonth']['arrive'] }}</span>&nbsp;&nbsp;&nbsp;本月实到率: {{ $counts['thismonth']['arrive_rate'] }}</td>
                            <td>明日应到：<a href="{{ route('member.search',['model'=>'modal','pubdate_start'=>\Carbon\Carbon::tomorrow()->startOfDay()->toDateString(),'pubdate_end'=>\Carbon\Carbon::tomorrow()->endOfDay()->toDateString()]) }}" class="text-info">{{ $counts['tomorrow']['should_arrive'] }}</a></td>
                        </tr>
                    </table>
                </div>
                <!-- /.card-footer -->
            </div>
        </div>
    </div>
@endsection
