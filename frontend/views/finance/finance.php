
<?php
use common\models\Meeting;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


$this->params["pageTitle"]="Finance dashboard";
?>
<div class="container-fluid mt-3 meet">
        
<div class="card shadow-lg">
<div class="card-header p-1 bg-success text-sm">
              
 </div>
    <div class="card-body text-center" style="font-family:lucida sans serif;font-size:12px">
    <span class="text-lg text-success">Financial Year <?=$annualbudget->year->title?></span>
    <?php if(!$annualbudget->isOpen()){?>
    <a href="<?=Url::to(['/finance/open-annual-budget','budget'=>urlencode(base64_encode($annualbudget->budgetID))])?>" class="btn btn-sm btn-success float-right" data-toggle="tooltip" data-title="Open Budget">
    <i class="fas fa-door-open"></i></a>
    <?php }else{ ?>
    <a href="<?=Url::to(['/finance/close-annual-budget','budget'=>urlencode(base64_encode($annualbudget->budgetID))])?>" class="btn btn-sm btn-success float-right mr-1" data-toggle="tooltip" data-title="Close Budget">
    <i class="fas fa-door-closed"></i></a>
    <?php } ?>
    <a href="<?=Url::to(['/finance/monthly-incomes','budget'=>urlencode(base64_encode($annualbudget->budgetID))])?>" class="btn btn-sm btn-success mr-1 float-right" ><i class="fa fa-arrow-right" data-toggle="tooltip" data-title="Go To Monthly Incomes"></i></a>
    <a href="#" class="btn btn-sm btn-success mr-1 float-right" ><i class="fa fa-download" data-toggle="tooltip" data-title="Download Balance sheet report"></i></a>
    <a href="#" class="btn btn-sm btn-success mr-1 float-right" data-toggle="modal" data-target="#incomemodal"><i class="fa fa-arrow-down" data-toggle="tooltip" data-title="Acquire New Income"></i></a>
</div>
</div>
<div class="card shadow-lg">
    <div class="card-header p-1 bg-success text-sm">
         Financial Overview 
    </div>
        
        <div class="card-body text-center" style="font-family:lucida sans serif;font-size:12px">
            
             <div class="row">
              <div class="col">
                <span class="text-bold">Expected Income</span><br>
                <?=$annualbudget->expectedIncome()?>
              </div>
              <div class="col">
                <span class="text-bold ">Total Revenue</span><br>
                <?=$annualbudget->totalIncome()?>
              </div>
              <div class="col">
                <span class="text-bold ">Deficit</span><br>
                <?=$annualbudget->deficit()?>
              </div>
              <div class="col">
                <span class="text-bold ">Unallocated</span><br>
                <?=$annualbudget->unallocated()?>
              </div>
              <div class="col">
                <span class="text-bold ">Balance</span><br>
                <?=$annualbudget->getBalance()?>
              </div>
              <div class="col">
                <span class="text-bold ">Total Expenses</span><br>
                <?=$annualbudget->getTotalExpenses()?>
              </div>

             </div>

        </div>
  
  
</div>

 <div class="card shadow-lg">
    <div class="card-header p-1 bg-success text-sm">
 
          
               
    </div>
        
        <div class="card-body " style="font-family:lucida sans serif;font-size:11.5px">
            
                    <div class="row text-bold mb-2"><div class="col-sm-1">#</div><div class="col">Branch</div><div class="col">Expected Income</div><div class="col">Total Revenue</div><div class="col">Deficit</div><div class="col">Balance</div><div class="col">Total Expenses</div><div class="col"></div></div>
                    
                        <?php
                        $count=0;
                        $branchbudgets=$annualbudget->branchAnnualBudgets;
  
                          foreach($branchbudgets as $bbudget)
                          {
                        ?>
                          <div class="row"><div class="col-sm-1"><?=++$count?></div><div class="col"><?=$bbudget->branch0->branch_short?></div><div class="col"><?=$bbudget->expectedIncome()?></div><div class="col"><?=$bbudget->totalIncome()?></div><div class="col"><?=$bbudget->deficit()?></div><div class="col"><?=$bbudget->getBalance()?></div><div class="col"><?=$bbudget->getTotalExpenses()?></div>
                          <div class="col">
                          <a href="<?=Url::to(['/finance/branch-finance','budget'=>urlencode(base64_encode($bbudget->bbID))])?>" data-toggle="tooltip" data-title="Go To Branch Budget"><i class="fa fa-arrow-circle-right text-success" style="font-size:20px"></i></a>
                          </div></div>
                        <?php
                          }

                        ?>
                        
        </div>
  
  <?=$this->render('newincome')?>
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
