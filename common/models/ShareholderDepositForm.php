<?php
namespace common\models;

use Yii;
use yii\base\Model;
use common\helpers\UniqueCodeHelper;
class ShareholderDepositForm extends Model
{
    /* -------- Deposit fields -------- */
    public $shareholderID;
    public $amount;
    public $interest_rate;
    public $type;
    public $deposit_date;

    /* -------- Cashbook fields -------- */
    public $customerID;
    public $reference_no;
    public $payment_document;

    public function rules()
    {
        return [
            [['shareholderID', 'amount', 'interest_rate', 'deposit_date'], 'required'],
            [['amount', 'interest_rate'], 'number'],
            [['shareholderID', 'customerID'], 'integer'],
            [['type', 'reference_no', 'payment_document'], 'string', 'max' => 255],
            [['deposit_date'], 'safe'],
        ];
    }

    /**
     * Save both Deposit and Cashbook in one DB transaction
     */
    public function save()
    {
        //BAADAE TUTAKUJA KUFANYA DYNAMIC INTEREST RATES
        $interest_rate=10;
        if (!$this->validate()) {
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            /* -------- 1. Save Deposit -------- */
            $deposit = new Deposit();
            $deposit->shareholderID = $this->shareholderID;
            $deposit->amount = $this->amount;
            $deposit->interest_rate = $interest_rate; //KWA MUDA FOR DEVELOPMENT PURPOSE
            $deposit->type = $this->type;
            $deposit->deposit_date = $this->deposit_date;

            if (!$deposit->save()) {
                throw new \Exception('Failed to save deposit');
            }

            /* -------- 2. Save Cashbook -------- */
            $cashbook = new Cashbook();
            $cashbook->customerID = $this->customerID;
            $cashbook->reference_no = UniqueCodeHelper::generate('DEP-REF', 5);
            $cashbook->description = 'Shareholder Deposit';
            $cashbook->category = 'Deposit';
            $cashbook->debit = 0;
            $cashbook->credit = $this->amount;
            $cashbook->balance = $this->getNewBalance($this->amount);
            $cashbook->payment_document = $this->payment_document;

            if (!$cashbook->save()) {
                throw new \Exception('Failed to save cashbook');
            }

            $transaction->commit();
            return true;

        } catch (\Throwable $e) {
            $transaction->rollBack();
            Yii::error($e->getMessage(), __METHOD__);
            return false;
        }
    }

    /**
     * Calculate new balance (example logic)
     */
    private function getNewBalance($credit)
    {
        $lastBalance = Cashbook::find()
            ->orderBy(['id' => SORT_DESC])
            ->select('balance')
            ->scalar();

        return ($lastBalance ?? 0) + $credit;
    }
}
