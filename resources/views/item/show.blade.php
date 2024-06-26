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
    <div class="container pb-5">
        <!-- 名前部分 -->
        <div class="row justify-content-center align-items-center mb-3">
            <h4 class="mr-2 mb-0">{{ $item->name }}の詳細</h4>
            @if(!Auth::guard('admin')->check())
            <span>
                <button class="btn">
                    <i class="bi bi-bookmark-check text-primary"></i>
                </button>
            </span>
            @endif
        </div>

        <!-- アイコン部分 -->
        <div class="text-center my-3">
            <img src="{{ $item->url }}" alt="書籍画像">
        </div>
        
        <!-- 変更と削除 -->
        @if(Auth::guard('admin')->check())
            <div class="row mb-1 align-items-middle justify-content-center">
                <a href="{{ route('items.edit', $item->id) }}" class="btn btn-outline-primary mr-2">変更</a>
                <form action="{{ route('items.delete', $item->id) }}" method="post" onsubmit='return confirm("本当に削除しますか？")'>
                @csrf
                @method('patch')
                    <button class="btn btn-outline-danger">削除</button>
                </form>
            </div>
        @endif
        
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
                    <th scope="row">所持者数</th>
                    <td>{{ $item->possesions_count }}人</td>
                </tr>
                <tr>
                    <th scope="row">詳細</th>
                    <td>{!! nl2br(htmlspecialchars($item->detail)) !!}</td>
                </tr>
            </tbody>
        </table>
    </div>
@stop

@section('css')
    <style>
        .row span i {
            font-size: 2rem;
        }

        .row span i:hover {
            color: #52BEFF !important;
        }

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