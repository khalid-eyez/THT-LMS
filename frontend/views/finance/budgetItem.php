
<?php
use common\models\Meeting;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


$this->params["pageTitle"]="Budget Item";
?>
<div class="container-fluid mt-3 meet">
        
<div class="card shadow-lg">
    <div class="card-header p-1 bg-success">
         <span class="ml-2"><i class="fas fa-wallet"></i> <?=$budgetItem->budgetItem?> - Allocations</span>
         <a href="<?=Url::to(['/finance/item-allocations','item'=>urlencode(base64_encode($budgetItem->projID))])?>" class="mr-2 float-right" data-toggle="tooltip" data-title="All Allocations"><i class="fa fa-donate btn btn-default btn-sm p-1"></i></a>
         <a href="<?=Url::to(['/finance/budget-projection-itemizer','projection'=>urlencode(base64_encode($budgetItem->projID))])?>" class="mr-2 float-right" data-toggle="tooltip" data-title="Update Budget Item Structure"><i class="fa fa-arrow-right btn btn-default btn-sm p-1"></i></a>
    </div>
        
        <div class="card-body text-center " style="font-size:13px">
            
             <div class="row">
              <div class="col">
                <span class="heading">Projected</span><br>
                <span class="money"><?=yii::$app->MoneyFormatter->format($budgetItem->projected_amount)?></span>
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
        <div class="card-footer  pl-4 border-top" style="background-color:#eef;font-size:13px">
        <div class="row text-bold border-bottom">
            <div class="col-sm-1">#</div>
            <div class="col">Item</div>
            <div class="col">Unit</div>
            <div class="col">Unit cost</div>
            <div class="col">No. units</div>
            <div class="col">Tot. allocated</div>
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
              <div class="row money border-bottom ">
            <div class="col-sm-1"><?=++$count?></div>
            <div class="col"><?=$itemizedproj->itemName?></div>
            <div class="col"><?=$itemizedproj->unit?></div>
            <div class="col"><?=yii::$app->MoneyFormatter->format($itemizedproj->unitcost)?></div>
            <div class="col"><?=$itemizedproj->numUnits?></div>
            <div class="col"><?=yii::$app->MoneyFormatter->format($itemizedproj->totalcost)?></div>
            <div class="col"><?=yii::$app->MoneyFormatter->format($itemizedproj->getTotalExpenses())?></div>
            <div class="col"><?=yii::$app->MoneyFormatter->format($itemizedproj->balance())?></div>
            
            <div class="col">
               <a href="<?=Url::to(['/finance/payments','item'=>urlencode(base64_encode($itemizedproj->ipID))])?>" data-toggle="tooltip" data-title="Payments"><i class="fa fa-arrow-right btn btn-success p-1"></i></a>
               <a href="#" id=<?=$itemizedproj->ipID?> data-toggle="tooltip" data-title="Delete item" class="idel"><i class="fa fa-trash btn btn-danger p-1"></i> </a>
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
    
    $(document).on('click', '.idel', function(){
  var id = $(this).attr('id');
  Swal.fire({
  title: 'Delete Item?',
  text: "You won't be able to revert this!",
  icon: 'question',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Delete'
  }).then((result) => {
  if (result.isConfirmed) {

  $.ajax({
  url:'/finance/delete-projection-structure-item',
  method:'post',
  async:false,
  dataType:'JSON',
  data:{item:id},
  success:function(data){
  if(data.success){
  Swal.fire(
  'Done !',
  data.success,
  'success'
  )
  setTimeout(function(){
  window.location.reload();
  }, 100);


  }
  else
  {
  Swal.fire(
  'Failed!',
  data.failure,
  'error'
  )


  }
  }
  })

  }
  })

  }) 

   })
JS;
$this->registerJs($script);
?>
