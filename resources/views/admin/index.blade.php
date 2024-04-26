@extends('adminlte::page')

@section('title', '管理者一覧')

@section('content_header')
    <div class="row">
        <h1 class="mr-3">管理者一覧</h1>
        <div class="input-group input-group-sm col">
            <div class="input-group-append ml-auto">
                @if(Auth::guard('admin')->check())
                    <a href="{{ url('/register/admin') }}" class="btn btn-default">管理者登録</a>
                @endif
            </div>
        </div>
    </div>
@stop

@section('content')
    <!-- 検索欄 -->
    <form action="" method="get" class="mb-2" role="search">
        <ul class="col-md-12 col-sm-12 p-0 row">
            <li class="col-lg-3 col-md-4 col-sm-12">
                <label for="search_name" class="form-label mb-0">名前</label>
                <input type="serach" id="search_name" name="search_name" value="{{ isset($search['search_name']) ? $search['search_name'] : '' }}" class="form-control" placeholder="名前">

                @error('search_name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </li>
            <li class="col-lg-3 col-md-4 col-sm-12">
                <label for="search_email" class="form-label mb-0 @error('search_email') is-invalid @enderror">メールアドレス</label>
                <input type="serach" id="search_email" name="search_email" value="{{ isset($search['search_email']) ? $search['search_email'] : '' }}" class="form-control" placeholder="email">

                @error('search_email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </li>
            <li class="col-md-3 d-flex flex-row align-items-end">
                <button class="mt-2 mr-2 btn btn-outline-success" type="submit">検索</button>
                <!-- クリアボタン -->
                <button type="submit" class="mt-2 btn btn-outline-info" id="clearSearch">クリア</button>
            </li>
        </ul>
        <div class="col-4">
        </div>
    </form>

    <!-- 本体 -->
    <div class="row">
        <div class="col-12">
            <table class="table table-hover text-nowrap">
                <thead>
                    <tr class="text-center">
                        <th>ID</th>
                        <th>名前</th>
                        <th>email</th>
                        <th>権限</th>
                        <th>更新日時</th>
                        <th>権限編集</th>
                        <th>削除</th>
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
                            <td><a href="{{ url('admins/edit', $admin) }}" class="btn btn-outline-primary">編集</a></td>
                            <td>
                                <form action="{{ route('admins.delete', $admin->id) }}" method="post" onsubmit='return confirm("本当に削除しますか？")'>
                                @csrf
                                @method('patch')
                                    <button class="btn btn-outline-danger">削除</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- フラッシュメッセージ欄 -->
    @if (isset($nothing_message))
        <div class="alert alert-light col-5">
            {{ $nothing_message }}
        </div>
    @endif
    
    <div class="d-flex justify-content-center">
        {{ $admins->appends(request()->query())->links() }}
    </div>
@stop

@section('css')
    <style>
        /* 検索欄 */
        form ul {
            list-style: none;
        }

        form ul li label{
            font-size: 0.9rem;
            padding-left: 0;
        }

        form ul li input {
            padding-left: 0;
        }

        /* テーブル */
        .row .col-12 table thead {
            background: #d3d3d3;
        }

        .row .col-12 table thead tr th {
            position: -webkit-sticky;
            position: sticky;
            top: 0;
            z-index: 1;
        }

        .row .col-12 table thead tr th:last-child{
            border-right: none;
        }

        .row .col-12 table tbody .icon{
            max-width: 100px;
            padding: 5px;
            align-items: center;
        }

        .row .col-12 table tbody .icon img{
            max-width: 45px;
            max-height: 45px;
            width: 70%;
            border-radius: 50%;
            
            @media screen and (max-width: 1100px){
                width: 100%;
                min-width: 30px;
                margin: auto;
            }
        }

        .row .col-12 table tbody tr td{
            vertical-align: middle;
        }
    </style>
@stop

@section('js')
    <script>
        // 検索クリアボタン
        $(function () {
            $('#clearSearch').on('click',function() {
                $('#search_name').val('');
                $('#search_email').val('');
                $('#search_admin').val('');
            });
        });
    </script>
@stop
