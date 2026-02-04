<?php
namespace frontend\shareholder_module\models;
use yii\base\Model;
use common\models\CustomerLoan;
use common\models\Cashbook;
use common\models\Deposit;
use common\models\Shareholder;
use yii\base\Exception;
use yii\base\UserException;
use yii;

class InterestPay extends Model{
     public $payment_date;
     public $payment_doc;

     public function rules(){
        return [
            [['payment_date'],'required'],
            ['payment_date','safe'],
            [
                'payment_doc',
                'file',
                'skipOnEmpty' => false,                   
                'maxSize' => 5 * 1024 * 1024,  
                'tooBig' => 'The file must be smaller than 5 MB.',
                'extensions' => 'jpg, png, pdf',
                'wrongExtension' => 'Only files with these extensions are allowed: {extensions}.',
                ]
        ];
     }
    
    public function saveFile()
    {
            $file=$this->payment_doc;
            $filename='/uploads/'.uniqid() . '.' . $file->extension;
            $path= Yii::getAlias('@webroot').$filename;
            if($file->saveAs($path))
            {
                return $filename;
            }

      
    }

    public function payInterest($shareholderID)
    {
      $transaction=yii::$app->db->beginTransaction();
      try{
         $shareholder=Shareholder::findOne($shareholderID);
         $paymentDetails=$shareholder->payInterests($this->saveFile(),$this->payment_date);
         $transaction->commit();
         return $paymentDetails;
      }
      catch(UserException $u)
      {
        $transaction->rollBack();
        throw $u;
      }
      catch(\Exception $r)
      {
        $transaction->rollBack();
        throw $r;
      }
    }
}