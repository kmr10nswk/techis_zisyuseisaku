@extends('adminlte::page')

@section('title', '商品一覧')

@section('content_header')
    <h1>アカウント一覧</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">アカウント一覧</h3>
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
                                    <td>{{ empty($user->image_icon) ? $user->image_icon : "" }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->nickname }}</td>
                                    <td>{{ $user->gmpl }}</td>
                                    <td>{{ $user->session_style }}</td>
                                    @if(Auth::user())
                                        <td>40文字までコメント</td>
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
@stop

@section('js')
@stop
