<?php

namespace App\Http\Controllers;

use App\Models\Diary;
use App\Models\CompanyDiary;
use Illuminate\Http\Request;

class SearchDiaryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('search');
    }

    public function searchDiaries(Request $request) 
    {
        $keyword = '%' . trim($request['keyword']) . '%';

        $diaries = Diary::where('user_id', '=', auth()->id())->where('text', 'like', $keyword)
            ->orderBy('date', 'desc')
            ->select('date', 'text')
            ->paginate(50);

        return $diaries;
    }

    public function searchCompanyDiaries(Request $request) 
    {
        $keyword = '%' . trim($request['keyword']) . '%';

        $diaries = CompanyDiary::where('user_id', '=', auth()->id())->where('text', 'like', $keyword)
            ->leftJoin('companies', 'company_code', 'code')
            ->orderBy('company_diaries.date', 'desc')
            ->select('company_diaries.company_code as code', 'companies.name', 'company_diaries.date', 'company_diaries.judgement', 'company_diaries.text')
            ->paginate(50);
        
        return $diaries;
    }
}
