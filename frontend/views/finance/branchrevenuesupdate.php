
<?php
use common\models\Meeting;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


$this->params["pageTitle"]="Branch Monthly Revenues Update";
?>
<div class="container-fluid mt-3 meet">
        
<div class="card shadow-lg">
<div class="card-header p-1  text-md  bg-success text-center">
   <div class="row">
      <div class="col-sm-6"></div>
      <div class="col-sm-6">
         <div class="row">
         <div class="col-sm-6">
         <strong>Total Income: </strong>
          <span class="inc"> <?=$income->receivedAmount?></span> TZS
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
<?php $form=Activeform::begin() ?>
   <?php
    $branchrevenues=$income->branchMonthlyRevenues;
 
    foreach($branchrevenues as $branchrevenue)
    {
   ?>
   <div class="row mb-1">
      <div class="col-sm-6 p-1 pl-3 " style="background-color:#dde">
     <?=$branchrevenue->branchbudget0->branch0->branch_short?>
    </div>
    <div class="col-sm-6">
    <input type="text" name=<?=$branchrevenue->revenueID?> class="form-control incput" style="border:none; background-color:#eef" value=<?=$branchrevenue->received_amount ?>></input>
    </div>
    </div>

   <?php 
    }

   ?>
   <button type="submit" class="btn btn-success btn-sm float-right mt-3"><i class="fa fa-save"> Save</i></button>
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
         Swal.fire('warning','allocation greater than income');
       }
       else
       {
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