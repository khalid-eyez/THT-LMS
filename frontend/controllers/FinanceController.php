<?php

namespace frontend\controllers;

use yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use common\models\Files;
use yii\helpers\Html;
use common\models\Annualbudget;
use common\models\Monthlyincome;
use common\models\BranchMonthlyRevenue;
use common\models\BranchAnnualBudget;
use common\models\Budgetprojections;
use common\models\Itemizedprojections;
use common\models\Payabletransactions;

class FinanceController extends Controller
{
    public function behaviors()
    {
    return [
        'access' => [
            'class' => AccessControl::className(),
            'rules' => [
              
                [
                    'actions' => [
                        'finance',
                        'new-income',
                        'update-revenues',
                        'open-annual-budget',
                        'close-annual-budget',
                        'new-item',
                        'budget-projection-itemizer',
                        'branch-income-allocate',
                        'payments',
                        'branch-finance',
                        'branch-accounts',
                        'budget-item',
                        'monthly-incomes',
                        'branch-monthly-incomes',
                        'item-allocations'
                    ],
                    'allow' => true,
                    'roles' => ['GENERAL SECRETARY HQ','CHAIRPERSON HQ'],
                ],
                [
                    'actions' => [
                        'finance',
                        'new-item',
                        'budget-projection-itemizer',
                        'branch-income-allocate',
                        'payments',
                        'branch-finance',
                        'branch-accounts',
                        'budget-item',
                        'branch-monthly-incomes',
                        'item-allocations'
                    ],
                    'allow' => true,
                    'roles' => ['CHAIRPERSON BR','GENERAL SECRETARY BR'],
                ],
                [
                    'actions' => [
                        'payments',
                        'finance',
                    ],
                    'allow' => true,
                    'roles' => ['TREASURER HQ','TREASURER BR'],
                ],
            ],
        ],
    ];
    }

    public function actionFinance()
    {
     $annualBudget=(new Annualbudget)->getCurrentBudget();
     $branchAnnualBudget=(new BranchAnnualBudget)->getCurrentBudget();
     $branch=yii::$app->user->identity->member->branch0;
     if(yii::$app->user->can('TREASURER HQ') || yii::$app->user->can('TREASURER BR'))
     {
        return $this->render('accounts',['annualbudget'=>$branchAnnualBudget]);
     }
     if($branch->isHQ())
     {
        return $this->render('finance',['annualbudget'=>$annualBudget]);
     }

    
    
        return $this->render('branchFinance',['annualbudget'=>$branchAnnualBudget]);
     
      
    }
    public function actionNewIncome()
    {
        $model=new Monthlyincome();
        try{
        $model->budgetID=(new Annualbudget)->getCurrentBudget()->budgetID;
        if($model->load(yii::$app->request->post()) && $model->save())
        {
            yii::$app->session->setFlash("success",'<i class="fa fa-info-circle"></i> Income Acquired and structured Successfully ! But you can still amend income structure below!');
            return $this->redirect(["/finance/update-revenues",'income'=>urlencode(base64_encode($model->incomeID))]);
        }
        else
        {
            yii::$app->session->setFlash('error','<i class="fa fa-exclamation-triangle"></i> Could not add income '.Html::errorSummary($model));
            return $this->redirect(yii::$app->request->referrer);
        }
    }
    catch(\Exception $d)
    {
        yii::$app->session->setFlash('error','<i class="fa fa-exclamation-triangle"></i> Could not add income '.$d->getMessage());
        return $this->redirect(yii::$app->request->referrer); 
    }
    }
    public function actionUpdateRevenues($income)
    {
        $income=base64_decode(urldecode($income));
        $income=Monthlyincome::findOne($income);
    try
    {
        if(yii::$app->request->isPost)
        {
            if((new BranchMonthlyRevenue)->updateRevenues(yii::$app->request->post()))
            {
                yii::$app->session->setFlash("success",'<i class="fa fa-info-circle"></i> Income structure updated Successfully !');
                return $this->redirect("finance");
            }
            else
            {
                yii::$app->session->setFlash('error','<i class="fa fa-exclamation-triangle"></i> Income structure updating failed ! '.Html::errorSummary($model));
                return $this->redirect(yii::$app->request->referrer); 
            }
        }
    }
    catch(\Exception $u)
    {
        yii::$app->session->setFlash('error','<i class="fa fa-exclamation-triangle"></i> Income structure updating failed '.$u->getMessage());
        return $this->redirect(yii::$app->request->referrer);   
    }

        return $this->render('branchrevenuesupdate',['income'=>$income]);
    }
    public function actionOpenAnnualBudget($budget)
    {
      $budget=base64_decode(urldecode($budget));
      $budget=Annualbudget::findOne($budget);

      try
      {
        $budget->status="open";
        if($budget->save())
        {
            yii::$app->session->setFlash("success",'<i class="fa fa-info-circle"></i> Budget Opened Successfully ! Planning and Reviews are allowed');
            return $this->redirect(yii::$app->request->referrer); 
        }
        else
        {
            yii::$app->session->setFlash('error','<i class="fa fa-exclamation-triangle"></i> Annual Budget Opening Failed ! '.Html::errorSummary($budget));
            return $this->redirect(yii::$app->request->referrer); 
        }
      }
      catch(\Exception $b)
      {
        yii::$app->session->setFlash('error','<i class="fa fa-exclamation-triangle"></i> Annual Budget Opening Failed ! '.$b->getMessage());
        return $this->redirect(yii::$app->request->referrer); 
      }
    }
    public function actionCloseAnnualBudget($budget)
    {
      $budget=base64_decode(urldecode($budget));
      $budget=Annualbudget::findOne($budget);

      try
      {
        $budget->status="closed";
        if($budget->save())
        {
            yii::$app->session->setFlash("success",'<i class="fa fa-info-circle"></i> Budget Closed Successfully ! Planning and Reviews are not allowed');
            return $this->redirect(yii::$app->request->referrer); 
        }
        else
        {
            yii::$app->session->setFlash('error','<i class="fa fa-exclamation-triangle"></i> Annual Budget Closing Failed ! '.Html::errorSummary($budget));
            return $this->redirect(yii::$app->request->referrer); 
        }
      }
      catch(\Exception $b)
      {
        yii::$app->session->setFlash('error','<i class="fa fa-exclamation-triangle"></i> Annual Budget Closing Failed ! '.$b->getMessage());
        return $this->redirect(yii::$app->request->referrer); 
      }
    }
    public function actionNewItem()
    {
        try
        {
        $model=new Budgetprojections;
        $model->branchbudget=(new BranchAnnualBudget)->getCurrentBudget()->bbID;
        if($model->load(yii::$app->request->post()) && $model->save())
        {
            yii::$app->session->setFlash("success",'<i class="fa fa-info-circle"></i> Budget Item added Successfully ! Now you can proceed with planning');
            return $this->redirect(['budget-projection-itemizer','projection'=>urlencode(base64_encode($model->projID))]);
        }
        else
        {
            yii::$app->session->setFlash('error','<i class="fa fa-exclamation-triangle"></i> An error occurred while adding Item ! '.Html::errorSummary($model));
            return $this->redirect(yii::$app->request->referrer);  
        }
    }
    catch(\Exception $i)
    {
        yii::$app->session->setFlash('error','<i class="fa fa-exclamation-triangle"></i> An error occurred while adding Item ! '.$i->getMessage());
        return $this->redirect(yii::$app->request->referrer); 
    }
    }
    public function actionBudgetProjectionItemizer($projection)
    {
        $projection=base64_decode(urldecode($projection));
        $budgetprojection=Budgetprojections::findOne($projection);

        if(yii::$app->request->isPost)
        {
            try{

                if($budgetprojection->acquireItems(yii::$app->request->post()))
                {
                    yii::$app->session->setFlash("success",'<i class="fa fa-info-circle"></i> Items added successfully !');
                    return $this->redirect(yii::$app->request->referrer); 
                }
                else
                {
                    yii::$app->session->setFlash('error','<i class="fa fa-exclamation-triangle"></i> An error occurred while adding Items ! '.Html::errorSummary($budgetprojection));
                    return $this->redirect(yii::$app->request->referrer);  
                }
            }
            catch(\Exception $i)
            {
                yii::$app->session->setFlash('error','<i class="fa fa-exclamation-triangle"></i> An error occurred while adding Items ! '.$i->getMessage());
                return $this->redirect(yii::$app->request->referrer); 
            }
        }
        return $this->render('branchbudgetItemizer',['projection'=>$budgetprojection]);
        
    }
    public function actionBranchIncomeAllocate($budget)
    {
      $budget=BranchAnnualBudget::findOne(base64_decode(urldecode($budget)));

      if(yii::$app->request->isPost)
      {
        try
        {
        $model=new Budgetprojections;
       if(($model)->acquireBudget(yii::$app->request->post()))
       {
        yii::$app->session->setFlash("success",'<i class="fa fa-info-circle"></i> Budget allocation successful !');
        return $this->redirect(yii::$app->request->referrer); 
       }
       else
       {
        yii::$app->session->setFlash('error','<i class="fa fa-exclamation-triangle"></i> Budget allocation failed ! '.Html::errorSummary($model));
        return $this->redirect(yii::$app->request->referrer);  
       }
    }
    catch(\Exception $r)
    {
        yii::$app->session->setFlash('error','<i class="fa fa-exclamation-triangle"></i> Budget allocation failed !  '.$r->getMessage());
        return $this->redirect(yii::$app->request->referrer);
    }
      }

      return $this->render('branchbudgetprojectionsupdate',['budget'=>$budget]);
    }
    public function actionPayments($item)
    {
        $item=base64_decode(urldecode($item));
        $item=Itemizedprojections::findOne($item);
        if(yii::$app->request->isPost)
        {
            $model=new Payabletransactions;
            $model->item=$item->ipID;
            try
            {
            if($model->load(yii::$app->request->post()) && $model->save())
            {
                yii::$app->session->setFlash("success",'<i class="fa fa-info-circle"></i> Payment successful !');
                return $this->redirect(yii::$app->request->referrer); 
            }
            else
            {
                yii::$app->session->setFlash('error','<i class="fa fa-exclamation-triangle"></i> Payment failed ! '.Html::errorSummary($model));
                return $this->redirect(yii::$app->request->referrer); 
            }
        }
        catch(\Exception $r)
        {
            yii::$app->session->setFlash('error','<i class="fa fa-exclamation-triangle"></i> Payment failed !  '.$r->getMessage());
            return $this->redirect(yii::$app->request->referrer);
        }
        }
        return $this->render('payments',['item'=>$item]);
    }
    public function actionBranchFinance($budget)
    {
        $budget=base64_decode(urldecode($budget));
        $annualbudget=BranchAnnualBudget::findOne($budget);
        return $this->render('branchFinance',['annualbudget'=>$annualbudget]);
    }
    public function actionBranchAccounts($budget)
    {
        $budget=base64_decode(urldecode($budget));
        $annualbudget=BranchAnnualBudget::findOne($budget);
        return $this->render('accounts',['annualbudget'=>$annualbudget]);   
    }
    public function actionBudgetItem($item)
    {
        $item=base64_decode(urldecode($item));
        $item=Budgetprojections::findOne($item);

        return $this->render('budgetItem',['budgetItem'=>$item]);
    }
    public function actionMonthlyIncomes($budget)
    {
        $budget=base64_decode(urldecode($budget));
        $annualbudget=Annualbudget::findOne($budget);

        return $this->render('monthlyincomes',['annualbudget'=>$annualbudget]);
    }
    public function actionBranchMonthlyIncomes($budget)
    {
        $budget=base64_decode(urldecode($budget));
        $annualbudget=BranchAnnualBudget::findOne($budget);

        return $this->render('branchmonthlyincomes',['annualbudget'=>$annualbudget]);
    }
    public function actionItemAllocations($item)
    {
        $item=base64_decode(urldecode($item));
        $item=Budgetprojections::findOne($item);

        return $this->render('itemallocations',['item'=>$item]);

    }
    


}










?>