<?php

namespace frontend\controllers;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use frontend\models\RegisterUserForm;
use common\models\User;
use yii\helpers\Html;
use Yii;
class UsersController extends \yii\web\Controller
{
	 public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'create'
                          
                        ],
                        'allow' => true,
                        'roles'=>['create_user']

                    ],
                    [
                        'actions' => [
                            'reset-password'
                          
                        ],
                        'allow' => true,
                        'roles'=>['reset_user_password']

                    ],
                    [
                        'actions' => [
                            'update',
                          
                        ],
                        'allow' => true,
                        'roles'=>['update_user_info']

                    ],
                    [
                        'actions' => [
                            'delete',
                          
                          
                        ],
                        'allow' => true,
                        'roles'=>['delete_user']

                    ],
                    [
                        'actions' => [
                            'unlock',
                          
                        ],
                        'allow' => true,
                        'roles'=>['unlock_user']

                    ],
                    [
                        'actions' => [
         
                            'lock',
                         
                          
                        ],
                        'allow' => true,
                        'roles'=>['lock_user']

                    ],
                    
                ],
            ],
        ];
    }
    /**
     * Adds a new user to the system
     * @return Yii\web\Response
     * @author khalid <thewinner016@gmail.com>
     * @since 1.0.0
     */
    public function actionCreate(){
    	try{
    	$model = new RegisterUserForm();
    	if($model->load(Yii::$app->request->post()) && $model->create()){
    		Yii::$app->session->setFlash('success', '<i class="fa fa-info-circle"></i> User registered successfully, The initial password is 123');
            return $this->redirect(yii::$app->request->referrer);
    	}
        else
        {
            Yii::$app->session->setFlash('error', 'An error occured while creating a user! '.Html::errorSummary($model));
            return $this->redirect(yii::$app->request->referrer);  
        }
    }catch(\Exception $e){
    	Yii::$app->session->setFlash('error', 'An error occured while creating a user! '.$e->getMessage());
        return $this->redirect(yii::$app->request->referrer);
    }
    }
    /**
     * locks a user
     * @param mixed $id the user identifier
     * @return Yii\web\Response
     * @author khalid <thewinner016@gmail.com>
     * @since 1.0.0
     */
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
    /**
     * Unlocks a user
     * @param mixed $id the user identifier
     * @return Yii\web\Response
     * @author khalid <thewinner016@gmail.com>
     * @since 1.0.0
     */
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
     * Deletes a user
     * @return Yii\web\Response
     * @author khalid <thewinner016@gmail.com>
     * @since 1.0.0
     */
    public function actionDelete()
    {
        $id=yii::$app->request->post('id');
        try
        {
        $user=User::findOne($id);
        if($user!=null && $user->delete())
        {
          
                return $this->asJson(['deleted'=>'User Deleted Successfully !']);
        }
        else
        {
            return $this->asJson(['failure'=>'User Deleting Failed !']);
        }
  
    }
    catch(\Exception $d)
    {
        return $this->asJson(['failure'=>$d->getMessage()]);
    }

      
    }
    /**
     * Updates User Information
     * @param mixed $id the user identifier
     * @throws \Exception
     * @return string|Yii\web\Response
     * @author khalid <thewinner016@gmail.com>
     */
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
    /**
     * Resets a user password
     * @param mixed $user the user identifier
     * @return Yii\web\Response
     * @author khalid <thewinner016@gmail.com>
     */
    public function actionResetPassword($user)
    {
        $user = base64_decode(urldecode($user));
        $model = User::findOne($user);
        $password ="123";

        $model->password = $password;

        if ($model->save()) {
            Yii::$app->session->setFlash('success','<i class="fa fa-info-circle"></i> User Password Reset successful, password defaults to '.$password);
        } else {
            Yii::$app->session->setFlash('error', '<i class="fa fa-exclamation-triangle"></i>User Password Reset failed ! ' . Html::errorSummary($model));
        }

            return $this->redirect("/admin/users-list");
    }

}
