<?php
namespace frontend\loans_module\models;
use common\models\CustomerLoan;
use common\models\LoanType;
use Exception;
use yii;

use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\base\UserException;
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
        if(!$this->validate()){
            throw new UserException('Could not validate your data submission!');
        }
        $loan=new CustomerLoan();
        $loan->loan_amount=$this->loan_amount;
        $loan->repayment_frequency=$this->repayment_frequency;
        $loan->loan_duration_units=$this->loan_duration_units;
        $loan->loan_type_ID=$this->loan_type_ID;
        $loan->customerID=$customerID;
        $loan->status="new";
        $loan->initializedby=yii::$app->user->identity->id;
        // loan type information
        $loanType=LoanType::findOne($this->loan_type_ID);
        $processing_fee_rate=$loanType->processing_fee_rate;
        $processing_fee=round(($this->loan_amount*$processing_fee_rate)/100,2);
        $interest_rate=$loanType->interest_rate;
        $penalty_rate=$loanType->penalty_rate;
        $topup_rate=$loanType->topup_rate;
        $loan->penalty_grace_days=$loanType->penalty_grace_days;
        $loan->processing_fee_rate= $processing_fee_rate;
        $loan->processing_fee=$processing_fee;
        $loan->interest_rate=$interest_rate;
        $loan->penalty_rate=$penalty_rate;
        $loan->topup_rate=$topup_rate;
        $loan->topup_amount=0;
        $loan->deposit_amount=$this->loan_amount-$processing_fee;

        if(!$loan->save())
        {
           throw new UserException("Unable to save loan details");
        }
        return $loan;
       
    }


}