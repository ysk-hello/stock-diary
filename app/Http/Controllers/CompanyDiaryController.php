<?php

namespace App\Http\Controllers;

use App\Models\CompanyDiary;
use Illuminate\Http\Request;

class CompanyDiaryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $code = $request['code'];

        if($request->has('ymd')){
            $selected_date = $request->ymd;
        }else{
            $selected_date = date('Y-m-d');
        }

        return view('companydiary', ['selected_date' => $selected_date, 'company_code' => $code]);
    }

    public function getCompanyDiary(Request $request)
    {
        $diary = CompanyDiary::where(['company_code' => $request['company_code'], 
            'user_id' => auth()->id(), 'date' => $request['date']])->first();

        return $diary;
    }

    public function getCompanyDiaries(Request $request)
    {
        $diaries = CompanyDiary
            ::where(['company_code' => $request['company_code'], 
                'user_id' => auth()->id()])
            ->whereDate('date', '<', $request['date'])
            ->orderBy('date', 'desc')
            ->paginate(5);

        return $diaries;
    }

    public function saveCompanyDiary(Request $request)
    {
        $diary = CompanyDiary::where(['company_code' => $request['company_code'], 
            'user_id' => auth()->id(), 'date' => $request['date']])->first();

        if(!isset($diary)){
            $diary = new CompanyDiary;
        }

        $diary->company_code = $request['company_code'];
        $diary->date = $request['date'];
        $diary->text = $request['text'];
        $diary->judgement = $request['judgement'];
        $diary->user_id = auth()->id();
        $diary->save();
    }

    public function deleteCompanyDiary(Request $request)
    {
        $diary = CompanyDiary::where(['company_code' => $request['company_code'], 
            'user_id' => auth()->id(), 'date' => $request['date']])->first();

        if(isset($diary)){
            $diary->delete();
        }
    }
}
