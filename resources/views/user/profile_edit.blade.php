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
        <div class="text-center my-3">
            <img src="{{ asset('storage/icon/' . $user->image_icon) }}" alt="プロフィールアイコン">
        </div>
        <form method="POST" action="{{ url('users/profile/update', $user) }}" enctype="multipart/form-data">
        @csrf
        @method('patch')

            <div class="row mb-3">
                <label for="image_icon" class="col-md-3 col-form-label text-md-end">{{__('アイコン')}}</label>
                <div class="col-md-6">
                    <!-- Todo:時間があったらボタン装飾 -->
                    <input type="file" name="image_icon" id="image_icon" style="display:block;">

                    @error('image_icon')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                <span>※jpg、pngのみ。1MB以下。</span>
                </div>
            </div>

            <div class="row mb-3">
                <label for="name" class="col-md-3 col-form-label text-md-end">{{__('ID')}}</label>

                <div class="col-md-6">
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $user->name) }}" required autocomplete="name" autofocus>

                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                <span>※英数字のみ。6～20文字</span>
                </div>
            </div>

            <div class="row mb-3">
                <label for="nickname" class="col-md-3 col-form-label text-md-end">{{ __('名前') }}</label>

                <div class="col-md-6">
                    <input id="nickname" type="text" class="form-control @error('nickname') is-invalid @enderror" name="nickname" value="{{ old('nickname', $user->nickname) }}" required autocomplete="nickname">

                    @error('nickname')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <span>※20文字</span>
                </div>
            </div>

            <div class="row mb-3 align-items-center">
                <label for="gmpl" class="col-md-3 col-form-label text-md-end">{{__('GM/PL傾向')}}</label>

                <div class="col-md-6">
                    <input id="GMonly" type="radio" class="@error('gmpl') is-invalid @enderror" name="gmpl" value="GMのみ" required {{ $user->gmpl === "GMのみ" ? "checked" : "" }}>
                    <label for="GMonly">GMのみ</label>

                    <input id="PLonly" type="radio" class="@error('gmpl') is-invalid @enderror" name="gmpl" value="PLのみ" required {{ $user->gmpl === "PLのみ" ? "checked" : "" }}>
                    <label for="PLonly">PLのみ</label>

                    <input id="GM" type="radio" class="@error('gmpl') is-invalid @enderror" name="gmpl" value="GMより" required {{ $user->gmpl === "GMより" ? "checked" : "" }}>
                    <label for="GM">GMより</label>

                    <input id="PL" type="radio" class="@error('gmpl') is-invalid @enderror" name="gmpl" value="PLより" required {{ $user->gmpl === "PLより" ? "checked" : "" }}>
                    <label for="PL">PLより</label>

                    @error('gmpl')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="row mb-3 d-flex align-items-center">
                <label for="session_style" class="col-md-3 col-form-label text-md-end">{{__('セッションスタイル')}}</label>

                <div class="col-md-6">
                    <input type="checkbox" class="@error('session_style') is-invalid @enderror" name="session_style[]" value="ボイスのみ" {{ is_array(old('session_style')) && in_array('ボイスのみ', old('session_style')) ? 'checked' : '' }} {{ in_array('ボイスのみ', $user->session_style) ? 'checked' : "" }} required_without="session_style[]" autocomplete="session_style">
                    <label for="voice">ボイスのみ</label>
                    
                    <input type="checkbox" class="@error('session_style') is-invalid @enderror" name="session_style[]" value="テキストのみ" {{ is_array(old('session_style')) && in_array('テキストのみ', old('session_style')) ? 'checked' : '' }} {{ in_array('テキストのみ', $user->session_style) ? 'checked' : "" }} required_without="session_style[]" autocomplete="session_style">
                    <label for="text">テキストのみ</label>
                    
                    <input type="checkbox" class="@error('session_style') is-invalid @enderror" name="session_style[]" value="半分テキスト" {{ is_array(old('session_style')) && in_array('半分テキスト', old('session_style')) ? 'checked' : '' }} {{ in_array('半分テキスト', $user->session_style) ? 'checked' : "" }} required_without="session_style[]" autocomplete="session_style">
                    <label for="mix">半分テキスト</label>

                    @error('session_style.*')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <label for="oneword" class="col-md-3 col-form-label text-md-end">{{ __('一言コメント') }}</label>

                <div class="col-md-6">
                    <input id="oneword" type="text" class="form-control @error('oneword') is-invalid @enderror" name="oneword" value="{{ old('oneword', $user->oneword) }}" autocomplete="oneword">

                    @error('oneword')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <!-- Todo:jsにて残り文字数をリアルタイムで表示させる -->
            <div class="row mb-3">
                <label for="comment" class="col-md-3 col-form-label text-md-end">{{ __('自己紹介') }}</label>

                <div class="col-md-6">
                    <textarea id="comment" rows="10" maxlength="400" class="form-control @error('comment') is-invalid @enderror" name="comment" autocomplete="comment" >{{ old('comment',$user->comment) }}</textarea>

                    @error('comment')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <label for="email" class="col-md-3 col-form-label text-md-end">{{ __('email') }}</label>

                <div class="col-md-6">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" required autocomplete="email">

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
