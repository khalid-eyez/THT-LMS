<?php

namespace frontend\shareholder_module\controllers;

use common\models\Shareholder;
use common\models\ShareholderSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ShareholderController implements the CRUD actions for Shareholder model.
 */
class ShareholderController extends Controller
{  //public $layout="user_dashboard";
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
     * Lists all Shareholder models.
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
    $model = new Shareholder();

    if ($this->request->isPost) {

        // Load POST data
        $model->load($this->request->post());

        // Attempt to save
        if ($model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            // SAVE FAILED - show validation errors
            var_dump($model->errors); 
            exit;
        }

    } else {
        // GET request - first time, just show form
        $model->loadDefaultValues();
    }

    return $this->render('create', [
        'model' => $model,
    ]);
}

    /** HAPA NI ACTION YA CLAIMS */
     public function actionClaims()
    {
        $model = new Shareholder();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('claims', [
            'model' => $model,
        ]);
    }
      /** HAPA NI ACTION YA CLAIMS PROGRESS */
     public function actionClaims2()
    {
        $model = new Shareholder();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('claims2', [
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

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
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
