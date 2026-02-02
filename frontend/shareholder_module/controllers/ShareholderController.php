<?php

namespace frontend\shareholder_module\controllers;
use Yii;
use frontend\shareholder_module\models\CustomerShareholderForm;
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

        return $this->renderAjax('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Shareholder model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {   
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
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
        $sharevalue = 1000;
        $shares= $model->initialCapital/$sharevalue;
        $model->shares=(int)$shares;
        $saved_model=$model->save();
        // HAPA NATENGENEZA CUSTOMER ID KUPITIA GENERATOR YA KHALID YA KWENYE HELPERS
        //$model->customerID = UniqueCodeHelper::generate('CUST', 6);
        // Attempt to save
        if ($saved_model) {
             Yii::$app->session->setFlash('success', '<i class="fa fa-info-circle"></i>Shareholder registered successfully!');
             return $this->redirect(['/loans/customer/view','customerID'=>$saved_model->id]);
        } else {
            throw new UserException('Unable to add a shareholder !' .Html::errorSummary($model));
        }
        }
        catch(UserException $u)
        {
          Yii::$app->session->setFlash('error', '<i class="fa fa-exclamation-triangle"></i>Shareholder registration failed!');
          throw $u;
        }
        catch(\Exception $e)
        {
          Yii::$app->session->setFlash('error', '<i class="fa fa-exclamation-triangle"></i>Shareholder registration failed!');
          throw new UserException('An unknown error occurred !');
        }

    }

    return $this->renderAjax('create', [
        'model' => $model,
    ]);
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
                  
        //HAPA NIMETENGENEZA SHAREVALUE NITAKAYOITUMIA KWA MUDA KIDOGO
        $sharevalue = 1000;
        $shares= $model->initialCapital/$sharevalue;
        $model->shares=(int)$shares;
        
        if ($model->load($this->request->post()) && $model->save()) {
             $searchModel = new ShareholderSearch();
             $dataProvider = $searchModel->search($this->request->queryParams);
            return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
        }
        }
        return $this->render('update', [
            'model' => $model,
        ]);
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

        return $this->redirect(['index']);
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
