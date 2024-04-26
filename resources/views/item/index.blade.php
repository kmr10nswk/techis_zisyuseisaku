@extends('adminlte::page')

@section('title', '商品一覧')

@section('content_header')
    <div class="row">
        <h1 class="mr-3">商品一覧</h1>
        <div class="input-group input-group-sm col">
            <div class="input-group-append ml-auto">
                @if(Auth::guard('admin')->check())
                    <a href="{{ url('items/add') }}" class="btn btn-default">商品登録</a>
                @endif
            </div>
        </div>
    </div>
    <hr style="margin-bottom: 1px;">
@stop

@section('content')
    <!-- 検索欄 -->
    <button class="btn btn-outline-light mb-1" data-toggle="collapse" data-target="#search-ori" aria-expand="false" aria-controls="search-ori">検索欄の表示</button>
    <div class="collapse" id="search-ori">
        <form action="" method="get" class="mb-2" role="search">
            <ul class="col-md-12 col-sm-12 p-0 row">
                <li class="col-lg-3 col-md-4 col-sm-12">
                    <label for="search_free" class="form-label mb-0 @error('search_free') is-invalid @enderror">フリーワード</label>
                    <input type="serach" id="search_free" name="search_free" value="{{ isset($search['search_free']) ? $search['search_free'] : '' }}" class="form-control" placeholder="フリーワード">

                    @error('search_free')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </li>
                <li class="col-lg-1 col-md-2 col-sm-4">
                    <label for="search_category" class="form-label mb-0">カテゴリ</label>
                    <select name="search_category" id="search_category" class="form-control @error('search_category') is-invalid @enderror">
                        <option></option>
                            @foreach($types['categories'] as $key => $value)
                                <option value="{{ $key }}" @if(isset($search['search_category']) && $search['search_category'] == $key) selected @endif>{{ $value }}</option>
                            @endforeach
                    </select>

                    @error('search_category')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </li>
                <li class="col-lg-2 col-md-3 col-sm-5">
                    <label for="search_theme" class="form-label mb-0">テーマ</label>
                    <select name="search_theme" id="search_theme" class="form-control @error('search_theme') is-invalid @enderror">
                        <option></option>
                            @foreach($types['themes'] as $key => $value)
                                <option value="{{ $key }}" @if(isset($search['search_theme']) && $search['search_theme'] == $key) selected @endif>{{ $value }}</option>
                            @endforeach
                    </select>

                    @error('search_theme')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </li>
                <li class="col-lg-2 col-md-3 col-sm-5">
                    <label for="search_kind" class="form-label mb-0">書籍種類</label>
                    <select name="search_kind" id="search_kind" class="form-control @error('search_kind') is-invalid @enderror">
                        <option></option>
                            @foreach($types['kinds'] as $key => $value)
                                <option value="{{ $key }}" @if(isset($search['search_kind']) && $search['search_kind'] == $key) selected @endif>{{ $value }}</option>
                            @endforeach
                    </select>

                    @error('search_kind')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                </li>
                <li class="col-lg-2 col-md-3 col-sm-5">
                    <label for="search_company" class="form-label mb-0">会社名</label>
                    <select name="search_company" id="search_company" class="form-control @error('search_company') is-invalid @enderror">
                        <option></option>
                            @foreach($types['companies'] as $key => $value)
                                <option value="{{ $key }}" @if(isset($search['search_company']) && $search['search_company'] == $key) selected @endif>{{ $value }}</option>
                            @endforeach
                    </select>

                    @error('search_company')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </li>
                <li class="col-lg-2 col-md-3 col-sm-5">
                    <label for="search_possesion" class="form-label mb-0">所持者数</label>
                    <div class="row">
                        <div class="col-lg-3 col-md-4 col-sm-2 pr-1">
                            <input name="search_possesion" type="text" class="form-control @error('search_possesion') is-invalid @enderror" id="search_possesion" placeholder="0~">

                            @error('search_possesion')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-3 p-0">
                            <select name="search_condition" class="form-control @error('search_condition') is-invalid @enderror">
                                <option value="up">以上</option>
                                <option value="down">以下</option>
                            </select>
                            
                            @error('search_condition')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </li>
                <li class="col-md-3 d-flex flex-row align-items-end">
                    <button class="mt-2 mr-2 btn btn-outline-success" type="submit">検索</button>
                    <!-- クリアボタン -->
                    <button type="submit" class="mt-2 btn btn-outline-info" id="clearSearch">クリア</button>
                </li>
            </ul>
            <div class="col-4">
            </div>
        </form>
    </div>

    <div class="tabbox">
        <div class="row">
            <!-- 切り替えボタン -->
            <div class="tabselect">
                <input type="radio" name="tabset" id="card-check" checked>
                <label for="card-check" class="tab_label">カード型</label>
                <input type="radio" name="tabset" id="table-check">
                <label for="table-check">テーブル型</label>
            </div>
            <!-- 並び替えボタン -->
            <div class="align-items-end ml-auto mr-3 orderselect">
                <form action="" method="get" id="orderForm">
                    <select name="order" id="order" class="form-control">
                        @foreach($types['orders'] as $key => $value)
                            <option value="{{ $key }}" @if(isset($order['order']) && $order['order'] == $key) selected @endif>{{ $value }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>

        <!-- フラッシュメッセージ欄 -->
        @if (isset($nothing_message))
            <div class="alert alert-light col-5">
                {{ $nothing_message }}
            </div>
        @endif

        <!-- カード用コンテンツ -->
        <div id="card-content" class="tabcontent">
            <div class="row align-items-stretch">
                @foreach($items as $item)
                    <div class="col-lg-3 col-md-4 mb-3">
                        <div class="card h-100 position-relative">
                            @if(!Auth::guard('admin')->check())
                                @if(!$item->has)
                                        <button class="btn position-absolute">
                                            <i class="bi bi-bookmark-check text-primary p-icon" id="card-icon{{ $item->id }}" data-possesion-id="{{ $item->id }}" data-has-id="{{ $item->has }}"></i>
                                        </button>
                                @else
                                        <button class="btn position-absolute">
                                            <i class="bi bi-bookmark-check-fill text-primary p-icon" id="card-icon{{ $item->id }}" data-possesion-id="{{ $item->id }}" data-has-id="{{ $item->has }}"></i>
                                        </button>
                                @endif
                            @endif
                            <div class="card-img mx-auto">
                                <img src="{{ $item->url }}" alt="書籍画像" class="mx-auto card-img-top d-block">
                            </div>
                            <hr>
                            <div class="card-body py-0">
                                <div class="card-title text-center">
                                    <a href="{{ route('items.show', $item) }}">{{ $item->name }}</a>
                                </div>
                                <p class="card-text text-center mb-1">{{ $item->release->format('Y/m') }}発売</p>
                                <p class="card-text text-center">所持者数：<span class="p-count" id="card-count{{ $item->id }}">{{ $item->possesions_count }}</span></p>
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
                            @if(!Auth::guard('admin')->check())
                                <th><i class="bi bi-check-square"></i><span>所持</span></th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td><img src="{{ $item->url }}" alt="書籍画像" class="mx-auto"></td>
                                <td class="t-name"><a href="{{ route('items.show', $item) }}">{{ $item->name }}</a></td>
                                <td>{{ $item->category }}</td>
                                <td>{{ $item->theme }}</td>
                                <td>{{ $item->kind }}</td>
                                <td>{{ $item->company }}</td>
                                <td>{{ $item->release->format('Y/m') }}</td>
                                <td><span class="p-count" id="table-count{{ $item->id }}">{{ $item->possesions_count }}</span>人</td>
                                @if(!Auth::guard('admin')->check())
                                    @if(!$item->has)
                                        <td class="text-center"><button class="btn-sm btn-outline-primary p-icon" id="table-icon{{ $item->id }}" data-possesion-id="{{ $item->id }}" data-has-id="{{ $item->has }}">✓</button></td>
                                    @else
                                        <td class="text-center"><button class="btn-sm btn-primary p-icon" id="table-icon{{ $item->id }}" data-possesion-id="{{ $item->id }}" data-has-id="{{ $item->has }}">✓</button></td>
                                    @endif
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="d-flex justify-content-center">
            {{ $items->appends(request()->query())->links() }}
        </div>
    </div>
@stop

@section('css')
    <style>
        /* 検索欄 */
        form ul {
            list-style: none;
        }

        form ul li label{
            font-size: 0.9rem;
            padding-left: 0;
        }

        form ul li input {
            padding-left: 0;
        }

        @media screen and (max-width: 576px) {
            form ul li .row .col-sm-2{
                width: 20%;
            }
            form ul li .row .col-sm-3{
                width: 30%;
            }
        }

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

        /* 並び替え欄 */
        .tabbox .row .orderselect {
            potision: relative;
            padding-right: 7.5px;
            padding-left: 7.5px;
        }

        @media screen and (max-width: 991px) {
            .tabbox .row .orderselect {
                flex: 0 0 20%;
                max-width: 20%;
            }
        }
        
        @media screen and (max-width: 767px) {
            .tabbox .row .orderselect {
                flex: 0 0 40%;
                max-width: 40%;
            }
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
        // localStorageから選択状態を復元
        $(function () {
            const cardButton = $('#card-check');
            const tableButton = $('#table-check');
            const cardContent = $('#card-content');
            const tableContent = $('#table-content');

            // selectしてた方を更新しても保存しておく。
            $(document).ready(function() {
                if(localStorage.getItem('selectedTab') === '#table-check'){
                    localStorage.setItem('selectedTab', '#table-check');
                    cardContent.hide();
                    tableContent.show();
                    $('#table-check').prop('checked', true);
                }
            });

            // カードとテーブルの切り替え
            cardButton.on('click', function() {
                tableContent.hide();
                cardContent.show();

                localStorage.setItem('selectedTab', '#card-check');
            });
            tableButton.on('click', function() {
                cardContent.hide();
                tableContent.show();
                
                localStorage.setItem('selectedTab', '#table-check');
            });

            // 検索クリアボタン
            $('#clearSearch').on('click',function() {
                $('#search_free').val('');
                $('#search_category').val('');
                $('#search_theme').val('');
                $('#search_kind').val('');
                $('#search_company').val('');
                $('#search_possesion').val('');
            });

            // 並び替え実行ボタン
            $('#orderForm').on('change', function() {
                $('#orderForm').submit();
            });

            // 所持機能 非同期
            $('.p-icon').on('click', function () {
                const possesion_id = $(this).data('possesion-id');
                const has_id = $(this).data('has-id');
                const possesion_obj = $(this);
                if($(this).hasClass('text-primary')){
                    var possesion_count_obj = $('#card-count' + possesion_id);
                    var row_icon = $('#table-icon' + possesion_id);
                    var row_count_obj = $('#table-count' + possesion_id);
                    var row_count = Number(row_count_obj.html());
                } else {
                    var possesion_count_obj = $('#table-count' + possesion_id);
                    var row_icon = $('#card-icon' + possesion_id);
                    var row_count_obj = $('#card-count' + possesion_id);
                    var row_count = Number(row_count_obj.html());
                }
                let possesion_count = Number(possesion_count_obj.html());
                
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
                        // p-count
                        possesion_count--;
                        possesion_count_obj.html(possesion_count);
                        $(this).data('has-id', false);

                        // p-icon
                        if($(this).hasClass('bi-bookmark-check-fill')){
                            possesion_obj.removeClass('bi-bookmark-check-fill');
                            possesion_obj.addClass('bi-bookmark-check');

                            // カード表示を押したらテーブルにもclass反映
                            row_icon.removeClass('btn-primary');
                            row_icon.addClass('btn-outline-primary');
                            
                            row_count--;
                            row_count_obj.html(row_count);
                        } else if ($(this).hasClass('btn-primary')){
                            possesion_obj.removeClass('btn-primary');
                            possesion_obj.addClass('btn-outline-primary');

                            // テーブル表示を押したらカードにもclass反映
                            row_icon.removeClass('bi-bookmark-check-fill');
                            row_icon.addClass('bi-bookmark-check');
                            
                            row_count--;
                            row_count_obj.html(row_count);
                        }
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
                        // p-count
                        possesion_count++;
                        possesion_count_obj.html(possesion_count);
                        $(this).data('has-id', true);

                        // p-icon
                        if($(this).hasClass('bi-bookmark-check')){
                            possesion_obj.removeClass('bi-bookmark-check');
                            possesion_obj.addClass('bi-bookmark-check-fill');
                            
                            // カード表示を押したらテーブルにもclass反映
                            row_icon.removeClass('btn-outline-primary');
                            row_icon.addClass('btn-primary');
                            
                            row_count++;
                            row_count_obj.html(row_count);
                        } else if($(this).hasClass('btn-outline-primary')){
                            possesion_obj.removeClass('btn-outline-primary');
                            possesion_obj.addClass('btn-primary');
                            
                            // テーブル表示を押したらカードにもclass反映
                            row_icon.removeClass('bi-bookmark-check');
                            row_icon.addClass('bi-bookmark-check-fill');
                            
                            row_count++;
                            row_count_obj.html(row_count);
                        }
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
