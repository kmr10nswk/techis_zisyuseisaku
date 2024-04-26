@extends('adminlte::page')

@if($checkUser === $user->id)
    @section('title', 'マイプロフィール')
@else
    @section('title', '{{ $user->nickname }}さんのプロフィール')
@endif

@section('content_header')
    <div class="row">
    @if($checkUser === $user->id)
        <h1>マイプロフィール</h1>
    @else
        <h1>{{ $user->nickname }}さんのプロフィール</h1>
    @endif
        <div class="input-group input-group-sm col">
            <div class="input-group-append ml-auto">
                <a href="{{ url('users/') }}" class="btn btn-default">戻る</a>
            </div>
        </div>
    </div>
    <hr>
@stop

@section('content')
    <div class="container pb-5">
        <div class="text-center mb-3">
            <h4>{{ $user->nickname }}さんのマイページ</h4>
        </div>

        <div class="text-center my-3">
            <img src="{{ $user->url }}" alt="プロフィールアイコン">
        </div>
        
        <table class="table table-bordered mx-auto">
            <tbody>
                <tr>
                    <th scope="row ">ID</th>
                    <td>{{ $user->name }}</td>
                </tr>
                <tr>
                    <th scope="row">名前</th>
                    <td>{{ $user->nickname }}</td>
                </tr>
                <tr>
                    <th scope="row">GM/PL</th>
                    <td>{{ $user->gmpl }}</td>
                </tr>
                <tr>
                    <th scope="row">セッションスタイル</th>
                    <td>{{ $user->session_style }}</td>
                </tr>
                <tr>
                    <th scope="row">所持ルールブック</th>
                    <td>
                        @foreach($user->possesions as $possesion)
                            {{ $possesion->name }}、 
                        @endforeach
                    </td>
                </tr>
                <tr>
                    <th scope="row">自己紹介</th>
                    <td>{{ $user->comment }}</td>
                </tr>
            </tbody>
        </table>
    </div>
@stop

@section('css')
    <style>
        .container .table{
            max-width: 900px;
        }

        .table th{
            background-color: #CFF4FC;
            width: 20%;
            @media screen and (max-width: 915px){
                width: 30%;
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
