<?php
namespace frontend\Models;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii;
use common\models\AuthRule;
use common\models\AuthItem;
use yii\helpers\Html;



class AddRole extends Model
{
    public $name;
    public $description;
    public $ruleName;
    public $type=1;
    public $childroles=[];
    public $permissions=[];

    public function rules()
    {
        return[
            [['name','description'],'required'],
            ['permissions','safe'],
            ['childroles','safe'],
            [['name', 'ruleName'], 'string', 'max' => 64],
            ['name','trim'],
            ['name', 'unique','targetClass' =>AuthItem::className(),'message'=>'Role already exists'],
            [['ruleName'], 'exist', 'skipOnError' => true, 'targetClass' => AuthRule::className(), 'targetAttribute' => ['ruleName' => 'name']],
        ];
    }
    public function getRoles()
    {
        return ArrayHelper::map(yii::$app->authManager->getRoles(),'name','name');
    }
    public function getRules()
    {
        return ArrayHelper::map(yii::$app->authManager->getRules(),'name','name');
    }
    public function getPermissions()
    {
        return ArrayHelper::map(yii::$app->authManager->getPermissions(),'name','name');
    }

    public function addRole()
    {
        if(!$this->validate())
        {
            return false;
        }
        $transaction=yii::$app->db->beginTransaction();
        try{
            $manager=yii::$app->authManager;
            $role=$manager->createRole($this->name);
            //getting the rule
            if($this->ruleName!=null)
            {
            $rule=$manager->getRule($this->ruleName);
            if($rule==null)
            {
                throw new \Exception("Rule does not exist");
            }
            $role->ruleName=$rule->name;
            }

            
            $role->description=$this->description;

            //now adding the role
            if(!$manager->add($role))
            {
                throw new \Exception("Could not add new role to the system");
            }

            //adding child roles
            if($this->childroles!=null)
            {
            foreach($this->childroles as $child)
            {
                $childrole=$manager->getRole($child);
                if($childrole==null){continue;}

                if(!$manager->addChild($role,$childrole))
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
                if(!$manager->addChild($role,$permissionItem))
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