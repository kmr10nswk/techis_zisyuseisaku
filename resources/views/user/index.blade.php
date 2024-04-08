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
                                <th>ID</th>
                                <th>名前</th>
                                <th>性別</th>
                                <th>年齢</th>
                                <th>email</th>
                                <th>権限</th>
                                <th>権限編集</th>
                                <th>詳細</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr class="text-center">
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->nickname }}</td>
                                    <td>{{ $user->sex }}</td>
                                    <td>{{ $user->age }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->admin }}</td>
                                    <td><a href="{{ url('users/admin_edit', $user) }}" class="btn btn-outline-success">編集</a></td>
                                    <td><a href="" class="btn btn-outline-danger">詳細</a></td>
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
