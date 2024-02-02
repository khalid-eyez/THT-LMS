<?php

namespace frontend\models;

use Yii;
use common\models\User;
use common\models\Student;

/**
 * This is the model class for table "user".
 *

 * @property string|null $email
 * @property Student[] $students
 */
class AddEmailForm extends \yii\db\ActiveRecord
{
    public $email;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email'], 'required'],
            [[ 'email'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'email' => Yii::t('app', 'Email'),
        ];
    }

   

    /**
     * Add Email.
     *
     * @return bool whether the email is added.
     */
    public function addEmail()
    {
         //get authManager instance
     $auth = Yii::$app->authManager;
     if (!$this->validate()) {
         return false;
    }

    $id = Yii::$app->user->getId(); 
    $reg_no = Yii::$app->user->identity->username;
    
        $user = User::findIdentity($id);
        $student = Student::findReg_no($reg_no);
        

        /* save the new password to the database */
        $transaction = Yii::$app->db->beginTransaction();
        try{
            $user->email = $this->email;
                if($user->save()){
                    $student->email = $this->email;
                    if($student->save()){
                        $transaction->commit();
                    return true;
                    }
                    
                }
       
       }catch(\Throwable $e){
             $transaction->rollBack();
            return $e->getMessage();
      }
    return false;
    }
}
