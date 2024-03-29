<?php

namespace frontend\controllers;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use frontend\models\RegisterUserForm;
use common\models\Member;
use common\models\User;
use yii\helpers\Html;
use Yii;
class UsersController extends \yii\web\Controller
{
	//public $layout = 'admin';
	 public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'create',
                            'lock',
                            'unlock',
                            'delete',
                            'update',
                            'reset-password'
                          
                        ],
                        'allow' => true,
                        'roles'=>['ADMIN']

                    ],
                    
                ],
            ],
            // 'verbs' => [
            //     'class' => VerbFilter::className(),
            //     'actions' => [
            //         'logout' => ['post'],
            //     ],
            // ],
        ];
    }
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionCreate(){
    	try{
    	$model = new RegisterUserForm();
    	if($model->load(Yii::$app->request->post()) && $model->create()){
    		Yii::$app->session->setFlash('success', 'User registered successfully, The initial password is 123');
    	}
    }catch(\Exception $e){
    	Yii::$app->session->setFlash('error', 'An error occured while creating a user! '.$e->getMessage());
    }
    return $this->redirect('/admin/users-list');
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

    public function actionDelete()
    {
        $id=yii::$app->request->post('id');
        $transaction=yii::$app->db->beginTransaction();
        try
        {
        $user=User::findOne($id);
        if($user!=null && $user->delete())
        {
            $member=Member::find()->where(['userID'=>$user->id])->one();
            if($member==null){
                $transaction->commit();
                return $this->asJson(['deleted'=>'User Deleted Successfully !']);  
            }
            if($member->delete())
            {
                $transaction->commit();
                return $this->asJson(['deleted'=>'User Deleted Successfully !']);
            }
     
            
          throw new \Exception("User Deleting Failed! Could not delete user member records! ");
        }
        else
        {
            throw new \Exception('User Deleting Failed ! '.Html::errorSummary($user));
        
        }
    }
    catch(\Exception $d)
    {
        $transaction->rollBack();
        return $this->asJson(['failure'=>$d->getMessage()]);
    }

      
    }

    public function actionUpdate($id)
    {
        $model = new RegisterUserForm;

        $id=base64_decode(urldecode($id));
        $user=User::findOne($id);

        $model->username=$user->username;
        $roles=array_keys(Yii::$app->authManager->getAssignments($id));
        $model->role=$roles;
        if(yii::$app->request->isPost){
        try{
        if ($model->load(Yii::$app->request->post()) && $model->update($id)) {
            yii::$app->session->setFlash("success","User updated successfully !");
            return $this->redirect(yii::$app->request->referrer);
        }
        else
        {
            throw new \Exception("Please verify your data and try again !");
        }
        }catch(\Exception $u)
        {
            yii::$app->session->setFlash("error","User updating failed !".$u->getMessage());
        }
        }
        return $this->render('/admin/userupdate', [
            'model' => $model,
            'user'=>$user
        ]);
    }

    public function actionResetPassword($user)
    {
        $user = base64_decode(urldecode($user));
        $model = User::findOne($user);
        $password = ($model->isMember())?$model->member->phone:"123";

        $model->password = $password;

        if ($model->save()) {
            Yii::$app->session->setFlash('success', '<i class="fa fa-info-circle"></i> User Password Reset successful, password defaults to '.$password);
        } else {
            Yii::$app->session->setFlash('error', '<i class="fa fa-exclamation-triangle"></i>User Password Reset failed ! ' . Html::errorSummary($model));
        }

            return $this->redirect("/admin/users-list");
    }

}
