@extends('adminlte::page')

@if(true)
    @section('title', '権限編集画面')
@endif

@section('content_header')
    <div class="row">
        <h1>権限編集画面</h1>
        <div class="input-group-append ml-auto">
            <div class="input-group-append ml-auto">
                <a href="{{ url('admins/') }}" class="btn btn-default">戻る</a>
            </div>
        </div>
    </div>
    <hr>
@stop

@section('content')
<div class="container">
    <!-- 名前部分 -->
    <div class="text-center mb-3">
        <h4>{{ $admin->name }}さんの権限</h4>
    </div>

    <div class="text-center">
        現在の権限：{{ $admin->policy_name }}
    </div>

    @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
    @endforeach

    <form method="POST" action="{{ url('admins/update', $admin) }}" class="form-list p-3 mx-auto text-center">
    @csrf
    @method('patch')

        <div class="mb-3">
            <div class="text-center mt-8">
                <div>
                    <input id="item" type="radio" class="me-2 @error('admin') is-invalid @enderror" name="policy_name" value="商品" required {{ $admin->policy->item_admin === 1 && empty($admin->policy->theread_admin) ? "checked" : "" }}>
                    <label for="item">商品管理</label>
                </div>

                <div>
                    <input id="theread" type="radio" class="@error('admin') is-invalid @enderror" name="policy_name" value="掲示板" required {{ empty($admin->policy->item_admin) && $admin->policy->theread_admin === 1 ? "checked" : "" }}>
                    <label for="theread">掲示板管理</label>
                </div>

                <div>
                    <input id="all" type="radio" class="@error('admin') is-invalid @enderror" name="policy_name" value="全て" required {{ $admin->policy->item_admin === 1 && $admin->policy->theread_admin === 1 ? "checked" : "" }}>
                    <label for="all">全ての管理者</label>
                </div>

                @error('admin')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <button type="submit" class="btn btn-primary pb-5">更新</button>
    </form>
</div>

@stop

@section('css')
@stop

@section('js')
@stop
