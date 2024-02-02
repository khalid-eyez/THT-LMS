
<?php
use common\models\Meeting;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


$this->params["pageTitle"]="Budget Item Allocations";
?>
<div class="container-fluid mt-3 meet">
        
<div class="card shadow-lg">
    <div class="card-header p-1 bg-success text-sm text-center">
         <span class="ml-2 text-bold"><?=ucfirst($item->budgetItem)?> (Allocations)</span>
    </div>
        
        <div class="card-body text-center text-sm" style="font-family:lucida sans serif;">
            
             <div class="row">
              <div class="col">
                <span class="text-bold ">Allocated</span><br>
                <?=$item->allocated()?>
              </div>
              <div class="col">
                <span class="text-bold ">Deficit</span><br>
                <?=$item->deficit()?>
              </div>

             </div>

        </div>
        <div class="card-footer text-sm pl-4 border-top" style="background-color:#eef">
        <div class="row text-bold border-bottom">
            <div class="col-sm-1">#</div>
            <div class="col">Amount</div>
            <div class="col">Date & Time</div>
         </div>
         <?php
           $receivables=$item->receivabletransactions;
           $count=0;
           foreach($receivables as $receivable)
           {
         ?>
              <div class="row border-bottom ">
            <div class="col-sm-1"><?=++$count?></div>
            <div class="col"><?=$receivable->receivedamount?></div>
            <div class="col"><?=date_format(date_create($receivable->datereceived),"d-m-Y H:i:s")?></div>
          
            
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
