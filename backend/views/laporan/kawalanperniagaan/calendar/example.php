<?php

include 'Calendar.php';
$calendar = new Calendar('2022-11-21');
$calendar->add_event('Birthday', '2022-11-03', 1, 'green');
$calendar->add_event('Doctors', '2022-11-04', 1, 'red');
$calendar->add_event('Holiday', '2022-11-16', 3);

$this->registerCssFile(Yii::getAlias('@web').'\css\style-calendar.css');
// $this->registerCssFile(Yii::getAlias('calendar.css'));

?>


<!DOCTYPE html>
	<head>
		<meta charset="utf-8">
		 <title>Event Calendar</title>
	</head>
    <br>
    
	<body>
		<?=$calendar?>

	</body>
</html>