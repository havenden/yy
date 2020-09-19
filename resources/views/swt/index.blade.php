@extends('layouts.app')
@section('css')
    <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
@endsection
@section('content-header')
    <a style="padding: 0 .5rem">共：{{ isset($swts)?$swts->total():0 }} &nbsp;条数据</a>
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
{{--                @can('members_create')<a href="{{ route('member.create') }}" class="btn btn-sm btn-info mt-3">添加</a>@endcan--}}

                <a href="javascript:;" class="btn btn-sm btn-info mt-3" data-toggle="modal" data-target="#importModal">excel导入</a>
                <a href="javascript:;" class="btn btn-sm btn-success mt-3" data-toggle="modal" data-target="#searchModal">筛选</a>
            </h3>
            <div class="card-tools mt-3">
                <form action="{{ route('swt.search') }}" method="get" id="search-form">
                    {{csrf_field()}}
                    <div class="input-group input-group-sm" style="width: 325px;">
                        <input type="text" name="key" class="form-control float-right" placeholder="客服/关键词/地区/客人类别/对话类型/对话类别/账户后缀">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0 table-hover" style=" min-height: 26rem;">
            <form action="" method="post" class="swt-form" id="swt-form">
                {{method_field('DELETE')}}
                {{csrf_field()}}
                <table class="table  table-bordered">
                    <thead>
                    <tr class="text-center bg-light text-nowrap">
                        <th style="">客服</th>
                        <th style="width: 5%;">名称</th>
                        <th style="">访问时间</th>
                        <th style="">客人讯息数</th>
                        <th style="">客人类别</th>
                        <th style="">对话类型</th>
                        <th style="">对话类别</th>
                        <th style="">关键词</th>
                        <th style="">IP定位</th>
{{--                        <th style="">永久身份</th>--}}
                        <th style="">对话来源</th>
                        <th style="">是否有效</th>
                        <th style="">是否留联</th>
                        <th style="">账户后缀</th>
{{--                        <th>操作</th>--}}
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($swts)&&!empty($swts))
                        @foreach($swts as $swt)
                            <tr class="text-center">
                                <td><a href="javascript:;" class="text-info swt-info" data-toggle="modal" data-mid="{{ $swt->id }}" data-target="#swtInfoModal">{{ $swt->author }}</a></td>
                                <td>{{ $swt->title }}</td>
                                <td>{{ $swt->start_time }}</td>
                                <td>{{ $swt->msg_num }}</td>
                                <td>{{ $swt->member_type }}</td>
                                <td>{{ $swt->msg_type }}</td>
                                <td>{{ $swt->chat_type }}</td>
                                <td>{{ $swt->keyword }}</td>
                                <td>{{ $swt->area }}</td>
{{--                                <td>{{ $swt->swt_id }}</td>--}}
                                <td >{{ \Illuminate\Support\Str::limit($swt->url,20) }}</td>
                                <td><span class="{{  $swt->is_effective==1?'text-blue':'' }}">{{ $swt->is_effective==1?"有效对话":'无效对话' }}</span></td>
                                <td><span class="{{  $swt->is_contact==1?'text-blue':'' }}">{{ $swt->is_contact==1?"留联":'' }}</span></td>
                                <td>{{ $swt->account }}</td>
{{--                                <td data-id="{{ $member->id }}" class="dropdown">--}}
{{--                                    @can('members_update')--}}
{{--                                        <a href="{{ route('member.edit',$member->id) }}"  title="编辑" data-toggle="tooltip" data-placement="top"><i class="fas fa-edit btn btn-sm btn-info"></i></a>--}}
{{--                                    @endcan--}}
{{--                                    @can('members_delete')--}}
{{--                                        <a href="javascript:void(0);" data-id="{{$member->id}}"  title="删除" data-toggle="tooltip" data-placement="top" class="delete-operation"><i class="fas fa-trash-alt btn btn-sm btn-danger"></i></a>--}}
{{--                                    @endcan--}}
{{--                                </td>--}}
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
                {{isset($swts)?$swts->appends(request()->all())->links():''}}
            </ul>
        </div>
    </div>

    <div id="importModal" class="modal fade"  tabindex="-1" role="dialog" aria-labelledby="importModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <form action="{{ route('swt.import') }}" method="post" id="importModalForm" class="members-form form-horizontal w-100" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="importModalTitle">excel导入</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="condition" class="col-form-label col-sm-3">选择文件</label>
                            <div class="col-sm-9 mb-0">
                                <input type="file" name="swt_excel" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">确定</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="searchModal" tabindex="-1" role="dialog" aria-labelledby="searchModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <form action="{{ route('swt.search') }}" method="get" id="searchModalForm" class="members-form form-horizontal w-100">
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
                                <label for="condition" class="col-form-label col-sm-3">客人类别</label>
                                <div class="col-sm-9 mb-0">
                                    <select name="member_type" id="member_type" name="member_type" class="form-control select2" data-placeholder="客人类别">
                                        <option value="">客人类别</option>
                                        @if(isset($memberTypes)&&!empty($memberTypes))
                                            @foreach($memberTypes as $memberType)
                                                <option value="{{ $memberType }}">{{ $memberType }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="chat_type" class="col-form-label col-sm-3">对话类别</label>
                                <div class="col-sm-9 mb-0">
                                    <select name="chat_type" id="chat_type" name="chat_type" class="form-control select2" data-placeholder="对话类别">
                                        <option value="">对话类别</option>
                                        @if(isset($chatTypes)&&!empty($chatTypes))
                                            @foreach($chatTypes as $chatType)
                                                <option value="{{ $chatType }}">{{ $chatType }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="is_effective" class="col-form-label col-sm-3">是否有效</label>
                                <div class="col-sm-9 mb-0">
                                    <select name="is_effective" id="is_effective" name="is_effective" class="form-control select2" data-placeholder="是否有效">
                                        <option value="">是否有效</option>
                                        <option value="1">有效对话</option>
                                        <option value="0">无效对话</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="account" class="col-form-label col-sm-3">账户后缀</label>
                                <div class="col-sm-9 mb-0">
                                    <select name="account" id="account" name="account" class="form-control select2" data-placeholder="账户后缀">
                                        <option value="">账户后缀</option>
                                        @if(isset($accounts)&&!empty($accounts))
                                            @foreach($accounts as $account)
                                                <option value="{{ $account }}">{{ $account }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="keyword" class="col-form-label col-sm-3">关键词</label>
                                <div class="col-sm-9 mb-0">
                                    <input name="keyword" id="keyword" name="keyword" class="form-control" placeholder="关键词"></input>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="title" class="col-form-label col-sm-3">客人名称</label>
                                <div class="col-sm-9 mb-0">
                                    <input name="title" id="title" name="title" class="form-control" placeholder="客人名称"></input>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="msg_type" class="col-form-label col-sm-3">对话类型</label>
                                <div class="col-sm-9 mb-0">
                                    <select name="msg_type" id="msg_type" name="msg_type" class="form-control select2" data-placeholder="对话类型">
                                        <option value="">对话类型</option>
                                        @if(isset($msgTypes)&&!empty($msgTypes))
                                            @foreach($msgTypes as $msgType)
                                                <option value="{{ $msgType }}">{{ $msgType }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="area" class="col-form-label col-sm-3">IP定位</label>
                                <div class="col-sm-9 mb-0">
                                    <select name="area" id="area" name="area" class="form-control select2" data-placeholder="IP定位">
                                        <option value="">IP定位</option>
                                        @if(isset($areas)&&!empty($areas))
                                            @foreach($areas as $area)
                                                <option value="{{ $area }}">{{ $area }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="is_contact" class="col-form-label col-sm-3">是否留联</label>
                                <div class="col-sm-9 mb-0">
                                    <select name="is_contact" id="is_contact" name="is_contact" class="form-control select2" data-placeholder="是否留联">
                                        <option value="">是否留联</option>
                                        <option value="1">是</option>
                                        <option value="0">否</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="author" class="col-form-label col-sm-3">客服</label>
                                <div class="col-sm-9 mb-0">
                                    <select name="author" id="author" name="author" class="form-control select2" data-placeholder="客服">
                                        <option value="">客服</option>
                                        @if(isset($authors)&&!empty($authors))
                                            @foreach($authors as $author)
                                                <option value="{{ $author }}">{{ $author }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="url" class="col-form-label col-sm-3">对话来源</label>
                                <div class="col-sm-9 mb-0">
                                    <input name="url" id="url" name="url" class="form-control" placeholder="对话来源"></input>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="swt_id" class="col-form-label col-sm-3">永久身份</label>
                                <div class="col-sm-9 mb-0">
                                    <input name="swt_id" id="swt_id" name="swt_id" class="form-control" placeholder="商务通永久身份"></input>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="grade" class="col-form-label col-sm-2">对话时间</label>
                        <div class="col-sm-10 mb-0">
                            <div class="form-row">
                                <div class="col">
                                    <input type="text" name="time_start" class="form-control itemDate" placeholder="开始时间">
                                </div>
                                <div class="col">
                                    <input type="text" name="time_end" class="form-control itemDate" placeholder="结束时间">
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
    {{--    swtInfoModal  --}}
    <div class="modal fade" id="swtInfoModal" tabindex="-1" role="dialog" aria-labelledby="swtInfoModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="memberInfoModalTitle">信息概况</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="swtInfoTable">

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
            //memberinfo
            $(".swt-info").on('click',function(){
                var mid=$(this).attr('data-mid');
                console.log(mid);
                $("#swtInfoTable").html('');
                htmlcontent='';
                $.ajax({
                    url:'/get-info-from-swt',
                    type: "post",
                    data: {'mid':mid,'_token': $('input[name=_token]').val()},
                    success: function(data){
                        console.log(data);
                        if(data.status){
                            htmlcontent += '<div class=\"table-responsive\"><table class=\"table table-bordered table-striped\"><tr><td>客服名称</td><td>'+data.swt.author+'</td></tr>';
                            htmlcontent += '<tr><td class=\"text-nowrap\">编号</td><td>'+data.swt.sid+'</td></tr>';
                            htmlcontent += '<tr><td class=\"text-nowrap\">永久身份</td><td>'+data.swt.swt_id+'</td></tr>';
                            htmlcontent += '<tr><td class=\"text-nowrap\">客人名称</td><td>'+data.swt.title+'</td></tr>';
                            htmlcontent += '<tr><td class=\"text-nowrap\">开始访问时间</td><td>'+data.swt.start_time+'</td></tr>';
                            htmlcontent += '<tr><td class=\"text-nowrap\">初始客服</td><td>'+data.swt.author+'</td></tr>';
                            htmlcontent += '<tr><td class=\"text-nowrap\">参与客服</td><td>'+data.swt.authors+'</td></tr>';
                            htmlcontent += '<tr><td class=\"text-nowrap\">客人讯息数</td><td>'+data.swt.msg_num+'</td></tr>';
                            htmlcontent += '<tr><td class=\"text-nowrap\">客人类别</td><td>'+data.swt.member_type+'</td></tr>';
                            htmlcontent += '<tr><td class=\"text-nowrap\">对话类型</td><td>'+data.swt.msg_type+'</td></tr>';
                            htmlcontent += '<tr><td class=\"text-nowrap\">对话类别</td><td>'+data.swt.chat_type+'</td></tr>';
                            htmlcontent += '<tr><td class=\"text-nowrap\">对话来源</td><td class=\"text-wrap\">'+data.swt.url+'</td></tr>';
                            htmlcontent += '<tr><td class=\"text-nowrap\">关键词</td><td>'+data.swt.keyword+'</td></tr>';
                            htmlcontent += '<tr><td class=\"text-nowrap\">地域</td><td>'+data.swt.area+'</td></tr>';
                            htmlcontent += '<tr><td class=\"text-nowrap\">是否有效</td><td>'+data.swt.is_effective+'</td></tr>';
                            htmlcontent += '<tr><td class=\"text-nowrap\">是否留联</td><td>'+data.swt.is_contact+'</td></tr>';
                            htmlcontent += '<tr><td class=\"text-nowrap\">账户后缀</td><td>'+data.swt.account+'</td></tr></table></div>';
                        }
                        $("#swtInfoTable").html(htmlcontent);
                    }
                });
            });
        } );
    </script>
@endsection