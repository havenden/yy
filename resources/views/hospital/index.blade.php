@extends('layouts.app')

@section('content')
    @include('layouts.tip')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">项目表 @can('hospitals_create')<a href="{{ route('hospital.create') }}" style="margin-left: 10px;">添加</a>@endcan</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive">
            <form action="" method="post" class="hospital-form" id="hospital-form">
                {{method_field('DELETE')}}
                {{csrf_field()}}
                <input type="hidden" name="key" value="{{ isset($key)?$key:'' }}">
            <table class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th style="">Name</th>
                    <th style="">名称</th>
                    <th style="">状态</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @if(!empty($hospitals))
                @foreach($hospitals as $hospital)
                <tr>
                    <td>{{ $hospital->name }}</td>
                    <td>{{ $hospital->display_name }}</td>
                    @if($hospital->status==1)
                        <td><span class="badge bg-success">正常</span></td>
                    @else
                        <td><span class="badge bg-danger">关闭</span></td>
                    @endif
                    <td data-id="{{ $hospital->id }}">
                        @can('hospitals_update')
                        <a href="{{ route('hospital.edit',$hospital->id) }}" style="margin-right: 5px;" title="编辑" data-toggle="tooltip" data-placement="top"><i class="fas fa-edit"></i></a>
                        @endcan
                        @can('hospitals_delete')
                        <a href="javascript:void(0);" data-id="{{$hospital->id}}"  alt="删除" title="删除" class="delete-operation"><i class="fas fa-trash-alt"></i></a>
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
                {{$hospitals->links()}}
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
                        $('form#hospital-form').attr('action',"{{route('hospital.index')}}/"+id);
                        $('form#hospital-form').submit();
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