<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Google\Cloud\Storage\StorageClient;

use App\Models\Item;
use App\Models\User;
use App\Models\Possesion;
use DateTime;

class ItemController extends Controller
{
    /**
     * 商品一覧
     */
    public function index(Request $request)
    {
        $items = Item::query()->withCount('possesions');
        $types['categories'] = Item::category_list();
        $types['themes'] = Item::theme_list();
        $types['kinds'] = Item::kind_list();
        $types['companies'] = Item::company_list();
        $types['orders'] = [
            'add_desc_order' => '登録日(降順)',
            'add_asc_order' => '登録日(昇順)',
            'desc_order' => '発売日(降順)',
            'asc_order' => '発売日(昇順)',
            'has_desc_order' => '所持者数(降順)',
            'has_asc_order' => '所持者数(昇順)',
            'name_order' => '名前順',
        ];

        // 検索用
        $search = $request->only(['search_free', 'search_category', 'search_theme', 'search_kind', 'search_company', 'search_possesion', 'search_condition']);

        $this->searchValidator($request->all())->validate();
        $items = $this->search($items, $search);

        // 並び替え用
        $this->Validate($request, [
            'order' => ['in:' . implode(',', array_keys($types['orders']))]
        ]);
        $order = $request->only('order');
        $items = $this->order($items, $order);

        // 通常
        $items = $items->where('status', 'active')
            ->paginate(20)->withQueryString();
        
        // blade整え
        $items = Item::listSeiton($items);
        if(!Auth::guard('admin')->check()){
            foreach($items as $item) {
                $item['has'] = Auth::user()->is_possesion($item->id);
            }
        }
        
        $nothing_message = null;
        if($items->first() === null) {
            $nothing_message =  '検索結果はありません';
        }
        
        // 画像読み込み
        $client = new StorageClient();
        $bucket_name = app()->isLocal()
            ? 'item-management-local'
            : 'item-manegement';
        $bucket = $client->bucket($bucket_name);
        foreach($items as $item){
            // signedUrlで簡単に署名付きURLが取得できる
            $item->url = $bucket->object($item->image_item)->signedUrl(
                new \Datetime('tomorrow'),
            );
        }

        return view('item.index', compact('items', 'search' , 'order', 'types', 'nothing_message'));
    }

    /**
     * 商品登録
     */
    public function add(Request $request)
    {
        // POSTリクエストのとき
        if ($request->isMethod('post')) {
            // バリデーション
            $this->validate($request, [
                'name' => ['required', 'max:50', 'string'],
                'category' => ['required', 'between:1,4'],
                'theme' => ['required', 'between:1,7',],
                'kind' => ['required', 'between:1,3',],
                'company' => ['required', 'between:1,4',],
                'release' => ['required', 'date'],
                'detail' => ['required', 'max:500', 'string'],
                'image_item' => ['nullable', 'image', 'mimes:jpeg,png', 'max:1024'],
            ]);

            // 画像処理
            $file = $request->file('image_item');
            $file_name = isset($file)
                ? User::uploadImage($file, 'item') 
                : 'item/default_item_1.jpg';

            // 商品登録
            Item::create([
                'name' => $request->name,
                'category' => $request->category,
                'theme' => $request->theme,
                'kind' => $request->kind,
                'company' => $request->company,
                'release' => $request->release,
                'detail' => $request->detail,
                'image_item' => $file_name,
            ]);

            return redirect('/items');
        }

        // GETリクエストのとき
        // 各リストを引き出す
        $category_list = Item::category_list();
        $theme_list = Item::theme_list();
        $kind_list = Item::kind_list();
        $company_list = Item::company_list();

        return view('item.add', compact('category_list', 'theme_list', 'kind_list', 'company_list'));
    }

    /**
     * 商品詳細画面
     */
    public function show($id)
    {
        $item_obj = Item::where('id', $id)->withCount('possesions')->where('status', 'active');
        $items =  $item_obj->get();

        // blade整え
        $items = Item::listSeiton($items);
        $item = $items->first();

        // 存在しない商品の場合は戻る
        if(!isset($item)){
            return back();
        }
        
        // 画像読み込み
        $client = new StorageClient();
        $bucket_name = app()->isLocal()
            ? 'item-management-local'
            : 'item-manegement';
        $bucket = $client->bucket($bucket_name);
        // signedUrlで簡単に署名付きURLが取得できる
        $item->url = $bucket->object($item->image_item)->signedUrl(
            new \Datetime('tomorrow'),
        );

        return view('item.show', compact('item'));
    }

    /**
     * 商品変更画面
     */
    public function edit($id)
    {
        $item = Item::find($id);

        // 存在しない商品の場合は戻る
        if($item->status === 'deleted'){
            return back();
        }

        // 各リストを引き出す
        $category_list = Item::category_list();
        $theme_list = Item::theme_list();
        $kind_list = Item::kind_list();
        $company_list = Item::company_list();

        // 画像の取得
        $client = new StorageClient();
        $bucket_name = app()->isLocal()
            ? 'item-management-local'
            : 'item-manegement';
        $bucket = $client->bucket($bucket_name);
        // signedUrlで簡単に署名付きURLが取得できる
        $item->url = $bucket->object($item->image_item)->signedUrl(
            new \Datetime('tomorrow'),
        );

        return view('item.edit', compact('item', 'category_list', 'theme_list', 'kind_list', 'company_list'));
    }

    /**
     * 商品変更処理
     */
    public function update(Request $request, Item $item)
    {
        $Rdate = substr($request->release, 0, 4) . '/';
        $Rdate .= substr($request->release, 5);
        $Rdate .= '/01';
        $request->release = new DateTime($Rdate);

        // バリデーション
        $this->validate($request, [
            'name' => ['required', 'max:50', 'string'],
            'category' => ['required', 'between:1,4'],
            'theme' => ['required', 'between:1,7',],
            'kind' => ['required', 'between:1,3',],
            'company' => ['required', 'between:1,4',],
            'release' => ['required', 'date'],
            'detail' => ['required', 'max:500', 'string'],
            'image_item' => ['nullable', 'image', 'mimes:jpeg,png', 'max:1024'],
        ]);

        // 画像処理
        $file = $request->file('image_item');
        $file_name = isset($file)
            ? User::uploadImage($file, 'item')
            : $item->image_item;

        // 商品上書き
        Item::where('id', $item->id)
            ->update([
                'name' => $request->name,
                'category' => $request->category,
                'theme' => $request->theme,
                'kind' => $request->kind,
                'company' => $request->company,
                'release' => $request->release,
                'detail' => $request->detail,
                'image_item' => $file_name,
            ]);

        return redirect()->route('items.edit', $item->id);
    }

    /**
     * 商品削除処理
     */
    public function delete($id){
        $item = Item::where('id', $id)
        ->update([
            'status' => 'deleted',
        ]);

    return redirect()->route('items.index');
    }

    /**
     * 検索用
     */
    public function searchValidator(array $data){
        return Validator::make($data,[
            'search_free' => ['string', 'max:20', 'nullable'],
            'search_category' => ['between:1,4', 'nullable'],
            'search_theme' => ['between:1,7', 'nullable'],
            'search_kind' => ['between:1,3', 'nullable'],
            'search_company' => ['between:1,4', 'nullable'],
            'search_possesion' => ['numeric', 'nullable'],
            'search_condition' => ['in:up,down', 'nullable'],
        ]);
    }
    
    public function search($query, $search)
    {
        // 検索クエリ(多分本当はsearch用のファイルを作った方が良い気がする)
        if(isset($search['search_free'])){  
            $query->where(function($query) use ($search){
                $query->orWhere('name', 'like', '%' . $search['search_free'] . '%')
                ->orWhere('detail', 'like', '%' . $search['search_free'] . '%');
            });
        }

        if(isset($search['search_category'])){
            $query->where('category', $search['search_category']);
        }

        if(isset($search['search_theme'])){
            $query->where('theme', $search['search_theme']);
        }

        if(isset($search['search_kind'])){
            $query->where('kind', $search['search_kind']);
        }

        if(isset($search['search_company'])){
            $query->where('company', $search['search_company']);
        }

        if(isset($search['search_possesion'])){
            if($search['search_condition'] === 'up'){
                $query->having('possesions_count', '>=' , $search['search_possesion']);
            } elseif($search['search_condition'] === 'down') {
                $query->having('possesions_count', '<=' , $search['search_possesion']);
            }
        }

        return $query;
    }

    /**
     * 並び替え用
     */
    public function order($query, $order)
    {
        if(!isset($order['order']) || $order['order'] === 'add_desc_order'){
            $query = $query->orderby('created_at', 'desc');
            return $query;
        }

        if(!isset($order) || $order['order'] === 'add_asc_order'){
            $query = $query->orderby('created_at', 'asc');
            return $query;
        }

        if($order['order'] === 'name_order'){
            $query = $query->orderby('name', 'asc');
            return $query;
        }

        if($order['order'] === 'desc_order'){
            $query = $query->orderby('release', 'desc');
            return $query;
        }
        
        if($order['order'] === 'asc_order'){
            $query = $query->orderby('release', 'asc');
            return $query;
        }
        
        if($order['order'] === 'has_asc_order'){
            $query = $query->orderby('possesions_count', 'asc');
            return $query;
        }
        
        if($order['order'] === 'has_desc_order'){
            $query = $query->orderby('possesions_count', 'desc');
            return $query;
        }
    }
}
