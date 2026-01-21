<?php

namespace frontend\loans_module\controllers;
use common\models\Cashbook;
use common\models\RepaymentSchedule;
use Exception;
use frontend\loans_module\models\TopUp;
use yii\base\UserException;
use common\models\CustomerInfo as ModelsCustomerInfo;
use yii\web\Controller;
use frontend\loans_module\models\LoanService;
use frontend\loans_module\models\Attachments;
use frontend\loans_module\models\Attachment;
use frontend\loans_module\models\CustomerInfo;
use frontend\loans_module\models\LoanInfo;
use common\models\Customer;
use common\models\CustomerLoan;
use frontend\loans_module\models\CustomerLoanSearch;
use frontend\loans_module\models\LoanCalculator;
use common\helpers\PdfHelper;
use yii\web\ErrorAction;

use common\models\LoanAttachment;
use yii\web\UploadedFile;
use common\helpers\UniqueCodeHelper;
use frontend\loans_module\models\LoanRepayment;
use yii;

class LoansController extends Controller
{
    public $layout="user_dashboard";
    public function actions()
    {
    return [
    'error' => [
        'class' => ErrorAction::class,
        'view'  => 'error', 
    ],
    ];
    }
    public function actionDashboard()
    {
       $loans=CustomerLoan::find()->all();
       return $this->render('loansdashboard',['loans'=>$loans]);
    }
    public function actionLoans()
    {
        if(yii::$app->request->isAjax)
            {
              $this->layout="user_dashboard";
        $searchModel = new CustomerLoanSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->renderAjax('/loans_crud/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
            }
            else{
                 $this->layout="user_dashboard";
        $searchModel = new CustomerLoanSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('/loans_crud/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]); 
            }
       
    }

    public function actionCreateLoan(){
        if(yii::$app->request->isPost){
            try{
                  $loan=(new LoanService(yii::$app->request))->saveLoan();
                  if($loan!=null)
                  {
                    yii::$app->session->setFlash('success',"<i class='fa fa-check-circle'></i> Loan application successful!");
                    return $this->redirect(['loan-view','loanID'=>$loan->id]);
                  }

            }
            catch(UserException $u)
            {
                yii::$app->session->setFlash('error','<i class="fa fa-exclamation-triangle"></i> Customer loan application failed !'.$u->getMessage());
                return $this->redirect(['loan-fail']);
            }
            catch(\Exception $e)
            {
                yii::$app->session->setFlash('error','<i class="fa fa-exclamation-triangle"></i> An unknown error occurred while submitting application!');
                return $this->redirect(['loan-fail']);

            }
        }
        if(yii::$app->request->isAjax) {
            return $this->renderAjax('loancreate2', [
                'customerinfo' => new CustomerInfo(),
                'loaninfo' => new LoanInfo(),
                'attachments' => new Attachments()
            ]);
        }
        else{
            return $this->redirect('/loans/dashboard');
        }



    }
    public function actionLoanView($loanID){
        $loan=CustomerLoan::findOne($loanID);
        return $this->render('loanview',['loan'=>$loan]);
    }
    public function actionLoanFail()
    {
        return $this->render('loanfail');
    }
    public function actionApprove($loanID)
    {
        $loan=CustomerLoan::findOne($loanID);
        $loan->status="approved";
        $loan->approvedby=yii::$app->user->identity->id;
        $loan->approved_at = date('Y-m-d H:i:s');

        if($loan->save()){
            yii::$app->session->setFlash('success','<i class="fa fa-check-circle"></i> Loan status updated successfully!');
            return $this->redirect(yii::$app->request->referrer);
        }

    }
    public function actionDisapprove($loanID)
    {
        $loan=CustomerLoan::findOne($loanID);
        $loan->status="rejected";
        if($loan->save()){
            yii::$app->session->setFlash('success','<i class="fa fa-check-circle"></i> Loan status updated successfully!');
            return $this->redirect(yii::$app->request->referrer);
        }

    }
    public function actionPay($loanID)
    {
       
        $loan=CustomerLoan::findOne($loanID);
        $document=new Attachment();
        if(yii::$app->request->isPost)
            {
             try{
                $transaction=yii::$app->db->beginTransaction();
                $loan->load(yii::$app->request->post());
                $loan->status="active";
                $loan->paidby=yii::$app->user->identity->id;
                $document->load(yii::$app->request->post());
                $uploaded=UploadedFile::getInstance($document,'file');
                $document->file=$uploaded;
                if(!$document->validate())
                {
                    throw new UserException(json_encode($document->getErrors()));
                }
                if(!$loan->save())
                {
                    throw new UserException("unable to update loan data");
                }
                //saving uploaded docs
                $savedfile=$document->saveFile();

                //updating the cashbook record

                $cashbook=new Cashbook();
                $cashbook->credit=$loan->deposit_amount;
                $cashbook->reference_no=UniqueCodeHelper::generate("LD").'-'.$loan->id.date("Y");
                $cashbook->description="[$loan->loanID] Loan disbursement";
                $cashbook->payment_document=$savedfile;
                $cashbook->category="disbursement";
                $cashbook->balance=$cashbook->updatedBalance();

                if(!$cashbook->save()){
                    throw new UserException(json_encode($cashbook->getErrors()));
                }

                // now time for the repayment schedule

                $schedule=(new LoanCalculator)->generateRepaymentSchedule(
                    $loan->loan_amount,
                    $loan->interest_rate,
                    $loan->repayment_frequency,
                    $loan->loan_duration_units,
                    date("Y-m-d H:i:s"),
                );

                    foreach($schedule as $record)
                    {
                       $repaymodel=new RepaymentSchedule();
                       $repaymodel->loanID=$loan->id;
                       $repaymodel->loan_amount=$record['loan_amount'];
                       $repaymodel->interest_amount=$record['interest'];
                       $repaymodel->installment_amount=$record['installment'];
                       $repaymodel->loan_balance=$record['balance'];
                       $repaymodel->principle_amount=$record['principal'];
                       $repaymodel->repayment_date=$record['payment_date'];
                       $repaymodel->status="active";
                       if(!$repaymodel->save())
                        {
                            throw new UserException(json_encode($repaymodel->getErrors()));
                        }

                    }
                    $transaction->commit();
                    // now generating the documents (loan agreement + repayment schedule)
                    yii::$app->session->setFlash("success","Disbursement successful ! ");
                    return $this->render("/loans/docs/loansummaryview",['loan'=>$loan]);
             }
             catch(UserException $r)
             {
                $transaction->rollBack();
               throw $r;
             }
             catch(Exception $w)
             {
               $transaction->rollBack();
               throw $w;
             }
            
            }
        if(yii::$app->request->isAjax)
            {
            return $this->renderAjax("loanpayment",['loan'=>$loan,'document'=>$document]);
            }
            return $this->redirect('/loans/dashboard');

    }
    public function actionRepaymentScheduleMini($loanID){
       $loan=CustomerLoan::findOne($loanID);
       return $this->renderAjax("/loans/docs/repaymentschedule",['loan'=>$loan]);
    }
    public function actionDownloadSummary($loanID)
    {
        $loan=CustomerLoan::findOne($loanID);
        $content=$this->renderPartial("/loans/docs/loansummarypdf",['loan'=>$loan]);
        PdfHelper::download($content,$loan->loanID);

    }
    public function actionTopUp($loanID)
    {
        if(yii::$app->request->isPost)
            {
                $loan=(new TopUp)->topUp($loanID,yii::$app->request);
                return $this->render("/loans/docs/loansummaryview",['loan'=>$loan]);
            }
       return $this->renderAjax("topup_form",['model'=>new TopUp()]);
    }

    public function actionRepay($loanID)
    {
         $repayment_model=new LoanRepayment();
         if(yii::$app->request->isPost)
            {
              $repayment_model->load(yii::$app->request->post());
              $uploaded=UploadedFile::getInstance($repayment_model,'payment_doc');
              $repayment_model->payment_doc=$uploaded;
              return $this->renderAjax('repayment_receipt',['payment_details'=>$repayment_model->pay_dry_run($loanID)]);
            }
         return $this->renderAjax('loanRepayment',['model'=>$repayment_model]);
    }
    public function actionRepaymentOverdues($loanID,$payment_date)
    {
        $loan=CustomerLoan::findOne($loanID);
        return $this->renderAjax('total_repayment',['overdues'=>$loan->computeOverdues($payment_date)]);
        
    }
    public function actionRepaymentConfirm($scheduleID, $paid_amount,$payment_date,$payment_doc){
        $schedule=RepaymentSchedule::findOne($scheduleID);
        $paid=$schedule->pay($payment_date,$paid_amount,$payment_doc);

        PdfHelper::download($this->renderPartial('/loans/docs/repayment_receipt_pdf',['paid'=>$paid]),'payment_receipt_'.$paid['reference']);

        //return $this->render('/loans/docs/repayment_receipt_pdf',['paid'=>$paid]);


    }
    public function actionCancelRepayment($file)
    {
        $filePath = Yii::getAlias('@webroot'.$file);

        if (file_exists($filePath)) {
         unlink($filePath);
        }
        yii::$app->session->setFlash('success','<i class="fa fa-info-circle"></i> Repayment Cancelled');
        return $this->redirect('dashboard');
    }

}
