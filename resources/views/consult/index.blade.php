@extends('layouts.app')

@section('content')
    @include('layouts.tip')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">咨询方式 <span class="text-muted">（{{ $consults->count() }}）</span> @can('consults_create')<a href="{{ route('consult.create') }}" style="margin-left: 10px;">添加</a>@endcan</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <form action="" method="post" class="consult-form" id="consult-form">
                {{method_field('DELETE')}}
                {{csrf_field()}}
                <input type="hidden" name="key" value="{{ isset($key)?$key:'' }}">
            <table class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th style="">Name</th>
                    <th style="">名称</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @if(!empty($consults))
                @foreach($consults as $consult)
                <tr>
                    <td>{{ $consult->name }}</td>
                    <td>{{ $consult->display_name }}</td>
                    <td data-id="{{ $consult->id }}">
                        @can('consults_update')
                        <a href="{{ route('consult.edit',$consult->id) }}" style="margin-right: 5px;" title="编辑" data-toggle="tooltip" data-placement="top"><i class="fas fa-edit"></i></a>
                        @endcan
                        @can('consults_delete')
                        <a href="javascript:void(0);" data-id="{{$consult->id}}"  alt="删除" title="删除" class="delete-operation"><i class="fas fa-trash-alt"></i></a>
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
                {{$consults->links()}}
            </ul>
        </div>
    </div>
@endsection
@section('javascripts')
    <script type="text/javascript" src="{{ asset('plugins/layer/layer.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $(".delete-operation").on('click',function(){
                var id=$(this).attr('data-id');
                layer.open({
                    content: '你确定要删除吗？',
                    btn: ['删除', '取消'],
                    yes: function(index, layero){
                        $('form#consult-form').attr('action',"{{route('consult.index')}}/"+id);
                        $('form#consult-form').submit();
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
        } );
    </script>
@endsection