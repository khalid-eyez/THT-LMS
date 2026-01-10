<?php

namespace frontend\loans_module\controllers;

use common\models\CustomerInfo as ModelsCustomerInfo;
use yii\web\Controller;
use frontend\loans_module\models\LoanService;
use frontend\loans_module\models\Attachments;
use frontend\loans_module\models\CustomerInfo;
use frontend\loans_module\models\LoanInfo;

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
        
       return $this->renderAjax("loans"); 
    }

    public function actionCreateLoan(){
        if(yii::$app->request->isPost){
            try{
                  if((new LoanService(yii::$app->request))->saveLoan())
                  {
                    yii::$app->session->setFlash('success',"created successfully");
                    return $this->redirect(yii::$app->request->referrer);
                  }

            }
            catch(\Exception $e)
            {
               throw $e;
            }
        }
        if(yii::$app->request->isAjax){
            return $this->renderAjax('loancreate2',[
                'customerinfo'=>new CustomerInfo(),
                'loaninfo'=>new LoanInfo(),
                'attachments'=>new Attachments()
            ]);
        }
        else
        {
            return $this->render('loancreate2',[
                'customerinfo'=>new CustomerInfo(),
                'loaninfo'=>new LoanInfo(),
                'attachments'=>new Attachments()
            ]);
        }

    }

}
