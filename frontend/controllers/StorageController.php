<?php

namespace frontend\controllers;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
class StorageController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                  
                    [
                        'actions' => [
                            'monitor',
                        ],
                        'allow' => true,
                        'roles' => ['view_storage_info'],
                    ],
                ],
            ],
        ];
    }
    /**
     * Displays storage information from a linux server
     * @return string
     * @author khalid <thewinner016@gmail.com>
     * @since 1.0.0
     */
    public function actionMonitor()
    {
        $info = shell_exec("df -h");
        return $this->render('storage', ['info' => $info]);
    }

}
