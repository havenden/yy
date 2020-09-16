@extends('layouts.app')

@section('content')
    @include('layouts.tip')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">权限名 <span class="text-muted">（{{ $permissions->count() }}）</span> @can('permissions_create')<a href="{{ route('permission.create') }}" style="margin-left: 10px;">添加</a>@endcan</h3>
            <div class="card-tools">
                <form action="{{ route('permission.search') }}" method="get" id="search-form">
                    {{csrf_field()}}
                    <div class="input-group input-group-sm" style="width: 150px;">
                        <input type="text" name="key" class="form-control float-right" placeholder="Search">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive">
            <form action="" method="post" class="permission-form" id="permission-form">
                {{method_field('DELETE')}}
                {{csrf_field()}}
                <input type="hidden" name="key" value="{{ isset($key)?$key:'' }}">
            <table class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th style="">权限</th>
                    <th style="">权限名</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @if(!empty($permissions))
                @foreach($permissions as $permission)
                <tr>
                    <td>{{ $permission->name }}</td>
                    <td>{{ $permission->display_name }}</td>
                    <td data-id="{{ $permission->id }}">
                        @can('permissions_update')
                        <a href="{{ route('permission.edit',$permission->id) }}" style="margin-right: 5px;" title="编辑" data-toggle="tooltip" data-placement="top"><i class="fas fa-edit"></i></a>
                        @endcan
                        @can('permissions_delete')
                        <a href="javascript:void(0);" data-id="{{$permission->id}}"  alt="删除" title="删除" class="delete-operation"><i class="fas fa-trash-alt"></i></a>
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
                {{$permissions->links()}}
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
                        $('form#permission-form').attr('action',"{{route('permission.index')}}/"+id);
                        $('form#permission-form').submit();
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