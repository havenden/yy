@extends('layouts.app')
@section('content')
    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title">添加</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form action="{{route('role.store')}}" method="post" class="roles-form form-horizontal">
            {{csrf_field()}}
            @include('role.form')
            <!-- /.card-body -->
            <div class="card-footer">
                <button type="submit" class="btn btn-info">保存</button>
            </div>
            <!-- /.card-footer -->
        </form>
    </div>
@endsection