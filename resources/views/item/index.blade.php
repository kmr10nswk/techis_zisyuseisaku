@extends('adminlte::page')

@section('title', '商品一覧')

@section('content_header')
    <div class="row">
        <h1 class="mr-3">商品一覧</h1>
        <div class="input-group input-group-sm col">
            <div class="input-group-append ml-auto">
                <!-- Todo:admin管理 -->
                <a href="{{ url('items/add') }}" class="btn btn-default">商品登録</a>
            </div>
        </div>
    </div>
    <hr style="margin-bottom: 1px;">
@stop

@section('content')
    <div class="tabbox">
        <div class="tabselect">
            <input type="radio" name="tabset" id="card-check" checked>
            <label for="card-check" class="tab_label">カード型</label>
            <input type="radio" name="tabset" id="table-check">
            <label for="table-check">テーブル型</label>
        </div>
        <!-- カード用コンテンツ -->
        <div id="card-content" class="tabcontent">
            <div class="row align-items-stretch">
                @foreach($items as $item)
                    <div class="col-lg-3 col-md-4 mb-3">
                        <div class="card h-100 position-relative">
                            @if(Auth::user())
                                @if(!$item->has)
                                        <button class="btn position-absolute">
                                            <i class="bi bi-bookmark-check text-primary p-icon" data-possesion-id="{{ $item->id }}" data-has-id="{{ $item->has }}"></i>
                                        </button>
                                @else
                                        <button class="btn position-absolute">
                                            <i class="bi bi-bookmark-check-fill text-primary p-icon" data-possesion-id="{{ $item->id }}" data-has-id="{{ $item->has }}"></i>
                                        </button>
                                @endif
                            @endif
                            <div class="card-img mx-auto">
                                <img src="{{ asset('storage/item/' . $item->image_item) }}" alt="書籍画像" class="mx-auto card-img-top d-block">
                            </div>
                            <hr>
                            <div class="card-body py-0">
                                <div class="card-title text-center">
                                    <a href="{{ route('items.show', $item) }}">{{ $item->name }}</a>
                                </div>
                                <p class="card-text text-center mb-1">{{ $item->release->format('Y/m') }}発売</p>
                                <p class="card-text text-center">所持者数：<span class="p-count">{{ $item->possesions_count }}</span></p>
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
        </div>

        <!-- テーブル用コンテンツ -->
        <div id="table-content" class="tabcontent">
            <div class="table-responsive-sm">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th></th>
                            <th class="t-name"><i class="bi bi-book"></i><span>名前</span></th>
                            <th><i class="bi bi-bar-chart"></i><span>カテゴリ</span></th>
                            <th><i class="bi bi-flag"></i><span>テーマ</span></th>
                            <th><i class="bi bi-journals"></i><span>書籍種類</span></th>
                            <th><i class="bi bi-building"></i><span>会社名</span></th>
                            <th><i class="bi bi-clock"></i><span>発売月</span></th>
                            <th><i class="bi bi-bookmark-check"></i><span>所持者数</span></th>
                            @if(Auth::user())
                                <th><i class="bi bi-check-square"></i><span>所持</span></th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td><img src="{{ asset('storage/item/' . $item->image_item) }}" alt="書籍画像" class="mx-auto"></td>
                                <td class="t-name"><a href="{{ route('items.show', $item) }}">{{ $item->name }}</a></td>
                                <td>{{ $item->category }}</td>
                                <td>{{ $item->theme }}</td>
                                <td>{{ $item->kind }}</td>
                                <td>{{ $item->company }}</td>
                                <td>{{ $item->release->format('Y/m') }}</td>
                                <td>{{ intval($item->possesions_count) }}人</td>
                                @if(Auth::user())
                                    @if(!$item->has)
                                        <td class="text-center"><button class="btn-sm btn-outline-primary p-icon" data-possesion-id="{{ $item->id }}" data-has-id="{{ $item->has }}">✓</button></td>
                                    @else
                                        <td class="text-center"><button class="btn-sm btn-primary p-icon" data-possesion-id="{{ $item->id }}" data-has-id="{{ $item->has }}">✓</button></td>
                                    @endif
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        /* ラジオボタン */
        .tabselect{
            display: flex;
            justify-content: center;
            border-radius: 3px;
            overflow: hidden;
            border: 1px solid #b6b6b6;
            max-width: 200px;
            width: 50%;
            margin-bottom: 10px;
        }

        .tabselect input{
            display: none;
        }

        .tabselect label{
            width: 100%;
            margin: 1px 0;
            padding: 0.8em;
            border-right: 1px solid #d7d7d7;
            background: #f1f3f9;
            color: #555e64;
            font-size: 0.8rem;
            text-align: center;
            line-height: 1;
            transition: 0.2s;
        }

        .tabselect input[type="radio"]:checked + label {
            background-color: #0090ff;
            border-radius: 3px;
            color: #fff;
            margin: 0;
        }

        /* カード型  */
        #card-content .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 7px 14px rgba(50,50,93,.1), 0 3px 6px rgba(0,0,0,.08);
            transition: all .5s;
        }

        #card-content .row .card .card-title{
            font-size: 1.5rem;
            float:none;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        #card-content .row .card .card-title a{
            color: #353535;
        }

        #card-content .row .card .card-title a:hover{
            color: #007BFF;
            text-decoration: underline;
        }

        #card-content .row .card hr{
            margin: 0.5rem 1rem;
        }

        #card-content .row .card button .p-icon{
            font-size: 2.5rem;
        }

        #card-content .row .card button .p-icon:hover {
            color: #52BEFF !important;
        }

        #card-content .row .card .card-img .card-img-top{
            width: 70%;
            max-height: 220px;
            min-height: 220px;
            object-fit: contain;
        }

        #card-content .row .card .card-body .p-count{
            font-size: 0.95rem;
        }

        #card-content .row .card ul{
            padding: 0 1.25rem;
        }

        #card-content .row .card ul li{
            font-size: 0.75rem;
            display: inline-block;
            margin: 0 .1em .6em 0;
            padding: 0.6em 0.8em;
            line-height: 1;
            text-decoration: none;
            border-radius: 2em;
        }

        #card-content .row .card ul .c-tag{
            background-color: #00a047;
            border: 1px solid #00a047;
            color: #fff;
        }

        #card-content .row .card ul .t-tag{
            background-color: #ed9e20;
            border: 1px solid #ed9e20;
            color: #fff;
        }

        #card-content .row .card ul .k-tag{
            background-color: #41aeed;
            border: 1px solid #41aeed;
            color: #fff;
        }

        #card-content .row .card ul .co-tag{
            background-color: #d9a0f0;
            border: 1px solid #d9a0f0;
            color: #fff;
        }

        /* テーブル型 */
        #table-content {
            display: none;
        }

        #table-content .table thead tr th i{
            display: none;
        }

        #table-content .table tbody tr td img{
            max-width: 50px;
            object-fit: contain;
        }

        #table-content .table tbody tr td a{
            color: #353535;
        }

        #table-content .table tbody tr td a:hover{
            color: #007BFF;
            text-decoration: underline;
        }

        #table-content .table thead tr th {
            border-right: 1px solid #dee2e6;
            background-color: #d5fccf;
            text-align: center;

            /* 固定 */
            position: -webkit-sticky;
            position: sticky;
            top: 0;
            z-index: 1;
        }

        #table-content .table thead th:last-child{
            border-right: none;
        }

        #table-content .table tbody td {
            border-right: 1px solid #dee2e6;
        }

        #table-content .table tbody td:last-child{
            border-right: none;
        }

        @media screen and (max-width: 1200px) {
            #table-content .table{
                font-size: 0.85rem
            }

            #table-content .table td{
                padding: 0.3rem
            }

            #table-content .table thead tr th {
                font-size: 0.8rem;
            }

            #table-content .table .t-name {
                max-width: 100px;
                overflow-wrap: break-word;
            }
        }

        @media screen and (max-width: 415px) {
            #table-content .table tbody tr td {
                min-width: 70px;
                font-size: 0.7rem;
            }

            #table-content .table tbody tr .t-name{
                font-size: 0.9rem;
            }

            #table-content .table thead tr th span{
                display: none !important;
            }
        
            #table-content .table thead tr th i{
                display: block !important;
                font-size: 1rem;
            }
        }
    </style>
@stop

@section('js')
    <script>
        $(function () {
            // カードとテーブルの切り替え
            const cardButton = $('#card-check');
            const tableButton = $('#table-check');
            const cardContent = $('#card-content');
            const tableContent = $('#table-content');

            cardButton.on('click', function() {
                tableContent.hide();
                cardContent.show();
            });

            tableButton.on('click', function() {
                cardContent.hide();
                tableContent.show();
            });

            // 所持機能 非同期
            $('.p-icon').on('click', function () {
                const possesion_id = $(this).data('possesion-id');
                const has_id = $(this).data('has-id');
                const possesion_obj = $('.p-icon');
                const possesion_count_obj = $('.p-count');
                let possesion_count = Number(possesion_obj.html());
                
                if(has_id){
                    // 取り消し
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                        },
                        url: '/api/possesions/remove',
                        type: 'DELETE',
                        data: {
                            'possesion_id' : possesion_id
                        },
                        timeout: 10000
                    })
                    .done(() => {
                        possesion_count--;
                        possesion_count_obj.html(possesion_count+1);
                        $(this).data('has-id', false);
                        possesion_obj.removeClass('bi-bookmark-check-fill', 'btn-primary');
                        possesion_obj.addClass('bi-bookmark-check', 'btn-outline-primary');
                    })
                    .fail((data) => {
                        alert('処理中にエラーが発生しました。');
                        console.log(data);
                    });
                } else {
                    // 追加
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
                        },
                        url: '/api/possesions/add',
                        type: 'POST',
                        data: {
                            'possesion_id' : possesion_id
                        },
                        timeout: 10000
                    })
                    .done((data) => {
                        possesion_count++;
                        possesion_count_obj.html(possesion_count);
                        $(this).data('has-id', true);
                        possesion_obj.removeClass('bi-bookmark-check', 'btn-outline-primary');
                        possesion_obj.addClass('bi-bookmark-check-fill', 'btn-primary');
                    })
                    .fail((data) => {
                        alert('処理中にエラーが発生しました。');
                        console.log(data);
                    });
                }
            });
        });
    </script>
@stop
