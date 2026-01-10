<?php
namespace frontend\loans_module\models;
use common\models\CustomerLoan;
use common\models\LoanType;
use Exception;
use yii;

use yii\base\Model;
use yii\helpers\ArrayHelper;

class LoanInfo extends Model
{ 
    public $loan_amount;
    public $loan_type_ID;
    public $repayment_frequency;
    public $loan_duration_units;
    public function rules()
    {
        return [
            [['loan_amount'], 'number'],
             
            [['loan_type_ID','repayment_frequency', 'loan_amount','loan_duration_units'], 'required'],
            [['repayment_frequency'], 'string'],
            [['loan_duration_units'], 'integer'],   
        ];
    }
    public function repayment_frequencies()
    {
        return CustomerLoan::optsRepaymentFrequency() ;
    }
    public function loantypes(){
        $loantypes=LoanType::find()->all();
        $loantypes=ArrayHelper::map($loantypes,'id','type');
        return $loantypes;
    }
    public function save($customerID)
    {
        $loan=new CustomerLoan();
        $loan->loan_amount=$this->loan_amount;
        $loan->repayment_frequency=$this->repayment_frequency;
        $loan->loan_duration_units=$this->loan_duration_units;
        $loan->loan_type_ID=$this->loan_type_ID;
        $loan->customerID=$customerID;
        $loan->status="new";
        $loan->initializedby=yii::$app->user->identity->id;
        $loan->processing_fee_rate=0;
        $loan->processing_fee=0;
        $loan->interest_rate=0;
        $loan->penalty_rate=0; 
        $loan->topup_rate=0;
        $loan->topup_amount=0;
        $loan->deposit_amount=0;

        if(!$loan->save())
        {
           throw new Exception(json_encode($loan->getErrors()));  
        }
        return $loan;
       
    }


}