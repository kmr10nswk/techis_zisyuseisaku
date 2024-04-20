@extends('adminlte::page')

@section('title', '商品一覧')

@section('content_header')
    <div class="row">
        <h1 class="col">商品一覧</h1>
        <div class="input-group input-group-sm col">
            <div class="input-group-append ml-auto">
                <a href="{{ url('items/add') }}" class="btn btn-default">商品登録</a>
            </div>
        </div>
    </div>
    <hr>
@stop

@section('content')
<i class="bi bi-bookmark-check"></i>
<div class="row align-items-stretch">
    @foreach($items as $item)
        <div class="col-lg-3 col-md-4 mb-3">
            <div class="card h-100 position-relative">
                <div class="card-img mx-auto">
                    <img src="{{ asset('storage/item/' . $item->image_item) }}" alt="書籍画像" class="mx-auto card-img-top d-block">
                    <i class="bi bi-bookmark-check text-primary position-absolute top-10 end-0"></i>
                </div>
                <hr>
                <div class="card-body py-0">
                    <div class="card-title text-center">{{ $item->name }}</div>
                    <p class="card-text text-center">{{ $item->release->format('Y/m') }}発売</p>
                </div>
                    <ul class="my-1">
                        <li class="c-tag">{{ $item->category }}</li>
                        <li class="t-tag">{{ $item->theme }}</li>
                        <li class="k-tag">{{ $item->kind }}</li>
                        <li class="co-tag">{{ $item->company }}</li>
                    </ul>
            </div>
        </div>
    @endforeach
</div>
@stop

@section('css')
<style>
    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 7px 14px rgba(50,50,93,.1), 0 3px 6px rgba(0,0,0,.08);
        transition: all .5s;
    }

    .row .card .card-title{
        font-size: 1.5rem;
        float:none;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .row .card hr{
        margin: 0.5rem 1rem;
    }

    .row .card .card-img .card-img-top{
        width: 70%;
        max-height: 220px;
        min-height: 220px;
        object-fit: contain;
    }

    .row .card ul{
        padding: 0 1.25rem;
    }

    .row .card ul li{
        font-size: 0.75rem;
        display: inline-block;
        margin: 0 .1em .6em 0;
        padding: 0.6em 0.8em;
        line-height: 1;
        text-decoration: none;
        border-radius: 2em;
    }

    .row .card ul .c-tag{
        background-color: #00a047;
        border: 1px solid #00a047;
        color: #fff;
    }

    .row .card ul .t-tag{
        background-color: #ed9e20;
        border: 1px solid #ed9e20;
        color: #fff;
    }

    .row .card ul .k-tag{
        background-color: #41aeed;
        border: 1px solid #41aeed;
        color: #fff;
    }

    .row .card ul .co-tag{
        background-color: #d9a0f0;
        border: 1px solid #d9a0f0;
        color: #fff;
    }
</style>
@stop

@section('js')
@stop
