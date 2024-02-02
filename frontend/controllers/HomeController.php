<?php

namespace frontend\controllers;
use frontend\models\ChangeRegNoForm;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use common\models\User;
use frontend\models\ChangePasswordForm;
use frontend\models\AddEmailForm;
use yii\helpers\Url;
use yii\helpers\VarDumper;
class HomeController extends \yii\web\Controller
{
        /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only'=>['dashboard'],
                'rules' => [
                    [
                        'actions' => ['dashboard','changePassword','password-change-cancel','change-password-restrict','add_email','change-regno'],
                        'allow' => true,
                        'roles' => ['@']
                    ],
                    
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    //'logout' => ['post'],
                ],
            ],
        ];
    }
     public function actionDashboard()
    {


        if(yii::$app->user->identity->hasDefaultPassword()){
            return $this->redirect(Url::to(['/home/change-password-restrict']));
          }
         return $this->redirect(Url::to(['/member/dashboard'])); 
   
}

public function actionChangepassword(){
    
    
    $models = new ChangePasswordForm;

    // VarDumper::dump($models->changePassword());

    try{
        if($models->load(Yii::$app->request->post())){
           // VarDumper::dump($models->changePassword());
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

public function actionChangePasswordRestrict()
{
    $models = new ChangePasswordForm;

    // VarDumper::dump($models->changePassword());
    $this->layout='restrictPasswordChange';
    try{
        if($models->load(Yii::$app->request->post())){
           // VarDumper::dump($models->changePassword());
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
public function actionPasswordChangeCancel()
{
    Yii::$app->user->logout();
    $destroySession = true;
    return $this->redirect(['auth']);
}




public function actionAdd_email(){
    
    
    $models = new AddEmailForm;

    // VarDumper::dump($models->changePassword());

    try{
        if($models->load(Yii::$app->request->post())){
           // VarDumper::dump($models->addEmail());
            if($models->addEmail()){
                Yii::$app->session->setFlash('success', 'Email added successfully');
            }else{
                Yii::$app->session->setFlash('error', 'Something went Wrong!, Email does not added');
            }
       
                
         } 
        
    }catch(\Exception $e){
        Yii::$app->session->setFlash('error', 'Something went wrong');
    }

    return $this->render('addEmail',['model' => $models]);
}





    public function actionChangeRegno(){


        $models = new ChangeRegNoForm;

        // VarDumper::dump($models->changePassword());

        try{
            if($models->load(Yii::$app->request->post())){
                // VarDumper::dump($models->addEmail());
                if($models->changeRegno()){
                    Yii::$app->session->setFlash('success', 'Reg number changed successfully');
                    return $this->refresh();
                }else{
                    Yii::$app->session->setFlash('error', 'Something went Wrong, Reg number does not changed!');
                }


            }

        }catch(\Exception $e){
            Yii::$app->session->setFlash('error', 'Something went wrong');
        }

        return $this->render('change_reg_no',['model' => $models]);
    }
   

}
