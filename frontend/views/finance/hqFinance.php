
<?php
use common\models\Meeting;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Budgetyear;


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
    <span class="text-lg text-success">[ <?=$annualbudget->branch0->branch_short?> ]</span>
    <?php if(!$annualbudget->budget->isOpen()){?>
      <span class="text-danger">Closed</span>
    <?php }else{ ?>
    <span class="text-primary">Open</span>
    <?php } ?>
    <a href="#" class="btn btn-sm btn-success mr-1 float-right" ><i class="fa fa-download" data-toggle="tooltip" data-title="Download Balance sheet report"></i></a>
    <a href="<?=Url::to(['/finance/branch-monthly-incomes','budget'=>urlencode(base64_encode($annualbudget->bbID))])?>" class="btn btn-sm btn-success mr-1 float-right" ><i class="fa fa-calendar" data-toggle="tooltip" data-title="Branch Monthly Revenues"></i></a>
    <a href="<?=Url::to(['/finance/center-budget-allocate','budget'=>urlencode(base64_encode($annualbudget->bbID))])?>" class="btn btn-sm btn-success mr-1 float-right"><i class="fas fa-donate" data-toggle="tooltip" data-title="Allocate Budget"></i></a>
    <a href="<?=Url::to(['/finance/costcenters-allocations-review','budget'=>urlencode(base64_encode($annualbudget->bbID))])?>" class="btn btn-sm btn-success mr-1 float-right"><i class="fa fa-edit " data-toggle="tooltip" data-title="Review Budget"></i></a> 
</div>
</div>
<div class="card shadow">
    <div class="card-header p-1 bg-success text-sm pl-2">
         <i class="fa fa-money"></i> Financial Overview 
    </div>
        
        <div class="card-body text-center" style="font-size:12px">
            
             <div class="row">
              <div class="col">
                <span class="heading">Total Revenue</span><br>
                <span class="money"><?=yii::$app->MoneyFormatter->format($annualbudget->branchTotalRevenue())?></span>
              </div>
              <div class="col">
                <span class="heading">Allocated</span><br>
                <span class="money"><?=yii::$app->MoneyFormatter->format($annualbudget->allocated())?></span>
              </div>
        
              <div class="col">
                <span class="heading">Unallocated</span><br>
                <span class="money"><?=yii::$app->MoneyFormatter->format($annualbudget->unallocated())?></span>
              </div>
              <div class="col">
                <span class="heading">Total Expenses</span><br>
                <span class="money"><?=yii::$app->MoneyFormatter->format($annualbudget->branch0->centersTotalExpenses())?></span>
              </div>
              <div class="col">
                <span class="heading">Balance</span><br>
                <span class="money"><?=yii::$app->MoneyFormatter->format($annualbudget->getBalance())?></span>
              </div>
           

             </div>

        </div>
  
  
</div>

 <div class="card shadow">
    <div class="card-header p-1 bg-success text-sm pl-2">
 
    <i class="fa fa-building"></i> Cost Centers
              
    </div>
        
        <div class="card-body " style="font-size:12px">
            
                    <div class="row heading mb-2"><div class="col-sm-1">#</div><div class="col-sm-3">Center</div><div class="col-sm-2">projection</div><div class="col-sm-2 ">Current Budget</div><div class="col-sm-1">Deficit</div><div class="col-sm-1">Total Expenses</div><div class="col-sm-1">Balance</div><div class="col-sm-1"></div></div>
                    
                        <?php
                        $count=0;
                        $centers=$annualbudget->branch0->costcenters;
  
                          foreach($centers as $center)
                          {
                        ?>
                          <div class="row money"><div class="col-sm-1"><?=++$count?></div><div class="col-sm-3"><?=$center->name?></div>
                          <div class="col-sm-2"><?=yii::$app->MoneyFormatter->format($center->totalProjection())?></div>
                          <div class="col-sm-2"><?=yii::$app->MoneyFormatter->format($center->currentBudget())?></div>
                          <div class="col-sm-1"><?=yii::$app->MoneyFormatter->format($center->deficit())?></div>
                          <div class="col-sm-1"><?=yii::$app->MoneyFormatter->format($center->totalexpenses())?></div>
                          <div class="col-sm-1"><?=yii::$app->MoneyFormatter->format($center->balance())?></div>
                          <div class="col-sm-1">
                          <a href="#" data-toggle="tooltip" data-title="Go To Cost Center"><i class="fa fa-arrow-right fa-1x btn btn-success p-1 btn-sm m-1 text-sm" style="font-size:20px"></i></a>
                          </div></div>
                        <?php
                          }

                        ?>
                        
        </div>
</div>
<?=$this->render('newBudgetItem')?>
<?=$this->render('branchotherincome')?>
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
