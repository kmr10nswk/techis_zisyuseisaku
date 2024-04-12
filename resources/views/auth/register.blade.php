@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-sm-10">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">ID</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            <span>※英数字のみ。6～20文字</span>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="nickname" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="nickname" type="text" class="form-control @error('nickname') is-invalid @enderror" name="nickname" value="{{ old('nickname') }}" required autocomplete="nickname">

                                @error('nickname')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <span>※20文字</span>
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label for="gmpl" class="col-md-4 col-form-label text-md-end">GM/PL傾向</label>

                            <div class="col-md-6">
                                <input id="GMonly" type="radio" class="@error('gmpl') is-invalid @enderror" name="gmpl" value="GMのみ" checked required>
                                <label for="GMonly">GMのみ</label>

                                <input id="PLonly" type="radio" class="@error('gmpl') is-invalid @enderror" name="gmpl" value="PLのみ" required>
                                <label for="PLonly">PLのみ</label>

                                <input id="GM" type="radio" class="@error('gmpl') is-invalid @enderror" name="gmpl" value="GMより" required>
                                <label for="GM">GMより</label>

                                <input id="PL" type="radio" class="@error('gmpl') is-invalid @enderror" name="gmpl" value="PLより" required>
                                <label for="PL">PLより</label>

                                @error('gmpl')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3 d-flex align-items-center">
                            <label for="session_style" class="col-md-4 col-form-label text-md-end">セッションスタイル</label>

                            <div class="col-md-6">
                                <input id="voice" type="checkbox" class="@error('session_style') is-invalid @enderror" name="session_style[]" value="voice" {{ is_array(old('session_style')) && in_array('voice', old('session_style')) ? 'checked' : '' }} required autocomplete="session_style">
                                <label for="voice">ボイス</label>
                                
                                <input id="text" type="checkbox" class="@error('session_style') is-invalid @enderror" name="session_style[]" value="text" {{ is_array(old('session_style')) && in_array('text', old('session_style')) ? 'checked' : '' }} required autocomplete="session_style">
                                <label for="text">テキスト</label>
                                
                                <input id="mix" type="checkbox" class="@error('session_style') is-invalid @enderror" name="session_style[]" value="mix" {{ is_array(old('session_style')) && in_array('mix', old('session_style')) ? 'checked' : '' }} required autocomplete="session_style">
                                <label for="mix">ボイス＋テキスト</label>

                                @error('session_style')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <span>※6～20文字</span>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
