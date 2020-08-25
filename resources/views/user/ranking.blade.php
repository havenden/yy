@extends('layouts.app')

@section('content')
    <div class="mb-3 bg-light">
        <form class="form-inline" method="post" action="{{ route('user.ranking') }}">
            {{method_field('POST')}}
            {{csrf_field()}}
            <div class="form-group mx-sm-3 mb-2">
                <input type="text" class="form-control item-date" name="startTime" id="startTime" placeholder="开始时间" value="{{ empty($data['startTime'])?'': $data['startTime']}}">
            </div>
            <div class="form-group mx-sm-3 mb-2">
                <input type="text" class="form-control item-date" name="endTime" id="endTime" placeholder="结束时间" value="{{ empty($data['endTime'])?'': $data['endTime']}}">
            </div>
            <button type="submit" class="btn btn-primary mb-2">查询</button>
        </form>
    </div>

    <div class="row md-2">
        <div class="col-md-4">
            <div class="card p-0">
                <div class="card-header bg-info">
                    预约排行TOP 10
                </div>
                <div class="card-body p-3">
                    <ul class="list-group">
                        @if(!empty($data['yy']))
                            @foreach($data['yy'] as $yy)
                                <li class="list-group-item">{{ !empty($users[$yy['uid']])?$users[$yy['uid']]:'' }}<span class="right badge badge-primary float-right">{{ $yy['count'] }}</span></li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        </div>


        <div class="col-md-4">
            <div class="card p-0">
                <div class="card-header bg-info">
                    实到排行TOP 10
                </div>
                <div class="card-body p-3">
                    <ul class="list-group">
                        @if(!empty($data['arrive']))
                            @foreach($data['arrive'] as $arrive)
                                <li class="list-group-item">{{ !empty($users[$arrive['uid']])?$users[$arrive['uid']]:'' }}<span class="right badge badge-primary float-right">{{ $arrive['count'] }}</span></li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-0">
                <div class="card-header bg-info">
                    流失数量TOP 10
                </div>
                <div class="card-body p-3">
                    <ul class="list-group">
                        @if(!empty($data['lost']))
                            @foreach($data['lost'] as $lost)
                                <li class="list-group-item">{{ !empty($users[$lost['uid']])?$users[$lost['uid']]:'' }}<span class="right badge badge-primary float-right">{{ $lost['count'] }}</span></li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('javascripts')
    <script src="{{ asset('plugins/laydate/laydate.js') }}"></script>
    <script>
        $('.item-date').each(function(){
            laydate.render({
                elem: this,
                trigger: 'click',
                type:'date',
                format: 'yyyy-MM-dd',
            });
        });
        function input_date(id, value)
        {
            var cv = document.getElementById(id).value;
            var time = cv.split(" ")[1];

            if (document.getElementById(id).disabled != true) {
                document.getElementById(id).value = value;
            }
        };

        function input_time(id, time)
        {
            var s = document.getElementById(id).value;
            if (s == '') {
                layer.alert("请先填写日期，再填写时间！");
                return;
            }
            var date = s.split(" ")[0];
            var datetime = date+" "+time;

            if (document.getElementById(id).disabled != true) {
                document.getElementById(id).value = datetime;
            }
        };

    </script>
@endsection