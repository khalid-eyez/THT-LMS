
<?php
use common\models\Meeting;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


$this->params["pageTitle"]="Accounts";
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
    <div class="card-body text-center" style="font-size:12px">
    <span class="text-lg text-success">Financial Year <?=$annualbudget->budget->year->title?> (<?=$annualbudget->branch0->branch_short?>)</span>
</div>
</div>
<div class="accordion" id="itemsaccordion">
<?php
  $budgetItems=$annualbudget->budgetprojections;
  foreach($budgetItems as $budgetItem)
  {
?>
<div class="card shadow" data-toggle="collapse" data-target="#collapse<?=$budgetItem->projID?>" aria-expanded="true" aria-controls="collapse<?=$budgetItem->projID?>">
    <div class="card-header p-1 bg-success ">
         <span class="ml-2"><i class="fas fa-wallet"></i> <?=$budgetItem->budgetItem?></span>
    </div>
        
        <div class="card-body text-center text-sm" style="">
            
             <div class="row">
              <div class="col">
                <span class="heading">Projected</span><br>
                <span class="money"><?=yii::$app->MoneyFormatter->format($budgetItem->projected())?></span>
              </div>
              <div class="col">
                <span class="heading">Current Budget</span><br>
                <span class="money"><?=yii::$app->MoneyFormatter->format($budgetItem->allocated())?></span>
              </div>
              <div class="col">
                <span class="heading">Deficit</span><br>
                <span class="money"><?=yii::$app->MoneyFormatter->format($budgetItem->deficit())?></span>
              </div>
              <div class="col">
                <span class="heading">Balance</span><br>
                <span class="money"><?=yii::$app->MoneyFormatter->format($budgetItem->balance())?></span>
              </div>
              <div class="col">
                <span class="heading">Total Expenses</span><br>
                <span class="money"><?=yii::$app->MoneyFormatter->format($budgetItem->getTotalExpenses())?></span>
              </div>

             </div>

        </div>
        <div id="collapse<?=$budgetItem->projID?>" class="collapse" aria-labelledby="heading<?=$budgetItem->projID?>" data-parent="#itemsaccordion">
        <div class="card-footer text-sm pl-4 border-top" style="background-color:#eef">
        <div class="row text-bold money border-bottom">
            <div class="col-sm-1">#</div>
            <div class="col">Item</div>
            <div class="col">Unit</div>
            <div class="col">Unit cost</div>
            <div class="col">No. units</div>
            <div class="col">Total allocated</div>
            <div class="col">Expenses</div>
            <div class="col">Available</div>
            
            <div class="col"></div>
         </div>
         <?php
           $itemizedprojs=$budgetItem->itemizedprojections;
           $count=0;
           foreach($itemizedprojs as $itemizedproj)
           {
         ?>
              <div class="row border-bottom money">
            <div class="col-sm-1"><?=++$count?></div>
            <div class="col"><?=$itemizedproj->itemName?></div>
            <div class="col"><?=$itemizedproj->unit?></div>
            <div class="col"><?=yii::$app->MoneyFormatter->format($itemizedproj->unitcost)?></div>
            <div class="col"><?=$itemizedproj->numUnits?></div>
            <div class="col"><?=yii::$app->MoneyFormatter->format($itemizedproj->totalcost)?></div>
            <div class="col"><?=yii::$app->MoneyFormatter->format($itemizedproj->getTotalExpenses())?></div>
            <div class="col"><?=yii::$app->MoneyFormatter->format($itemizedproj->balance())?></div>
            
            <div class="col">
               <a href="<?=Url::to(['/finance/payments','item'=>urlencode(base64_encode($itemizedproj->ipID))])?>"><i class="fa fa-arrow-right btn btn-success p-0"></i></a>
            </div>
         </div>
         <?php } ?>

         </div>

        </div>
  
</div>

<?php } ?>
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
