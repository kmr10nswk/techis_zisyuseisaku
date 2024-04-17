@extends('adminlte::page')

@section('title', '管理者一覧')

@section('content_header')
    <h1>管理者一覧</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">管理者一覧
                        <!-- Todo:上手く動いてないテスト -->
                        @can('all_admin') {{dd('aaa')}} @endcan
                    </h3>
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
                                <th>email</th>
                                <th>権限</th>
                                <th>更新日時</th>
                                <th>削除</th>
                                <th>権限編集</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($admins as $admin)
                                <tr class="text-center">
                                    <td>{{ $admin->id }}</td>
                                    <td>{{ $admin->name }}</td>
                                    <td>{{ $admin->email }}</td>
                                    <td>{{ $admin->policy_name }}</td>
                                    <td>{{ $admin->updated_at }}</td>
                                    <td><a href="" class="btn btn-outline-danger">削除</td>
                                    <td><a href="{{ url('admins/edit', $admin) }}" class="btn btn-outline-success">編集</a></td>
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
