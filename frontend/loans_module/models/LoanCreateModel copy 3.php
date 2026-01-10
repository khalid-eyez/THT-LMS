<?php
namespace Frontend\loans_module\Models;

use yii\base\Model;

class LoanCreateModel extends Model
{
    public $full_name;
    public $birthDate;
    public $gender;
    //public $address=[];
    //public $emails=[];
    //public $phones=[];
    public $NIN;
    public $TIN;
    public $loan_type_ID;
    public $amount;
    public $repayment_frequency;

    public $loan_duration_units;

    //public $documents=[];
    public function rules()
    {
        return [
            [['full_name', 'birthDate', 'gender', 'NIN'], 'required'],
            [['birthDate'], 'safe'],
            //['address', 'each', 'rule' => ['string', 'max' => 255]],
            //['emails', 'each', 'rule' => ['email']],
            //['phones', 'each', 'rule' => ['string']],
            [['full_name', 'NIN', 'TIN'], 'string', 'max' => 50],
            [['NIN'], 'unique'],
            [['TIN'], 'unique'],
            //customer loans
            [['loan_type_ID', 'amount', 'repayment_frequency', 'loan_duration_units'], 'required'],
            [['loan_duration_units'], 'integer'],
            [['amount'], 'number'],
            [['repayment_frequency'], 'string'],
        //     [
        //     ['documents'],
        //     'file',
        //     'skipOnEmpty' => false,
        //     'extensions' => ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx','txt'],
        //     'maxSize' => 20 * 1024 * 1024,
        //    ],
        ];
    }


}