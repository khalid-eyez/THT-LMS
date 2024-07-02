
<?php
use common\models\Meeting;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


$this->params["pageTitle"]="Cost Center Budget Review [ ".$center->getCenterBudget()->budget->year->startingyear." ]";
?>
<div class="container-fluid mt-3 meet">
        
<div class="card shadow">
<div class="card-header p-1 bg-success text-center pl-3">
   <div class="row">
      <div class="col-sm-3"><span class="float-left"><i class="fa fa-edit"></i> Projections Review</span></div>
      <div class="col-sm-9">
         <div class="row">
         <div class="col-sm-5">
         <strong>Current Projection: </strong>
          <span class="cproj"> <?=$center->totalProjection()?></span> TZS
         </div>
         <div class="col-sm-6">
         <strong>Resulting Projection:</strong>
          <span class="rproj">0</span> TZS
         </div>
         <div class="col-sm-1">
         <a href="#" data-toggle="modal" data-target="#budgetitemmodal"><i class="fa fa-plus-circle btn btn-default p-1 float-right mr-2" data-toggle="tooltip" data-title="Add New Budget Item"></i></a>
         </div>
         </div>

      </div>

   </div>
</div>
<div class="card-body money">
<div class="row mb-1  p-0">
      <div class="col-sm-6 p-1 pl-3 p-0" style="background-color:#eef">
     <div class="row text-bold p-0">
     <span class="col-sm-4 border-right border-white">Item</span>
      <span class="col border-right border-white">Projected</span>
      <span class="col border-right border-white">Allocated</span>
      <span class="col border-right border-white">Deficit</span>
      <span class="col border-right border-white">Balance</span>
   </div>
    </div>
    <div class="col-sm-6 pl-3">
    <div class="row text-bold text-muted p-0 m-0" >
     <span class="col text-center p-0">Adjustment value [-x or +x ]</span>
    </div>
    </div></div>
<?php $projform=Activeform::begin() ?>
   <?php
    $projections=$center->getYearProjections();
 
    foreach($projections as $ccprojection)
    {
      $projection=$ccprojection->projection0;
   ?>
   <div class="row mb-1 ">
      <div class="col-sm-6 p-1 pl-3 " style="background-color:#dde">
      <div class="row">
   <a class="col-sm-4 heading  text-dark" href="<?=Url::to(['/finance/budget-item','item'=>urlencode(base64_encode($projection->projID))])?>" data-toggle="tooltip" data-title="Go To Item Structure"><i class="fa fa-link"></i> <?=$projection->budgetItem?></a>
      <span class="col"><?=$projection->projected_amount?></span>
      <span class="col"><?=$projection->allocated()?></span>
      <span class="col"><?=$projection->deficit()?></span>
      <span class="col"><?=$projection->balance()?></span>
   </div>
    </div>
    <div class="col-sm-6">
    <input type="text" name=<?=$projection->projID?> class="form-control projput" value=0 style="border:none; background-color:#eef" placeholder="Amount"></input>
    </div>
    </div>

   <?php 
    }

   ?>
   <button type="submit" class="btn btn-success btn-sm float-right mt-3"><i class="fa fa-save"></i> Save</button>
   <?php $projform=Activeform::end() ?>
</div>
</div>
<div class="card shadow">
<div class="card-header p-1  pl-3 text-md  bg-success text-center">
   <div class="row">
      <div class="col-sm-6"><span class="float-left"><i class="fa fa-edit"></i> Allocations Review</span></div>
      <div class="col-sm-6">
         <div class="row">
         <div class="col-sm-6">
         <strong>Total Available: </strong>
          <span class="inc"> <?=$center->balance()-$center->allocatedBudget()?></span> TZS
         </div>
         <div class="col-sm-6">
          <strong>Unallocated:</strong>
          <span class="unl">0</span> TZS
         </div>
         </div>

      </div>

   </div>
</div>
<div class="card-body money">
<div class="row mb-1  p-0">
      <div class="col-sm-6 p-1 pl-3 p-0" style="background-color:#eef">
     <div class="row text-bold  p-0">
     <span class="col-sm-4 border-right border-white">Item</span>
      <span class="col border-right border-white">projected</span>
      <span class="col border-right border-white">allocated</span>
      <span class="col border-right border-white">deficit</span>
      <span class="col border-right border-white">balance</span>
   </div>
    </div>
    <div class="col-sm-6 pl-3">
    <div class="row text-bold text-muted p-0 m-0" >
     <span class="col text-center p-0">Adjustment value [-x or +x ]</span>
    </div>
    </div></div>
<?php $form=Activeform::begin(['method'=>'post','action'=>"branch-income-allocate?budget=".urlencode(base64_encode($center->getCenterBudget()->bbID))]) ?>
   <?php
  $projections=$center->getYearProjections();
 
  foreach($projections as $ccprojection)
  {
    $projection=$ccprojection->projection0;
   ?>
   <div class="row mb-1 ">
      <div class="col-sm-6 p-1 pl-3 " style="background-color:#dde">
      <div class="row">
      <a class="col-sm-4 text-bold text-dark" href="<?=Url::to(['/finance/budget-item','item'=>urlencode(base64_encode($projection->projID))])?>" data-toggle="tooltip" data-title="Go To Item Structure"><i class="fa fa-link"></i> <?=$projection->budgetItem?></a>
      <span class="col"><?=$projection->projected_amount?></span>
      <span class="col"><?=$projection->allocated()?></span>
      <span class="col"><?=$projection->deficit()?></span>
      <span class="col"><?=$projection->balance()?></span>
   </div>
    </div>
    <div class="col-sm-6">
    <input type="text" name=<?=$projection->projID?> class="form-control incput " value=<?=abs($projection->deficit())?> style="border:none; background-color:#eef" placeholder="Amount"></input>
    </div>
    </div>

   <?php 
    }

   ?>
   <button type="submit" class="btn btn-success btn-sm float-right mt-3 allsub"><i class="fa fa-save"></i> Save</button>
   <?php $form=Activeform::end() ?>
</div>
</div>
<?=$this->render('newCBudgetItem')?>
<?php
$script = <<<JS
    $('document').ready(function(){
    $('.finance').addClass("active");

    function projections()
    {
      var inputs=$('.projput');
      var total=0;
       inputs.each(function(){

         total+=parseFloat($(this).val());
         
       });
      total+=parseFloat($(".cproj").text());
      $('.rproj').text(total);
    }
    $('.projput').keyup(function(){
      projections();
    })
    projections();
    function unallocated()
    {
      var inputs=$('.incput');
      var total=0;
       inputs.each(function(){

         total+=parseFloat($(this).val());
         
       });
       var income=$('.inc').text();
       
       var unl=parseFloat(income)-parseFloat(total);
       unl=(!isNaN(unl))?unl:0;
       unl=unl.toFixed(2);
       if(unl<0)
       {
         $('.allsub').prop('disabled',true);
         Swal.fire('warning','allocation greater than available funds');
       }
       else
       {
         $('.allsub').prop('disabled',false);
         $('.unl').text(unl);
       }
    }
    $('.incput').keyup(function(){

      unallocated();
    })
    unallocated();

   })
JS;
$this->registerJs($script);
?>