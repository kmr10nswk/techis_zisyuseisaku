@extends('adminlte::page')

@if(true)
    @section('title', 'プロフィール編集画面')
@endif

@section('content_header')
    <h1>プロフィール編集画面</h1>
    <hr>
@stop

@section('content')
<div class="container">
    <form method="POST" action="{{ url('admins/profile/update', $admin) }}" enctype="multipart/form-data">
    @csrf
    @method('patch')

        <div class="row mb-3">
            <label for="name" class="col-md-3 col-form-label text-md-end">{{ __('名前') }}</label>

            <div class="col-md-6">
                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $admin->name) }}" required autocomplete="name">

                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <span>※20文字</span>
            </div>
        </div>

        <div class="row mb-3">
            <label for="email" class="col-md-3 col-form-label text-md-end">{{ __('email') }}</label>

            <div class="col-md-6">
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $admin->email) }}" required autocomplete="email">

                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <label for="now_password" class="col-md-3 col-form-label text-md-end">{{ __('パスワード') }}</label>

            <div class="col-md-6">
                <input id="now_password" type="password" class="form-control @error('now_password') is-invalid @enderror" name="now_password" required autocomplete="new-password">

                @error('now_password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <span>※6～20文字</span>
            </div>
        </div>

        <div class="row mb-3">
            <label for="password" class="col-md-3 col-form-label text-md-end">{{ __('新しいパスワード') }}</label>

            <div class="col-md-6">
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password">

                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <span>※6～20文字</span>
            </div>
        </div>

        <div class="row mb-3">
            <label for="password-confirm" class="col-md-3 col-form-label text-md-end">{{ __('新しいパスワード（確認）') }}</label>

            <div class="col-md-6">
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password">
            </div>
        </div>

        <div class="row mb-0">
            <div class="col-md-6 offset-md-4">
                <button type="submit" class="btn btn-primary">
                    {{ __('更新') }}
                </button>
            </div>
        </div>
    </form>



</div>

@stop

@section('css')
<style>
    .container {
        max-width: 50%;
        @media screen and (max-width: 915px) {
            max-width: 90%;
        }
    }

    .container .text-center img{
        max-width: 280px;
        max-height: 280px;
        width: 30%;
        border-radius: 50%;
    }
</style>
@stop

@section('js')
@stop
