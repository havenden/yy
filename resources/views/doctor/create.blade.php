@extends('layouts.app')
@section('content')
    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title">添加</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form action="{{route('doctor.store')}}" method="post" class="doctors-form form-horizontal">
            {{csrf_field()}}
            @include('doctor.form')
            <!-- /.card-body -->
            <div class="card-footer">
                <button type="submit" class="btn btn-info">保存</button>
            </div>
            <!-- /.card-footer -->
        </form>
    </div>
@endsection