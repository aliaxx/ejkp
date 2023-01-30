<?php

namespace common\utilities;

use Yii;

class DateTimeHelper
{
    public static function convert($dateTime, $sqlFormat = true, $convertMeridian = false)
    {
        // var_dump(date('d/m/Y, h:i A', strtotime($sqldatetime)));
        // exit();
        $formatter = Yii::$app->formatter;
        if ($sqlFormat) {
            list($date, $time) = explode(' ', $dateTime);
            $date = $formatter->asDate(str_replace('/', '-', $date), 'php:Y-m-d');

            $time = str_replace('PAGI', 'AM', $time);
            $time = str_replace('PTG', 'PM', $time);
            $time = $formatter->asTime($time, 'php:H:i:s');
            return $date . ' ' . $time;
        } else {
            list($date, $time) = explode(' ', $dateTime);
            $result = $formatter->asDate($date) . ', ' . $formatter->asTime($time);

            if ($convertMeridian) return self::convertMeridian($result);
            return $result;
        }
    }

    public static function convertMeridian($time)
    {
        $time = str_replace('AM', 'PAGI', $time);
        $time = str_replace('PM', 'PTG', $time);
        return $time;
    }

    /**
     * @param string $sqldatetime The sqldatetime format Y-m-d H:i:s. eg: 2019-12-31 20:30:00
     */
    public static function convertDisplay($sqldatetime)
    {
        $output = date('d/m/Y, h:i A', strtotime($sqldatetime));
        $output = str_replace('AM', 'PAGI', $output);
        $output = str_replace('PM', 'PTG', $output);
        return $output;
    }
}
