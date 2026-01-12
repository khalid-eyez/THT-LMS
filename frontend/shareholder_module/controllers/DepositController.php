<?php

namespace frontend\shareholder_module\controllers;
//from shareholder
use Yii;
use common\models\ShareholderDepositForm;
use common\models\CustomerShareholderForm;
use common\helpers\UniqueCodeHelper;
use common\models\Shareholder;
use common\models\ShareholderSearch;
//
use common\models\Deposit;
use common\models\DepositSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
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
        $searchModel = new ShareholderSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
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

                $model->payment_document = UploadedFile::getInstance($model, 'payment_document');

                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Deposit recorded successfully');
                    return $this->redirect(['index']);
                }

                // Debug validation
               // var_dump($model->errors);
              //  exit;
            }

            return $this->render('create', [
                'model' => $model,
            ]);
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
