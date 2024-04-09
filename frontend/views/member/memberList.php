<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use common\models\Member;
use common\models\User;
/* @var $this yii\web\View */
/* @var $searchModel common\models\MemberSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Members';
$this->params['pageTitle']="Members";

$user=yii::$app->user;
?>
<div class="container-fluid">

    
        
  

    
<div class="row"></div>
      <div class="row">
               <!-- Left col -->
               <section class="col-sm-12 table-responsive">
                <?php if($user->can("CHAIRPERSON BR")){?>
               <div class="col-sm-12"><?= Html::a('<i class="fa fa-plus-circle"></i> Add Member', ['create'], ['class' => 'btn btn-success btn-sm float-right ml-1','data-toggle'=>'modal','data-target'=>'#membermodal']) ?></div>
               <?php } ?>
    <table class="table table-bordered table-striped table-hover " id="memberTable" style="width:100%;">
            <thead>
            <tr class="bg-success p-1"><th width="1%">#</th><th>Full Name</th><th>Gender</th><th>Email</th><th>Individual Number</th><th>Phone</th><th>Branch</th><th width="10%"></th></tr>
            
            </thead>
            <tbody>
            <?php $i = 0; ?>
            <?php foreach($members as $member): ?>
              
            <tr>
            <td><?= ++$i; ?></td>
            <td><?= $member->fullName()?></td>
            <td><?= $member->gender?></td>
            <td><?= $member->email?></td>
            
            <td><?=$member->IndividualNumber ?></td>
            <td><?= $member->phone?></td>
            <td><?= $member->branch()?></td>
            <td>
            <a href="<?=Url::to(['/member/update','id'=>urlencode(base64_encode($member->memberID))])?>" data-toggle="tooltip" data-title="Update User" class="mr-1"><i class="fas fa-edit"></i></a>  
       
             <?php if($member->userID!=yii::$app->user->identity->id && !yii::$app->user->identity->getBranch()->isHQ() ){?>
            <a href="#"  id=<?=$member->memberID?> data-toggle="tooltip" data-title="Delete User" class="mr-1  userdel"><i class="fa fa-trash text-danger"></i></a> 
          <?php } ?>
            </td>
            </tr>
        
            <?php endforeach; ?>
            </tbody>
            </table>
        </section>
        </div></div>
 
<?=$this->render('memberCreate.php')?>
</div>
<?php
$script = <<<JS
    $('document').ready(function(){
    $('button').addClass("btn-sm");
    $('.members').addClass("active");

    $("#memberTable").DataTable({
    responsive:true,
    dom: 'Bfrtip',
        buttons: [
            'csv',
            {
                extend: 'pdfHtml5',
                title: 'Members list'
            },
            {
                extend: 'excelHtml5',
                title: 'Members list'
            },
            'print',
        ]
  });

  $(document).on('click', '.userdel', function(){
      var user = $(this).attr('id');
      Swal.fire({
  title: 'Delete User?',
  text: "You won't be able to revert to this, and the user will not be able to recover his account. consider locking the user instead, if this decision is for temporary reasons !",
  icon: 'question',
  showCancelButton: true,
  confirmButtonColor: "green",

  confirmButtonText: 'Delete'
}).then((result) => {
  if (result.isConfirmed) {
 
    $.ajax({
      url:'/member/delete',
      method:'post',
      async:false,
      dataType:'JSON',
      data:{id:user},
      success:function(data){
        if(data.deleted){
          Swal.fire(
              'Deleted !',
              data.deleted,
              'success'
    )
    setTimeout(function(){
      window.location.reload();
    }, 100);
   

        }
        else
        {
          Swal.fire(
              'Deleting failed !',
              data.failure,
              'error'
    )
    setTimeout(function(){
      window.location.reload();
    }, 100);
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
