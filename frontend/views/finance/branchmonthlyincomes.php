
<?php
use common\models\Meeting;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


$this->params["pageTitle"]="Branch Monthly Incomes";
?>
<div class="container-fluid mt-3 meet">
        
<div class="card shadow-lg">
<div class="card-header p-1 bg-success text-sm">
              
 </div>
    <div class="card-body text-center text-sm" style="font-family:lucida sans serif;">
    <div class="row">
      <span class="col text-right pr-2">
    <span class="text-bold text-center">Total Received</span><br>
    <span class="text-center"><?=$annualbudget->totalIncome()?> TZS</span>
</span><span class="col text-left pl-3">
    <span class="text-bold text-center">Deficit</span><br>
    <span class="text-center"><?=$annualbudget->deficit()?> TZS</span>
</span></div>
    </div>
</div>
<?php 
$incomes=$annualbudget->branchMonthlyRevenues;
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
                <?=$income->received_amount?> TZS
              </div>
              <div class="col">
                <span class="text-bold ">Date & Time</span><br>
                <?=date_format(date_create($income->income->datereceived),"d-m-Y H:i:s")?>
              </div>
            

             </div>

        </div>
      
       </div>

<?php } ?>
</div>


 <?php
$script = <<<JS
    $('document').ready(function(){
    $('.finance').addClass("active");
    
  

   })
JS;
$this->registerJs($script);
?>
