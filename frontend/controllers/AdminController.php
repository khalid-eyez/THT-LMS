<?php
namespace frontend\controllers;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\models\User;
class AdminController extends Controller
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
                        'actions' => [
                            'users-list',
                        ],
                        'allow' => true,
                        'roles' => ['view_users_list'],
                    ],
                ],
            ],
        ];
    }
    /**
     * Displays users' list
     * @return string
     * @author khalid <thewinner016@gmail.com>
     * @since 1.0.0
     */
    public function actionUsersList(){
        $users=User::find()->all();

        return $this->render('users_list',['users'=>$users]);
    }

}
