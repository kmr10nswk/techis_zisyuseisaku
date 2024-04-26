@extends('adminlte::page')

@section('title', 'ユーザー一覧')

@section('content_header')
    <h1>ユーザー一覧</h1>
@stop

@section('content')
    
    <!-- 検索欄 -->
    <button class="btn btn-outline-light mb-1" data-toggle="collapse" data-target="#search-ori" aria-expand="false" aria-controls="search-ori">検索欄の表示</button>
    <div class="collapse" id="search-ori">
        <form action="" method="get" class="mb-2" role="search">
            <ul class="col-md-12 col-sm-12 p-0 row">
                <li class="col-lg-3 col-md-4 col-sm-12">
                    <label for="search_free" class="form-label mb-0 @error('search_free') is-invalid @enderror">フリーワード</label>
                    <input type="serach" id="search_free" name="search_free" value="{{ isset($search['search_free']) ? $search['search_free'] : '' }}" class="form-control" placeholder="フリーワード">

                    @error('search_free')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </li>
                <li class="col-lg-3 col-md-4 col-sm-12">
                    <label for="search_name" class="form-label mb-0">名前</label>
                    <input type="serach" id="search_name" name="search_name" value="{{ isset($search['search_name']) ? $search['search_name'] : '' }}" class="form-control" placeholder="名前">

                    @error('search_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </li>
                <li class="col-lg-1 col-md-2 col-sm-5">
                    <label for="search_gmpl" class="form-label mb-0">GM/PL傾向</label>
                    <select name="search_gmpl" id="search_gmpl" class="form-control @error('search_gmpl') is-invalid @enderror">
                        <option></option>
                            @foreach($types['gmpl'] as $value)
                                <option value="{{ $value }}" @if(isset($search['search_gmpl']) && $search['search_gmpl'] == $value) selected @endif>{{ $value }}</option>
                            @endforeach
                    </select>

                    @error('search_gmpl')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </li>
                <li class="col-lg-2 col-md-3 col-sm-5 session">
                    <label for="search_style" class="form-label mb-0">セッションスタイル</label>
                    <select name="search_style" id="search_style" class="form-control @error('search_style') is-invalid @enderror">
                        <option></option>
                            @foreach($types['style'] as $value)
                                <option value="{{ $value }}" @if(isset($search['search_style']) && $search['search_style'] == $value) selected @endif>{{ $value }}</option>
                            @endforeach
                    </select>

                    @error('search_style')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                </li>
                <li class="col-lg-2 col-md-3 col-sm-5">
                    <label for="search_item" class="form-label mb-0">所持ルールブック</label>
                    <select name="search_item" id="search_item" class="form-control @error('search_item') is-invalid @enderror">
                        <option></option>
                            @foreach($types['item'] as $key => $value)
                                <option value="{{ $key }}" @if(isset($search['search_item']) && $search['search_item'] == $key) selected @endif>{{ $value }}</option>
                            @endforeach
                    </select>

                    @error('search_item')
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
    </div>

    <!-- 本体 -->
    <div class="row">
        <div class="col-12">
            <table class="table table-hover text-nowrap">
                <thead>
                    <tr class="text-center">
                        <th width="100"></th>
                        <th>ID</th>
                        <th>名前</th>
                        <th>GM/PL</th>
                        <th>セッションスタイル</th>
                        @if(!Auth::guard('admin')->check())
                            <th>一言コメント</th>
                        @else
                            <th>email</th>
                        @endif
                        <th>詳細</th>
                        @if(Auth::guard('admin')->check())
                            <th>削除</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr class="text-center">
                            <td class="icon">
                                <img src="{{ $user->url }}" alt="アイコン">
                            </td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->nickname }}</td>
                            <td>{{ $user->gmpl }}</td>
                            <td>{{ $user->session_style }}</td>
                            @if(!Auth::guard('admin')->check())
                                <td>{{ $user->oneword }}</td>
                            @else
                                <td>{{ $user->email }}</td>
                            @endif
                            <td><a href="{{ url('users/profile/show', $user) }}" class="btn btn-outline-primary">詳細</a></td>
                            @if(Auth::guard('admin')->check())
                                <td>
                                    <form action="{{ route('users.delete', $user->id) }}" method="post" onsubmit='return confirm("本当に削除しますか？")'>
                                    @csrf
                                    @method('patch')
                                        <button class="btn btn-outline-danger">削除</button>
                                    </form>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{ $users->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
    
    <!-- フラッシュメッセージ欄 -->
    @if (isset($nothing_message))
        <div class="alert alert-light col-5">
            {{ $nothing_message }}
        </div>
    @endif
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

        form ul .session {
            max-width: 200px;
        }

        /* テーブル */
        .row .col-12 table thead {
            background: #d3d3d3;
        }

        .row .col-12 table thead tr th {
            background-color: #d3d3d3;

            /* 固定 */
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
                $('#search_free').val('');
                $('#search_name').val('');
                $('#search_gmpl').val('');
                $('#search_style').val('');
                $('#search_item').val('');
            });
        });
    </script>
@stop
