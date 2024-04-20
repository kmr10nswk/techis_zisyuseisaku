<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\User;

class ItemController extends Controller
{
    /**
     * 商品一覧
     */
    public function index()
    {
        // 商品一覧取得
        $items = Item::query();
        $items = $items->orderby('id', 'asc')
            ->paginate(10)->withQueryString();

        // blade整え
        $c_list = Item::category_list();
        $t_list = Item::theme_list();
        $k_list = Item::kind_list();
        $co_list = Item::company_list();

        foreach ($items as $item){
            $item->category = $c_list[$item->category];
            $item->theme = $t_list[$item->theme];
            $item->kind = $k_list[$item->kind];
            $item->company = $co_list[$item->company];
        }

        return view('item.index', compact('items'));
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
}
