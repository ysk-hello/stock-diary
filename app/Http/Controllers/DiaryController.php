<?php

namespace App\Http\Controllers;

use App\Models\Diary;
use Illuminate\Http\Request;

class DiaryController extends Controller
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

        return view('diary', ['selected_date' => $selected_date]);
    }

    public function getDiary(Request $request)
    {
        $diary = Diary::where(['user_id' => auth()->id(), 'date' => $request['date']])->first();

        return $diary;
    }

    public function getDiaries(Request $request)
    {
        $diaries = Diary::where('user_id', '=', auth()->id())
            ->whereDate('date', '<', $request['date'])
            ->orderBy('date', 'desc')
            ->paginate(5);

        return $diaries;
    }

    public function saveDiary(Request $request)
    {
        $diary = Diary::where(['user_id' => auth()->id(), 'date' => $request['date']])->first();

        if(!isset($diary)){
            $diary = new Diary;
        }

        $diary->date = $request['date'];
        $diary->text = $request['text'];
        $diary->user_id = auth()->id();
        $diary->save();
    }

    public function deleteDiary(Request $request)
    {
        $diary = Diary::where(['user_id' => auth()->id(), 'date' => $request['date']])->first();

        if(isset($diary)){
            $diary->delete();
        }
    }
}
