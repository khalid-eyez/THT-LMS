<?php
namespace frontend\shareholder_module\models;

use Yii;
use yii\base\Model;
use common\helpers\UniqueCodeHelper;
use yii\web\UploadedFile;
use common\models\Deposit;
use frontend\cashbook_module\models\Cashbook;
use common\models\Setting;
use common\models\Shareholder;

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

        $this->interest_rate = (new Setting())->getSettingValue("Interest Rate");
    
        if(!$this->validate()) {
        Yii::error($this->errors, 'form_validation');
        return false;
       }
    
        $transaction = Yii::$app->db->beginTransaction();

        try {
            /* ---------- Upload file ---------- */
            $uploadPath = Yii::getAlias('@frontend/web/uploads/');

            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $fileName = 'DEP_' . time() . '.' . $this->payment_document->extension;
            $fileFullPath = $uploadPath . $fileName;

            if (!$this->payment_document->saveAs($fileFullPath)) {
                throw new \Exception('Could not save payment reference document');
            }

            /* ---------- Save Deposit ---------- */
            $deposit = new Deposit();
            $deposit->shareholderID = $this->shareholderID;
            $deposit->amount = $this->amount;
            $deposit->interest_rate = $this->interest_rate ;
            $deposit->type = $this->type;
            $deposit->deposit_date = $this->deposit_date;

            if (!$deposit->save()) {
                throw new \Exception(json_encode($deposit->geErrors()));
            }

            /* ---------- Save Cashbook ---------- */
          
            $shareholder=Shareholder::findOne($this->shareholderID);
            $memberID=$shareholder->memberID;
            $description=($this->type=="capital")?"Shareholder Initial Capital Deposit":"Shareholder Monthly Deposit";
            $description="[$memberID] ".$description;
            $cashbook_record=[
                'debit'=>$this->amount,
                'credit'=>0,
                'category'=>'Deposit',
                'payment_doc'=>$fileFullPath,
                'description'=>$description
            ];
            $cashbook = new Cashbook($cashbook_record);
            $record_saved=$cashbook->save("DP",substr($shareholder->customer->NIN, -1));
            $transaction->commit();
            return true;

        } catch (\Throwable $e) {

        $transaction->rollBack();
        // Remove uploaded file if DB fails
        if (isset($fileFullPath) && file_exists($fileFullPath)) {
            unlink($fileFullPath);
        }

        Yii::error($e->getMessage(), __METHOD__);
         throw $e;
    }
    }
}
