
<?php
use common\models\Meeting;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


$this->params["pageTitle"]="Finance dashboard";
?>
<div class="container-fluid mt-3 meet">
        <?php
          if($annualbudget==null)
          {
        ?>
        <div class="card shadow-lg">
        <div class="card-header p-1 bg-success text-sm">
               
          </div>
          <div class="card-body text-center text-lg text-success">
            No budget found
          </div>
        </div>
          <?php
          return false;
           }
          ?>
<div class="card shadow-lg">
<div class="card-header p-1 bg-success text-sm">
              
 </div>
    <div class="card-body text-center" style="font-family:lucida sans serif;font-size:12px">
    <span class="text-lg text-success">Financial Year <?=$annualbudget->budget->year->title?> (<?=$annualbudget->branch0->branch_short?>)</span>
    <?php if(!$annualbudget->budget->isOpen()){?>
      <span class="text-danger">Closed</span>
    <?php }else{ ?>
    <span class="text-primary">Open</span>
    <?php } ?>
    <a href="#" class="btn btn-sm btn-success mr-1 float-right" ><i class="fa fa-download" data-toggle="tooltip" data-title="Download Balance sheet report"></i></a>
    <a href="<?=Url::to(['/finance/branch-monthly-incomes','budget'=>urlencode(base64_encode($annualbudget->bbID))])?>" class="btn btn-sm btn-success mr-1 float-right" ><i class="fa fa-calendar" data-toggle="tooltip" data-title="Branch Monthly Revenues"></i></a>
    <a href="<?=Url::to(['/finance/branch-accounts','budget'=>urlencode(base64_encode($annualbudget->bbID))])?>" class="btn btn-sm btn-success mr-1 float-right" ><i class="fa fa-arrow-right" data-toggle="tooltip" data-title="Go To Accounts"></i></a>
    <?php if($annualbudget->hasAuthority()){?>
    <a href="<?=Url::to(['/finance/branch-income-allocate','budget'=>urlencode(base64_encode($annualbudget->bbID))])?>" class="btn btn-sm btn-success mr-1 float-right"><i class="fas fa-donate" data-toggle="tooltip" data-title="Allocate Budget"></i></a>
    <?php if($annualbudget->budget->isOpen()){?>
    <a href="#" class="btn btn-sm btn-success mr-1 float-right" data-toggle="modal" data-target="#budgetitemmodal"><i class="fa fa-plus-circle" data-toggle="tooltip" data-title="Add budget Item"></i></a>
    <?php } } ?>
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
                <span class="text-bold">Projected</span><br>
                <?=$annualbudget->projected()?>
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
            
                    <div class="row text-bold mb-2"><div class="col-sm-1">#</div><div class="col">Budget Item</div><div class="col">projection</div><div class="col">Current Budget</div><div class="col">Deficit</div><div class="col">Balance</div><div class="col">Total Expenses</div><div class="col"></div></div>
                    
                        <?php
                        $count=0;
                        $projections=$annualbudget->budgetprojections;
  
                          foreach($projections as $projection)
                          {
                        ?>
                          <div class="row"><div class="col-sm-1"><?=++$count?></div><div class="col"><?=$projection->budgetItem?></div><div class="col"><?=$projection->projected_amount?></div><div class="col"><?=$projection->allocated()?></div><div class="col"><?=$projection->deficit()?></div><div class="col"><?=$projection->balance()?></div><div class="col"><?=$projection->getTotalExpenses()?></div>
                          <div class="col">
                          <a href="<?=Url::to(['/finance/budget-item','item'=>urlencode(base64_encode($projection->projID))])?>" data-toggle="tooltip" data-title="Go To Budget Item"><i class="fa fa-arrow-circle-right text-success" style="font-size:20px"></i></a>
                          </div></div>
                        <?php
                          }

                        ?>
                        
        </div>
</div>
<?=$this->render('newBudgetItem')?>
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
