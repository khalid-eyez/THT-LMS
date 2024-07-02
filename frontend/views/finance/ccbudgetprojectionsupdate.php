
<?php
use common\models\Meeting;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


$this->params["pageTitle"]="Cost Center Budget Allocations";
?>
<div class="container-fluid mt-3 meet">
        
<div class="card shadow-lg">
<div class="card-header p-1 text-md  bg-success text-center money">
   <div class="row">
      <div class="col-sm-6"></div>
      <div class="col-sm-6">
         <div class="row">
         <div class="col-sm-6">
         <strong>Total Available: </strong>
          <span class="inc"> <?=$center->unallocated()?></span> TZS
         </div>
         <div class="col-sm-6">
          <strong>Unallocated:</strong>
          <span class="unl">0</span> TZS
         </div>
         </div>

      </div>

   </div>
</div>
<div class="card-body">
<div class="row mb-1 p-0">
      <div class="col-sm-6 p-1 pl-3 p-0" style="background-color:#eef">
     <div class="row text-bold text-muted p-0 ">
     <span class="col-sm-4 border-right border-white">Item</span>
      <span class="col border-right border-white">projected</span>
      <span class="col border-right border-white">allocated</span>
      <span class="col border-right border-white">deficit</span>
      <span class="col border-right border-white">balance</span>
   </div>
    </div>
    <div class="col-sm-6 pl-3">
    <div class="row text-bold text-muted p-0 m-0" >
     <span class="col text-center p-0">Amounts</span>
    </div>
    </div></div>
<?php $form=Activeform::begin() ?>
   <?php
    $ccprojections=$center->getYearProjections();
 
    foreach($ccprojections as $ccprojection)
    {
      $projection=$ccprojection->projection0;
   ?>
   <div class="row mb-1 money">
      <div class="col-sm-6 p-1 pl-3 " style="background-color:#dde">
      <div class="row">
     <span class="col-sm-4 text-bold text-muted"><?=$projection->budgetItem?></span>
      <span class="col"><?=$projection->projected_amount?></span>
      <span class="col"><?=$projection->allocated()?></span>
      <span class="col"><?=$projection->deficit()?></span>
      <span class="col"><?=$projection->balance()?></span>
   </div>
    </div>
    <div class="col-sm-6">
    <input type="text" name=<?=$projection->projID?> class="form-control incput" value=<?=abs($projection->deficit())?> style="border:none; background-color:#eef" placeholder="Amount"></input>
    </div>
    </div>

   <?php 
    }

   ?>
   <button type="submit" class="btn btn-success btn-sm float-right mt-3 sb"><i class="fa fa-save"> Save</i></button>
   <?php $form=Activeform::end() ?>
</div>
</div>

<?php
$script = <<<JS
    $('document').ready(function(){
    $('.finance').addClass("active");
    
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
         $('.sb').prop('disabled',true);
         Swal.fire('warning','allocation greater than available funds');
       }
       else
       {
         $('.sb').prop('disabled',false);
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