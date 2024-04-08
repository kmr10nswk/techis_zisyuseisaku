@extends('adminlte::page')

@section('title', '商品一覧')

@section('content_header')
    <h1>権限編集</h1>
    <hr class="mt-1 mb-3">
@stop

@section('content')
    <div class="container text-center">
        <h5>編集アカウント：{{ $user->name }}さん</h5>
        <form action="{{ url('users/admin_update', $user) }}" method="POST" class="form-list w-50 p-3 mx-auto">
        @csrf
        @method('patch')

            <div class="mb-2">
                <h5 class="mt-3 mb-4">【権限一覧】</h5>
                <div class="d-flex justify-content-between">

                    <div class="col-3">
                        <input type="radio" name="admin" id="ippan" value="一般" {{ $user->item_admin === 1 || $user->theread_admin === 1 ? "" : "checked" }} />
                        <label for="ippan">一般</label>
                    </div>

                    <div class="col-3">
                        <input type="radio" name="admin" id="item" value="商品" {{ $user->item_admin === 1 && !isset($user->theread_admin) ? "checked" : "" }} >
                        <label for="item">商品管理</label>
                    </div>

                    <div class="col-3">
                        <input type="radio" name="admin" id="theread" value="掲示板" {{ $user->theread_admin === 1 && !isset($user->item_admin) ? "checked" : "" }} >
                        <label for="theread">掲示板管理</label>
                    </div>

                    <div class="col-3">
                        <input type="radio" name="admin" id="subete" value="全て" {{ $user->item_admin === 1 && $user->theread_admin === 1 ? "checked" : "" }} >
                        <label for="subete">全て</label>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-sm btn-primary mt-3">送信</button>
        </form>

        <form action="" method="POST">
            @csrf
            @method('delete')
            <button type="submit" class="btn-sm btn-danger" onclick='return confirm("本当に削除しますか？")'>
                削除
            </button>
        </form>



    </div>
    






@stop

@section('css')
@stop

@section('js')
@stop