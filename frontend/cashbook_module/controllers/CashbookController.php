<?php

namespace frontend\cashbook_module\controllers;

use yii\web\Controller;
use frontend\cashbook_module\models\Cashbook;
use common\models\Cashbook as Book;
use yii;
use common\helpers\PdfHelper;

/**
 * Default controller for the `cashbook` module
 */
class CashbookController extends Controller
{
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
        return $this->renderAjax('cashbooksearchresults',['model'=>$model]);
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
        $cashbook=Book::findOne($cashbookID);
        $cashbook_rev=$cashbook->reverse();

        //cashbook record buffer
        $rev_buffer=[
            'debit'=>$cashbook_rev->debit,
            'credit'=>$cashbook_rev->credit,
            'reference'=>$cashbook_rev->reference_no,
            'description'=>$cashbook_rev->description,
            'payment_doc'=>$cashbook_rev->payment_document,
            'category'=>$cashbook_rev->category,
        ];

        $saver_book=new Cashbook($rev_buffer);

        $saver_book->save_with_reference();
    }
}
