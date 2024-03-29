<?php
namespace frontend\controllers;
use common\models\Annualbudget;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\TblAuditEntry;
use common\models\TblAuditEntrySearch;
use yii\data\ActiveDataProvider;
use common\models\User;
use common\models\Member;
use common\models\Budgetyear;
use common\models\Branch;
use yii\helpers\Html;
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
                            'activity-logs',
                            'users-list',
                            'budget-year',
                            'migrate',
                            'migrate-back'
                        ],
                        'allow' => true,
                        'roles' => ['ADMIN'],
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
        //$searchModel = new TblAuditEntrySearch();
        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $users=User::find()->count();
        $members=Member::find()->count();
        $branches=Branch::find()->count();
        $financialyear=Budgetyear::find()->where(['operationstatus'=>'open'])->one();
        return $this->render('index', [
            //'searchModel' => $searchModel,
            //'dataProvider' => $dataProvider,
            'year'=>$financialyear,
            'branches'=>$branches,
            'members'=>$members,
            'users'=>$users
           
        ]);
    }
    public function actionUsersList(){
        $users=User::find()->all();

        return $this->render('users_list',['users'=>$users]);
    }
// get Activity Logs
    public function actionActivityLogs(){
        $searchModel = new TblAuditEntrySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('activity_logs', ['searchModel' => $searchModel,'dataProvider' => $dataProvider]);
    }

    public function actionBudgetYear()
    {
        $budgetyears=BudgetYear::find()->all();
        return $this->render('budgetyear',['budgetyears'=>$budgetyears]);
    }
    public function actionMigrate()
    {
        $budget=(new Budgetyear)->getBudgetYear();

        //migration to the next budget year

        $newbudget=new Budgetyear;
        $transaction=yii::$app->db->beginTransaction();

        try{
            //constructing the new budget year details
            $starting=$budget->startingyear;
            $ending=$budget->endingyear;
            ++$starting;
            ++$ending;

            //does the year already exist

            $followingyear=Budgetyear::find()->where(['startingyear'=>$starting,'endingyear'=>$ending])->one();

            if($followingyear!=null)
            {
                $followingyear->operationstatus="open"; //reopen it only  
                if(!$followingyear->save())
                {
                    throw new \Exception("Could not migrate to the next budget year! ".Html::errorSummary($followingyear));
                }

                $newbudget=$followingyear;
            }
            else
            {
            //create a fresh budget year
            $newbudget->startingyear=$starting;
            $newbudget->endingyear=$ending;
            $newbudget->title=$newbudget->startingyear." - ".$newbudget->endingyear;
            $newbudget->operationstatus="open";

            if(!$newbudget->save())
            {
                throw new \Exception("Could not migrate to the next budget year! ".Html::errorSummary($newbudget));
            }
            }
            //closing the current budget year
            $budget->operationstatus="closed";

            if(!$budget->save())
            {
                throw new \Exception("Could not close the current budget year! ".Html::errorSummary($budget));
            }

            //creating the annual budget for the new budget year
            $annualbudget=new Annualbudget;
            $annualbudget->projected_amount=0; // no projection for annual budget
            $annualbudget->yearID=$newbudget->yearID;
            $annualbudget->status="open";

            if(!$annualbudget->save())
            {
                throw new \Exception("Could not create annual budget for this budget year! ".Html::errorSummary($annualbudget));  
            }

            //creating branch budget years

            (new Branch)->createAnnualBudgets($annualbudget->budgetID);

            $transaction->commit();

            yii::$app->session->setFlash("success","Budget Year Migration Successful!");
            return $this->redirect(yii::$app->request->referrer);
        }
        catch(\Exception $y)
        {
           $transaction->rollBack();

           yii::$app->session->setFlash("error","Budget migration failed ! ".$y->getMessage());
           return $this->redirect(yii::$app->request->referrer);
        }
    }
    public function actionMigrateBack()
    {
        $budget=(new Budgetyear)->getBudgetYear();

        //migration to the next budget year
        $transaction=yii::$app->db->beginTransaction();
        
        try{
            //updating the current budget year

            $budget->operationstatus="closed";

            if(!$budget->save())
            {
                throw new \Exception("Could not close the current budget ! ".Html::errorSummary($budget));
            }

            //moving the pointer to the previous budget year
            $start=--$budget->startingyear;
            $end=--$budget->endingyear;

            $previousbudget=Budgetyear::find()->where(['startingyear'=>$start,'endingyear'=>$end])->one();
            
            //No previous budget year found

            if($previousbudget==null)
            {
                throw new \Exception("No Previous Budget Year Found! ");
            }
            //actualizing to the current budget

            $previousbudget->operationstatus="open";

            if(!$previousbudget->save())
            {
                throw new \Exception("Could not migrate back to previous budget year! ".Html::errorSummary($previousbudget));
            }

            $transaction->commit();
            yii::$app->session->setFlash("success","Budget Year Migration Successful!");
            return $this->redirect(yii::$app->request->referrer);
        }
        catch(\Exception $b)
        {
          $transaction->rollBack();
          yii::$app->session->setFlash("error","Budget migration failed ! ".$b->getMessage());
           return $this->redirect(yii::$app->request->referrer);
        }
    }

}
