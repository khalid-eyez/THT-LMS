<?php
namespace frontend\loans_module\models;
use yii\base\Model;
use common\models\CustomerLoan;


class LoanSearch extends Model
{
  public $keyword;

  public function rules()
  {
     return [
        ['keyword','required'],
     ];
  }

  public function searchLoans()
  {
   $keyword = $this->keyword;

    $loans = CustomerLoan::find()
    ->joinWith('customer')
    ->andFilterWhere([
    'or',
    ['like', 'customer_loans.loanID', $keyword],
    ['like', 'customers.full_name', $keyword],
    ['like','customers.NIN',$keyword],
    ['like','customers.customerID',$keyword]
    ])->all();

     return $loans;
  }
}