<?php
// use Yii;
include 'Calendar.php';

// var_dump($stylesheet);
// exit;
$calendar = new Calendar('2021-02-02');
$calendar->add_event('Birthday', '2021-02-03', 1, 'green');
$calendar->add_event('Doctors', '2021-02-04', 1, 'red');
$calendar->add_event('Holiday', '2021-02-16', 3);

?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
	<head>
		<meta charset="utf-8">
		<!-- <link href="style.css" rel="stylesheet" type="text/css"> -->
		<link href="<?= Yii::getAlias('@web').'/style-calendar.css';?>" rel="stylesheet" type="text/css">
	</head>

	<body>
		<div class="content home">
			<?=$calendar?>
		</div>
	</body>
 </html>