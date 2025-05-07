<?php
namespace App\Calendars\General;

use Carbon\Carbon;
use Auth;

class CalendarView{

  private $carbon;
  function __construct($date){
    $this->carbon = new Carbon($date);
  }

  public function getTitle(){
    return $this->carbon->format('Y年n月');//"Y-m-01"に直してもダメ
  }

  function render(){
    $html = [];  // 空の配列を作成して、HTMLコードを格納します。
    $html[] = '<div class="calendar text-center">';
    $html[] = '<table class="table">';
    $html[] = '<thead>'; //テーブルのヘッダー部分を開始します。これにより、カレンダーの曜日（例：月、火、水、木、金、土、日）を表示する部分を作ります。
    $html[] = '<tr>'; //テーブルの行（<tr>）を開始します。この行は曜日を表示するためのものです。
    $html[] = '<th>月</th>'; //月曜日の曜日を表示する <th>（ヘッダーセル）を追加します。同様に、火曜日から日曜日までの曜日も <th> タグで追加されます。
    $html[] = '<th>火</th>';
    $html[] = '<th>水</th>';
    $html[] = '<th>木</th>';
    $html[] = '<th>金</th>';
    $html[] = '<th>土</th>';
    $html[] = '<th>日</th>';
    $html[] = '</tr>';
    $html[] = '</thead>';
    $html[] = '<tbody>'; //カレンダーの内容（週ごとの日付）を表示するためのテーブルボディ部分（<tbody>）を開始します。
    $weeks = $this->getWeeks();//カレンダー内の全週を取得し、その結果を $weeks という変数に格納しているということです。
    foreach($weeks as $week){
      $html[] = '<tr class="'.$week->getClassName().'">';

      $days = $week->getDays();
      // 現在の日付を取得
      $today = Carbon::today(); // Carbonを使って現在の日付を取得
      foreach($days as $day){
        $dayDate = Carbon::parse($day->everyDay()); // ← 明示的にCarbonに変換
        // 現在の日付を基に、過去か未来の日付を判定
        // if($dayDate->lt($today)){
        // $html[] = '<td class="calendar-td bg-secondary text-black" style="background-color: #eeedee !important;">';
        // $html[] = '<div>' . $dayDate->day . '日</div>';
        //上の状態だと当日を過去認定してしまうため

if ($dayDate->lt($today->startOfDay())) {
    // 昨日以前は過去として処理
    $html[] = '<td class="calendar-td bg-secondary text-black" style="background-color: #eeedee !important;">';
    $html[] = '<div>' . $dayDate->day . '日</div>';
    // 過去に予約していたか確認
    $reserveDate = $day->authReserveDate($day->everyDay())->first();
    if($reserveDate){
        $reservePart = $reserveDate->setting_part;
        if($reservePart == 1){
            $reservePart = "リモ1部";
        }elseif($reservePart == 2){
            $reservePart = "リモ2部";
        }elseif($reservePart == 3){
            $reservePart = "リモ3部";
        }
        $html[] = '<div style="font-size: 12px;">' . $reservePart . '</div>';
    } else {
        $html[] = '<div style="font-size: 12px;">受付終了</div>';
    }
    $html[] = '</td>';
    continue; // この日付はスキップして次の処理に進む
}

        $startDay = $this->carbon->copy()->format("Y-m-01");
        $toDay = $this->carbon->copy()->format("Y-m-d");

        if($startDay <= $day->everyDay() && $toDay >= $day->everyDay()){
          $html[] = '<td class="calendar-td">';
        }else{
          $html[] = '<td class="calendar-td '.$day->getClassName().'">';
        }
        $html[] = $day->render();

    // 予約が存在する場合に表示する処理
    if(in_array($day->everyDay(), $day->authReserveDay())){
        $reserveDate = $day->authReserveDate($day->everyDay())->first(); // 予約情報を取得
        if ($reserveDate) {
            $reservePart = $reserveDate->setting_part;
            // 予約部数を判定
            if($reservePart == 1){
                $reservePart = "リモ1部";
            } else if($reservePart == 2){
                $reservePart = "リモ2部";
            } else if($reservePart == 3){
                $reservePart = "リモ3部";
            }
            // 表示部分

    if ($dayDate->lt($today->startOfDay()))  {     //$dayDate->lt(Carbon::today()
    // 過去日（今日より前） → 削除ボタンを出さない
    $html[] = '<p class="m-auto p-0 w-75" style="font-size:12px">' . $reservePart . '</p>';
    $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';
    } else {
    // 今日・未来 → 削除ボタンを出す
    $html[] = '<button type="submit" class="btn btn-danger p-0 w-75" name="delete_date" style="font-size:12px" value="' . $reserveDate->setting_reserve . '">' . $reservePart . '</button>';
    $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';
    }
    }
    } else {
    $html[] = $day->selectPart($day->everyDay());

    }
    $html[] = $day->getDate();
    $html[] = '</td>';
    }
    $html[] = '</tr>';
    }
    $html[] = '</tbody>';
    $html[] = '</table>';
    $html[] = '</div>';
    $html[] = '<form action="/reserve/calendar" method="post" id="reserveParts">'.csrf_field().'</form>';
    $html[] = '<form action="/delete/calendar" method="post" id="deleteParts">'.csrf_field().'</form>';

    return implode('', $html);
  }

  protected function getWeeks(){
    $weeks = [];
    $firstDay = $this->carbon->copy()->firstOfMonth(); //月初日
    $lastDay = $this->carbon->copy()->lastOfMonth();  // 月末日
    $week = new CalendarWeek($firstDay->copy());  // 1週目を作成
    $weeks[] = $week; // 1週目を配列に追加

      // 月末まで次の週を作成していく
    $tmpDay = $firstDay->copy()->addDay(7)->startOfWeek(); // 次の週の初め
    while($tmpDay->lte($lastDay)){
      $week = new CalendarWeek($tmpDay, count($weeks)); // 次の週を作成
      $weeks[] = $week; // 週を配列に追加
      $tmpDay->addDay(7); // 1週間進める
    }
    return $weeks;
  }
}
