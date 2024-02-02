
<?php
use common\models\Meeting;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


$this->params["pageTitle"]="Budget Item";
?>
<div class="container-fluid mt-3 meet">
        
<div class="card shadow-lg">
    <div class="card-header p-1 bg-success text-sm">
         <span class="ml-2"><?=$budgetItem->budgetItem?></span>
         <a href="<?=Url::to(['/finance/item-allocations','item'=>urlencode(base64_encode($budgetItem->projID))])?>" class="mr-2 float-right" data-toggle="tooltip" data-title="All Allocations"><i class="fa fa-donate"></i></a>
    </div>
        
        <div class="card-body text-center text-sm" style="font-family:lucida sans serif;">
            
             <div class="row">
              <div class="col">
                <span class="text-bold">Projected</span><br>
                <?=$budgetItem->projected()?>
              </div>
              <div class="col">
                <span class="text-bold ">Current Budget</span><br>
                <?=$budgetItem->allocated()?>
              </div>
              <div class="col">
                <span class="text-bold ">Deficit</span><br>
                <?=$budgetItem->deficit()?>
              </div>
              <div class="col">
                <span class="text-bold ">Balance</span><br>
                <?=$budgetItem->balance()?>
              </div>
              <div class="col">
                <span class="text-bold ">Total Expenses</span><br>
                <?=$budgetItem->getTotalExpenses()?>
              </div>

             </div>

        </div>
        <div class="card-footer text-sm pl-4 border-top" style="background-color:#eef">
        <div class="row text-bold border-bottom">
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
              <div class="row border-bottom ">
            <div class="col-sm-1"><?=++$count?></div>
            <div class="col"><?=$itemizedproj->itemName?></div>
            <div class="col"><?=$itemizedproj->unit?></div>
            <div class="col"><?=$itemizedproj->unitcost?></div>
            <div class="col"><?=$itemizedproj->numUnits?></div>
            <div class="col"><?=$itemizedproj->totalcost?></div>
            <div class="col"><?=$itemizedproj->getTotalExpenses()?></div>
            <div class="col"><?=$itemizedproj->balance()?></div>
            
            <div class="col">
               <a href="<?=Url::to(['/finance/payments','item'=>urlencode(base64_encode($itemizedproj->ipID))])?>"><i class="fa fa-arrow-right btn btn-success p-0"></i></a>
            </div>
         </div>
         <?php } ?>

         </div>

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
