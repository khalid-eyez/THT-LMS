<?php

namespace frontend\controllers;
use yii\filters\AccessControl;
class SuperAdminController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['dashboard'],
                        'allow' => true,
                        'roles' =>['SUPER_ADMIN']
                        
                    ],
                    
                    
                    
                ],
            ],
            // 'verbs' => [
            //     'class' => VerbFilter::className(),
            //     'actions' => [
            //         'logout' => ['post'],
            //     ],
            // ],
        ];
    }
    public function actionDashboard()
    {
        return $this->render('index');
    }

}
