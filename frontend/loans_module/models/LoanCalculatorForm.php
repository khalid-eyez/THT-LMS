<?php
namespace frontend\loans_module\models;
use yii\base\Model;
use common\models\LoanType;
use yii\helpers\ArrayHelper;

class LoanCalculatorForm extends Model{

 public $type;
 public $repayment_frequency;

 public $loan_duration;

 public $loan_amount;

 public function rules(){
    return [
        [['type','repayment_frequency','loan_duration','loan_amount'],'required'],
        ['loan_amount','number'],
        [['loan_duration','type'],'integer']
    ];
 }
public function types()
{
   return ArrayHelper::map(LoanType::find()->all(),'id','type');
}

public function calculate()
{
   $loantype=LoanType::findOne($this->type);
   $interest_rate=$loantype->interest_rate;
   $data=(new LoanCalculator())->generateRepaymentSchedule($this->loan_amount,$interest_rate,$this->repayment_frequency,$this->loan_duration,date('Y-m-d H:i:s'));
   $meta=[
    'Loan Amount'=>$this->loan_amount,
    'Loan Type'=>$loantype->type,
    'Repayment frequency'=>$this->repayment_frequency,
    'Loan Duration'=>$this->loan_duration,
    'Interest Rate'=>$interest_rate,
   ];
   return [
       'schedules'=>$data,
       'meta'=>$meta
   ];
}


}