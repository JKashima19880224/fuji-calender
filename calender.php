<?php
date_default_timezone_set('Asia/Tokyo');

$year = date('Y');
$month = date('n');

if (validateParameter($_GET)) {
    $year = intval($_GET['year']);
    $month = intval($_GET['month']);
}

$today = today($year, $month);

function validateParameter($params) {
    if (!isset($params['year'])) {
        return false;
    }
    if (!intval($params['year'])) {
        return false;
    }
    if (!isset($params['month'])) {
        return false;
    }
    if (!intval($params['month'])) {
        return false;
    }
    if (!checkdate(1, $params['month'], $params['year'])) {
        return false;
    }
    return true;
}

function today($year, $month) {
    $month = date('Y-n', mktime(0, 0, 0, $month, 1, $year));
    $current_month = date('Y-n');
    return $month == $current_month ? date('j') : -1;
}

function buildQueryParameterForPrevMonth($year, $month) {
    $first_month = date('Y-n-d', mktime(0, 0, 0, $month, 1, $year));
    $prev_year = date("Y",strtotime($first_month . "-1 month"));
    $prev_month = date("n",strtotime($first_month . "-1 month"));
    return http_build_query(["year"=>$prev_year, 'month'=>$prev_month]);
}

function buildQueryParameterForNextMonth($year, $month) {
    $first_month = date('Y-n-d', mktime(0, 0, 0, $month, 1, $year));
    $next_year = date("Y",strtotime($first_month . "+1 month"));
    $next_month = date("n",strtotime($first_month . "+1 month"));
    return http_build_query(["year"=>$next_year, 'month'=>$next_month]);
}

function getMonthFullName($year, $month) {
    return date('F', mktime(0, 0, 0, $month, 1, $year));
}

function generateCalendar($year, $month) {
    $first_month_timestamp = mktime(0, 0, 0, $month, 1, $year);
    $end_month = date('t', $first_month_timestamp);
    $first_week = date('w', $first_month_timestamp);
    $end_month_timestamp = mktime(0, 0, 0, $month, $end_month, $year);
    $last_week = date('w', $end_month_timestamp);

    $calendar = [];
    $j = 0;

    appendBlankValueForFirstWeek($calendar, $first_week);
    appendDayValue($calendar, $end_month, $j);
    appendBlankValueForLastWeek($calendar, $j);

    return $calendar;
}

function appendBlankValueForFirstWeek(&$calendar, $first_week) {
    for($i = 0; $i < $first_week; $i++){
        $calendar[0][] = '';
    }
}

function appendDayValue(&$calendar, $end_month, &$j) {
    for ($i = 1; $i <= $end_month; $i++){
        if(isset($calendar[$j]) && count($calendar[$j]) == 7){
            $j++;
        }
        $calendar[$j][] = $i; 
    }
}

function appendBlankValueForLastWeek(&$calendar, &$j) {
    for($i = count($calendar[$j]); $i < 7; $i++){
        $calendar[$j][] = '';
    }
}

function getWeeks() {
    return ['Sun', 'Mon', 'Thu', 'Wed', 'Thu', 'Fri', 'Sat'];
}

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>カレンダー</title>
<style>
* {
  margin: 0 auto;
}
.calendar-wrap {
  width: 500px;
  background: #eee;
  color: #333;
}
.header {
  line-height:100px;
  text-align: center;
}
h1 {
  width: 60%;
  font-size: 30px;
  display: inline-block;
  vertical-align: middle;
}
.btn{
  display:inline-block;
  width:40px;
  height:40px;
  line-height:40px;
  background:gray;
  border-radius:100%;
  color:#fff;
  font-size:14px;
  font-weight:bold;
  text-decoration:none;
  text-align:center;
  vertical-align: middle;
}
.calendar {
  padding: 20px;
  width: 100%;
  table-layout: fixed;
}
th,td {
  text-align: center;
  padding: 10px;
  background-color: #fff;
}
td {
  font-weight: bold;
}
.sun {
  color: red;
}
.sat {
  color: blue;
}
.today {
  background-color: #fafad2;
}
</style>
</head>
<body>
  <div class="calendar-wrap">
    <div class="header">
        <a class="btn" href="?<?php echo(buildQueryParameterForPrevMonth($year, $month)) ?>"><</a>
        <a class="btn" href="?<?php echo(buildQueryParameterForNextMonth($year, $month)) ?>">></a>
        <h1>
            <?php echo(getMonthFullName($year, $month)) ?> 
            <?php echo($year) ?>
        </h1>
    </div>
    <table class="calendar">
      <tr>
        <?php foreach (getWeeks() as $week) { ?>
        <th><?php echo($week) ?></th>
        <?php } ?>
      </tr>
      <?php foreach (generateCalendar($year, $month) as $line) { ?>
      <tr>
        <?php
            foreach ($line as $key => $day) { 
                $class_name = '';
                if ($key == 0) {
                    $class_name = 'sun';
                } else if ($key == 6) {
                    $class_name = 'sat';
                }
                if ($day == $today) {
                    $class_name .= ' today';
                }
        ?>
        <td class="<?php echo($class_name) ?>"><?php echo($day) ?></td>
        <?php } ?>
      </tr>
      <?php } ?>
    </table>
  </div>
</body>
</html>