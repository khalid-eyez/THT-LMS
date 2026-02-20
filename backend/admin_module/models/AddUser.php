<?php
namespace frontend\admin_module\models;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii;
use common\models\AuthRule;
use common\models\AuthItem;
use yii\helpers\Html;
use common\models\User;



class AddUser extends Model
{

    public $users=[];

    public function rules()
    {
        return[
            ['users','safe'],
            ['users','required']
        ];
    }
    public function getUsers()
    {
        return ArrayHelper::map(User::find()->all(),'id','username');
    }
    public function addTo($item)
    {
        if(!$this->validate())
        {
            return false;
        }
        $transaction=yii::$app->db->beginTransaction();
        try{
            $manager=yii::$app->authManager;
            $item=($manager->getRole($item))?$manager->getRole($item):$manager->getPermission($item);
            //adding child roles
            if($this->users!=null)
            {
            foreach($this->users as $user)
            {
                try
                {
                if(!$manager->assign($item,$user))
                {
                    continue;
                }
                }catch(\Exception $t)
                {
                    continue;
                }
            }
        }
         
          $transaction->commit();
          return true;
        }
        catch(\Exception $r)
        {
          $transaction->rollBack();
          throw $r;
        }
    }
}









?>