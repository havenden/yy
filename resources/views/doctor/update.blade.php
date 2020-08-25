@extends('layouts.app')
@section('content')
    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title">更新</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form action="{{route('doctor.update',$doctor->id)}}" method="post" class="doctors-form form-horizontal">
        {{csrf_field()}}
        {{method_field('PUT')}}
        @include('doctor.form')
        <!-- /.card-body -->
            <div class="card-footer">
                <button type="submit" class="btn btn-info">保存</button>
            </div>
            <!-- /.card-footer -->
        </form>
    </div>
@endsection