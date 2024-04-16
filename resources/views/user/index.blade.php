@extends('adminlte::page')

@section('title', 'ユーザー一覧')

@section('content_header')
    <h1>ユーザー一覧</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">ユーザー一覧</h3>
                    <div class="card-tools">
                        <div class="input-group input-group-sm">
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr class="text-center">
                                <th>アイコン予定</th>
                                <th>ID</th>
                                <th>名前</th>
                                <th>GM/PL</th>
                                <th>セッションスタイル</th>
                                @if(Auth::user())
                                    <th>一言コメント</th>
                                @elseif(Auth::guard('admin')->check())
                                    <th>email</th>
                                    <th>削除</th>
                                @endif
                                <th>詳細</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr class="text-center">
                                    <!-- ""じゃなくてcontrollerの方でimage_iconに入れる値を管理 -->
                                    <td><img src="{{ asset('icon/', $user->image_icon) }}" alt="アイコン" /></td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->nickname }}</td>
                                    <td>{{ $user->gmpl }}</td>
                                    <td>{{ $user->session_style }}</td>
                                    @if(Auth::user())
                                        <td>{{ $user->oneword }}</td>
                                    @elseif(Auth::guard('admin')->check())
                                        <td>{{ $user->email }}</td>
                                        <td><a href="" class="btn btn-outline-danger">削除</td>
                                    @endif
                                    <td><a href="{{ url('users/profile/show', $user) }}" class="btn btn-outline-primary">詳細</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
<style>
    .row .col-12 .card .card-body .table tbody img{
        width: 10%;
        border-radius: 50%;
    }

</style>
@stop

@section('js')
@stop
