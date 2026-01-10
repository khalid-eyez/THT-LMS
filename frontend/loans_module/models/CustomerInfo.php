<?php

namespace frontend\loans_module\models;
use yii\base\Model;
use common\models\Customer;
use common\models\User;

use Yii;


class CustomerInfo extends Model
{
    public $full_name;
    public $birthDate;
    public $gender;
    public $address;
    public $contacts;
    public $NIN;
    public $TIN;
    public function rules()
    {
        return [
            
            [['full_name', 'birthDate', 'gender', 'address', 'contacts', 'NIN'], 'required'],
            [['birthDate', 'address', 'contacts'], 'safe'],
            [['full_name', 'NIN', 'TIN'], 'string', 'max' => 50],
            [['gender'], 'string', 'max' => 8],
            [['NIN'], 'unique'],
            [['TIN'], 'default', 'value' => null],
            
        ];
    }
    public function save()
    {
      $customer=new Customer();
      $customer->full_name=$this->full_name;
      $customer->birthDate=$this->birthDate;
      $customer->gender=$this->gender;
      $customer->contacts=$this->contacts;
      $customer->address=$this->address;
      $customer->NIN=$this->NIN;
      $customer->TIN=$this->TIN;
      $customer->customerID=uniqid();
      if (!$customer->save()) {
       throw new \Exception(json_encode($customer->errors));
      }


      return $customer;
    }

   
}
