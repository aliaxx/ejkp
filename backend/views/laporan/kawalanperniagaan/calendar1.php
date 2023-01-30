<?php
// require_once('@vendor/donatj/SimpleCalendar/src/SimpleCalendar.php');
$calendar = new donatj\SimpleCalendar();

$calendar->setStartOfWeek('Sunday');

$calendar->addDailyHtml('Sample Event', 'today', 'tomorrow' );
$calendar->show(true);

// $currentMonth = Calendar::make($date);

// $currentMonth->startOfPrevMonth; // get the start of the previous month - instance of Carbon
// $currentMonth->startOfPrevMonth->toDateString(); // '2020-02-01'

// $currentMonth->startOfNextMonth; // get the start of the previous month - instance of Carbon
// $currentMonth->startOfNextMonth->toDateString(); // '2020-04-01'

?>

