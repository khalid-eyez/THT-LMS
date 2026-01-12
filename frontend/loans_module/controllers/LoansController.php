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
use frontend\loans_module\models\CustomerLoanSearch;

use common\models\LoanAttachment;
use yii\web\UploadedFile;
use yii;

class LoansController extends Controller
{
    public $layout="user_dashboard";
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
        $loan->approved_at = date('Y-m-d H:i:s');

        if($loan->save()){
            yii::$app->session->setFlash('success','<i class="fa fa-check-circle"></i> Loan status updated successfully!');
            return $this->redirect(yii::$app->request->referrer);
        }

    }

}
