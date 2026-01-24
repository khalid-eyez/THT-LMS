<?php

namespace frontend\cashbook_module\controllers;

use yii\web\Controller;
use frontend\cashbook_module\models\Cashbook;
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
}
