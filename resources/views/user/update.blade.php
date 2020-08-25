@extends('layouts.app')

@section('content')
    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title">更新</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form action="{{route('user.update',$user->id)}}" method="post" class="users-form form-horizontal">
            {{csrf_field()}}
            {{method_field('PUT')}}
            @include('user.form')
            <div class="card-footer">
                <button type="submit" class="btn btn-info">更新</button>
            </div>
            <!-- /.card-footer -->
        </form>
    </div>
@endsection
