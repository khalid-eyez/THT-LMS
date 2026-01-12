<?php

namespace common\models;
use common\helpers\UniqueCodeHelper;
use Yii;
use yii\base\Model;
use yii\db\Exception;

class CustomerShareholderForm extends Model
{
    /* ---------- CUSTOMER FIELDS ---------- */
    public $customerID;
    public $full_name;
    public $birthDate;
    public $gender;
    public $address;
    public $contacts;
    public $NIN;
    public $TIN;

    /* ---------- SHAREHOLDER FIELDS ---------- */
    public $memberID;
    public $initialCapital;
    public $shares;

    public function rules()
    {
        return [
            [['customerID','full_name','birthDate','gender','address','contacts','NIN'], 'required'],
            [['memberID','initialCapital'], 'required'],
            [['initialCapital'], 'number'],
            [['shares'], 'integer'],
        ];
    }
   //loading for search
public function loadExisting($shareholderId): bool
  {
    $shareholder = Shareholder::findOne($shareholderId);
    if (!$shareholder) {
        return false;
    }

    $customer = $shareholder->customer;

    // Customer fields
    $this->customerID   = $customer->id;
    $this->full_name    = $customer->full_name;
    $this->birthDate    = $customer->birthDate;
    $this->gender       = $customer->gender;
    $this->address      = $customer->address;
    $this->contacts     = $customer->contacts;
    $this->NIN          = $customer->NIN;
    $this->TIN          = $customer->TIN;

    // Shareholder fields
    $this->shareholder_id  = $shareholder->id;
    $this->memberID        = $shareholder->memberID;
    $this->initialCapital  = $shareholder->initialCapital;
    $this->shares          = $shareholder->shares;

    return true;
}

    /**
     * Save both Customer and Shareholder using TRANSACTION
     */
    public function save()
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            /* ---------- SAVE CUSTOMER ---------- */
            $customer = new Customer();
            // HAPA NATENGENEZA CUSTOMER ID KUPITIA GENERATOR YA KHALID YA KWENYE HELPERS
            $customer->customerID = UniqueCodeHelper::generate('CUST', 6);
           // $customer->customerID = $this->customerID;
            $customer->full_name  = $this->full_name;
            $customer->birthDate  = $this->birthDate;
            $customer->gender     = $this->gender;
            $customer->address    = $this->address;
            $customer->contacts   = $this->contacts;
            $customer->NIN        = $this->NIN;
            $customer->TIN        = $this->TIN;

            if (!$customer->save()) {
                throw new Exception('Customer save failed: ' . json_encode($customer->errors));
            }

            /* ---------- SAVE SHAREHOLDER ---------- */
            $shareholder = new Shareholder();
            $shareholder->customerID     = $customer->id;
            $shareholder->memberID       = $this->memberID;
            $shareholder->initialCapital = $this->initialCapital;
            $shareholder->shares         = $this->shares;

            if (!$shareholder->save()) {
                throw new Exception('Shareholder save failed: ' . json_encode($shareholder->errors));
            }

            $transaction->commit();
            return true;

        } catch (\Throwable $e) {
            $transaction->rollBack();
            $this->addError('customerID', $e->getMessage());
            return false;
        }
    }
}
