<?php
namespace backend\components\validator;

use yii\validators\Validator;
use common\utilities\DateTimeHelper;

class LawatanMainTrkhTamatValidator extends Validator
{
    public function init()
    {
        parent::init();
        $this->message = '"Tarikh Tamat Lawatan" mestilah lebih besar dari "Tarikh Mula Lawatan".';
        // $this->message2 = '"Masa Tamat Lawatan" mestilah lebih kecil dari "Masa Mula Lawatan".';
    }

    public function validateAttribute($model, $attribute)
    {
        $trkhTamat = $model->$attribute;
        $trkhMula = $model->TRKHMULA;
        $today = date('Y-m-d H:i:s');

        $trkhTamatdmy = $trkhTamat;  
        $trkhMuladmy = $trkhMula;
        
        // var_dump($today);
        // var_dump($trkhTamatdmy);
        // var_dump($trkhTamat);
        // exit();

        // if ($trkhTamat <= $trkhMula) {
        //     if ($trkhMuladmy <= $trkhTamatdmy) {
        //         $model->addError($attribute, '"Masa Tamat Lawatan" mestilah lebih besar dari "Masa Mula Lawatan".');
        //     }else{
        //         $model->addError($attribute, $this->message);
        //     }
        // }
        // if ($trkhMula > $today) {
        //     $model->addError($attribute,'"Masa Tamat Lawatan" mestilah lebih kecil dari "Masa Semasa".');
        // }
    }

    public function clientValidateAttribute($model, $attribute, $view)
    {
            $message = json_encode($this->message, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            $value= $model->$attribute;

            // the closing position for JS; needs to be like this if not, will get syntax error
            return <<<JS
                var trkhTamat = value;
                var trkhMula = $('#lawatanmain-trkhmula').val();
                var role = $('#lawatanmain-roles').val();

                var trkhmulaYYMMDDhhmm = trkhMula.substr(6,4) + trkhMula.substr(3,2)  + trkhMula.substr(0,2) + ' ' + trkhMula.substr(12,5);
                var trkhmulaYYMMDD = trkhMula.substr(6,4) + trkhMula.substr(3,2)  + trkhMula.substr(0,2);
                trkhmulaYYMMDD = trkhmulaYYMMDD.trim();
                var trkhMulahhmm = trkhMula.substr(12,5);
                var trkhTamatYYMMDDhhmm = trkhTamat.substr(6,4) + trkhTamat.substr(3,2)  + trkhTamat.substr(0,2) + ' ' + trkhTamat.substr(12,5);
                var trkhTamatYYMMDD = trkhTamat.substr(6,4) + trkhTamat.substr(3,2)  + trkhTamat.substr(0,2);
                trkhTamatYYMMDD = trkhTamatYYMMDD.trim();
                var trkhTamathhmm = trkhTamat.substr(12,5);
                
                
                if (trkhTamatYYMMDDhhmm && trkhmulaYYMMDDhhmm) {
                    if ((trkhTamatYYMMDD == trkhmulaYYMMDD)&&(trkhTamathhmm < trkhMulahhmm)) {
                        messages.push('"Masa Tamat Lawatan mestilah lebih besar dari Masa Mula Lawatan".');
                    }else if (trkhTamatYYMMDD < trkhmulaYYMMDD) {
                        messages.push($message);
                    }
                }
                
            
JS;
    }
}