<?php
namespace frontend\Models;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii;
use common\models\AuthRule;
use common\models\AuthItem;
use yii\helpers\Html;



class AddChildren extends Model
{

    public $childroles=[];
    public $permissions=[];

    public function rules()
    {
        return[
            ['permissions','safe'],
            ['childroles','safe'],
        ];
    }
    public function getRoles()
    {
        return ArrayHelper::map(yii::$app->authManager->getRoles(),'name','name');
    }
    public function getPermissions()
    {
        return ArrayHelper::map(yii::$app->authManager->getPermissions(),'name','name');
    }
    public function addTo($name,$type)
    {
        if(!$this->validate())
        {
            return false;
        }
        $transaction=yii::$app->db->beginTransaction();
        try{
            $manager=yii::$app->authManager;
            $item=($type==1)?$manager->getRole($name):$manager->getPermission($name);
            //adding child roles
            if($this->childroles!=null)
            {
            foreach($this->childroles as $child)
            {
                $childrole=$manager->getRole($child);
                if($childrole==null){continue;}

                if(!$manager->addChild($item,$childrole))
                {
                    continue;
                }
            }
        }
            //permissions
            if($this->permissions!=null)
            {
            foreach($this->permissions as $permission)
            {
                $permissionItem=$manager->getPermission($permission);
                if($permission==null){continue;}
                if(!$manager->addChild($item,$permissionItem))
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