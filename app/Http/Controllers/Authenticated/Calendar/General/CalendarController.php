<?php

namespace App\Http\Controllers\Authenticated\Calendar\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Calendars\General\CalendarView;
use App\Models\Calendars\ReserveSettings;
use App\Models\Calendars\Calendar;
use App\Models\USers\User;
use Auth;
use DB;

class CalendarController extends Controller
{
    public function show(){
        $calendar = new CalendarView(time());
        return view('authenticated.calendar.general.calendar', compact('calendar'));
                    //generalフォルダに入っているcalendar.blade.phpに戻る
    }

    public function reserve(Request $request){
        DB::beginTransaction();
        try{
            $getPart = $request->getPart;
            $getDate = $request->getData;
            $reserveDays = array_filter(array_combine($getDate, $getPart));
            foreach($reserveDays as $key => $value){
                $reserve_settings = ReserveSettings::where('setting_reserve', $key)->where('setting_part', $value)->first();
                $reserve_settings->decrement('limit_users');
                $reserve_settings->users()->attach(Auth::id());
            }
            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
        }
        return redirect()->route('calendar.general.show', ['user_id' => Auth::id()]);
    }

    public function delete(Request $request)
{
    DB::beginTransaction();
    try {

        $deleteDate = $request->input('delete_date'); // "2025-05-14 2"
        // dd($deleteDate);
        $datePart = explode(' ', $deleteDate);

        if (count($datePart) !== 2) {
            return redirect()->back()->with('error', '削除形式が不正です');
        }

        $date = $datePart[0]; // "2025-05-14"
        $part = $datePart[1]; // "2"

        // 予約設定を取得
        $reserve = ReserveSettings::where('setting_reserve', $date)
                                  ->where('setting_part', $part)
                                  ->first();

        if (!$reserve) {
            DB::rollBack();
            return redirect()->back()->with('error', '該当予約が見つかりませんでした');
        }

        // 予約が存在していれば削除処理
        $reserve->users()->detach(Auth::id()); // 中間テーブルから削除
        $reserve->increment('limit_users'); // 枠を1つ戻す

        DB::commit();
        return redirect()->route('calendar.general.show')
                         ->with('message', '予約をキャンセルしました');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'キャンセル処理に失敗しました');
    }
}


}
