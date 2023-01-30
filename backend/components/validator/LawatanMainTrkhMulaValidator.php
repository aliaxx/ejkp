<?php
namespace backend\components\validator;

use Yii;
use yii\validators\Validator;
use common\utilities\DateTimeHelper;

class LawatanMainTrkhMulaValidator extends Validator
{
    public function init()
    {
        parent::init();
        $this->message = '"Tarikh Mula Lawatan" mestilah lebih kecil dari "Tarikh Tamat Lawatan".';
        // $this->message = '"Masa Mula Lawatan" mestilah lebih kecil dari "Masa Tamat Lawatan".';
    }

    public function validateAttribute($model, $attribute)
    {
        $trkhMula = $model->$attribute;
        $trkhTamat = $model->TRKHTAMAT;
        date_default_timezone_set("Asia/Kuala_Lumpur");
        $today = date('Y-m-d H:i:s');
        
        $trkhTamatdmy = $trkhTamat;  
        $trkhMuladmy = $trkhMula;

        // var_dump($trkhTamat);
        // var_dump($trkhTamatdmy);
        // exit();

       // if ($trkhTamat <= $trkhMula) {
            // if ($trkhMuladmy <= $trkhTamatdmy) {
            //     $model->addError($attribute, '"Masa Mula Lawatan" mestilah lebih kecil dari "Masa Tamat Lawatan".');
            // }else{
            //     $model->addError($attribute, $this->message);
            // }
        //}
        // if ($trkhMula > $today) {

        //     $model->addError($attribute, '"Masa Mula Lawatan" mestilah lebih kecil dari "Masa Semasa".');

        // }
        
    }

    public function clientValidateAttribute($model, $attribute, $view)
    {
            $message = json_encode($this->message, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            
            // the closing position for JS; needs to be like this if not, will get syntax error
            return <<<JS
                // var trkhMula = value;
                var trkhMula = $('#lawatanmain-trkhmula').val();
                var trkhTamat = $('#lawatanmain-trkhtamat').val();
                let today = new Date().toLocaleString('en-GB', '+8 hours');
                let date1= ('2022-01-01');
                let date2= ('2022-01-05');
                var prevyear = today.substr(6,4) - 1;
                // var today1=prevyear + "1201";
                var lawatanmaindatestart = prevyear + "0101"
                var lawatanmaindateend = prevyear + "1231"
                // console.log(new Date().toString('dd-MMM HH:mm'));
                // const date = new Date("01/01/Y"); // 20th January 2021
                // const year =today.getFullYear();

                // echo trkhmulab;
                // var isvalidentry = false;
                // var role = "userrole";

                var trkhmulaYYMMDDhhmm = trkhMula.substr(6,4) + trkhMula.substr(3,2)  + trkhMula.substr(0,2) + ' ' + trkhMula.substr(12,5);
                var trkhmulaYYMMDD = trkhMula.substr(6,4) + trkhMula.substr(3,2)  + trkhMula.substr(0,2);
                trkhmulaYYMMDD = trkhmulaYYMMDD.trim();
                var trkhMulahhmm = trkhMula.substr(12,5);
                var trkhTamatYYMMDDhhmm = trkhTamat.substr(6,4) + trkhTamat.substr(3,2)  + trkhTamat.substr(0,2) + ' ' + trkhTamat.substr(12,5);
                var trkhTamatYYMMDD = trkhTamat.substr(6,4) + trkhTamat.substr(3,2)  + trkhTamat.substr(0,2);
                trkhTamatYYMMDD = trkhTamatYYMMDD.trim();
                var trkhTamathhmm = trkhTamat.substr(12,5);
                var todayYYMMDD = today.substr(6,4) + today.substr(3,2) + today.substr(0,2) + ' ' + today.substr(12,5);

                var today1 = today.substr(6,4) + today.substr(3,2) + today.substr(0,2);
                date1 = today.substr(6,4) + date1.substr(3,2) + date1.substr(0,2);
                date2 = today.substr(6,4) + date2.substr(3,2) + date2.substr(0,2);
                
            
                // console.log('trkhmulaYYMMDD ' + trkhmulaYYMMDD);
                // console.log('todayYYMMDD ' + todayYYMMDD);
                // console.log('trkhmulaYYMMDDhhmm > todayYYMMDD ' + (trkhmulaYYMMDDhhmm > todayYYMMDD));
                console.log('trkhmulaYYMMDDhhmm ' + trkhmulaYYMMDDhhmm);
                console.log('trkhTamatYYMMDDhhmm ' + trkhTamatYYMMDDhhmm);
                console.log('trkhTamatYYMMDDhhmm && trkhmulaYYMMDDhhmm ' + (trkhTamatYYMMDDhhmm && trkhmulaYYMMDDhhmm));
                // console.log('trkhMulahhmm ' + trkhMulahhmm);
                // console.log('trkhTamathhmm ' + trkhTamathhmm);
                // console.log('today1 ' + today1);
                // console.log("date1: " + date1);
                // console.log("date2: " + date2);
                // console.log("prevyear: " + prevyear);
                // console.log("lawatanmaindatestart: " + lawatanmaindatestart);
                // console.log("lawatanmaindateend: " + lawatanmaindateend);

                // trkhmulaYYMMDDhhmm 20221019 09:28
                // trkhTamatYYMMDDhhmm 20221019 09:28

                // trkhmulaYYMMDD 20221019
                // trkhTamatYYMMDD 20221019
           
                // trkhMulahhmm 09:28
                // trkhTamathhmm 09:28
                
                // if(role=="Admin")
                // {
                    //(if tarikhmula & tarikhtamat sama tarikh tapi masa tarikhmula mesti lebih besar dari tarikhtamat )

                if (trkhmulaYYMMDDhhmm) {
                    if (trkhmulaYYMMDDhhmm > todayYYMMDD) {
                        messages.push('"Masa Mula Lawatan" mestilah lebih kecil dari "Masa Semasa".');
                    }                    
                }
            
                if (trkhTamatYYMMDDhhmm && trkhmulaYYMMDDhhmm) {
                    if ((trkhTamatYYMMDD == trkhmulaYYMMDD)&&(trkhTamathhmm < trkhMulahhmm)) {
                        messages.push('"Masa Mula Lawatan mestilah lebih kecil dari Masa Tamat Lawatan".');
                    }else if (trkhTamatYYMMDD < trkhmulaYYMMDD) {
                        messages.push($message);
                    }
                }
                    

                    

                    // }else{

                    // if ((trkhTamatYYMMDD == trkhmulaYYMMDD)&&(trkhTamathhmm < trkhMulahhmm)) {
                    //         messages.push('"Masa Mula Lawatan mestilah lebih kecil dari Masa Tamat Lawatan".');
                    //     }else if (trkhTamatYYMMDD < trkhmulaYYMMDD) {
                    //         messages.push($message);
                    //     }
                    // }
                
JS;
    }
}