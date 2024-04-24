<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Models\Item;
use App\Models\User;
use App\Models\Possesion;

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

        // 検索用
        $search = $request->only(['search_free', 'search_category', 'search_theme', 'search_kind', 'search_company', 'search_possesion', 'search_condition']);

        $this->searchValidator($request->all(), $types)->validate();
        $items = $this->search($items, $search, $types);

        // 通常
        $items = $items->where('status', 'active')
            ->orderby('id', 'asc')
            ->paginate(10)->withQueryString();
        
        // blade整え
        $items = Item::listSeiton($items);
        foreach($items as $item) {
            $item['has'] = Auth::user()->is_possesion($item->id);
        }
        
        $nothing_message = null;
        if($items->first() === null) {
            $nothing_message =  '検索結果はありません';
        }

        return view('item.index', compact('items', 'search' , 'types', 'nothing_message'));
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
            $file_name = User::uploadImage($file, 'item');

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
        $item_obj = Item::where('id', $id);
        $items =  $item_obj->get();

        // blade整え
        $items = Item::listSeiton($items);
        $item = $items->first();

        return view('item.show', compact('item'));
    }

    /**
     * 検索用
     */
    public function searchValidator(array $data, array $types){
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
    
    public function search($query, $search, $types)
    {
        // 検索クエリ(多分本当はsearch用のファイルを作った方が良い)
        if(isset($search['search_free'])){  
            $query->where('name', 'like', '%' . $search['search_free'] . '%')
                ->orWhere('detail', 'like', '%' . $search['search_free'] . '%');
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
}
