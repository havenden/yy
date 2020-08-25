@extends('layouts.app')

@section('content')
    @include('layouts.tip')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">挂号表 @can('ghs_create')<a href="{{ route('gh.create') }}" style="margin-left: 10px;display: none;">添加</a>@endcan</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive">
            <form action="" method="post" class="gh-form" id="gh-form">
                {{method_field('DELETE')}}
                {{csrf_field()}}
                <input type="hidden" name="key" value="{{ isset($key)?$key:'' }}">
                <input type="hidden" name="referer" value="{{ isset($referer)?$referer:'' }}">
            <table class="table table-bordered table-hover">
                <thead class="text-center bg-light text-nowrap">
                <tr>
                    <th style="">姓名</th>
                    <th style="">年龄</th>
                    <th style="">电话</th>
                    <th style="">来源</th>
                    <th style="">科室</th>
                    <th style="">病种</th>
                    <th style="">添加时间</th>
                    <th style="">预约时间</th>
                    <th style="">病情描述</th>
                    <th style="">备注</th>
                    <th style="">状态</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @if(!empty($ghs))
                @foreach($ghs as $gh)
                <tr class="{{ $gh->status=='已回访'?'text-info':($gh->status=='已预约'?'text-success':'') }}">
                    <td>{{ $gh->gh_name }}</td>
                    <td>{{ $gh->gh_age }}</td>
                    <td>{{ $gh->gh_tel }}</td>
                    <td>{{ $gh->gh_ref }}</td>
                    <td>{{ (!empty($gh->gh_office)&&isset($hospitals[$gh->gh_office]))?$hospitals[$gh->gh_office]:'' }}</td>
                    <td>{{ (!empty($gh->gh_disease)&&isset($diseases[$gh->gh_disease]))?$diseases[$gh->gh_disease]:''  }}</td>
                    <td>{{ $gh->created_at }}</td>
                    <td>{{ $gh->gh_date }}</td>
                    <td>{{ $gh->description }}</td>
                    <td>{{ $gh->addons }}</td>
                    <td>{{ $gh->status }}</td>
                    <td data-id="{{ $gh->id }}">
                        @can('ghs_update')
                        <a href="{{ route('gh.edit',$gh->id) }}"  class="text-muted" title="编辑" data-toggle="tooltip" data-placement="top"><i class="fas fa-edit"></i></a>
                        @endcan
                        @can('ghs_delete')
                        <a href="javascript:void(0);" data-id="{{$gh->id}}" alt="删除" title="删除" class="delete-operation text-muted"><i class="fas fa-trash-alt"></i></a>
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
                {{$ghs->links()}}
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
                        $('form#gh-form').attr('action',"{{route('gh.index')}}/"+id);
                        $('form#gh-form').submit();
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