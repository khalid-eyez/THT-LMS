
<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


$this->params["pageTitle"]="Budget Item Structure";
$budgetstructures=$projection->itemizedprojections;
?>
<div class="container-fluid mt-3 meet">
        
<div class="card shadow-lg">
<div class="card-header p-1  text-md  bg-success text-center">
   <div class="row">
      <div class="col-sm-6">
        <span class="float-left pl-3"><i class="fas fa-wallet"></i> <?=$projection->budgetItem?></span>
      </div>
      <div class="col-sm-6">
         <div class="row">
         <div class="col-sm-6">
         <strong>Projected: </strong>
          <span class="inc"> <?=$projection->projected_amount?></span> TZS
         </div>
         <div class="col-sm-6">
          <strong>Unallocated:</strong>
          <span class="unl"><?=$projection->Unallocated()?></span> TZS
         </div>
         </div>

      </div>

   </div>
</div>
<div class="card-body money">
<?php $form=Activeform::begin(['method'=>'post']) ?>
    <?php foreach($budgetstructures as $budgetstructure){ ?>
    <div class="row mb-1 ">
      <div class="col pl-3 ">
      <input type="text" name="<?=$budgetstructure->itemName?>[]"   value="<?=$budgetstructure->itemName?>" class="form-control incput" style="border:none; background-color:#eef" placeholder="Item Name"></input>
    </div>
    <div class="col">
    <input type="text" name="<?=$budgetstructure->itemName?>[]" value="<?=$budgetstructure->unit?>" class="form-control valueput" style="border:none; background-color:#eef" placeholder="Unit"></input>
    </div>
    <div class="col">
    <input type="text" name="<?=$budgetstructure->itemName?>[]" value="<?=$budgetstructure->unitcost?>" class="form-control unitput" style="border:none; background-color:#eef" placeholder="Unit cost"></input>
    </div>
    <div class="col">
    <input type="text" name="<?=$budgetstructure->itemName?>[]" value="<?=$budgetstructure->numUnits?>" class="form-control unitput" style="border:none; background-color:#eef" placeholder="No. of Units"></input>
    </div>
    <div class="col">
    <input type="text" name="<?=$budgetstructure->itemName?>[]" value="<?=$budgetstructure->totalcost?>" class="form-control totalput" style="border:none; background-color:#eef" placeholder="Total budget"></input>
    <input type="hidden" name="<?=$budgetstructure->itemName?>[]" value=<?=$budgetstructure->ipID?> ></input>
    </div>
    </div>
    <?php } ?>
    <div class="row mb-1 initial">
      <div class="col pl-3 ">
      <input type="text" name=""   class="form-control incput" style="border:none; background-color:#eef" placeholder="Item Name"></input>
    </div>
    <div class="col">
    <input type="text" name="" class="form-control valueput" style="border:none; background-color:#eef" placeholder="Unit"></input>
    </div>
    <div class="col">
    <input type="text" name="" class="form-control unitput" style="border:none; background-color:#eef" placeholder="Unit cost"></input>
    </div>
    <div class="col">
    <input type="text" name="" class="form-control unitput" style="border:none; background-color:#eef" placeholder="No. of Units"></input>
    </div>
    <div class="col">
    <input type="text" name="" class="form-control totalput" style="border:none; background-color:#eef" placeholder="Total budget"></input>
    </div>
    <input type="hidden" name="" value=0 ></input>
    </div>
    
   <button type="submit" class="btn btn-success btn-sm float-right mt-3 sub"><i class="fa fa-save"> Save</i></button>
   <?php $form=Activeform::end() ?>
</div>
</div>

<?php
$script = <<<JS
    $('document').ready(function(){
    $('.finance').addClass("active");
    var unallocatedvalue=$('.unl').text();
    var initial=$('.initial').clone();
    $('body').on('keyup','.incput',function(){

      var inputs=$(this).parent().parent().find('input');
      var name=$(this).val();
      inputs.each(function(e){
         $(this).prop('name',name+'[]');
      })
      
    })
    $('body').on('focus','.totalput',function(){
      additemfields($(this));
      var units=$(this).parent().parent().find('.unitput');
      var value=1;
      units.each(function(e){
       value*=parseFloat($(this).val());
      })
      if(!isNaN(value)){ $(this).val(value); }
      else
      {
        $(this).val("");
      }
      

})


function additemfields(elem)
{
  var totalputs=$('.totalput');
  var lastindex=(totalputs.length)-1;
  if(totalputs.index(elem)==lastindex)
  {
   initial.clone().insertBefore($('.sub'));
  }
}

    function unallocated()
    {
      var inputs=$('.totalput');
      var total=0;
       inputs.each(function(){
         var val=parseFloat($(this).val());
         val=(!isNaN(val))?val:0;
         total+=val;
         
       });
       if(total==0){ $('.unl').text(unallocatedvalue); return false;}
       var unl=parseFloat(unallocatedvalue)-total;
       unl=(!isNaN(unl))?unl:0;
       unl=unl.toFixed(2);
       if(unl<0)
       {
         Swal.fire('warning','Current allocation greater than unallocated budget');
       }
       else
       {
         $('.unl').text(unl);
       }
    }
    $('body').on('keyup','.totalput',function(){
      unallocated();
    })
    $('body').on('blur','.totalput',function(){
      unallocated();
    })
  

   })
JS;
$this->registerJs($script);
?>