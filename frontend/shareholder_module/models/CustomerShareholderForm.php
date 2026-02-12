<?php

namespace frontend\shareholder_module\models;
use common\models\Customer;
use common\models\Shareholder;
use common\helpers\UniqueCodeHelper;
use Yii;
use yii\base\Model;
use yii\base\Exception;
use yii\base\UserException;
use yii\helpers\Html;

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

 

    public function rules()
    {
        return [
            [['full_name','birthDate','gender','address','contacts','NIN'], 'required'],
            ['NIN', 'unique',
            'targetClass' => \common\models\Customer::class,
            'targetAttribute' => 'NIN',
            'message' => 'This NIN is already registered.'
            ],
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

    return true;
}

    /**
     * Save both Customer and Shareholder using TRANSACTION
     */
    public function save()
    {
        if(!$this->validate())
            {
                throw new UserException(Html::errorSummary($this));
            }
        $transaction = Yii::$app->db->beginTransaction();

        try {
            /* ---------- SAVE CUSTOMER ---------- */
            $customer = new Customer();
            // HAPA NATENGENEZA CUSTOMER ID KUPITIA GENERATOR YA KHALID YA KWENYE HELPERS
            $customer->customerID = UniqueCodeHelper::generate('HTHO', 5)."-".date('y').substr($this->NIN, -1);
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
            $shareholder->memberID       =UniqueCodeHelper::generate('SH', 5)."-".date('y').substr($this->NIN, -1);
            $shareholder->initialCapital = 0;
            $shareholder->shares         = 0;

            if (!$shareholder->save()) {
                throw new UserException('Shareholder save failed: ' . json_encode($shareholder->errors));
            }

            $transaction->commit();
            return $customer;

        } catch (UserException $e) {
            $transaction->rollBack();
            throw $e;
         
        }
        catch (Exception $t) {
            $transaction->rollBack();
            throw $t;
         
        }
    }
}
