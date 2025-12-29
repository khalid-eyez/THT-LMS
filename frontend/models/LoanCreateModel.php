<?php
namespace Frontend\Models;

use yii\base\Model;

class LoanCreateModel extends Model{

 public function rules()
    {
        return [
            [['full_name', 'birthDate', 'gender', 'address', 'contacts', 'NIN'], 'required'],
            [['birthDate', 'address', 'contacts'], 'safe'],
            [['full_name', 'NIN', 'TIN'], 'string', 'max' => 50],
            [['NIN'], 'unique'],
            [['TIN'], 'unique'],
            //customer loans
            [['loan_type_ID', 'amount', 'repayment_frequency', 'loan_duration_units'], 'required'],
            [['loan_duration_units'], 'integer'],
            [['amount'], 'number'],
            [['repayment_frequency'], 'string'] 
        ];
    }


}