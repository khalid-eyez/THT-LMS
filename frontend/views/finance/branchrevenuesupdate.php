
<?php
use common\models\Meeting;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


$this->params["pageTitle"]="Branch Monthly Revenues Update";
?>
<div class="container-fluid mt-3 meet">
        
<div class="card shadow-lg">
<div class="card-header p-1  text-md  bg-success ">
   <div class="row">
      <div class="col-sm-6 text-bold pl-3"><i class="fa fa-building"></i> Branch Returns</div>
      <div class="col-sm-6 text-center">
         <div class="row ">
         <div class="col-sm-6">
         <strong>Total: </strong>
          <span class="inc"> <?=$income->branchReturnsTotal()?></span> TZS
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
<div class="row mb-1 border border-muted text-bold">
      <div class="col-sm-4 p-1 pl-3 " >
     Branch
    </div>
    <div class="col-sm-8">
      <div class="row"><div class="col-sm-3 p-1 pl-2">
          Total Amount
      </div>
      <div class="col-sm-3 p-1 pl-2">
      Total return
      </div>
      <div class="col-sm-3 p-1 pl-2">
      Total Contributions
      </div>
      <div class="col-sm-3 p-1 pl-2">
        Total Delivered 
    </div>
      </div>
    </div>
    </div>
<?php $form=Activeform::begin() ?>
   <?php
    $branchrevenues=$income->branchMonthlyRevenues;
 
    foreach($branchrevenues as $branchrevenue)
    {
      if($branchrevenue->branchbudget0->branch0->level=="HQ")
      {
         continue;
      }
      $membercount=$branchrevenue->branchbudget0->branch0->membersCount();
      $contribfactor=($income->spcontribution!=null)?$income->spcontribution->IndividualAmount:0;
      $branchcontrib=$membercount*$contribfactor;
   ?>
   <div class="row mb-1">
      <div class="col-sm-4 p-1 pl-3 " style="background-color:#dde">
     <?=$branchrevenue->branchbudget0->branch0->branch_short?>
    </div>
    <div class="col-sm-8">
      <div class="row"><div class="col-sm-3">
      <input type="text" name=<?=$branchrevenue->revenueID?> id="cal<?=$branchrevenue->revenueID?>" value=0.00 class="form-control total" style="border:none; background-color:#eef"></input>
      </div>
      <div class="col-sm-3">
      <input type="text" id="ret<?=$branchrevenue->revenueID?>" value=0.00 class="form-control bg-muted" style="border:none; " disabled></input>
      </div>
      <div class="col-sm-3">
      <input type="text" id="cont<?=$branchrevenue->revenueID?>" value="<?=-$branchcontrib?>" placeholder="Total contributions" class="form-control bg-muted" style="border:none;" disabled></input>
      </div>
      <div class="col-sm-3">
    <input type="text" id=<?=$branchrevenue->revenueID?> name=<?=$branchrevenue->revenueID?> class="form-control incput" value=0.00 style="border:none; background-color:#eef" ></input>
    </div>
      </div>
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
    function totalreturned(totalcol,id)
    {
       totalcol=parseFloat(totalcol);
       $('#ret'+id).val(totalcol/2);
       $('#'+id).val(parseFloat($('#ret'+id).val())+parseFloat($('#cont'+id).val()))

    }
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
    $('.total').keyup(function(){
      var totalcol=$(this).val();
      var id=$(this).attr('name');
      totalreturned(totalcol,id);
      unallocated();

    })
    $('.incput').keyup(function(){
      var id=$(this).attr('id');
      var totalcol=$("#cal"+id).val();
      totalreturned(totalcol,id);
      unallocated();
    })
  
    unallocated();

   })
JS;
$this->registerJs($script);
?>