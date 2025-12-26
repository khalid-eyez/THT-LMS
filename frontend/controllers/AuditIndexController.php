<?php

namespace frontend\controllers;

use bedezign\yii2\audit\components\panels\RendersSummaryChartTrait;
use bedezign\yii2\audit\components\web\Controller;
use bedezign\yii2\audit\models\AuditEntry;
use yii\filters\AccessControl;
use Yii;
class AuditIndexController extends Controller
{
    use RendersSummaryChartTrait;

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
                        'actions' => [
                            'index',
                        ],
                        'allow' => true,
                        'roles' => ['view_audit_data'],
                    ],
                ],
            ],
        ];
    }
    public function actionIndex()
    {
        $this->layout="audit";
        $chartData = $this->getChartData();
        return $this->render('index', ['chartData' => $chartData]);
    }

    protected function getChartModel()
    {
        return AuditEntry::className();
    }
}
