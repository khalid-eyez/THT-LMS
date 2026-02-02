<?php
namespace frontend\loans_module\models;

use Exception;
use yii\web\Request;
use yii\web\UploadedFile;
use common\models\LoanAttachment;
use yii\base\UserException;

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
   return $loaninfo;
   }
   catch(UserException $w)
   {
       $transaction->rollBack();
       throw $w;

   }
   catch(Exception $r)
   {
    $transaction->rollback();
    throw $r;
   }

   }
    public function saveLoanR($customerID){
   try{
   $transaction=yii::$app->db->beginTransaction();
   $loanInfo=new LoanInfo();
   $loanInfo->load($this->request->post());
   $attachments=new Attachments();
   $attachments->load($this->request->post());
   $uploadedFiles=UploadedFile::getInstances($attachments,'files');
   $attachments->files=$uploadedFiles;
  
   $uploadedAttachments=$attachments->saveFiles();
   $loaninfo=$loanInfo->save($customerID);
   $attachmentmodel=new LoanAttachment;
   $attachmentmodel->loanID=$loaninfo->id;
   $attachmentmodel->saveAttachments($uploadedAttachments);
   $transaction->commit();
   return $loaninfo;
   }
   catch(UserException $w)
   {
       $transaction->rollBack();
       throw $w;

   }
   catch(Exception $r)
   {
    $transaction->rollback();
    throw $r;
   }

   }
  


   }
   


