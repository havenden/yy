@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">用户表 <span class="text-muted">（{{ $users->count() }}）</span> @can('users_create')<a href="{{ route('user.create') }}" style="margin-left: 10px;">添加</a>@endcan</h3>
            <div class="card-tools">
                <form action="{{ route('user.search') }}" method="get" id="search-form">
                    {{csrf_field()}}
                <div class="input-group input-group-sm" style="width: 150px;">
                    <input type="text" name="key" class="form-control float-right" placeholder="Search:姓名/id">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                    </div>
                </div>
                </form>
            </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive">
            <form action="" method="post" class="user-form" id="user-form">
                {{method_field('DELETE')}}
                {{csrf_field()}}
                <input type="hidden" name="key" value="{{ isset($key)?$key:'' }}">
            <table class="table table-bordered table-hover table-sm">
                <thead>
                <tr>
                    <th style="">用户（ID）</th>
                    <th style="">昵称</th>
                    <th style="">所属组</th>
                    <th style="">账户状态</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @if(!empty($users))
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->display_name }}</td>
                    <td>
                        @foreach($user->roles as $role)
                        <span class="badge bg-primary">{{ $role->display_name }}</span>
                        @endforeach
                    </td>
                    @if($user->status==1)
                        <td><span class="badge bg-success">正常</span></td>
                    @else
                        <span class="badge bg-danger">异常</span>
                    @endif
                    <td data-id="{{ $user->id }}">
                        @can('users_update')
                        <a href="{{ route('user.edit',$user->id) }}" style="margin-right: 20px;" title="编辑" data-toggle="tooltip" data-placement="top"><i class="fas fa-edit"></i></a>
                        @endcan
                        @can('users_delete')
                        <a href="javascript:void(0);" data-id="{{$user->id}}"  alt="删除" title="删除" class="delete-operation"><i class="fas fa-trash-alt"></i></a>
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
                {{$users->appends(request()->all())->links()}}
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
                        $('form#user-form').attr('action',"{{route('user.index')}}/"+id);
                        $('form#user-form').submit();
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