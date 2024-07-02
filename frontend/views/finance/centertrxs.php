
<?php
use common\models\Meeting;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


$this->params["pageTitle"]="Cost Center TRXS";
?>
<div class="container-fluid mt-3 meet">
        
<div class="card shadow-sm">
<div class="card-header p-1 bg-success text-sm">
              
 </div>
    <div class="card-body text-sm" style="">
    <div class="row">
      <span class="col">
    <span class="text-bold">Month</span>

</span><span class="col">
    <span class="text-bold">Amount</span>
</span>
<span class="col">
    <span class="text-bold text-center">Date & Time</span>
</span>
</div>
    </div>
</div>
<div class="container-fluid money shadow">
<?php 
$trxs=$center->costcenterrevenues;
foreach($trxs as $trx)
{
?>


            
             <div class="row border-top p-1">
              <div class="col">
                <?=date_format(date_create($trx->datereceived),"M")?>
              </div>
              <div class="col">
                <?=$trx->amount?> TZS
              </div>
              <div class="col">
                <?=date_format(date_create($trx->datereceived),"d-m-Y H:i:s")?>
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
