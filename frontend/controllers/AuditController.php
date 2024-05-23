<?php

namespace frontend\controllers;

use Yii;
use common\models\TblAuditEntry;
use common\models\TblAuditEntrySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * AuditController implements the CRUD actions for TblAuditEntry model.
 */
class AuditController extends Controller
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
                        'actions' => [
                            'index',
                            'delete-all'
                        ],
                        'allow' => true,
                        'roles' => ['ADMIN'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all TblAuditEntry models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblAuditEntrySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDeleteAll()
    {
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand("delete from tbl_audit_entry");

        $command->execute();

        return $this->redirect(yii::$app->request->referrer);
    }
}
