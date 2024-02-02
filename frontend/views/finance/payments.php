
<?php
use common\models\Meeting;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


$this->params["pageTitle"]="Payments";
?>
<div class="container-fluid mt-3 meet">
      
<div class="card shadow-lg" >
    <div class="card-header p-1 bg-success text-sm text-center">
         <span class="ml-2 text-bold"><?=$item->proj->budgetItem?>  -  <?=$item->itemName?></span>
         <?php if($item->balance()>0 && $item->proj->branchbudget0->hasAuthority() && (yii::$app->user->can('TREASURER HQ') || yii::$app->user->can('TREASURER BR'))){ ?>
         <span class="float-right text-white btn btn-sm border border-white shadow" data-toggle="modal" data-target="#paymentmodal"><i class="fa fa-money" aria-hidden="true"></i> Make Payment</span>
         <?php } ?>
    </div>
        
        <div class="card-body text-center text-sm" style="font-family:lucida sans serif;">
            
        <div class="row text-bold">
            <span  class="col">Unit cost</span >
            <span  class="col">Available No. units</span >
            <span  class="col">Total allocated</span >
            <span  class="col">Expenses</span >
            <span  class="col">Available budget</span >
         </div>
            <div class="row text-center text-sm">
            <span  class="col"><?=$item->unitcost?></span>
            <span  class="col"><?=$item->availableunits()?> (<?=$item->unit?>)</span>
            <span  class="col"><?=$item->totalcost?> TZS</span>
            <span  class="col"><?=$item->getTotalExpenses()?> TZS</span>
            <span  class="col"><?=$item->balance()?> TZS</span>

         </div>

        </div>
        <div class="card-footer text-sm pl-4 border-top" style="background-color:#eef;font-family:lucida sans serif;">
        <div class="row text-bold text-sm text-center">
            <span  class="col text-success mb-3">Payment Transactions</span>
         </div>
        <div class="row text-bold text-sm border-bottom">
            <span  class="col">Reference</span >
            <span  class="col">Date & Time</span >
            <span  class="col">Quantity</span >
            <span  class="col">Amount</span >
            <span  class="col">status</span >
         </div>
           <?php 
            $payables=$item->payabletransactions;
            foreach($payables as $payable)
            {
            
            
           ?>
            <div class="row text-sm border-bottom">
            <span  class="col"><?=$payable->reference()?></span >
            <span  class="col"><?=date_format(date_create($payable->dateapplied),"d-m-Y H:i:s")?></span >
            <span  class="col"><?=$payable->quantity?></span >
            <span  class="col"><?=$payable->Amount?> TZS</span >
            <span  class="col"><?=$payable->status()?></span >
         </div>
           <?php 
           }
           ?>
         </div>

        </div>
  
      <?=$this->render('newpayment')?>
</div>


 <?php
$script = <<<JS
    $('document').ready(function(){
    $('.finance').addClass("active");
    
   })
JS;
$this->registerJs($script);
?>
