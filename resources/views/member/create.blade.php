@extends('layouts.app')
@section('content')
    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title">添加</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form action="{{route('member.store')}}" method="post" class="members-form form-horizontal">
            {{csrf_field()}}
            @include('member.form')
        </form>
    </div>
@endsection