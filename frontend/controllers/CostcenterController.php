<?php

namespace frontend\controllers;

use common\models\Branch;
use Yii;
use common\models\Costcenter;
use common\models\CostcenterSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Html;

/**
 * CostcenterController implements the CRUD actions for Costcenter model.
 */
class CostcenterController extends Controller
{
    /**
     * {@inheritdoc}
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
                            'roles' => ['view_costcenters']
                        ],
                        [
                            'actions' => ['update'],
                            'allow' => true,
                            'roles' => ['update_costcenter']
                        ],
                        [
                            'actions' => ['create'],
                            'allow' => true,
                            'roles' => ['create_costcenter']
                        ],
                        [
                            'actions' => ['delete'],
                            'allow' => true,
                            'roles' => ['delete_costcenter']
                        ],
                        [
                            'actions' => ['view'],
                            'allow' => true,
                            'roles' => ['view_costcenter']
                        ],
                     
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Costcenter models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CostcenterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Costcenter model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Costcenter model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Costcenter();
        $user=yii::$app->user;
        $branch=($user->can('HQ'))?((new Branch())->getHQ()):(($user->member!=null)?$user->member->branch:null);
        if($branch==null)
        {
            throw new NotFoundHttpException("Branch Not Found");
        }
        $model->branch=$branch->branchID;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->centerID]);
        }
        else
        {
            throw new \Exception(Html::errorSummary($model));
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Costcenter model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->centerID]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Costcenter model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Costcenter model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Costcenter the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Costcenter::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
