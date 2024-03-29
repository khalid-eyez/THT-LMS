<?php

namespace frontend\controllers;

use Yii;
use common\models\Member;
use common\models\MemberSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\User;
use yii\filters\AccessControl;

/**
 * MemberController implements the CRUD actions for Member model.
 */
class MemberController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                  
                    [
                        'actions' => [
                            'member-list',
                            'view',
                            'create',
                            'update',
                            'delete',
                            'lock',
                            'unlock',
                            
                        ],
                        'allow' => true,
                        'roles' => ['CHAIRPERSON BR','GENERAL SECRETARY BR','CHAIRPERSON HQ','GENERAL SECRETARY HQ','ADMIN'],
                    ],
                    [
                        'actions' => [
                            'dashboard',
                            'get-time',
                            'profile'
                         
                            
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Member models.
     * @return mixed
     */
    public function actionMemberList()
    {
       $members=null;

       if(yii::$app->user->can("CHAIRPERSON HQ") || yii::$app->user->can("GENERAL SECRETARY HQ"))
       {
        $members=Member::find()->all();
       }
       else
       {
        $branch=yii::$app->user->identity->member->branch;
        $members=Member::find()->where(['branch'=>$branch])->all();
       }
       

        return $this->render('memberList', [
            'members' =>$members,
        ]);
    }
    public function actionDashboard()
    {
        if(yii::$app->user->identity->hasDefaultPassword()){
            return $this->redirect(Url::to(['/home/change-password-restrict']));
          }
       return $this->render("/home/dashboard"); 
    }

    /**
     * Displays a single Member model.
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
    public function actionProfile()
    {
        $id=yii::$app->user->identity->member->memberID;
        return $this->render('profile', [
            'model' => Member::findOne($id),
        ]);
    }

    /**
     * Creates a new Member model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Member();

        if ($model->load(Yii::$app->request->post())) {
            try
            {
                if($model->save())
                {
                    yii::$app->session->setFlash("success","<i class='fa fa-info-circle'></i> Member Registered Successfully !");
                    return $this->redirect(['view', 'id' =>urlencode(base64_encode($model->memberID))]);
                }
                else
                {
                    yii::$app->session->setFlash("error","<i class='fa fa-exclamation-triangle'></i> Member Registration Failed! ".Html::errorSummary($model));
                    return $this->redirect(yii::$app->request->referrer);
                }
                
            }
            catch(\Exception $d)
            {
                yii::$app->session->setFlash("error","<i class='fa fa-exclamation-triangle'></i> Member Registration Failed! ".$d->getMessage());
                return $this->redirect(yii::$app->request->referrer);
            }
            
        }


        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Member model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->role="MEMBER";
        if(yii::$app->request->isPost)
        {
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            yii::$app->session->setFlash("success","<i class='fa fa-info-circle'></i> Member Updated Successfully !");
            return $this->redirect(['view', 'id' =>urlencode(base64_encode($model->memberID))]);
        }
       
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Member model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete()
    {
        $id=yii::$app->request->post('id');
        try
        {
        $member=Member::findOne($id);
        if($member!=null && $member->delete())
        {
            return $this->asJson(['deleted'=>'User Deleted Successfully !']);
          
        }
        else
        {
            return $this->asJson(['failure'=>'User Deleting Failed ! '.Html::errorSummary($member)]);
           
        }
    }
    catch(\Exception $d)
    {
        return $this->asJson(['failure'=>'User Deleting Failed ! '.$d->getMessage()]);
    }

      
    }
    public function actionLock($id)
    {
        $id=base64_decode(urldecode($id));
        $model = User::findOne($id);
     
            
            if($model->lock()){
                Yii::$app->session->setFlash('success', '<i class="fa fa-info-circle"></i> User Lock successful');
             
                }else{
                Yii::$app->session->setFlash('error', '<i class="fa fa-exclamation-triangle"></i> User Lock failed ! '.Html::errorSummary($model));
            
            }
    
            return $this->redirect(yii::$app->request->referrer);
    }
    public function actionUnlock($id)
    {
        $id=base64_decode(urldecode($id));
        $model = User::findOne($id);
     
            
            if($model->unlock()){
                Yii::$app->session->setFlash('success', '<i class="fa fa-info-circle"></i> User Reactivation successful');
             
                }else{
                Yii::$app->session->setFlash('error', '<i class="fa fa-exclamation-triangle"></i> User Reactivation failed ! '.Html::errorSummary($model));
            
            }
    
            return $this->redirect(yii::$app->request->referrer);
    }

    /**
     * Finds the Member model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Member the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $id=base64_decode(urldecode($id));
        if (($model = Member::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionGetTime()
    {
        date_default_timezone_set('Africa/Dar_es_Salaam');
        return $this->asJson(['time'=>date("l jS F Y ")]);
    }
}
