<?php

namespace frontend\controllers;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use common\models\RegisterUserForm;
use yii\helpers\ArrayHelper;
use common\models\AuthItem;
use common\models\College;
use yii\data\ActiveDataProvider;
use common\models\Admin;
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
                            'admin-list'
                        ],
                        'allow' => true,
                        'roles'=>['SUPER_ADMIN']

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
    	$roles = ArrayHelper::map(AuthItem::find()->where(['name'=>'SYS_ADMIN'])->all(), 'name', 'name');
        $colleges = ArrayHelper::map(College::find()->all(), 'collegeID', 'college_name');
    	if($model->load(Yii::$app->request->post()) && $model->create()){
    		Yii::$app->session->setFlash('success', 'User registered successfully');
    	}
    }catch(\Exception $e){
    	Yii::$app->session->setFlash('error', 'Something went wrong!');
    }
    return $this->render('create', ['model'=>$model, 'roles'=>$roles, 'colleges'=>$colleges]);
    }
    public function actionAdminList(){
        $users= Admin::find()->all();
        // $dataProvider = new ActiveDataProvider([
        //     'query'=>$query,
        //     'pagination'=>['pageSize'=>2],
        // ]);
        return $this->render('admin-list', ['users'=>$users]);


    }

    

}
