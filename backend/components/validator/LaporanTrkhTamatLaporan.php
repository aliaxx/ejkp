<?php
namespace backend\components\validator;

use yii\validators\Validator;
use common\utilities\DateTimeHelper;

class LaporanTrkhTamatLaporan extends Validator
{
    public function init()
    {
        parent::init();
        $this->message = 'Julat antara "Tarikh Tamat Lawatan" & "Tarikh Mula Lawatan" tidak boleh melebihi 1 hari.';
    }

    public function validateAttribute($model, $attribute)
    {
        $TRKHTAMAT = str_replace('/', '-', $model->$attribute);
        $TRKHMULA = str_replace('/', '-', $model->TRKHMULA);
        
        $TRKHTAMAT = date('Y-m-d', strtotime($TRKHTAMAT));
        $TRKHMULA = date('Y-m-d', strtotime($TRKHMULA));
        
        $TRKHTAMAT = date_create($TRKHTAMAT);
        $TRKHMULA = date_create($TRKHMULA);

        $diff = date_diff($TRKHMULA, $TRKHTAMAT);
        $diff = (int)$diff->format("%a");

        if ($model->jenislaporan == 1) {
            if ($diff > 1) {
                $model->addError($attribute, $this->message);
            }
        }
    }

    public function clientValidateAttribute($model, $attribute, $view)
    {
            $message = json_encode($this->message, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            
            // the closing position for JS; needs to be like this if not, will get syntax error
            return <<<JS
                var TRKHTAMAT = value
                var TRKHMULA = $('#laporansearch-TRKHMULA').val();
                var jenislaporan = $('#laporansearch-jenislaporan input[type=radio]:checked').val();
        
                // change format from d/m/Y to Y-m-d
                var TRKHTAMAT = TRKHTAMAT.split("/").reverse().join("-");
                var TRKHMULA = TRKHMULA.split("/").reverse().join("-");

                const date1 = new Date(TRKHTAMAT);
                const date2 = new Date(TRKHMULA);

                const diffTime = Math.abs(date1 - date2);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
                
                //console.log(diffTime + " milliseconds");
                //console.log(diffDays + " days");

                if (jenislaporan == 1) {
                    if (TRKHTAMAT && TRKHMULA) {
                        if (diffDays > 1) {
                            messages.push($message);
                        }
                    }
                }
JS;
    }
}