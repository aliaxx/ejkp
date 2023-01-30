<?php

namespace backend\modules\option\controllers;

use backend\modules\inventori\models\PasukanAhli;
use Yii;
use yii\web\Controller;
use common\models\Pengguna;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;

/**
 * Pengguna controller
 */
class PenggunaController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
            ],
        ];
    }

    /**
     * Return list
     */
    public function actionIndex($search = null, $page = 1, $includekp = false, $kodcawangan = null)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $perPage = 20;
        $output['results'] = [];

        $query = Pengguna::find()->where(['status' => 1])->orderBy(['nama' => SORT_ASC]);
        if (!is_null($search)) {
            $query->andWhere(['or', ['like', 'nama', $search], ['like', 'nokp', $search]]);
        }
        if ($kodcawangan) {
            $query->andWhere(['kodcawangan' => $kodcawangan]);
        }
        $output['total'] = $query->count('*');
        $results = $query->limit($perPage)->offset(($page - 1) * $perPage)->all();
        foreach ($results as $result) {
            $text = $result->nama;
            if ($includekp) $text = $result->nokp . ' - ' . $result->nama;

            $output['results'][] = ['id' => $result->id, 'text' => $text];
        }

        return $output;
    }

    /**
     * Return list
     */
    public function actionAhlipasukan($search = null, $page = 1, $kodpasukan = null)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $perPage = 20;
        $output['results'] = [];

        //$subquery = \backend\modules\inventori\models\PasukanAhli::find()->select(['{{%pasukan_ahli}}.kodpasukan', '{{%pasukan_ahli}}.idpengguna'])->where(['{{%pasukan_ahli}}.kodpasukan' => $kodpasukan, '{{%pasukan_ahli}}.jenispengguna' => 2, '{{%pasukan_ahli}}.status' => 1]);
        $subquery = \backend\modules\inventori\models\PasukanAhli::find()->select(['{{%pasukan_ahli}}.kodpasukan', '{{%pasukan_ahli}}.idpengguna'])->where(['{{%pasukan_ahli}}.kodpasukan' => $kodpasukan, '{{%pasukan_ahli}}.status' => 1]);
        $query = Pengguna::find()
            ->join('INNER JOIN', ['p' => $subquery], 'p.idpengguna = {{%pengguna}}.id')
            ->andWhere(['status' => 1])
            ->orderBy(['nama' => SORT_ASC]);

        if (!is_null($search)) {
            $query->andWhere(['like', 'nama', $search]);
        }

        $output['total'] = $query->count('*');
        $results = $query->limit($perPage)->offset(($page - 1) * $perPage)->all();
        foreach ($results as $result) {
            $text = $result->nama;
            $output['results'][] = ['id' => $result->id, 'text' => $text];
        }

        return $output;
    }

    public function actionDependCawangan($search = null, $selected = null, $kodcawangan = null, $page = 1)
    {
        if (Yii::$app->request->isAjax) {
            $depDrop = Yii::$app->request->post('depdrop_parents');
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $perPage = 20;
            $output['results'] = [];

            $query = Pengguna::find()->where(['status' => 1])->orderBy(['nama' => SORT_ASC]);
            if (!is_null($search)) {
                $query->andWhere(['like', 'nama', '%' . $search . '%', false]);
            }
            if ($depDrop[0]) {
                $query->andWhere(['kodcawangan' => $depDrop[0]]);
            } else if ($kodcawangan) {
                $query->andWhere(['kodcawangan' => $kodcawangan]);
            } else {
                return ['output' => [], 'total' => 0];
            }

            $output['total'] = $query->count('*');
            $results = $query->limit($perPage)->offset(($page - 1) * $perPage)->all();
            foreach ($results as $result) {
                $output['results'][] = ['id' => $result->id, 'text' => $result->nama, 'name' => $result->nama];
            }

            return ['output' => $output['results'], 'selected' => $selected, 'page' => $page, 'kodcawangan' => $kodcawangan, 'total' => $output['total']];
        }
        return null;
    }

    public function actionList($kodcawangan, $jenisahli = 1)
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $source = Pengguna::find()->select(['id', 'nama', 'kodcawangan', 'status']);

            if ($jenisahli == 2) {
                $source->where(['status' => 1]);
            } else {
                $source->where(['kodcawangan' => $kodcawangan, 'status' => 1]);
            }

            $source = $source->orderBy(['nama' => SORT_ASC])->all();

            if ($source) {
                $data = ArrayHelper::map($source, 'id', 'nama');
                return [
                    'success' => true,
                    'results' => $data,
                ];
            }

            return ['success' => false];
        }
        return null;
    }
}
