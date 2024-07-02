<?php
namespace frontend\models;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii;
use common\models\AuthRule;
use common\models\AuthItem;
use yii\helpers\Html;



class AddRule extends Model
{
    public $classname;
    public $namespace;
    public function rules()
    {
        return[
            [['classname'],'required','message'=>'The rule class name is required'],
            ['namespace','default','value'=>'\common\rules\\'],
            [['classname','namespace'],'trim']
        ];
    }
 

    public function addrule()
    {
        if(!$this->validate())
        {
            return false;
        }
            $manager=yii::$app->authManager;
            $fullclassname=$this->namespace.$this->classname;
            if(!class_exists($fullclassname))
            {
                throw new \Exception("Rule class {$fullclassname} not found !");
            }

            $reflection=new \ReflectionClass($fullclassname);

            if(!$reflection->isInstantiable())
            {
                throw new \Exception("Class {$fullclassname} not instatiable");
            }

            $rule=new $fullclassname;
            if($manager->getRule($rule->name)!=null)
            {
                throw new \Exception("Rule already exists !");
            }
            
            $manager->add($rule);
            return true;
        
    
    }
}









?>