<?php
namespace frontend\models;

use Yii;
use yii\helpers\VarDumper;
use yii\base\Model;
use common\models\User;
use yii\web\NotFoundHttpException;

/**
 * Signup form
 */
class ChangePasswordForm extends Model
{
    public $current_password;
    public $new_password;
    public $confirm_new_password;

    

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['current_password', 'new_password', 'confirm_new_password'], 'required'],
            [
                'confirm_new_password', 'compare', 'compareAttribute' => 'new_password',
                'message' => "Passwords don't match", 
            ],
            
        ];
    }

    /**
     * change user password.
     *
     *
     */
    /**
     * {@inheritdoc}
     */
    public function changePassword()
    {
         if (!$this->validate()) {
             return false;
        }
        
        $id = Yii::$app->user->getId(); 
    
        $user = User::findIdentity($id);
        

        /* save the new password to the database */
        try{
            if($user->validatePassword($this->current_password)){
                // VarDumper::dump($user->validatePassword($this->current_password));
                $user->setPassword($this->new_password);
                $user->generateAuthKey();
                $user->generateEmailVerificationToken();
                if($user->save()){
                    return true;
                }
               
            }
       
       }catch(\Throwable $e){

            throw new NotFoundHttpException('fail to change password');
      }
    return false;
}

  
   
}
