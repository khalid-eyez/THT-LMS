<?php
namespace frontend\loans_module\models;

use common\models\Customer;
use Exception;
use frontend\loans_module\models\CustomerInfo;
use yii\web\Request;
use yii\web\UploadedFile;
use common\models\LoanAttachment;

use yii\base\Model;
use yii;

class LoanService extends Model
{
   public Request $request;

   public function __construct(Request $request,$config = [])
   {
    $this->request=$request;
    parent::__construct($config);
   }
   public function saveLoan(){
   try{
   $transaction=yii::$app->db->beginTransaction();
   $customerInfo=new CustomerInfo();
   $customerInfo->load($this->request->post());
   $loanInfo=new LoanInfo();
   $loanInfo->load($this->request->post());
   $attachments=new Attachments();
   $attachments->load($this->request->post());
   $uploadedFiles=UploadedFile::getInstances($attachments,'files');
   $attachments->files=$uploadedFiles;
  
   $uploadedAttachments=$attachments->saveFiles();
   $customerinfo=$customerInfo->save();
   $loaninfo=$loanInfo->save($customerinfo->id);
   $attachmentmodel=new LoanAttachment;
   $attachmentmodel->loanID=$loaninfo->id;
   $attachmentmodel->saveAttachments($uploadedAttachments);
   $transaction->commit();
   }
   catch(Exception $r)
   {
    $transaction->rollback();
    throw $r;
   }

   }


   }
   


