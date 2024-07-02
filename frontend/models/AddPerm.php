<?php
namespace frontend\models;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii;
use common\models\AuthRule;
use common\models\AuthItem;
use yii\helpers\Html;



class AddPerm extends Model
{
    public $name;
    public $description;
    public $ruleName;
    public $permissions=[];

    public function rules()
    {
        return[
            [['name','description'],'required'],
            [['name', 'ruleName'], 'string', 'max' => 64],
            ['name','trim'],
            ['permissions','safe'],
            ['name', 'unique','targetClass' =>AuthItem::className(),'message'=>'Permission already exists'],
            [['ruleName'], 'exist', 'skipOnError' => true, 'targetClass' => AuthRule::className(), 'targetAttribute' => ['ruleName' => 'name']],
        ];
    }
    public function getRules()
    {
        return ArrayHelper::map(yii::$app->authManager->getRules(),'name','name');
    }
    public function getPermissions()
    {
        return ArrayHelper::map(yii::$app->authManager->getPermissions(),'name','name');
    }

    public function addPerm()
    {
        if(!$this->validate())
        {
            return false;
        }
        $transaction=yii::$app->db->beginTransaction();
        try{
            $manager=yii::$app->authManager;
            $perm=$manager->createPermission($this->name);
            //getting the rule
            if($this->ruleName!=null)
            {
            $rule=$manager->getRule($this->ruleName);
            if($rule==null)
            {
                throw new \Exception("Rule does not exist");
            }
            $perm->ruleName=$rule->name;
            }

            
            $perm->description=$this->description;

            //now adding the permission
            if(!$manager->add($perm))
            {
                throw new \Exception("Could not add new permission to the system");
            }

        
            //Child permissions
            if($this->permissions!=null){
            foreach($this->permissions as $permission)
            {
                $permissionItem=$manager->getPermission($permission);
                if($permissionItem==null){continue;}
                if(!$manager->addChild($perm,$permissionItem))
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