<?php
namespace App\Calendars\Admin;
use Carbon\Carbon;
use App\Models\Users\User;

class CalendarView{
  private $carbon;

  function __construct($date){
    $this->carbon = new Carbon($date);
  }

  public function getTitle(){
    return $this->carbon->format('Y年n月');
  }

  public function render(){
    $html = [];
    $html[] = '<div class="calendar text-center">';
    $html[] = '<table class="table m-auto border">';
    $html[] = '<thead>';
    $html[] = '<tr>';
    $html[] = '<th class="border">月</th>';
    $html[] = '<th class="border">火</th>';
    $html[] = '<th class="border">水</th>';
    $html[] = '<th class="border">木</th>';
    $html[] = '<th class="border">金</th>';
    $html[] = '<th class="border" style="color: #0000FF;">土</th>';
    $html[] = '<th class="border" style="">日</th>';
    $html[] = '</tr>';
    $html[] = '</thead>';
    $html[] = '<tbody>';

    $weeks = $this->getWeeks();

    foreach($weeks as $week){
      $html[] = '<tr class="'.$week->getClassName().'">';
      $days = $week->getDays();
foreach($days as $day){
    $startDay = $this->carbon->format("Y-m-01");
    $toDay = $this->carbon->format("Y-m-d");

    // 共通クラス構築
    $dayClass = $day->getClassName(); // ← これが 'day-sun' や 'day-sat' を返す
    $class = 'border ' . $dayClass;

    // 過去日なら past-day を追加
    if($startDay <= $day->everyDay() && $toDay >= $day->everyDay()){
        $class = 'past-day ' . $class;
    }

    // tdタグ出力
    $html[] = '<td class="'.$class.'">';

    // 日付出力
    $html[] = '<p class="day calendar-day-number">'.$day->render().'</p>';
    $html[] = $day->dayPartCounts($day->everyDay());
    $html[] = '</td>';
}

      $html[] = '</tr>';
    }
    $html[] = '</tbody>';
    $html[] = '</table>';
    $html[] = '</div>';

    return implode("", $html);
  }

  protected function getWeeks(){
    $weeks = [];
    $firstDay = $this->carbon->copy()->firstOfMonth();
    $lastDay = $this->carbon->copy()->lastOfMonth();
    $week = new CalendarWeek($firstDay->copy());
    $weeks[] = $week;
    $tmpDay = $firstDay->copy()->addDay(7)->startOfWeek();
    while($tmpDay->lte($lastDay)){
      $week = new CalendarWeek($tmpDay, count($weeks));
      $weeks[] = $week;
      $tmpDay->addDay(7);
    }
    return $weeks;
  }
}
