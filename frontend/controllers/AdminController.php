<?php
namespace frontend\controllers;

use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\RegisterInstructorForm;
use frontend\models\RegisterHodForm;
use frontend\models\UploadStudentForm;
use common\models\Instructor;
use common\models\Student;
use common\models\College;
use common\models\Department;
use common\models\Hod;
use common\models\Program;
use common\models\Course;
use yii\helpers\ArrayHelper;
use common\models\AuthItem;
use yii\helpers\URL;
use common\models\TblAuditEntry;
use common\models\TblAuditEntrySearch;
use yii\data\ActiveDataProvider;
//use yii\web\Controller;
use yii\web\NotFoundHttpException;
//use yii\filters\VerbFilter;

/**
 * Site controller
 */
class AdminController extends Controller
{
    /**
     * {@inheritdoc}
     */
    //apply admin layout to this controller
//public $layout = 'admin';
public $defaultAction = 'dashboard';
      public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                  
                    [
                        'actions' => [
                            'dashboard',
                            'instructor-list',
                            'hod-list',
                            'create-instructor',
                            'create-hod',
                            'create-student',
                            'student-list',
                            'activity-logs',
                            'delete' => ['POST'],
                        ],
                        'allow' => true,
                        'roles' => ['SYS_ADMIN'],
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
    /**
     * {@inheritdoc}
     */

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionDashboard()
    {
        $searchModel = new TblAuditEntrySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

         //passing the numbers of users to the admin dashboard
         $instructors = Instructor::find()->all();
         $instructorsnumber=count($instructors);
 
         $students = Student::find()->all();
         $studentsnumber=count($students);
 
         $programs = Program::find()->all();
         $programsnumber=count($programs);
 
         $courses = Course::find()->all();
         $coursesnumber=count($courses);

        return $this->render('index', ['searchModel' => $searchModel,'dataProvider' => $dataProvider,'instructorsnumber'=> $instructorsnumber,'studentsnumber'=>$studentsnumber,
            'programsnumber'=> $programsnumber,'coursesnumber'=> $coursesnumber,
        ]);
    }
    //Create instructor
    public function actionCreateInstructor(){
        $model = new RegisterInstructorForm;
        $roles = ArrayHelper::map(AuthItem::find()->where(['name'=>'INSTRUCTOR & HOD'])->orwhere(['name'=>'INSTRUCTOR'])->all(), 'name', 'name');
        $departments = ArrayHelper::map(Department::find()->where(['collegeID'=>Yii::$app->user->identity->admin->college->collegeID])->all(), 'departmentID', 'department_name');
        
        try{
        
        if($model->load(Yii::$app->request->post())){
            if($model->createi()){
            Yii::$app->session->setFlash('success', 'Instructor registered successfully');
            return $this->redirect(Yii::$app->request->referrer);
            }else{
            Yii::$app->session->setFlash('error', 'Something went Wrong!');
        }
         } 
        
    }catch(\Exception $e){
          Yii::$app->session->setFlash('error', 'Something went wrong'.$e->getMessage());
    }
        return $this->render('create_instructor', ['model'=>$model, 'departments'=>$departments, 'roles'=>$roles]);
    }


        //Create hod
        public function actionCreateHod(){
            $model = new RegisterHodForm;
            $roles = ArrayHelper::map(AuthItem::find()->where(['name'=>'HOD'])->all(), 'name', 'name');
            $departments = ArrayHelper::map(Department::find()->where(['collegeID'=>Yii::$app->user->identity->admin->college->collegeID])->all(), 'departmentID', 'department_name');
            try{
            
            if($model->load(Yii::$app->request->post())){
                if($model->create()){
                Yii::$app->session->setFlash('success', 'Hod registered successfully');
                }else{
                    Yii::$app->session->setFlash('error', 'Something went Wrong!');
                }
           
                    
             } 
            
        }catch(\Exception $e){
            Yii::$app->session->setFlash('error', 'Something went wrong'.$e->getMessage());
        }
            return $this->render('create_hod', ['model'=>$model, 'departments'=>$departments, 'roles'=>$roles]);
        }
    
    

    //get instructor list
    public function actionInstructorList(){
        $instructors = Instructor::find()->all();
        return $this->render('instructor_list', ['instructors'=>$instructors]);
    }

    //get Hod list
    public function actionHodList(){
        $hods = Hod::find()->all();
        return $this->render('hod_list', ['hods'=>$hods]);
    }

//get list of students

  public function actionStudentList(){
    $students = Student::find()->all();
    return $this->render('student_list', ['students'=>$students]);
}

// get Activity Logs
    public function actionActivityLogs(){
        $searchModel = new TblAuditEntrySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('activity_logs', ['searchModel' => $searchModel,'dataProvider' => $dataProvider]);
    }

}
