<?php
namespace common\models;

use Yii;
use yii\base\Model;
use common\helpers\UniqueCodeHelper;
use yii\web\UploadedFile;

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
                [['type'], 'string'],
                [['deposit_date'], 'safe'],
                [['payment_document'], 'file',
                    'skipOnEmpty' => false,
                    'extensions' => 'pdf,jpg,jpeg,png',
                    'maxSize' => 10 * 1024 * 1024 // HIZI NI 10 MB
                ],
            ];
        }

    /**
     * Save both Deposit and Cashbook in one DB transaction
     */
    public function save()
    {

        $this->interest_rate = 10;
    
        if(!$this->validate()) {
        Yii::error($this->errors, 'form_validation');
        return false;
       }
       if ($this->validate()) {
        print("HII BWANA KHALID ATASOLVE MIMI LIMENISHINDA,maana naona apa imevalidate");
       }
        $transaction = Yii::$app->db->beginTransaction();

        try {
            /* ---------- Upload file ---------- */
            $uploadPath = Yii::getAlias('@frontend/web/uploads/deposits/');

            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $fileName = 'DEP_' . time() . '.' . $this->payment_document->extension;
            $fileFullPath = $uploadPath . $fileName;

            if (!$this->payment_document->saveAs($fileFullPath)) {
                throw new \Exception('File upload failed');
            }

            /* ---------- Save Deposit ---------- */
            $deposit = new Deposit();
            $deposit->shareholderID = $this->shareholderID;
            $deposit->amount = $this->amount;
            $deposit->interest_rate = $this->interest_rate ;
            $deposit->type = $this->type;
            $deposit->deposit_date = $this->deposit_date;

            if (!$deposit->save()) {
                  var_dump($deposit->shareholderID);
                  exit;
                throw new \Exception(json_encode($deposit->errors));
            }

            /* ---------- Save Cashbook ---------- */
            $cashbook = new Cashbook();
            $cashbook->customerID = $this->customerID;
            $cashbook->reference_no = UniqueCodeHelper::generate('DEP-REF', 5);
            $cashbook->description = 'Shareholder Deposit';
            $cashbook->category = 'Deposit';
            $cashbook->debit = 0;
            $cashbook->credit = $this->amount;
            $cashbook->balance = $this->getNewBalance($this->amount);
            $cashbook->payment_document = $fileName;
              // var_dump($fileName);
               //var_dump($cashbook->payment_document);
              // exit;
            if (!$cashbook->save()) {
                //  var_dump($fileName);
                 // var_dump($cashbook->payment_document);
                //  exit;
                throw new \Exception(json_encode($cashbook->errors));
            }

            $transaction->commit();
            return true;

        } catch (\Throwable $e) {

        $transaction->rollBack();

        // Remove uploaded file if DB fails
        if (isset($fileFullPath) && file_exists($fileFullPath)) {
            unlink($fileFullPath);
        }

        Yii::error($e->getMessage(), __METHOD__);
        return false;
    }
    }

    /**
     * Calculate new balance
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
