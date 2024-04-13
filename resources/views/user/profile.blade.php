@extends('adminlte::page')

<!-- Todo:if文で自分の時はマイプロフィール、そうじゃない時はプロフィール画面 -->
@if(true)
    @section('title', 'マイプロフィール')
@endif
{{ dd($user) }}
@section('content_header')
    <!-- Todo:if文 -->
    <h1>マイプロフィール</h1>
    <hr>
@stop

@section('content')
<!-- どう考えてもここに書くべきじゃないんだけど、CSSをどこに書いたらいいのか分からん -->
<style>
    /**
    ◇やりたいことを書いていきます。
    ・レスポンシブ対応を出来れば短く済ませたい。
    　こいつに時間を取られたくない。
    ・見出し幅でかすぎ。ちっちゃくしたい。
    ・なんかもうちょっとおしゃれにしたい。これはググった奴持ってくる。
    */

    .table th{
        background-color: #CFF4FC;
    }

    

</style>


<div class="container">
    <!-- ID部分 -->
    <div class="text-center mb-3">
        <h4>{{ $user->nickname }}さんのマイページ</h4>
    </div>

    <!-- アイコン部分 -->
    
    
    <table class="table table-bordered">
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
@stop

@section('js')
@stop
