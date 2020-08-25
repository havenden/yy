@extends('layouts.app')
@section('css')
    <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
@endsection
@section('content')
    @include('layouts.tip')
    <script type="text/javascript">
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
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                @can('members_create')<a href="{{ route('member.create') }}" class="btn btn-sm btn-info mt-3">添加</a>@endcan
                <a href="javascript:;" class="btn btn-sm btn-success mt-3" data-toggle="modal" data-target="#searchModal">筛选</a>
                <div class="btn-group mt-3">
                    <form action="{{ route('member.search') }}" method="get" id="quick-search-form">
                        {{csrf_field()}}
                        <input type="hidden" name="model" value="quick">
                        <input type="hidden" name="type" value="">
                    <a href="javascript:;" class="btn btn-sm btn-default quickModel" data-type="today-add">今日登记</a>
                    <a href="javascript:;" class="btn btn-sm btn-default quickModel" data-type="today-arrive">今日应到</a>
                    <a href="javascript:;" class="btn btn-sm btn-default quickModel" data-type="today-arrived">今日已到</a>
                    <a href="javascript:;" class="btn btn-sm btn-default quickModel" data-type="tomorrow-arrive">明日应到</a>
                    </form>
                </div>
            </h3>
            <div class="card-tools mt-3">
                <form action="{{ route('member.search') }}" method="get" id="search-form">
                    {{csrf_field()}}
                <div class="input-group input-group-sm" style="width: 200px;">
                    <input type="text" name="key" class="form-control float-right" placeholder="姓名/预约号/电话/微信/地区">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                    </div>
                </div>
                </form>
            </div>
        </div>
        <form action="{{ route('member.condition') }}" id="member-condition-form" method="post">
            {{csrf_field()}}
            <input type="hidden" name="mid">
            <input type="hidden" name="condition">
        </form>
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0 table-hover" style=" min-height: 26rem;">
            <form action="" method="post" class="member-form" id="member-form">
                {{method_field('DELETE')}}
                {{csrf_field()}}
            <table class="table  table-bordered">
                <thead>
                <tr class="text-center bg-light text-nowrap">
                    <th style="">姓名</th>
                    <th style="">年龄</th>
                    <th style="">联系方式</th>
                    <th style="">微信/qq</th>
                    <th style="">预约号</th>
                    <th style="">所属项目</th>
                    <th style="">添加时间</th>
                    <th style="">预约时间</th>
                    <th style="">到诊时间</th>
                    <th style="">咨询员</th>
                    <th style="">摘要</th>
                    <th style="">咨询方式</th>
                    @can('tracks_create')
                    <th style="">回访</th>
                    @endcan
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @if(!empty($members))
                @foreach($members as $member)
                <tr class="text-center text-nowrap {{ $member->condition==1?'text-danger':(empty($member->pubdate)&&\Carbon\Carbon::now()->diffInDays($member->created_at)>15?'text-muted':'') }}">
                    <td><a href="javascript:;" class="text-info member-info" data-toggle="modal" data-mid="{{ $member->id }}" data-target="#memberInfoModal">{{ $member->name }}</a></td>
                    <td>{{ $member->age }}</td>
                    <td>{{ $member->tell }}</td>
                    <td>{{ $member->wechat }}</td>
                    <td>{{ $member->yy_num }}</td>
                    <td>{{ isset($hospitals[$member->hid])?$hospitals[$member->hid]:'' }}</td>
                    <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$member->created_at)->format('Y-m-d H:i') }}</td>
                    <td>{{ !empty($member->pubdate)?\Carbon\Carbon::create($member->pubdate)->format('Y-m-d H:i'):$member->pubdate }}</td>
                    <td>{{ !empty($member->okdate)?\Carbon\Carbon::create($member->okdate)->format('Y-m-d H:i'):$member->okdate }}</td>
                    <td>{{ isset($users[$member->uid])?$users[$member->uid]:'不存在' }}</td>
                    <td data-toggle="tooltip" data-placement="top" title="{{\Str::limit($member->description,78)}}">{{ \Str::limit($member->description,20) }}</td>
                    <td>{{ isset($consults[$member->consult])?$consults[$member->consult]:'' }}</td>
                    @can('tracks_create')
                    @if($member->tracks->count()>0)
                    <td><a href="javascript:;" class="btn-sm btn-info track" data-toggle="modal" data-mid="{{ $member->id }}" data-target="#trackModal">已回访</a></td>
                    @else
                    <td><a href="javascript:;" class="btn-sm btn-danger track" data-toggle="modal"  data-mid="{{ $member->id }}" data-target="#trackModal">未回访</a></td>
                    @endif
                    @endcan
{{--                    <td>{{ isset($conditionsArray[$member->condition])?$conditionsArray[$member->condition]:'' }}</td>--}}
                    <td data-id="{{ $member->id }}" class="dropdown">
                        @can('members_update')
                        <a href="{{ route('member.edit',$member->id) }}"  title="编辑" data-toggle="tooltip" data-placement="top"><i class="fas fa-edit btn btn-sm btn-info"></i></a>
                        @endcan
                        @can('members_delete')
                        <a href="javascript:void(0);" data-id="{{$member->id}}"  title="删除" data-toggle="tooltip" data-placement="top" class="delete-operation"><i class="fas fa-trash-alt btn btn-sm btn-danger"></i></a>
                        @endcan
                        @can('members_update')
                            <a class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">状态</a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    @if(isset($conditions))
                                    @foreach($conditions as $condition)
                                    <a class="dropdown-item condition-operation {{ isset($member)&&$member->condition==$condition->id?'active':'' }}" href="#" data-id="{{$member->id}}" data-condition="{{ $condition->id }}">{{ $condition->display_name }}</a>
                                    @endforeach
                                    @endif
                                </div>
                        @endcan
                    </td>
                </tr>
                @endforeach
                @endif
                </tbody>
            </table>
            </form>
        </div>
        <!-- /.card-body -->
        <div class="card-footer clearfix">
            <ul class="pagination pagination-sm m-0 float-right">
                {{$members->appends(request()->all())->links()}}
            </ul>
        </div>
    </div>


    <div class="modal fade" id="searchModal" tabindex="-1" role="dialog" aria-labelledby="searchModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <form action="{{ route('member.search') }}" method="get" id="searchModalForm" class="members-form form-horizontal w-100">
                {{csrf_field()}}
                <input type="hidden" name="model" value="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="searchModalTitle">搜索</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="condition" class="col-form-label col-sm-3">客户状态</label>
                                <div class="col-sm-9 mb-0">
                                    <select name="condition" id="condition" name="condition" class="form-control select2" data-placeholder="选择一个客户状态">
                                        <option value="">状态</option>
                                        @if(isset($conditions)&&!empty($conditions))
                                            @foreach($conditions as $condition)
                                                <option value="{{ $condition->id }}">{{ $condition->display_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="channel" class="col-form-label col-sm-3">来源渠道</label>
                                <div class="col-sm-9 mb-0">
                                    <select name="channel" id="channel" name="channel" class="form-control select2" data-placeholder="选择一个来源渠道">
                                        <option value="">来源渠道</option>
                                        @if(isset($channels)&&!empty($channels))
                                            @foreach($channels as $k=>$v)
                                                <option value="{{ $k }}">{{ $v }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="disease" class="col-form-label col-sm-3">病种</label>
                                <div class="col-sm-9 mb-0">
                                    <select name="disease" id="disease" name="disease" class="form-control select2" data-placeholder="选择一个病种">
                                        <option value="">病种</option>
                                        @if(isset($diseases)&&!empty($diseases))
                                            @foreach($diseases as $k=>$v)
                                                <option value="{{ $k }}">{{ $v }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="doctor" class="col-form-label col-sm-3">医生</label>
                                <div class="col-sm-9 mb-0">
                                    <select name="doctor" id="doctor" name="doctor" class="form-control select2" data-placeholder="选择一个医生">
                                        <option value="">医生</option>
                                        @if(isset($doctors)&&!empty($doctors))
                                            @foreach($doctors as $k=>$v)
                                                <option value="{{ $k }}">{{ $v }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="condition" class="col-form-label col-sm-3">咨询方式</label>
                                <div class="col-sm-9 mb-0">
                                    <select name="consult" id="consult" name="consult" class="form-control select2" data-placeholder="选择一个咨询方式">
                                        <option value="">咨询方式</option>
                                        @if(isset($consults)&&!empty($consults))
                                            @foreach($consults as $k=>$v)
                                                <option value="{{ $k }}">{{ $v }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="user" class="col-form-label col-sm-3">客服</label>
                                <div class="col-sm-9 mb-0">
                                    <select name="user" id="user" name="uid" class="form-control select2" data-placeholder="选择一个客服">
                                        <option value="">客服</option>
                                        @if(isset($activeUsers)&&!empty($activeUsers))
                                            @foreach($activeUsers as $k=>$v)
                                                <option value="{{ $k }}">{{ $v }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="cfz" class="col-form-label col-sm-3">初复诊</label>
                                <div class="col-sm-9 mb-0">
                                    <select name="cfz" id="cfz" name="cfz" class="form-control select2" data-placeholder="初复诊">
                                        <option value="">初复诊</option>
                                        <option value="1">初诊</option>
                                        <option value="2">复诊</option>
                                        <option value="3">来过</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="grade" class="col-form-label col-sm-3">班次</label>
                                <div class="col-sm-9 mb-0">
                                    <select name="grade" id="grade" name="grade" class="form-control select2" data-placeholder="选择一个班次">
                                        <option value="">选择一个班次</option>
                                        <option value="1">白班</option>
                                        <option value="2">晚班</option>
                                        <option value="3">大夜班</option>
                                        <option value="4">其它</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="grade" class="col-form-label col-sm-2">登记时间</label>
                        <div class="col-sm-10 mb-0">
                            <div class="form-row">
                                <div class="col">
                                    <input type="text" name="created_at_start" class="form-control itemDate" placeholder="开始时间">
                                </div>
                                <div class="col">
                                    <input type="text" name="created_at_end" class="form-control itemDate" placeholder="结束时间">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="grade" class="col-form-label col-sm-2">预约时间</label>
                        <div class="col-sm-10 mb-0">
                            <div class="form-row">
                                <div class="col">
                                    <input type="text" name="pubdate_start" class="form-control itemDate" placeholder="开始时间">
                                </div>
                                <div class="col">
                                    <input type="text" name="pubdate_end" class="form-control itemDate" placeholder="结束时间">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="grade" class="col-form-label col-sm-2">到诊时间</label>
                        <div class="col-sm-10 mb-0">
                            <div class="form-row">
                                <div class="col">
                                    <input type="text" name="okdate_start" class="form-control itemDate" placeholder="开始时间">
                                </div>
                                <div class="col">
                                    <input type="text" name="okdate_end" class="form-control itemDate" placeholder="结束时间">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">关闭</button>
                    <button type="reset" class="btn btn-danger" id="searchModalReset" onclick="">重置</button>
                    <button type="submit" class="btn btn-success">搜索</button>
                </div>
            </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="trackModal" tabindex="-1" role="dialog" aria-labelledby="trackModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <form action="{{ route('track.store') }}" method="post" id="trackModalForm" class="tracks-form form-horizontal w-100">
                {{csrf_field()}}
                <input type="hidden" name="mid" value="" id="trackMember">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="trackModalTitle">回访</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="track-create-tab" data-toggle="tab" href="#trackCreate" role="tab" aria-controls="trackCreate" aria-selected="true">添加回访</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="track-view-tab" data-toggle="tab" href="#trackView" role="tab" aria-controls="trackView" aria-selected="false">回访记录</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="trackTabContent" style="min-height: 26rem;">
                            <div class="tab-pane fade show active" id="trackCreate" role="tabpanel" aria-labelledby="track-create-tab">
                                <div class="row">
                                    <div class="col-sm-5 table-responsive">
                                        <table class="table mt-3" id="memberTable">
                                            <tr>
                                                <td class="text-nowrap font-weight-bold">客户姓名</td>
                                                <td id="memberName" class="">查询中...</td>
                                            </tr>
                                            <tr>
                                                <td class="text-nowrap font-weight-bold">预约时间</td>
                                                <td class="" id="memberPubdate">查询中...</td>
                                            </tr>
                                            <tr>
                                                <td class=" text-nowrap font-weight-bold">联系电话</td>
                                                <td class="" id="memberTell">查询中...</td>
                                            </tr>
                                            <tr>
                                                <td class=" text-nowrap font-weight-bold">预约医院</td>
                                                <td class="" id="memberHospital">查询中...</td>
                                            </tr>
                                            <tr>
                                                <td class=" text-nowrap font-weight-bold">预约病种</td>
                                                <td class="" id="memberDisease">查询中...</td>
                                            </tr>
                                            <tr>
                                                <td class=" text-nowrap font-weight-bold">预约医生</td>
                                                <td class="" id="memberDoctor">查询中...</td>
                                            </tr>
                                            <tr>
                                                <td class="align-middle font-weight-bold">客户摘要</td>
                                                <td class="" id="memberDescription">查询中...</td>
                                            </tr>
                                        </table>
                                        <hr>
                                    </div>
                                    <div class="col-sm-7">
                                        <form action="">
                                            <div class="form-group mt-3">
                                                <label for="trackType">回访类型：</label>
                                                <div class="form-control border-0">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" name="track_type" type="radio" id="trackType1" value="1">
                                                        <label class="form-check-label" for="trackType1">例行回访</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" name="track_type" type="radio" id="trackType2" value="2">
                                                        <label class="form-check-label" for="trackType2">未来跟进</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" name="track_type" type="radio" id="trackType3" value="3">
                                                        <label class="form-check-label" for="trackType3">事后跟踪</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" name="track_type" type="radio" id="trackType4" value="4" checked>
                                                        <label class="form-check-label" for="trackType4">其它</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="trackContent">回访内容：</label>
                                                <textarea type="text" class="form-control" name="content" id="trackContent" ></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="trackCondition">更新客户状态：</label>
                                                <select class="form-control select2" id="trackCondition" name="condition" data-placeholder="更新客户状态"></select>
                                            </div>
                                            <div class="form-group">
                                                <label for="trackContent">更新预约时间：</label>
                                                <input type="text" class="form-control itemDateTime" name="pubdate" id="trackPubdate" >
                                            </div>
                                            <div class="form-group">
                                                <div class="formControls col-sm-12">
                                                    日期:
                                                    <a class="small" href="javascript:input_date('trackPubdate', '{{ \Carbon\Carbon::now()->startOfDay()->format('Y-m-d H:i') }}')">[今]</a>
                                                    <a class="small" href="javascript:input_date('trackPubdate', '{{ \Carbon\Carbon::now()->add('1 day')->startOfDay()->format('Y-m-d H:i') }}')">[明]</a>
                                                    <a class="small" href="javascript:input_date('trackPubdate', '{{ \Carbon\Carbon::now()->add('2 days')->startOfDay()->format('Y-m-d H:i') }}')">[后]</a>
                                                    <a class="small" href="javascript:input_date('trackPubdate', '{{ \Carbon\Carbon::now()->add('3 days')->startOfDay()->format('Y-m-d H:i') }}')">[大后天]</a>
                                                    <a class="small" href="javascript:input_date('trackPubdate', '{{ (new \Carbon\Carbon('next saturday'))->startOfDay()->format('Y-m-d H:i') }}')">[周六]</a>
                                                    <a class="small" href="javascript:input_date('trackPubdate', '{{ (new \Carbon\Carbon('next sunday'))->startOfDay()->format('Y-m-d H:i') }}')">[周日]</a>
                                                    <a class="small" href="javascript:input_date('trackPubdate', '{{ (new \Carbon\Carbon('next monday'))->startOfDay()->format('Y-m-d H:i') }}')">[周一]</a>
                                                    <a class="small" href="javascript:input_date('trackPubdate', '{{ \Carbon\Carbon::now()->add('7 days')->startOfDay()->format('Y-m-d H:i') }}')">[一周后]</a>
                                                    <a class="small" href="javascript:input_date('trackPubdate', '{{ \Carbon\Carbon::now()->add('15 days')->startOfDay()->format('Y-m-d H:i') }}')">[半月后]</a>
                                                    <br>时间:
                                                    <a class="small" href="javascript:input_time('trackPubdate','00:00')">[时间不限]</a>
                                                    <a class="small" href="javascript:input_time('trackPubdate','09:00')">[9点左右]</a>
                                                    <a class="small" href="javascript:input_time('trackPubdate','11:00')">[11点左右]</a>
                                                    <a class="small" href="javascript:input_time('trackPubdate','13:00')">[1点左右]</a>
                                                    <a class="small" href="javascript:input_time('trackPubdate','15:00')">[3点左右]</a>
                                                    <a class="small" href="javascript:input_time('trackPubdate','17:00')">[5点左右]</a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="trackView" role="tabpanel" aria-labelledby="track-view-tab">
                                <table class="table table-sm mt-3 table-bordered" id="memberTrackTable">
                                    <thead>
                                        <tr>
                                            <th class="text-nowrap font-weight-bold">回访人</th>
                                            <th class="text-nowrap font-weight-bold">回访类型</th>
                                            <th class="text-nowrap font-weight-bold">回访时间</th>
                                            <th class="font-weight-bold">回访内容</th>
                                        </tr>
                                    </thead>
                                    <tbody id="memberTrackList"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">关闭</button>
                        <button type="submit" class="btn btn-success">保存</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
{{--    memberInfoModal  --}}
    <div class="modal fade" id="memberInfoModal" tabindex="-1" role="dialog" aria-labelledby="memberInfoModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="memberInfoModalTitle">信息概况</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="memberInfoTable">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">关闭</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('javascripts')
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/layer/layer.js') }}"></script>
    <script src="{{ asset('plugins/laydate/laydate.js') }}"></script>
    <script type="text/javascript">

        $(document).ready(function() {
            $(".delete-operation").on('click',function(){
                var id=$(this).attr('data-id');
                layer.open({
                    content: '你确定要删除吗？',
                    btn: ['删除', '取消'],
                    yes: function(index, layero){
                        $('form#member-form').attr('action',"{{route('member.index')}}/"+id);
                        $('form#member-form').submit();
                    },
                    btn2: function(index, layero){
                        //按钮【按钮二】的回调
                        //return false 开启该代码可禁止点击该按钮关闭
                    },
                    cancel: function(){
                        //右上角关闭回调
                        //return false; 开启该代码可禁止点击该按钮关闭
                    }
                });
            });
            $(".condition-operation").on('click',function(){
                var id=$(this).attr('data-id');
                var condition=$(this).attr('data-condition');
                $('form#member-condition-form input[name=mid]').val(id);
                $('form#member-condition-form input[name=condition]').val(condition);
                $('form#member-condition-form').submit();
            });
            $("#quick-search-form .quickModel").on('click',function(){
                var type=$(this).attr('data-type');
                $('form#quick-search-form input[name=type]').val(type);
                $('form#quick-search-form').submit();
            });
            $('[data-toggle="tooltip"]').tooltip();
            $('.select2').select2({allowClear: true});
            $("#searchModalReset").click(function () {
                $('#searchModalForm select.select2').each(function(){
                    $(this).select2('data', {});
                    $(this).select2({ allowClear: true });
                });
            });
            $('.itemDate').each(function(){
                laydate.render({
                    elem: this,
                    trigger: 'click',
                    type:'date',
                    format: 'yyyy-MM-dd',
                });
            });
            $('.itemDateTime').each(function(){
                laydate.render({
                    elem: this,
                    trigger: 'click',
                    type:'datetime',
                    format: 'yyyy-MM-dd HH:mm',
                });
            });
            $(".track").on('click',function(){
                var mid=$(this).attr('data-mid');
                $("#memberTrackList").html('');
                $.ajax({
                    url:'/get-tracks-from-member',
                    type: "post",
                    data: {'mid':mid,'_token': $('input[name=_token]').val()},
                    success: function(data){
                        if(data.status){
                            // console.log(data);
                            $("#trackMember").val(data.member.id);
                            $("#memberName").html(data.member.name);
                            $("#memberPubdate").html(data.member.pubdate);
                            $("#memberTell").html(data.member.tell);
                            $("#memberHospital").html(data.member.hospital);
                            $("#memberDisease").html(data.member.disease);
                            $("#memberDoctor").html(data.member.doctor);
                            $("#memberDescription").html(data.member.description);
                            if (data.conditions){
                                var conditionStr='<option value=\'\'>客户状态</option>';
                                for (var key in data.conditions) {
                                    conditionStr += '<option value=\''+key+'\'>'+data.conditions[key]+'</option>';
                                }
                                console.log(conditionStr);
                                $("#trackCondition").html(conditionStr);
                            }
                            if (data.tracks.length>0){
                                var memberTrackList='';
                                for (var i = 0; i < data.tracks.length; i++) {
                                    memberTrackList += '<tr><td>'+data.tracks[i].user+'</td>';
                                    memberTrackList += '<td>'+data.tracks[i].track_type+'</td>';
                                    memberTrackList += '<td>'+data.tracks[i].created_at+'</td>';
                                    memberTrackList += '<td>'+data.tracks[i].content+'</td></tr>';
                                }
                                $("#memberTrackList").html(memberTrackList);
                            }
                        }
                    }
                });
            });
            //memberinfo
            $(".member-info").on('click',function(){
                var mid=$(this).attr('data-mid');
                $("#memberInfoTable").html('');
                htmlcontent='';
                $.ajax({
                    url:'/get-info-from-member',
                    type: "post",
                    data: {'mid':mid,'_token': $('input[name=_token]').val()},
                    success: function(data){
                        console.log(data);
                        if(data.status){
                           htmlcontent += '<div class=\"table-responsive\"><table class=\"table table-bordered table-striped\"><tr><td>客户姓名</td><td>'+data.member.name+'</td></tr>';
                           htmlcontent += '<tr><td>登记时间</td><td>'+data.member.created_at+'</td></tr>';
                           htmlcontent += '<tr><td>预约时间</td><td>'+data.member.pubdate+'</td></tr>';
                           htmlcontent += '<tr><td>编号信息</td><td>预约号：'+data.member.yy_num+' | 登记人：'+data.member.user+'</td></tr>';
                           htmlcontent += '<tr><td>联系电话</td><td>'+data.member.tell+'</td></tr>';
                           htmlcontent += '<tr><td>客户微信</td><td>'+data.member.wechat+'</td></tr>';
                           htmlcontent += '<tr><td>预约医院</td><td>'+data.member.hospital+'</td></tr>';
                           htmlcontent += '<tr><td>预约病种</td><td>'+data.member.disease+'</td></tr>';
                           htmlcontent += '<tr><td>预约医生</td><td>'+data.member.doctor+'</td></tr>';
                           htmlcontent += '<tr><td>入口页面</td><td>'+data.member.url+'</td></tr>';
                           htmlcontent += '<tr><td>搜索关键词</td><td>'+data.member.keywords+'</td></tr>';
                           htmlcontent += '<tr><td>客户摘要</td><td>'+data.member.description+'</td></tr>';
                           htmlcontent += '<tr><td>修改记录</td><td>'+data.member.edit_log+'</td></tr>';
                           htmlcontent += '<tr><td>状态记录</td><td>'+data.member.change_log+'</td></tr>';
                           htmlcontent += '<tr><td>当前状态</td><td>'+data.member.condition+'</td></tr></table></div>';
                           htmlcontent += '<div class=\"card\"><div class=\"card-header\"> <h3 class=\"card-title\">对话信息</h3><div class=\"card-tools\"><button type=\"button\" class=\"btn btn-tool\" data-card-widget=\"collapse\"><i class=\"fas fa-minus\"></i></button></div></div> <div class=\"card-body\">'+data.member.body+'</div></div>';
                        }
                        $("#memberInfoTable").html(htmlcontent);
                    }
                });
            });
        } );
    </script>
@endsection