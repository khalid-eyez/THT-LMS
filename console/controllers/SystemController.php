<?php
   namespace console\controllers;
   use Yii;
   use yii\console\Controller;
   class SystemController extends Controller
   {
    public $admin_username;
    public $admin_password;

    public $no_admin;

    public function options($actionID) {
        return array_merge(parent::options($actionID), ['admin_username', 'admin_password','no_admin']);
    }
     public function actionInit()
     {

        $this->stdout("******************WS ADMIN 1.0.0******************* \n ");
        $this->stdout("Initializing... \n ");
       //initializing the authorization data

       yii::$app->runAction('rbac/init');

       //creating the first admin ever
       if(!$this->no_admin)
       {
       yii::$app->runAction('admin/create',['username'=>$this->admin_username,'password'=>$this->admin_password]);
       }

       $this->stdout("**************INITIALIZATION COMPLETE***************");
     }
   }


?>