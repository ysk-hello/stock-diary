<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FavoriteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index(Request $request)
    {
        if($request->has('ymd')){
            $selected_date = $request->ymd;
        }else{
            $selected_date = date('Y-m-d');
        }

        return view('favorite', ['selected_date' => $selected_date]);
    }

    public function getFavorites(Request $request)
    {
        $date = $request['date'];

        // DB::enableQueryLog();
        
        $companydiaries = DB::table('favorites')
            ->where('favorites.user_id', auth()->id())
            ->leftJoin('company_diaries', function($join) use($date) {
                $join->on('favorites.company_code', '=', 'company_diaries.company_code')
                    -> where('company_diaries.date', '=', $date);
            })
            ->join('companies', 'favorites.company_code', '=', 'companies.code')
            ->select('favorites.company_code as code', 'companies.name as name', 'company_diaries.judgement as judgement', 'company_diaries.text as text')
            ->get();

        // dd(DB::getQueryLog());
        
        return $companydiaries;
    }

    public function checkState(Request $request)
    {
        $favorite = Favorite::where('user_id', auth()->id())->where('company_code', $request['code'])->first();

        if(isset($favorite)){
            return ['state' => true];
        }
        else{
            return ['state' => false];
        }
    }

    public function toggleState(Request $request)
    {
        // 複合主キーのエラー: サロゲートキーに変更して解決
        // https://www.threestarsoftware.co.jp/laravel/laravel%E3%81%A7%E8%A4%87%E5%90%88%E3%82%AD%E3%83%BC%E3%81%AB%E5%AF%BE%E5%BF%9C%E3%81%99%E3%82%8B%E6%96%B9%E6%B3%95/
        // https://stackoverflow.com/questions/73053454/column-not-found-1054-unknown-column-0-in-where-clause-sql-select-house
        $favorite = Favorite::where(['user_id' => auth()->id(), 'company_code' => $request['code']])->first();

        if(isset($favorite)){
            // 削除
            $favorite->delete();

            return ['state' => 'delete'];
        }
        else{
            $favorites = Favorite::where(['user_id' => auth()->id()])->get();
            if($favorites->count() < 50){
                // 追加
                $favorite = new Favorite;
                $favorite->company_code = $request['code'];
                $favorite->user_id = auth()->id();
                $favorite->save();
    
                return ['state' => 'add'];
            }
            else{
                // 上限
                return ['state' => 'upper-limit'];
            }
        }
    }
}
