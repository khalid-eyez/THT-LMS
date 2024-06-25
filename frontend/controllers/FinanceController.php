<?php

namespace frontend\controllers;

use common\models\Budgetyear;
use common\models\Costcenter;
use common\models\Costcenterrevenue;
use common\models\Otherincomes;
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
use common\models\Branch;
use frontend\models\MonthlyincomeForm;
use common\models\Branchotherincomes;
use frontend\models\Reporter;

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
                        'item-allocations',
                        'other-income',
                        'delete-income',
                        'switch-financial-year',
                        'budget-review',
                        'review-itemizer',
                        'delete-budget-item',
                        'delete-projection-structure-item',
                        'download-annual-report',
                        'hq-finance',
                        'center-budget-allocate',
                        'costcenters-allocations-review'
                      
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
                        'item-allocations',
                        'branch-other-income',
                        'switch-financial-year',
                        'budget-review',
                        'review-itemizer',
                        'delete-budget-item',
                        'delete-projection-structure-item',
                        'center-budget-allocate',
                        'costcenters-allocations-review'
                       
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
     $budgetyear=yii::$app->session->get("financialYear"); 
     $annualbudget=Annualbudget::find()->where(['yearID'=> $budgetyear->yearID])->one();
     $branchAnnualBudget=(new BranchAnnualBudget)->getCurrentBudget();
     $branch=yii::$app->user->identity->getBranch();
     if(yii::$app->user->can('TREASURER HQ') || yii::$app->user->can('TREASURER BR'))
     {
        return $this->render('accounts',['annualbudget'=>$branchAnnualBudget]);
     }
     if($branch->isHQ())
     {
        return $this->render('finance',['annualbudget'=>$annualbudget]);
     }

    
    
        return $this->render('branchFinance',['annualbudget'=>$branchAnnualBudget]);
     
      
    }
    public function actionNewIncome()
    {
        $model=new MonthlyincomeForm();
        try{
        if($model->load(yii::$app->request->post()) && $model->acquireIncome())
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
        yii::$app->session->setFlash('error','<i class="fa fa-exclamation-triangle"></i> Could not add income! '.$d->getMessage());
        return $this->redirect(yii::$app->request->referrer); 
    }
    }

    public function actionOtherIncome()
    {
        $model=new Otherincomes();

        try{
        $model->budget=yii::$app->session->get("financialYear")->annualbudget->budgetID;
        if($model->load(yii::$app->request->post()) && $model->save())
        {
            yii::$app->session->setFlash("success",'<i class="fa fa-info-circle"></i> Income Acquired Successfully !');
            return $this->redirect(yii::$app->request->referrer);
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

    public function actionBranchOtherIncome()
    {
        $model=new Branchotherincomes();

        try{
        $model->budget=(new BranchAnnualBudget)->getCurrentBudget()->bbID;
        if($model->load(yii::$app->request->post()) && $model->save())
        {
            yii::$app->session->setFlash("success",'<i class="fa fa-info-circle"></i> Income Acquired Successfully !');
            return $this->redirect(yii::$app->request->referrer);
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
                yii::$app->session->setFlash('error','<i class="fa fa-exclamation-triangle"></i> Income structure updating failed ! ');
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
            if(strpos(yii::$app->request->referrer,"/finance/budget-review"))
            {
                return $this->redirect(['review-itemizer','projection'=>urlencode(base64_encode($model->projID))]);  
            }
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
            //print_r(yii::$app->request->post());return  null;
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
    public function actionReviewItemizer($projection)
    {
        $projection=base64_decode(urldecode($projection));
        $budgetprojection=Budgetprojections::findOne($projection);

        if(yii::$app->request->isPost)
        {
            try{

                if($budgetprojection->acquireItems(yii::$app->request->post()))
                {
                    yii::$app->session->setFlash("success",'<i class="fa fa-info-circle"></i> Items added successfully !');
                    return $this->redirect(["/finance/budget-review","budget"=>base64_encode(urlencode($budgetprojection->branchbudget0->bbID))]); 
                }
                else
                {
                    yii::$app->session->setFlash('error','<i class="fa fa-exclamation-triangle"></i> An error occurred while adding Items ! '.Html::errorSummary($budgetprojection));
                    return $this->redirect(["/finance/budget-review","budget"=>$budgetprojection->branchbudget0->bbID]); 
                }
            }
            catch(\Exception $i)
            {
                yii::$app->session->setFlash('error','<i class="fa fa-exclamation-triangle"></i> An error occurred while adding Items ! '.$i->getMessage());
                return $this->redirect(yii::$app->request->referrer); 
            }
        }
        return $this->render('reviewitemizer',['projection'=>$budgetprojection]);
        
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

    public function actionCenterBudgetAllocate($budget)
    {
      $budget=BranchAnnualBudget::findOne(base64_decode(urldecode($budget)));

      if(yii::$app->request->isPost)
      {
        try
        {
       $model=new Costcenter();
       if(($model)->acquireBudget(yii::$app->request->post(),$budget->bbID))
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

      return $this->render('centerbudgetdispatcher',['budget'=>$budget]);
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
        $currentbudgetyear=yii::$app->session->get('financialYear');
        $yearID=($currentbudgetyear!=null)?$currentbudgetyear->yearID:null;
        $Bbudgetid=base64_decode(urldecode($budget));
        $branchannualbudget=null;
        if($yearID!=null)
        {
            $annualbudget=Annualbudget::find()->where(['yearID'=> $currentbudgetyear->yearID])->one();
            $branch=BranchAnnualBudget::findOne($Bbudgetid)->branch0->branchID;
            $branchannualbudget=BranchAnnualBudget::find()->where(['budgetID'=>$annualbudget->budgetID,'branch'=>$branch])->one();
        }
        else
        {
            $branchannualbudget=BranchAnnualBudget::findOne($Bbudgetid);
        }
       
        //is this HQ

        if($branchannualbudget->branch0->isHQ())
        {
            return $this->redirect(['hq-finance','budget'=>$budget]);
        }
        return $this->render('branchFinance',['annualbudget'=>$branchannualbudget]);
    }
    public function actionHqFinance($budget)
    {
        $currentbudgetyear=yii::$app->session->get('financialYear');
        $yearID=($currentbudgetyear!=null)?$currentbudgetyear->yearID:null;
        $Bbudgetid=base64_decode(urldecode($budget));
        $branchannualbudget=null;
        if($yearID!=null)
        {
            $annualbudget=Annualbudget::find()->where(['yearID'=> $currentbudgetyear->yearID])->one();
            $branch=BranchAnnualBudget::findOne($Bbudgetid)->branch0->branchID;
            $branchannualbudget=BranchAnnualBudget::find()->where(['budgetID'=>$annualbudget->budgetID,'branch'=>$branch])->one();
        }
        else
        {
            $branchannualbudget=BranchAnnualBudget::findOne($Bbudgetid);
        }
        return $this->render('hqFinance',['annualbudget'=>$branchannualbudget]);
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

    public function actionDeleteIncome()
    {
        $income=yii::$app->request->post("income");
        $monthlyincome=Monthlyincome::findOne($income);

        if($monthlyincome->delete())
        {
            return $this->asJson(['success'=>"Income deleted successfully!"]);
        }
        else
        {
            return $this->asJson(['failure'=>"Income Deleting Failed!"]); 
        }
    }

    public function actionDeleteBudgetItem()
    {
        $item=yii::$app->request->post("item");
        $itemmodel=Budgetprojections::findOne($item);
        try
        {
        if($itemmodel->delete())
        {
            return $this->asJson(['success'=>"Budget Item deleted successfully!"]);
        }
        else
        {
            return $this->asJson(['failure'=>"Budget Item Deleting Failed!"]); 
        }
        }
        catch(\Exception $d)
        {
            return $this->asJson(['failure'=>"Budget Item Deleting Failed! ".$d->getMessage()]);  
        }
    }

    public function actionDeleteProjectionStructureItem()
    {
        $item=yii::$app->request->post("item");
        $itemmodel=Itemizedprojections::findOne($item);
        try
        {
        if($itemmodel->delete())
        {
            return $this->asJson(['success'=>"Budget Structure Item Deleted Successfully!"]);
        }
        else
        {
            return $this->asJson(['failure'=>"Budget Structure Item Deleting Failed!"]); 
        }
       }
       catch(\Exception $p)
       {
        return $this->asJson(['failure'=>"Budget Structure Item Deleting Failed! ".$p->getMessage()]); 
       }
    }
    
    public function actionSwitchFinancialYear()
    {
        $yearid=yii::$app->request->post("year");
        $year=Budgetyear::findOne($yearid);

        yii::$app->session->set('financialYear',$year);

        return $this->redirect(yii::$app->request->referrer);
    }

    public function actionBudgetReview($budget)
    {
        $budget=BranchAnnualBudget::findOne(base64_decode(urldecode($budget)));
        if(yii::$app->request->isPost)
        {
          try
          {
          $model=new Budgetprojections;
         if($model->updateProjections(yii::$app->request->post()))
         {
          yii::$app->session->setFlash("success",'<i class="fa fa-info-circle"></i> Budget projections updated successfully !');
          return $this->redirect(yii::$app->request->referrer); 
         }
         else
         {
          yii::$app->session->setFlash('error','<i class="fa fa-exclamation-triangle"></i> Budget projections updating failed ! '.Html::errorSummary($model));
          return $this->redirect(yii::$app->request->referrer);  
         }
      }
      catch(\Exception $r)
      {
          yii::$app->session->setFlash('error','<i class="fa fa-exclamation-triangle"></i> Budget projections updating failed !  '.$r->getMessage());
          return $this->redirect(yii::$app->request->referrer);
      }
        }
        return $this->render('budgetreview',['budget'=>$budget]);
    }
    public function actionCostcentersAllocationsReview($budget)
    {
        $budget=BranchAnnualBudget::findOne(base64_decode(urldecode($budget)));
        if(yii::$app->request->isPost)
        {
          try
          {
          $model=new Costcenter();
         if($model->acquireBudget(yii::$app->request->post(),$budget->bbID))
         {
          yii::$app->session->setFlash("success",'<i class="fa fa-info-circle"></i> Budget updated successfully !');
          return $this->redirect(yii::$app->request->referrer); 
         }
         else
         {
          yii::$app->session->setFlash('error','<i class="fa fa-exclamation-triangle"></i> Budget updating failed ! '.Html::errorSummary($model));
          return $this->redirect(yii::$app->request->referrer);  
         }
      }
      catch(\Exception $r)
      {
          yii::$app->session->setFlash('error','<i class="fa fa-exclamation-triangle"></i> Budget updating failed !  '.$r->getMessage());
          return $this->redirect(yii::$app->request->referrer);
      }
        }
        return $this->render('costcenter_allocations_review',['budget'=>$budget]);
    }
    public function actionDownloadAnnualReport()
    {
        (new Reporter)->downloadPDFReport((new Reporter)->incomesReportBuilder());
        (new Reporter)->downloadExcelReport();
    }


}










?>