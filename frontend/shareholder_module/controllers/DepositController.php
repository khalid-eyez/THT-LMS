<?php

namespace frontend\shareholder_module\controllers;
//from shareholder
use Exception;
use Yii;
use common\models\ShareholderDepositForm;
use common\models\CustomerShareholderForm;
use common\helpers\UniqueCodeHelper;
use common\models\Shareholder;
use common\models\DepositSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use common\helpers\PdfHelper;
use frontend\shareholder_module\models\ExcelReporter;
use common\models\Deposit;
use frontend\shareholder_module\models\ShareholderInterestForm;

/**
 * DepositController implements the CRUD actions for Deposit model.
 */
class DepositController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Deposit models.
     *
     * @return string
     */
  
     public function actionIndex()
    {
        $searchModel = new DepositSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
        ]);
    }

    public function actionShareholderInterestStatement($shareholderID)
    {
        $model=new ShareholderInterestForm;
        if(yii::$app->request->isPost)
            {
               $model->load(yii::$app->request->post()); 
               return $this->renderAjax('shareholder_interests_result',['interests'=>$model->getInterests($shareholderID),'date_range'=>$model->date_range]);
            }
        return $this->renderAjax('shareholder_interests_form',['model'=>$model,'shareholderID'=>$shareholderID]);
    }

    public function actionShareholderInterestStatementPdf($shareholderID)
    {
    $model=new ShareholderInterestForm;
    if(yii::$app->request->isPost)
    {
    $shareholder=Shareholder::findOne($shareholderID);
    $model->load(yii::$app->request->post());
    $content=$this->renderPartial('shareholder_interests_pdf',['interests'=>$model->getInterests($shareholderID),'date_range'=>$model->date_range,'shareholder'=>$shareholder]);
    PdfHelper::download($content,'Interests_statement'); 
    }
    }
  
    public function actionShareholderInterestStatementExcel($shareholderID)
    {
    $model=new ShareholderInterestForm;
    if(yii::$app->request->isPost)
    {
    $model->load(yii::$app->request->post());
    $model->exportInterestStatementExcel($shareholderID);
    }
    }
     public function actionShareholderDepositsPdfReport($shareholderID)
    {
        // 1) Load shareholder with customer relation
        $shareholder = Shareholder::find()
            ->with('customer')
            ->where(['id' => (int)$shareholderID])
            ->one();

        if (!$shareholder) {
            throw new NotFoundHttpException('Shareholder not found.');
        }

        // 2) Build search & data provider in locked shareholder context
        $searchModel = new DepositSearch();

        // supports both POST (your export JS) and GET (manual)
        $params = Yii::$app->request->post();
        if (empty($params)) {
            $params = Yii::$app->request->get();
        }

        // IMPORTANT:
        // Your DepositSearch::search signature: search($params, $formName = null, $contextShareholderID = null)
        // Passing null for formName lets Yii use DepositSearch::formName() => "DepositSearch"
        $dataProvider = $searchModel->search($params, null, (int)$shareholderID);

        // optional: stable ordering in PDF (latest first). Adjust if you prefer ASC.
        $dataProvider->query->orderBy(['deposit_date' => SORT_DESC, 'depositID' => SORT_DESC]);
        $dataProvider->pagination = false; // PDFs should include all rows

        // 3) Render MPDF-compatible view as HTML
        $content = $this->renderPartial('_shareholder_deposits_table_pdf', [
            'dataProvider'   => $dataProvider,
            'shareholder'    => $shareholder,
            'shareholderID'  => (int)$shareholderID,
        ]);

        // 4) Generate and return PDF via helper
        // Assumes PdfHelper::generate($content) returns a response (or sends output)
        return PdfHelper::download($content,'shareholder_deposits');
    }


// ...

public function actionShareholderDepositsExcelReport($shareholderID)
{
    $shareholderID = (int)$shareholderID;

    $shareholder = Shareholder::find()
        ->with('customer')
        ->where(['id' => $shareholderID])
        ->one();

    if (!$shareholder) {
        throw new NotFoundHttpException('Shareholder not found.');
    }

    $searchModel = new DepositSearch();

    $params = Yii::$app->request->post();
    if (empty($params)) {
        $params = Yii::$app->request->get();
    }

    $dataProvider = $searchModel->search($params, null, $shareholderID);
    $dataProvider->pagination = false;

    $dateRange = Yii::$app->request->post('DepositSearch')['deposit_date']
        ?? Yii::$app->request->get('DepositSearch')['deposit_date']
        ?? null;

    return ExcelReporter::shareholderDeposits(
        $shareholder,
        $dataProvider,
        $dateRange
    );
    }
  

    public function actionShareholderDeposits($shareholderID=null)
    {
     $searchModel = new DepositSearch();

    // 1) First modal load (GET) -> show wrapper view only, cashbook has "Search results"
    if (Yii::$app->request->isGet) {
        return $this->renderAjax('shareholder-deposits', [
            'searchModel' => $searchModel,
            'shareholderID' => $shareholderID,
        ]);
    }

    // 2) Filtering (AJAX POST) -> load deposits and return only the table partial
    $dataProvider = $searchModel->search(Yii::$app->request->post(), null, $shareholderID);

    return $this->renderPartial('_shareholder_deposits_table', [
        'dataProvider'  => $dataProvider,
        'shareholderID' => $shareholderID,
    ]);
    }
    


    /**
     * Displays a single Deposit model.
     * @param int $depositID Deposit ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($depositID)
    {
        return $this->render('view', [
            'model' => $this->findModel($depositID),
        ]);
    }

    /**
     * Creates a new Deposit model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */

        public function actionCreate($shareholder_id = null)
        {
            $model = new ShareholderDepositForm();

            if ($shareholder_id === null) {
                throw new \yii\web\BadRequestHttpException('Shareholder ID is required.');
            }

            $model->shareholderID = $shareholder_id;

            if ($model->load(Yii::$app->request->post())) {
                //print_r($model); return null;

               $model->payment_document = UploadedFile::getInstance($model, 'payment_document');
              
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', '<i class="fa fa-info-circle"></i> Deposit recorded successfully');
                    return $this->redirect(yii::$app->request->referrer);
                }
                throw new Exception(json_encode($model->getErrors()));
    
            }

            return $this->renderAjax('create', [
                'model' => $model,
            ]);
        }

        public function actionDeleteDeposit($depositID)
        {
        $deposit=Deposit::findOne($depositID);
        if($deposit->delete())
        {
            yii::$app->session->setFlash('success','<i class="fa fa-info-circle"></i> Deposit Deleted Successfully !');
            return $this->redirect(yii::$app->request->referrer);
        }
        else{
            yii::$app->session->setFlash('error','<i class="fa fa-exclamation-triangle"></i> Deposit Deleting Failed !');
            return $this->redirect(yii::$app->request->referrer);
        }
        }

    /**
     * Updates an existing Deposit model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $depositID Deposit ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($depositID)
    {
        $model = $this->findModel($depositID);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'depositID' => $model->depositID]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Deposit model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $depositID Deposit ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($depositID)
    {
        $this->findModel($depositID)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Deposit model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $depositID Deposit ID
     * @return Deposit the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($depositID)
    {
        if (($model = Deposit::findOne(['depositID' => $depositID])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
