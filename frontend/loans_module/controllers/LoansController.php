<?php

namespace frontend\loans_module\controllers;
use yii\base\UserException;
use common\models\CustomerInfo as ModelsCustomerInfo;
use yii\web\Controller;
use frontend\loans_module\models\LoanService;
use frontend\loans_module\models\Attachments;
use frontend\loans_module\models\CustomerInfo;
use frontend\loans_module\models\LoanInfo;
use common\models\Customer;
use common\models\CustomerLoan;

use common\models\LoanAttachment;
use yii\web\UploadedFile;
use yii;

class LoansController extends Controller
{
    public $layout="user_dashboard";
    public function actionDashboard()
    {
       return $this->render('loansdashboard');
    }
    public function actionLoans()
    {
        if(yii::$app->request->isAjax)
            {
             return $this->renderAjax("loans"); 
            }
            else{
                return $this->render("loans"); 
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

}
