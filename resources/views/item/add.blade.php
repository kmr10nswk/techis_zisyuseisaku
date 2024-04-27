@extends('adminlte::page')
@section('plugins.Datatables', true)

@section('title', '商品登録')

@section('content_header')
    <h1>商品登録</h1>
@stop

@section('content')
<div class="container">
    <form method="POST" action="" enctype="multipart/form-data">
    @csrf

        <div class="row mb-3">
            <label for="image_item" class="col-md-3 col-form-label text-md-end">{{__('書籍画像')}}</label>
            <div class="col-md-6">
                <p>
                    <img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" id="preview" class="no-image">
                </p>
                <!-- Todo:時間があったらボタン装飾 -->
                <input type="file" name="image_item" id="image_item" style="display:block;" accept='image/*' onchange="previewImage(this);">

                @error('image_item')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <span>※jpg、pngのみ。1MB以下。</span>
            </div>
        </div>

        <div class="row mb-3 form-group">
            <label for="name" class="col-md-3 col-form-label text-md-end">{{__('書籍名')}}</label>

            <div class="col-md-6">
                <input id="name" type="text" class="form-control @error('bookName') is-invalid @enderror" name="bookName" value="{{ old('bookName') }}" required autocomplete="name" maxlength="50" autofocus>

                @error('bookName')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="row mb-3 form-group">
            <label for="category" class="col-md-3 col-form-label text-md-end">{{ __('カテゴリ') }}</label>

            <div class="col-md-3">
                <select name="category" id="category" class="form-control @error('category') is-invalid @enderror" required autocomplete="category" area-label="default form-control">
                    @foreach($category_list as $c_key => $c_name)
                        <option value="{{ $c_key }}" @if(old('category')==$c_key) selected @endif>{{ $c_name }}</option>
                    @endforeach
                </select>

                @error('category')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="row mb-3 align-items-center form-group">
            <label for="theme" class="col-md-3 col-form-label text-md-end">{{__('テーマ')}}</label>

            <div class="col-md-3">
                <select name="theme" id="theme" class="form-control" @error('theme') is-invalid @enderror" required autocomplete="theme" area-label="default form-control">
                    @foreach($theme_list as $t_key => $t_name)
                        <option value="{{ $t_key }}" @if(old('theme')==$t_key) selected @endif>{{ $t_name }}</option>
                    @endforeach
                </select>

                @error('theme')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="row mb-3 d-flex align-items-center form-group">
            <label for="kind" class="col-md-3 col-form-label text-md-end">{{__('書籍種類')}}</label>

            <div class="col-md-3">
                <select name="kind" id="kind" class="form-control" @error('kind') is-invalid @enderror" required autocomplete="kind" area-label="default form-control">
                    @foreach($kind_list as $k_key => $k_name)
                        <option value="{{ $k_key }}" @if(old('kind')==$k_key) selected @endif>{{ $k_name }}</option>
                    @endforeach
                </select>

                @error('kind')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="row mb-3 form-group">
            <label for="company" class="col-md-3 col-form-label text-md-end">{{ __('開発会社') }}</label>

            <div class="col-md-3">
                <select name="company" id="company" class="form-control" @error('company') is-invalid @enderror" required autocomplete="company" area-label="default form-control">
                    @foreach($company_list as $co_key => $co_name)
                        <option value="{{ $co_key }}" @if(old('company')==$co_key) selected @endif>{{ $co_name }}</option>
                    @endforeach
                </select>

                @error('company')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        
        <div class="row mb-3 form-group">
            <label for="release" class="col-md-3 col-form-label text-md-end">{{ __('発売月') }}</label>

            <div class="col-md-6">
                <input id="release" type="month" class="form-control @error('release') is-invalid @enderror" name="release" required autocomplete="release" value="{{ old('relase') }}">

                @error('release')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <!-- Todo:jsにて残り文字数をリアルタイムで表示させる -->
        <div class="row mb-3 form-group">
            <label for="detail" class="col-md-3 col-form-label text-md-end">{{ __('詳細') }}</label>

            <div class="col-md-6">
                <textarea id="detail" rows="10" maxlength="500" class="form-control @error('detail') is-invalid @enderror" name="detail" maxlength="500" autocomplete="detail" required>{{ old('detail') }}</textarea>

                @error('detail')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary">
                {{ __('登録') }}
            </button>
        </div>
    </form>
</div>
@stop

@section('css')
    <style>
        .container p .no-image{
            display: none;
        }

        .container p .loaded-image{
            max-width: 500px;
            max-height: 500px;
            min-width: 200px;
            min-height: 200px;
            width: 70%;
        }
    </style>
@stop

@section('js')
    <script>
        function previewImage(obj)
        {
            const previewImage = document.getElementById('preview');
            const fileInput = document.getElementById('image_item');

            if (fileInput.files.length === 1) {
                var fileReader = new FileReader();
                fileReader.onload = function() {
                    previewImage.src = fileReader.result;

                    previewImage.classList.add('loaded-image');
                    previewImage.classList.remove('no-image');
                }
                fileReader.readAsDataURL(obj.files[0]);
            } else {
                previewImage.src = '';
                previewImage.classList.remove('loaded-image');
                previewImage.classList.add('no-image');
            }
        }
    </script>
@stop
