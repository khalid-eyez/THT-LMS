<?php

namespace frontend\controllers;
use common\models\Student;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\VerifyEmailForm;
use common\models\Session;
use common\models\Budgetyear;
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
                        'actions' => ['login','requestpasswordreset'],
                        'allow' => true,
                        
                    ],
                    [
                        'actions' => ['logout', 'error','requestPasswordResetToken','resertPassword','resendVerificationEmail','verify_email'],
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
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
   
    
    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        $this->layout="login";
      if (!Yii::$app->user->isGuest) {

             return $this->redirect(['/member/dashboard']);
       }
      $model = new LoginForm();
      if ($model->load(Yii::$app->request->post()) && $model->login()) {
      
           return $this->redirect(['/home/dashboard']);
     }

       return $this->render('login', ['model'=>$model]);
        
    }
     /**
     * Logs out the current user.
     *
     * @return mixed
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

   
    
    

}
