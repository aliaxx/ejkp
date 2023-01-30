<?php

namespace backend\components;

use Yii;

use common\models\AuditLog;

/**
 * FtpCredentials Class
 */
class LogActions
{
    const ACTION_CREATE = 1;
    const ACTION_UPDATE = 2;
    const ACTION_DELETE = 3;
    const ACTION_UPLOAD = 4;
    const ACTION_UPLOAD_DELETE = 5;

    public function recordLog($tindakan, $model = null, $oldmodel = null)
    {
        $log = new AuditLog;
        $log->TINDAKAN = $tindakan;
        $log->NAMATABLE = $model->tableSchema->name;
        $log->URLMENU = Yii::$app->request->url;
        if ($oldmodel) { $log->DATALAMA = $oldmodel; }
        $log->DATA = json_encode($model->getAttributes());
        $log->PENGGUNA = Yii::$app->user->ID;
        //$log->TARIKHMASA = date('Y-m-d H:i:s'); 
        $log->TARIKHMASA = DATE('Y-m-d H:i:s'); //Oracle using format TO_DATE. Nurul 200722 
        $log->save(false);
    }
}
