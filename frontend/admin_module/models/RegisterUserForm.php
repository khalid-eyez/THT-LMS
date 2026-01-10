<?php

namespace frontend\admin_module\models;

use Yii;
use yii\base\Model;
use common\models\User;
use yii\helpers\Html;

/**
 * Signup form
 */
class RegisterUserForm extends Model
{
    public $role =[];
    public $username;
    public $password="123";

    public $full_name;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            [['username','full_name'], 'required'],
            ['role','required','message'=>'Privilege or position must be assigned'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This Username has already been taken.'],
            ['username', 'email','message' => 'Invalid Email Address.'],
            ['username', 'string', 'min' => 2, 'max' => 255],
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function create()
    {
        //get authManager instance
        $auth = Yii::$app->authManager;
        if (!$this->validate()) {
            return false;
        }

        $user = new User();
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $user->username = $this->username;
            $user->name=$this->full_name;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->generateEmailVerificationToken();
            if ($user->save()) {
                    //now assign role to this newlly created user========>>
                    foreach($this->role as $r)
                    {
                        $userRole = $auth->getRole($r);
                        $auth->assign($userRole, $user->getId());
                    }
                   
                    $transaction->commit();
                    return true;
                }
        
            throw new \Exception("Could not create user !".Html::errorSummary($user));
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function update($id)
    {
        //get authManager instance
        $auth = Yii::$app->authManager;
        $user =User::findOne($id);
      
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $user->username = $this->username;
            if ($user->save()) {
                    //now assign role to this newlly created user========>>
                    $auth->revokeAll($user->id);
                    foreach($this->role as $r)
                    {
                        $userRole = $auth->getRole($r);
                        $auth->assign($userRole, $user->getId());
                    }
                   
                    $transaction->commit();
                    return true;
                }
        
            throw new \Exception("Could not update user !".Html::errorSummary($user));
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw new \Exception($e->getMessage());
        }
    }

}
