
<?php
use common\models\Meeting;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Budgetyear;


$this->params["pageTitle"]="Cost Center Finance";
?>
<div class="container-fluid mt-3 meet">
       
<div class="card shadow-lg">
<div class="card-header p-1 bg-success text-sm">
              
 </div>
    <div class="card-body text-center" style="font-size:12px">
    <form class="float-left" method="post" action="/finance/switch-financial-year">
      <div class="form-group row">
        <div class="col-sm-10">
      <select class="form-control pl-1" name="year">
        <?php
          $financialyears=Budgetyear::find()->orderBy(['yearID'=>SORT_DESC])->all();
          foreach($financialyears as $financialyear)
          {
        ?>
        <option value=<?=$financialyear->yearID?> <?=($financialyear->yearID==yii::$app->session->get("financialYear")->yearID)?"selected":""?>>Financial Year <?=$financialyear->startingyear?></option>
        <?php } ?>
      </select>
</div><div class="col-sm-2 p-0">
<input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
      <button type="submit" class="form-control p-2"><i class="fa fa-refresh"></i></button>
      </div>
      </div>
    </form> 
    <span class="text-lg text-success">[ <?=$center->branch0->branch_short?> ][ <?=$center->name?> ]</span>
    <a href="#" class="btn btn-sm btn-success mr-1 float-right" ><i class="fa fa-download" data-toggle="tooltip" data-title="Download report"></i></a>
    <a href="<?=Url::to(['/finance/center-trxs','center'=>urlencode(base64_encode($center->centerID))])?>" class="btn btn-sm btn-success mr-1 float-right" ><i class="fa fa-calendar" data-toggle="tooltip" data-title="Center Budget Transactions"></i></a>
    <a href="<?=Url::to(['/finance/cc-income-allocate','center'=>urlencode(base64_encode($center->centerID))])?>" class="btn btn-sm btn-success mr-1 float-right"><i class="fas fa-donate" data-toggle="tooltip" data-title="Allocate Funds"></i></a>
    <a href="#" class="btn btn-sm btn-success mr-1 float-right" data-toggle="modal" data-target="#budgetitemmodal"><i class="fa fa-plus-circle" data-toggle="tooltip" data-title="Add budget Item"></i></a>
</div>
</div>
<div class="card shadow">
    <div class="card-header p-1 bg-success text-sm pl-2">
         <i class="fa fa-money"></i> Financial Overview 
    </div>
        
        <div class="card-body text-center" style="font-size:12px">
            
             <div class="row">
           
              <div class="col">
                <span class="heading">Total Projection</span><br>
                <span class="money"><?=yii::$app->MoneyFormatter->format($center->totalProjection())?></span>
              </div>
              <div class="col">
                <span class="heading">Total Revenue</span><br>
                <span class="money"><?=yii::$app->MoneyFormatter->format($center->currentBudget())?></span>
              </div>
              <div class="col">
                <span class="heading">Allocated</span><br>
                <span class="money"><?=yii::$app->MoneyFormatter->format($center->allocatedBudget())?></span>
              </div>
              <div class="col">
                <span class="heading">Unallocated</span><br>
                <span class="money"><?=yii::$app->MoneyFormatter->format($center->unallocated())?></span>
              </div>
              <div class="col">
                <span class="heading">Total Expenses</span><br>
                <span class="money"><?=yii::$app->MoneyFormatter->format($center->totalexpenses())?></span>
              </div>
              <div class="col">
                <span class="heading">Balance</span><br>
                <span class="money"><?=yii::$app->MoneyFormatter->format($center->balance())?></span>
              </div>
           

             </div>

        </div>
  
  
</div>

 <div class="card shadow">
    <div class="card-header p-1 bg-success text-sm pl-2">
 
    <i class="fa fa-building"></i> Center Budget
           <a href="<?=Url::to(['/finance/cc-budget-review','center'=>$_GET['center']])?>"><i class="fa fa-edit float-right btn btn-default mt-1 p-1" data-toggle="tooltip" data-title="Review Budget"></i></a>    
    </div>
        
        <div class="card-body " style="font-size:12px">
            
                    <div class="row heading mb-2"><div class="col-sm-1">#</div><div class="col">Budget Item</div><div class="col">projection</div><div class="col">Current Budget</div><div class="col">Deficit</div><div class="col">Balance</div><div class="col">Total Expenses</div><div class="col"></div></div>
                    
                        <?php
                        $count=0;
                        $projections=$center->getYearProjections();
  
                          foreach($projections as $projection)
                          {
                        ?>
                          <div class="row money"><div class="col-sm-1"><?=++$count?></div><div class="col"><?=$projection->projection0->budgetItem?></div>
                          <div class="col"><?=yii::$app->MoneyFormatter->format($projection->projection0->projected_amount)?></div>
                          <div class="col"><?=yii::$app->MoneyFormatter->format($projection->projection0->allocated())?></div>
                          <div class="col"><?=yii::$app->MoneyFormatter->format($projection->projection0->deficit())?></div>
                          <div class="col"><?=yii::$app->MoneyFormatter->format($projection->projection0->balance())?></div>
                          <div class="col"><?=yii::$app->MoneyFormatter->format($projection->projection0->getTotalExpenses())?></div>
                          <div class="col">
                          <a href="<?=Url::to(['/finance/budget-item','item'=>urlencode(base64_encode($projection->projection0->projID))])?>" data-toggle="tooltip" data-title="Go To Budget Item">
                            <i class="fa fa-arrow-right fa-1x btn btn-success p-1 btn-sm m-1 text-sm" style="font-size:20px"></i></a>
                          <a href="#" class="bdel" id=<?=$projection->projection0->projID?> data-toggle="tooltip" data-title="Delete Budget Item"><i class="fa fa-trash btn btn-danger btn-sm p-1 "></i></a>
                          </div></div>
                        <?php
                          }

                        ?>
                        
        </div>
</div>
<?=$this->render('newCBudgetItem')?>
</div>
</div>

 <?php
$script = <<<JS
    $('document').ready(function(){
    $('.finance').addClass("active");
    
    $(document).on('click', '.bdel', function(){
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
  url:'/finance/delete-budget-item',
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
