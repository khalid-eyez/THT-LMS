<?php

namespace frontend\admin_module\controllers;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\admin_module\models\ChangePasswordForm;
class AuthController extends \yii\web\Controller
{
        /**
     * {@inheritdoc}
     */
    public $defaultAction = 'login';
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login'],
                        'allow' => true,
                        
                    ],
                    [
                        'actions' => ['logout','view-profile', 'error','changepassword','change-password-restrict'],
                        'allow' => true,
                        'roles' =>['@']
                        
                    ],
                    
                    
                    
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['get'],
                ],
            ],
        ];
    }
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
                'view' => '@frontend/admin_module/views/auth/error.php',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
   
    
    /**
     * Logs in a user
     * @return string|Yii\web\Response
     * @author khalid <thewinner@gmail.com>
     * @since 1.0.0
     */
    public function actionLogin()
    {
        $this->layout="login";
      if (!Yii::$app->user->isGuest) {
             
             if(Yii::$app->user->can("ADMIN"))
             {
                return $this->redirect(['/admin/users-list']); 
             }
             else
             {
                return $this->redirect(['/loans/dashboard']); 
             }
       }
      $model = new LoginForm();
      if ($model->load(Yii::$app->request->post()) && $model->login()) {
             if(yii::$app->user->identity->hasDefaultPassword())
             {
                return $this->redirect(['change-password-restrict']);
             }
             if(Yii::$app->user->can("ADMIN"))
             {
                return $this->redirect(['/admin/users-list']); 
             }
             else
             {
                return $this->redirect(['/loans/dashboard']); 
             }
     }

       return $this->render('login', ['model'=>$model]);
        
    }
    /**
     * Logs out the current user
     * @return Yii\web\Response
     * @author khalid <thewinner016@gmail.com>
     */
    public function actionLogout()
    {

     $session = Yii::$app->session;
         if ($session->isActive){
             $session->destroy();
         }
        $saved=yii::$app->user->identity->saveLastLogin();
        Yii::$app->user->logout();
        
        return $this->redirect(['auth/login']);
    }
    /**
     * Changes the user password
     * @return string|Yii\web\Response
     * @author khalid <thewinner016@gmail.com>
     * @since 1.0.0
     */
    public function actionChangepassword(){
        $models = new ChangePasswordForm;
        try{
            if($models->load(Yii::$app->request->post())){
                if($models->changePassword()){
                    Yii::$app->user->logout();
                    $destroySession = true;
                    Yii::$app->session->setFlash('success', '<i class="fa fa-info-circle"></i> Password changed successfully, Now login with the new password!');
                    return $this->redirect(['auth']);
                }else{
                    Yii::$app->session->setFlash('error', '<i class="fa fa-exclamation-triangle"></i> Password Not Changed, check your information then try again later!');
                    return $this->redirect(yii::$app->request->referrer);
                }
           
                    
             } 
            
        }catch(\Exception $e){
            Yii::$app->session->setFlash('error', '<i class="fa fa-exclamation-triangle"></i> Password Not Changed! check your information then try again later');
            return $this->redirect(yii::$app->request->referrer);
        }
    
        return $this->render('changePassword',['model' => $models]);
    }
    /**
     * Changes the user password on restriction (when the user still has the default password)
     * @return string|Yii\web\Response
     * @author khalid <thewinner016@gmail.com>
     * @since 1.0.0
     */
    public function actionChangePasswordRestrict()
    {
        $models = new ChangePasswordForm;
        $this->layout='restrictPasswordChange';
        try{
            if($models->load(Yii::$app->request->post())){
                if($models->changePassword()){
                      Yii::$app->user->logout();
                      $destroySession = true;
                      Yii::$app->session->setFlash('success', 'Password changed successfully, Now login with the new password!');
                    return $this->redirect(['auth']);
                }else{
                    Yii::$app->session->setFlash('error', 'The current password is wrong');
                    return $this->redirect(yii::$app->request->referrer);
                }
           
                    
             } 
            
        }catch(\Exception $e){
            Yii::$app->session->setFlash('error', 'Something went wrong! try again later');
            return $this->redirect(yii::$app->request->referrer);
        }
    
        return $this->render('changePasswordrestrict',['model' => $models]);  
    }
    public function actionViewProfile()
    {
        return $this->renderAjax('profile');
    }
    

}
