
<?php
use common\models\Meeting;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


$this->params["pageTitle"]="Monthly Incomes";
?>
<div class="container-fluid mt-3 meet">
        
<div class="card shadow-lg">
<div class="card-header p-1 bg-success text-sm">
              
 </div>
    <div class="card-body text-center" style="font-family:lucida sans serif;font-size:12px">
    <span class="text-lg text-success">Financial Year <?=$annualbudget->year->title?></span>
    </div>
</div>
<div class="accordion" id="incomeaccordion">
<?php 
$incomes=$annualbudget->monthlyincomes;
foreach($incomes as $income)
{
?>
<div class="card shadow-lg" data-toggle="collapse" data-target="#collapse<?=$income->incomeID?>" aria-expanded="true" aria-controls="collapse<?=$income->incomeID?>">
    <div class="card-header p-1 bg-success text-sm">
    </div>
        
        <div class="card-body text-center text-sm" style="font-family:lucida sans serif">
            
             <div class="row">
              <div class="col">
                <span class="text-bold">Month</span><br>
                <?=$income->month?>
              </div>
              <div class="col">
                <span class="text-bold ">Amount Received</span><br>
                <?=$income->receivedAmount?> TZS
              </div>
              <div class="col">
                <span class="text-bold ">Date & Time</span><br>
                <?=date_format(date_create($income->datereceived),"d-m-Y H:i:s")?>
              </div>
            

             </div>

        </div>
        <div id="collapse<?=$income->incomeID?>" class="collapse" aria-labelledby="heading<?=$income->incomeID?>" data-parent="#incomeaccordion">

        <div class="card-footer text-sm pl-4 border-top" style="background-color:#eef;font-family:lucida sans serif">
        <div class="row text-bold text-success mb-3"><div class="col text-center">Branch Allocations</div></div>
        <div class="row text-bold border-bottom">
            <div class="col-sm-1">#</div>
            <div class="col">Branch</div>
            <div class="col">Amount</div>
            <div class="col">Date & Time</div>
         </div>
        

        <?php 
        $branchrevs=$income->branchMonthlyRevenues;
        $count=0;
        foreach($branchrevs as $branchrev)
        {
         ?>

         <div class="row text-sm border-bottom">
            <div class="col-sm-1"><?=++$count?></div>
            <div class="col"><?=$branchrev->branchbudget0->branch0->branch_short?></div>
            <div class="col"><?=$branchrev->received_amount?> TZS</div>
            <div class="col"><?=date_format(date_create($income->datereceived),"d-m-Y H:i:s")?></div>
         </div>
         <?php
        }
         ?>

       </div>
       </div>
  
</div>
<?php } ?>
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
