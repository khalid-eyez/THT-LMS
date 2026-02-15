<?php
namespace frontend\loans_module\models;
use yii\base\Model;
use common\models\CustomerLoan;
use yii;
use yii\base\UserException;

class LoanRepayment extends Model{
     public $paid_amount;
     public $payment_date;
     public $payment_doc;

     public function rules(){
        return [
            [['paid_amount','payment_date'],'required'],
            ['payment_date','safe'],
            [
                'payment_doc',
                'file',
                'skipOnEmpty' => false,                   
                'maxSize' => 10 * 1024 * 1024,  
                'tooBig' => 'The file must be smaller than 10 MB.',
                'extensions' => 'jpg, png, pdf',
                'wrongExtension' => 'Only files with these extensions are allowed: {extensions}.',
                ]
        ];
     }
     public function pay_dry_run($loanID)
     {
         $transaction=yii::$app->db->beginTransaction();
         try
         {
         $loan=CustomerLoan::findOne($loanID);
         $simulatedOverdues=$loan->overduesSimulate($this->payment_date);
         $loan->refresh();
         $overdues=$loan->computeOverdues($this->payment_date);
         $due=$overdues['due'];
         $transaction->commit();
         return $due->pay_dry_run($this->payment_date,$this->paid_amount,$this->saveFile());
         }
         catch(UserException $u)
         {
            $transaction->rollBack();
            throw $u;
         }
         catch(\Throwable $t)
         {
           $transaction->rollBack();
           throw new UserException("Repayment failed ! An unknown error happened !");
         }
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
}