<?php
namespace frontend\loans_module\models;
use yii\base\Model;
use yii\base\UserException;
use yii\web\UploadedFile;

class TopUp extends Model
{
  public $topup_mode;
  public $topup_amount;
  public $reference_document;

  public function rules(){
    return [
           [['topup_mode','topup_amount'],'required'],
           ['topup_amount','number'],
           ['reference_document', 'required', 'message' => 'Please upload at least one file.'],
            [
            'reference_document',
            'file',
            'skipOnEmpty' => false,                   
            'maxSize' => 10 * 1024 * 1024,  
            'tooBig' => 'The file must be smaller than 10 MB.',
            'extensions' => 'jpg, png, pdf',
            'wrongExtension' => 'Only files with these extensions are allowed: {extensions}.',
            ]
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

    

  }
}