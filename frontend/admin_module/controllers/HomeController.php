<?php

namespace frontend\admin_module\controllers;
use yii\filters\AccessControl;
class HomeController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                  
                    [
                        'actions' => [
                            'dashboard',
                        ],
                        'allow' => true,
                        'roles' => ['view_dashboard'],
                    ],
                ],
            ],
        ];
    }
    /**
     * display a welcome message to a User
     * @return string the page to be rendered
     * @author khalid <thewinner016@gmail.com>
     * @since 1.0.0
     * 
     */
    public function actionDashboard()
    {
        $this->layout="user_dashboard";
        return $this->render('/loans/loansdashboard');
    }

}
