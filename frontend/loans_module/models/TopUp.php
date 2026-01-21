<?php
namespace frontend\loans_module\models;
use yii\base\Model;
use yii\base\UserException;
use yii\web\UploadedFile;
use common\models\CustomerLoan;
use yii\helpers\Html;
use yii;


class TopUp extends Model
{
  public $topup_mode;
  public $topup_amount;
  public $reference_document;

  public $extension_periods=0;

  public function rules(){
    return [
           [['topup_mode','topup_amount'],'required'],
           ['topup_amount','number','min'=>10000],
           ['reference_document', 'required', 'message' => 'Please upload at least one file.'],
            [
            'reference_document',
            'file',
            'skipOnEmpty' => false,                   
            'maxSize' => 10 * 1024 * 1024,  
            'tooBig' => 'The file must be smaller than 10 MB.',
            'extensions' => 'jpg, png, pdf',
            'wrongExtension' => 'Only files with these extensions are allowed: {extensions}.',
            ],
                 [['extension_periods'], 'required',
            'when' => function ($model) {
                return $model->topup_mode === 'tenure_extension';
            },
            'whenClient' => "function (attribute, value) {
                return $('#" . Html::getInputId($this, 'topup_mode') . "').val() === 'tenure_extension';
            }"
        ],

        [['extension_periods'], 'integer', 'min' => 1]
    ];
  }

  public function topUp($loanID, $request)
  {
     $this->load($request->post());
     $uploadedFile=UploadedFile::getInstance($this,"reference_document");
     $this->reference_document=$uploadedFile;

     if(!$this->validate())
    {
        throw new UserException(json_encode($this->getErrors()));
    }
    try
    {
    $transaction=yii::$app->db->beginTransaction();
    $documentUrl=$this->saveFile();

    $loan=CustomerLoan::findOne($loanID);

    $extension_periods=($this->extension_periods)?$this->extension_periods:0;

    $loan->topUp($this->topup_amount,$this->topup_mode, $extension_periods,$documentUrl);
    $transaction->commit();

    return $loan;
    }
    catch(UserException $u)
    {
       $transaction->rollBack();
        if ($documentUrl && file_exists(Yii::getAlias('@webroot') . $documentUrl)) {
        unlink(Yii::getAlias('@webroot') . $documentUrl);
        }
       throw $u;
    }
    catch(\Exception $e)
    {
      $transaction->rollBack();
      if ($documentUrl && file_exists(Yii::getAlias('@webroot') . $documentUrl)) {
      unlink(Yii::getAlias('@webroot') . $documentUrl);
      }
       throw $e;
    }

    

    

  }
     public function saveFile()
    {
            $file=$this->reference_document;
            $filename='/uploads/'.uniqid() . '.' . $file->extension;
            $path= Yii::getAlias('@webroot').$filename;
            if($file->saveAs($path))
            {
                return $filename;
            }

      
    }
}