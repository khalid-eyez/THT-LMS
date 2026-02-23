<?php

namespace frontend\shareholder_module\controllers;
use Exception;
use Yii;
use frontend\shareholder_module\models\CustomerShareholderForm;
use frontend\shareholder_module\models\DepositsSummaryForm;
use common\helpers\UniqueCodeHelper;
use common\models\Shareholder;
use common\models\ShareholderSearch;
use yii\base\UserException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\web\ErrorAction;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\data\ActiveDataProvider;
use frontend\shareholder_module\models\InterestPay;

use common\models\Deposit;
use common\helpers\PdfHelper;

use frontend\shareholder_module\models\ExcelReporter;
use frontend\shareholder_module\models\ShareholderInterestForm;
use yii\web\UploadedFile;
use kartik\mpdf\Pdf;
use common\models\Setting;
use yii\filters\AccessControl;

/**
 * ShareholderController implements the CRUD actions for Shareholder model.
 */
class ShareholderController extends Controller
{  
    //HAPA NIMETENGENEZA SHAREVALUE NITAKAYOITUMIA KWA MUDA KIDOGO
    public $sharevalue;
    public $layout='@frontend/loans_module/views/layouts/user_dashboard';
    
    //public $layout="user_dashboard";
    /**
     * @inheritDoc
     */
     public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' =>['view_shareholders_list']
                        
                    ],
                    [
                        'actions' => ['deposits-summary'],
                        'allow' => true,
                        'roles' =>['view_deposits_summary']
                        
                    ],
                     [
                        'actions' => ['deposits-summary-reporter'],
                        'allow' => true,
                        'roles' =>['download_deposits_summary_report']
                        
                    ],
                     [
                        'actions' => ['interest-summary-reporter'],
                        'allow' => true,
                        'roles' =>['view_interest_summary_report']
                        
                    ],
                     [
                        'actions' => ['interest-summary-pdf'],
                        'allow' => true,
                        'roles' =>['download_interest_summary_report']
                        
                    ],
                     [
                        'actions' => ['interest-summary-excel'],
                        'allow' => true,
                        'roles' =>['download_interest_summary_report']
                        
                    ],
                     [
                        'actions' => ['deposits-summary-pdf'],
                        'allow' => true,
                        'roles' =>['download_deposits_summary_report']
                        
                    ],
                     [
                        'actions' => ['deposits-summary-excel'],
                        'allow' => true,
                        'roles' =>['download_deposits_summary_report']
                        
                    ],
                    [
                        'actions' => ['approve-interest'],
                        'allow' => true,
                        'roles' =>['approve_interest_claims']
                        
                    ],
                    [
                        'actions' => ['delete-deposit'],
                        'allow' => true,
                        'roles' =>['delete_shareholder_deposit']
                        
                    ],
                    [
                        'actions' => ['pay-interests'],
                        'allow' => true,
                        'roles' =>['pay_shareholder_interests']
                        
                    ],
                    [
                        'actions' => ['claim-interest'],
                        'allow' => true,
                        'roles' =>['claim_interest']
                        
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' =>['register_shareholder']
                        
                    ],
                       [
                        'actions' => ['download-por'],
                        'allow' => true,
                        'roles' =>['download_shareholder_proof_of_registration']
                        
                    ],
                       [
                        'actions' => ['deposit'],
                        'allow' => true,
                        'roles' =>['record_monthly_deposit']
                        
                    ],
              
                       [
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' =>['update_shareholder']
                        
                    ],
                       [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' =>['delete_shareholder']
                        
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
  public function actions()
    {
    return [
    'error' => [
        'class' => ErrorAction::class,
        'view'  => '@frontend/loans-module/views/loans/error', 
    ],
    ];
    }
    /**
     * Lists all Shareholder models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ShareholderSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        if(yii::$app->request->isAjax)
            {
        return $this->renderAjax('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
            }

            return $this->redirect("/loans/dashboard");
    }


public function actionDepositsSummary()
    {
        $model = new DepositsSummaryForm();

        // render the template view that contains search + buttons + .cashbook
        if(yii::$app->request->isAjax){

      
        return $this->renderAjax('deposits-summary', [
            'model' => $model,
        ]);
          }
          return $this->redirect("/loans/dashboard");
    }

    
    public function actionDepositsSummaryReporter()
    {
        $model = new DepositsSummaryForm();
        $model->load(Yii::$app->request->post());
        if($model->date_range!=null)
            {
                [$from,$to]=explode(' - ',$model->date_range);
            }
        
        if(yii::$app->request->isAjax)
            {
        return $this->renderAjax('_deposits_summary_results', [
            'range' => ['from'=>$from??null,'to'=>$to??null],
            'deposits'=>$model->depositsSummary()
        ]);
            }
            return $this->redirect("/loans/dashboard");
    }
    public function actionInterestSummaryReporter()
    {
        $model=new ShareholderInterestForm();
        if(yii::$app->request->isPost)
            {
                $model->load(yii::$app->request->post());
                $summaries=$model->interests_summary();

                return $this->renderAjax('interest_summary_results',['interest_summaries'=>$summaries,'date_range'=>$model->date_range]);
            }
            if(yii::$app->request->isAjax)
                {
        return $this->renderAjax('interests_summary',['model'=>$model]);
                }
                return $this->redirect("/loans/dashboard");
    }
    public function actionInterestSummaryPdf()
    {
    $model=new ShareholderInterestForm();
    if(yii::$app->request->isPost)
    {
    $model->load(yii::$app->request->post());
    $summaries=$model->interests_summary();
    $content= $this->renderPartial('interest_summary_pdf',['interest_summaries'=>$summaries,'date_range'=>$model->date_range]);
    PdfHelper::download($content,'shareholder_interest_summary');
    }
    }
     public function actionInterestSummaryExcel()
    {
    $model=new ShareholderInterestForm();
    if(yii::$app->request->isPost)
    {
    $model->load(yii::$app->request->post());
    $model->exportInterestSummaryExcel();
    }
    }

    public function actionDepositsSummaryPdf()
    {
        $model = new DepositsSummaryForm();
        $model->load(Yii::$app->request->post());
        if($model->date_range!=null)
            {
                [$from,$to]=explode(' - ',$model->date_range);
            }
        

       $content=$this->renderPartial('deposits_summary_pdf', [
            'range' => ['from'=>$from??null,'to'=>$to??null],
            'deposits'=>$model->depositsSummary()
        ]);

        PdfHelper::download($content,'deposits_summary');
    }
     public function actionDepositsSummaryExcel()
    {
        $model = new DepositsSummaryForm();
        $model->load(Yii::$app->request->post());
        $model->exportShareholdersDepositsSummaryXlsx();
    
    }

    public function actionApproveInterest($shareholderID)
    {
        $shareholder=Shareholder::findOne($shareholderID);

        if($shareholder->approveInterests())
            {
               yii::$app->session->setFlash('success','<i class="fa fa-info-circle"></i> Interests approved successfully !');
                return $this->redirect(yii::$app->request->referrer); 
            }
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
    public function actionPayInterests($shareholderID)
    {
      $paymentmodel=new InterestPay();
      try{
         if(yii::$app->request->isPost)
            {
                $paymentmodel->load(yii::$app->request->post());
                $file=UploadedFile::getInstance($paymentmodel,'payment_doc');
                $paymentmodel->payment_doc=$file;
                $paymentDetails=$paymentmodel->payInterest($shareholderID);
                Yii::error(['created_at' => $paymentDetails->created_at], __METHOD__);

                $receipt=$this->renderPartial('interest_payment_receipt_pdf',['cashbook'=>$paymentDetails]);
                PdfHelper::download($receipt,'payment_receipt');
            }
      }
      catch(UserException $t)
      {
          throw $t;
      }
      catch(\Exception $n)
      {
        throw $n;
      }
      if(yii::$app->request->isAjax)
        {
      return $this->renderAjax('interest_payment_form',['model'=>$paymentmodel]);
        }
        return $this->redirect("/loans/dashboard");
    }
    public function actionClaimInterest($shareholderID)
    {
        $shareholder=Shareholder::findOne($shareholderID);
        try
        {
        if($shareholder->claimInterests())
            {
                yii::$app->session->setFlash('success','<i class="fa fa-info-circle"></i> Interest claim successful !');
                return $this->redirect(yii::$app->request->referrer);
            }
            else
                {
                   yii::$app->session->setFlash('error','<i class="fa fa-exclamation-triangle"></i> An unknown error occured while claiming interests !');
            return $this->redirect(yii::$app->request->referrer);  
                }
        }
        catch(UserException $u)
        {
          yii::$app->session->setFlash('error','<i class="fa fa-exclamation-triangle"></i> Interest claiming failed !'.$u->getMessage());
          return $this->redirect(yii::$app->request->referrer);
        }
         catch(\Exception $e)
        {
            yii::$app->session->setFlash('error','<i class="fa fa-exclamation-triangle"></i> An unknown error occured while claiming interests !'.$e->getMessage());
            return $this->redirect(yii::$app->request->referrer);
        }
    }


    /**
     * Creates a new Shareholder model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
public function actionCreate()
{
    $this->layout = '@frontend/loans_module/views/layouts/user_dashboard';
 
    $model = new CustomerShareholderForm();

      if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return ActiveForm::validate($model);
    }
    if ($this->request->isPost) {
       // Load POST data
           try{
        $model->load($this->request->post());

        //HAPA NIMETENGENEZA SHAREVALUE NITAKAYOITUMIA KWA MUDA KIDOGO
        $saved_model=$model->save();
        if ($saved_model) {
             Yii::$app->session->setFlash('success', '<i class="fa fa-info-circle"></i>Shareholder registered successfully!');
             //$content=$this->renderPartial('shareholder_por_pdf',['shareholder'=>$saved_model->shareholder]);
             //PdfHelper::download($content,'shareholder_PoR');
             return $this->redirect(['/loans/customer/view','customerID'=>$saved_model->id]);
        } else {
            throw new UserException('Unable to add a shareholder !' .Html::errorSummary($model));
        }
        }
        catch(UserException $u)
        {
          Yii::$app->session->setFlash('error', '<i class="fa fa-exclamation-triangle"></i> Shareholder registration failed! '.$u->getMessage());
          throw $u;
        }
        catch(\Exception $e)
        {
          Yii::$app->session->setFlash('error', '<i class="fa fa-exclamation-triangle"></i> Shareholder registration failed!');
          throw new UserException('An unknown error occurred !');
        }

    }
     if(yii::$app->request->isAjax){
    return $this->renderAjax('create', [
        'model' => $model,
    ]);
     }
     return $this->redirect("/loans/dashboard");
}

public function actionDownloadPor($shareholderID)
{
    $shareholder=Shareholder::findOne($shareholderID);
    $content=$this->renderPartial('shareholder_por_pdf',['shareholder'=>$shareholder]);
    PdfHelper::download($content,"shareholder_PoR");
}
      /** HAPA NI ACTION YA DEPOSIT */
     public function actionDeposit()
    {
         $model = new CustomerShareholderForm();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
          //  $model->loadDefaultValues();
        }

        return $this->render('deposit', [
            'model' => $model,
        ]);
    }
    /** HAPA NI ACTION YA CLAIMS */
     public function actionClaims()
    {
         $model = new CustomerShareholderForm();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            //$model->loadDefaultValues();
        }

        return $this->render('claims', [
            'model' => $model,
        ]);
    }

    
    /**
     * Updates an existing Shareholder model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */


    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if($this->request->isPost){
        $customer=$model->customer;
        if ($customer->load($this->request->post()) && $customer->save()) {
                
                 yii::$app->session->setFlash('success','<i class="fa fa-info-circle"></i> Shareholder updated successfully !');
                 return $this->redirect(['/loans/customer/view','customerID'=>$customer->id]);
        }
        }
        if(yii::$app->request->isAjax)
            {
        return $this->renderAjax('update', [
            'model' => $model,
        ]);
            }

            return $this->redirect("/loans/dashboard");
    }

    /**
     * Deletes an existing Shareholder model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        yii::$app->session->setFlash('success','Shareholder deleted successfully!');
        $searchModel = new ShareholderSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        if(yii::$app->request->isAjax)
            {
        return $this->renderAjax('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
            }
    }

    /**
     * Finds the Shareholder model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Shareholder the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Shareholder::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
