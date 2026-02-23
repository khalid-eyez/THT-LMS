<?php

namespace frontend\cashbook_module\controllers;

use yii\web\Controller;
use frontend\cashbook_module\models\Cashbook;
use common\models\Cashbook as Book;
use yii;
use common\helpers\PdfHelper;
use yii\base\UserException;

/**
 * Default controller for the `cashbook` module
 */
class CashbookController extends Controller
{
  public function behaviors()
{
    return [
        'access' => [
            'class' => \yii\filters\AccessControl::className(),
            'rules' => [

                // View cashbook report screen + filtering results
                [
                    'actions' => ['cashbook-reporter'],
                    'allow'   => true,
                    'roles'   => ['view_cashbook_report'],
                ],

                // Export reports
                [
                    'actions' => ['cashbook-pdf'],
                    'allow'   => true,
                    'roles'   => ['download_cashbook_report'],
                ],
                [
                    'actions' => ['cashbook-excel'],
                    'allow'   => true,
                    'roles'   => ['download_cashbook_report'],
                ],

                // Individual payment receipt
                [
                    'actions' => ['receipt-pdf'],
                    'allow'   => true,
                    'roles'   => ['download_cashbook_receipt'],
                ],

                // Reverse a transaction (sensitive accounting action)
                [
                    'actions' => ['reverse'],
                    'allow'   => true,
                    'roles'   => ['reverse_cashbook_transaction'],
                ],
            ],
        ],
    ];
}
     public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        // Only check for logged in users
        if (!Yii::$app->user->isGuest) {

            $identity = Yii::$app->user->identity;

            if ($identity->hasDefaultPassword()) {

                // Prevent redirect loop
                if ($action->id !== 'change-password-restrict') {
                    return $this->redirect(['/admin/auth/change-password-restrict']);
                }
            }
        }

        return true;
    }
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionCashbookReporter()
    {
        $model=new Cashbook();
        if(yii::$app->request->isPost)
            {
              $model->load(yii::$app->request->post());
              return $this->renderAjax('cashbookview',['model'=>$model]); 
            } 
        if(yii::$app->request->isAjax)
            {
              return $this->renderAjax('cashbooksearchresults',['model'=>$model]);
            }

            return $this->redirect("/loans/dashboard");
        
    }
     public function actionCashbookPdf()
    {
        $model=new Cashbook();
        if(yii::$app->request->isPost)
            {
              $model->load(yii::$app->request->post());
              $content=$this->renderPartial('cashbookPDF',['model'=>$model]);
              PdfHelper::download($content,'cashbook_report',['orientation'=>'L']); 
            } 
        
    }
     public function actionCashbookExcel()
    {
        $model=new Cashbook();
        if(yii::$app->request->isPost)
            {
              $model->load(yii::$app->request->post());
              $model->cashbookExcel(); 
            } 
        
    }
    public function actionReceiptPdf($cashbookID)
    {
        $cashbook=Book::findOne($cashbookID);
        $content=$this->renderPartial('receipt_pdf',['cashbook'=>$cashbook]);
        PdfHelper::download($content,"Payment_receipt");
    }
    public function actionReverse($cashbookID)
    {
        $transaction=yii::$app->db->beginTransaction();
        try{
        $cashbook=Book::findOne($cashbookID);
        $cashbook_rev=$cashbook->reverse();

        //cashbook record buffer
        $rev_buffer=[
            'debit'=>$cashbook_rev->debit,
            'credit'=>$cashbook_rev->credit,
            'reference'=>$cashbook_rev->reference_no,
            'description'=>$cashbook_rev->description,
            'payment_doc'=>$cashbook_rev->payment_document,
            'category'=>$cashbook_rev->category
        ];

        $saver_book=new Cashbook($rev_buffer);

        $saver_book->save_with_reference();
        $transaction->commit();
        yii::$app->session->setFlash("success","<i class='fa fa-info-circle'></i> Transaction reversed successfully! ");
        return $this->redirect(yii::$app->request->referrer);
        }
        catch(UserException $u)
        {
          $transaction->rollBack();
          throw $u;
        }
        catch(\Throwable $t)
        {
          $transaction->rollBack();
          throw $t;
        }
    }
}
