@extends('adminlte::page')

<!-- Todo:if文で自分の時はマイプロフィール、そうじゃない時はプロフィール画面 -->
@if(true)
    @section('title', 'マイプロフィール')
@endif

@section('content_header')
    <!-- Todo:if文 -->
    <h1>マイプロフィール</h1>
    <hr>
@stop

@section('content')
    <div class="container">
        <!-- 名前部分 -->
        <div class="text-center mb-3">
            <h4>{{ $user->nickname }}さんのマイページ</h4>
        </div>

        <!-- アイコン部分 -->
        <div class="text-center my-3">
            <img src="{{ asset('storage/icon/' . $user->image_icon) }}" alt="プロフィールアイコン">
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
                    <!-- Todo:所持ルールブックの対応 -->
                    <th scope="row">所持ルールブック</th>
                    <td>あとでね</td>
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
    /**
    ◇やりたいことを書いていきます。
    ・レスポンシブ対応を出来れば短く済ませたい。
    　こいつに時間を取られたくない。
    ・なんかもうちょっとおしゃれにしたい。これはググった奴持ってくる。
    */

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
