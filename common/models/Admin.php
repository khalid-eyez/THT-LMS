<?php

namespace common\models;

use Exception;
use Yii;
use yii\base\Model;
use common\models\User;
use yii\helpers\Html;
use bedezign\yii2\audit\AuditTrailBehavior;

/**
 * Signup form
 */
class Admin extends Model
{
    public $role ='ADMIN';
    public $username="admin@ws.com";
    public $password="123";

    public $name="SYS ADMIN";
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            [['username','name'], 'required'],
            ['role','required','message'=>'Privilege or position must be assigned'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'User already exists.'],
            ['username', 'email','message' => 'Username must be a valid Email Address.'],
            ['username', 'string', 'min' => 5, 'max' => 255],
        ];
    }

 
    public function __construct($username,$password,$name,$config = [])
    {
        if(isset($username) && $username!=null)
        {
            $this->username=$username;
        }
        if(isset($password) && $password!=null)
        {
            $this->password=$password;
        }
         if(isset($name) && $name!=null)
        {
            $this->name=$name;
        }
        parent::__construct($config);
    }
    public function create()
    {
        //get authManager instance
        $auth = Yii::$app->authManager;
        if (!$this->validate()) {
            foreach($this->getErrorSummary(true) as $error)
            {
                throw new Exception($error."\n");
            }
        }

        $user = new User();
        $user->detachBehavior('auditBehaviour');
       
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $user->username = $this->username;
            $user->name=$this->name;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->generateEmailVerificationToken();

           
            if ($user->save()) {
                    //now assign role to this newlly created user========>>
             
                        $userRole = $auth->getRole($this->role);
                        if(!$userRole){ throw new Exception("The user role does not exist !");}
                        $auth->assign($userRole, $user->getId());
                    
                   
                    $transaction->commit();
                    return true;
                }
        
            throw new \Exception("Could not create user ! \n");
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw new \Exception($e->getMessage());
        }
    }

   

}
