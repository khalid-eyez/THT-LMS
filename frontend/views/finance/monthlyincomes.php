
<?php
use common\models\Meeting;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$months=[
  '1'=>'January',
  '2'=>'February',
  '3'=>'March',
  '4'=>'April',
  '5'=>'May',
  '6'=>'June',
  '7'=>'July',
  '8'=>'August',
  '9'=>'September',
  '10'=>'October',
  '11'=>'November',
  '12'=>'December'
];
$this->params["pageTitle"]="Monthly Incomes";
?>
<div class="container-fluid mt-3 meet">
        
<div class="card shadow-lg">

    <div class="card-body text-center" style="font-size:12px">
    <span class="text-lg text-success">Financial Year <?=$annualbudget->year->title?></span>
    </div>
</div>
<div class="accordion" id="incomeaccordion">
<?php 
$incomes=$annualbudget->monthlyincomes;
foreach($incomes as $income)
{
?>
<div class="card shadow" data-toggle="collapse" data-target="#collapse<?=$income->incomeID?>" aria-expanded="true" aria-controls="collapse<?=$income->incomeID?>">
    <div class="card-header p-1 bg-success text-sm">
         <i class="float-right fa fa-trash m-1 del" id=<?=$income->incomeID ?> data-toggle="tooltip" data-title="Delete Income"></i> 
    </div>
        
        <div class="card-body text-center text-sm" >
            
             <div class="row">
              <div class="col">
                <span class="heading">Month</span><br>
                <?=$months[$income->month]?>
              </div>
              <div class="col">
                <span class="heading">Amount Received</span><br>
                <span class="money"><?=yii::$app->MoneyFormatter->format($income->receivedAmount)?></span>
              </div>
              <div class="col">
                <span class="heading">Date & Time</span><br>
                <?=date_format(date_create($income->datereceived),"d-m-Y H:i:s")?>
              </div>
            

             </div>

        </div>
        <div id="collapse<?=$income->incomeID?>" class="collapse" aria-labelledby="heading<?=$income->incomeID?>" data-parent="#incomeaccordion">

        <div class="card-footer text-sm pl-4 border-top" style="background-color:#eef;">
        <div class="row heading text-success mb-3"><div class="col text-center">Branch Allocations</div></div>
        <div class="row text-bold border-bottom">
            <div class="col-sm-1">#</div>
            <div class="col">Branch</div>
            <div class="col">Amount</div>
            <div class="col">Date & Time</div>
         </div>
        

        <?php 
        $branchrevs=$income->branchMonthlyRevenues;
        $count=0;
        foreach($branchrevs as $branchrev)
        {
         ?>

         <div class="row text-sm border-bottom">
            <div class="col-sm-1"><?=++$count?></div>
            <div class="col normalText"><?=$branchrev->branchbudget0->branch0->branch_short?></div>
            <div class="col money"><?=yii::$app->MoneyFormatter->format($branchrev->received_amount)?></div>
            <div class="col"><?=date_format(date_create($income->datereceived),"d-m-Y H:i:s")?></div>
         </div>
         <?php
        }
         ?>

       </div>
       </div>
  
</div>
<?php } ?>
</div>
</div>
</div>

 <?php
$script = <<<JS
    $('document').ready(function(){
    $('.finance').addClass("active");
    
  $(document).on('click', '.del', function(){
  var id = $(this).attr('id');
  Swal.fire({
  title: 'Delete Income?',
  text: "You won't be able to revert this!",
  icon: 'question',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Delete'
  }).then((result) => {
  if (result.isConfirmed) {

  $.ajax({
  url:'/finance/delete-income',
  method:'post',
  async:false,
  dataType:'JSON',
  data:{income:id},
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
