
<?php
use common\models\Budgetyear;
use common\models\Meeting;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


$this->params["pageTitle"]="Finance";
?>
<div class="container-fluid mt-3 meet">
        
<div class="card shadow-lg">
    <div class="card-body text-center" style="font-size:12px">
    <form class="float-left" method="post" action="/finance/switch-financial-year">
      <div class="form-group row">
        <div class="col-sm-10">
      <select class="form-control pl-1" name="year">
        <?php
          $financialyears=Budgetyear::find()->orderBy(['yearID'=>SORT_DESC])->all();
          foreach($financialyears as $financialyear)
          {
        ?>
        <option value=<?=$financialyear->yearID?> <?=($financialyear->yearID==yii::$app->session->get("financialYear")->yearID)?"selected":""?>>Financial Year <?=$financialyear->startingyear?></option>
        <?php } ?>
      </select>
</div><div class="col-sm-2 p-0">
<input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
      <button type="submit" class="form-control p-2"><i class="fa fa-refresh"></i></button>
      </div>
      </div>
    </form> 
    
    <?php if(!$annualbudget->isOpen()){?>
    <a href="<?=Url::to(['/finance/open-annual-budget','budget'=>urlencode(base64_encode($annualbudget->budgetID))])?>" class="btn btn-sm btn-success float-right" data-toggle="tooltip" data-title="Open Budget">
    <i class="fas fa-door-open"></i></a>
    <?php }else{ ?>
    <a href="<?=Url::to(['/finance/close-annual-budget','budget'=>urlencode(base64_encode($annualbudget->budgetID))])?>" class="btn btn-sm btn-success float-right mr-1" data-toggle="tooltip" data-title="Close Budget">
    <i class="fas fa-door-closed"></i></a>
    <?php } ?>
    <a href="<?=Url::to(['/finance/monthly-incomes','budget'=>urlencode(base64_encode($annualbudget->budgetID))])?>" class="btn btn-sm btn-success mr-1 float-right" ><i class="fa fa-arrow-right" data-toggle="tooltip" data-title="Go To Monthly Incomes"></i></a>
    <a href="/finance/download-annual-report" class="btn btn-sm btn-success mr-1 float-right" ><i class="fa fa-download" data-toggle="tooltip" data-title="Download Annual Report"></i></a>
    <a href="#" class="btn btn-sm btn-success mr-1 float-right" data-toggle="modal" data-target="#otherincome"><i class="fa fa-arrow-down" data-toggle="tooltip" data-title="Acquire Other Incomes"></i></a>
    <a href="#" class="btn btn-sm btn-success mr-1 float-right" data-toggle="modal" data-target="#incomemodal"><i class="fa fa-donate" data-toggle="tooltip" data-title="Acquire Monthly collections"></i></a>
    
</div>
</div>
<div class="card shadow-lg">
    <div class="card-header p-1 bg-success text-sm pl-2">
         <i class="fa fa-money"></i> Financial Overview 
    </div>
        
        <div class="card-body text-center " style="font-size:13px">
            
             <div class="row">
            
              <div class="col">
                <span class="heading">Tot. Revenue</span><br>
                <span class="money" ><?=yii::$app->MoneyFormatter->format($annualbudget->totalRevenue())?></span>
              </div>
              <div class="col">
                <span class="heading">Tot. Contributions</span><br>
                <span class="money"><?=yii::$app->MoneyFormatter->format($annualbudget->totalIncome())?></span>
              </div>
              <div class="col">
                <span class="heading">Other Incomes</span><br>
                <span class="money"><?=yii::$app->MoneyFormatter->format($annualbudget->otherIncomeTotal())?></span>
              </div>
              <div class="col">
                <span class="heading">Branch Returns</span><br>
                <span class="money"><?=yii::$app->MoneyFormatter->format(($annualbudget->totalReturns()))?></span>
              </div>
              <div class="col">
                <span class="heading">HQ Revenue</span><br>
                <span class="money"><?=yii::$app->MoneyFormatter->format($annualbudget->HQrevenue())?></span>
              </div>
              <div class="col">
                <span class="heading">Unallocated</span><br>
                <span class="money"><?=yii::$app->MoneyFormatter->format($annualbudget->unallocated())?></span>
              </div>
              <div class="col">
                <span class="heading">Tot. Expenses</span><br>
                <span class="money"><?=yii::$app->MoneyFormatter->format((-$annualbudget->getTotalExpenses()))?></span>
              </div>
           
              <div class="col">
                <span class="heading">Balance</span><br>
                <span class="money"><?=yii::$app->MoneyFormatter->format($annualbudget->getBalance())?></span>
              </div>
           
             </div>

        </div>
  
  
</div>

 <div class="card shadow-lg">
    <div class="card-header p-1 bg-success text-sm pl-2">
 
          <i class="fa fa-building"></i> Branches Budget
               
    </div>
        
        <div class="card-body " style="font-size:13px">
            
                    <div class="row mb-2 heading"><div class="col-sm-1">#</div><div class="col">Branch</div><div class="col">Projection</div><div class="col">Total Revenue</div><div class="col">Deficit</div><div class="col">Total Expenses</div><div class="col">Balance</div><div class="col"></div></div>
                    
                        <?php
                        $count=0;
                        $branchbudgets=$annualbudget->branchAnnualBudgets;
  
                          foreach($branchbudgets as $bbudget)
                          {
                        ?>
                          <div class="row money"><div class="col-sm-1"><?=++$count?></div>
                          <div class="col"><?=$bbudget->branch0->branch_short?></div>
                          <div class="col"><?=yii::$app->MoneyFormatter->format($bbudget->projected())?></div>
                          <div class="col"><?=yii::$app->MoneyFormatter->format($bbudget->branchTotalRevenue())?></div>
                          <div class="col"><?=yii::$app->MoneyFormatter->format($bbudget->deficit())?></div>
                          <div class="col"><?=yii::$app->MoneyFormatter->format($bbudget->getTotalExpenses())?></div>
                          <div class="col"><?=yii::$app->MoneyFormatter->format($bbudget->getBalance())?></div>
                          <div class="col">
                          <a href="<?=Url::to(['/finance/branch-finance','budget'=>urlencode(base64_encode($bbudget->bbID))])?>" data-toggle="tooltip" data-title="Go To Branch Budget"><i class="fa fa-arrow-circle-right text-success" style="font-size:20px"></i></a>
                          </div></div>
                        <?php
                          }

                        ?>
                        
        </div>
  
  <?=$this->render('newincome')?>
  <?=$this->render('otherincome')?>
</div>
</div>
</div>

 <?php
$script = <<<JS
    $('document').ready(function(){
    $('.finance').addClass("active");
    
  

   })
JS;
$this->registerJs($script);
?>
