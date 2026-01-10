<?php
namespace console\controllers;
use yii\console\Controller;
use yii;
use common\models\Admin;
use yii\helpers\Console;



class AdminController extends Controller
{

public $username;
public $password;
public $name;

public function options($actionID) {
   return array_merge(parent::options($actionID), ['username', 'password','name']);
}
   public function actionCreate()
   {
      $this->stdout("Creating the administrator of the system... \n ");
    try{
    	$model = new Admin($this->username,$this->password,$this->name);
    	if($model->create()){
    		$this->stdout("A user with username \"$model->username\" and password \"$model->password\" was created successfully ! \n",Console::FG_BLUE);
            return 0;
    	}
     
    }catch(\Exception $e){
    	$this->stderr("An error occured while creating a user!".$e->getMessage()." \n ");
    } 
   }


}












?>