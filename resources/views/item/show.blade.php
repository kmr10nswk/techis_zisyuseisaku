@extends('adminlte::page')

@section('title', '商品詳細')

@section('content_header')
    <div class="row">
        <h1 class="col">商品詳細</h1>
        <div class="input-group input-group-sm col">
            <div class="input-group-append ml-auto">
                <a href="{{ url('items/') }}" class="btn btn-default">戻る</a>
            </div>
        </div>
    </div>
    <hr>
@stop

@section('content')
    <div class="container">
        <!-- 名前部分 -->
        <div class="text-center mb-3">
            <h4>{{ $item->name }}の詳細</h4>
        </div>

        <!-- アイコン部分 -->
        <div class="text-center my-3">
            <img src="{{ asset('storage/item/' . $item->image_item) }}" alt="書籍画像">
        </div>
        
        
        <table class="table table-bordered mx-auto">
            <tbody>
                <tr>
                    <th scope="row">名前</th>
                    <td>{{ $item->name }}</td>
                </tr>
                <tr>
                    <th scope="row">カテゴリ</th>
                    <td>{{ $item->category }}</td>
                </tr>
                <tr>
                    <th scope="row">テーマ</th>
                    <td>{{ $item->theme }}</td>
                </tr>
                <tr>
                    <th scope="row">書籍種類</th>
                    <td>{{ $item->kind }}</td>
                </tr>
                <tr>
                    <th scope="row">会社</th>
                    <td>{{ $item->company }}</td>
                </tr>
                <tr>
                    <th scope="row">発売月</th>
                    <td>{{ $item->release->format('Y/m') }}</td>
                </tr>
                <tr>
                    <!-- Todo:所持ルールブックの対応 -->
                    <th scope="row">所持者数</th>
                    <td>あとでね</td>
                </tr>
                <tr>
                    <th scope="row">詳細</th>
                    <td>{{ $item->detail }}</td>
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
            background-color: #d5fccf;
            width: 20%;
            @media screen and (max-width: 915px){
                width: 25%;
            }
        }

        .container .text-center img{
            max-width: 330px;
            max-height: 330px;
            width: 40%;
            object-fit: contain;
        }
    </style>
@stop

@section('js')
@stop