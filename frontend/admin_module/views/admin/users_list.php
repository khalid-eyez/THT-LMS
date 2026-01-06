<?php

use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel common\models\MemberSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['pageTitle']="Users";
?>
<div class="container-fluid text-sm">

    
        
  

    
<div class="row"></div>
      <div class="row">
               <!-- Left col -->
               <section class="col-sm-12 table-responsive">
               <div class="col-sm-12"><?= Html::a('<i class="fa fa-plus-circle"></i> Add User', ['create'], ['class' => 'btn btn-primary btn-sm float-right ml-1','data-toggle'=>'modal','data-target'=>'#membermodal']) ?></div>
    <table class="table table-bordered table-striped table-hover text-sm" id="userTable" style="width:100%">
            <thead>
            <tr class="p-1" style="background-color:#0341a3;color:white"><th width="1%">#</th><th>Username</th><th>Roles</th><th>Last Login</th><th width="10%"></th></tr>
            
            </thead>
            <tbody>
            <?php $i = 0; ?>
            <?php 
            foreach($users as $user):
              if($user->id==yii::$app->user->identity->id){continue;}
             ?>
              
            <tr>
            <td><?= ++$i; ?></td>
            <td><?=$user->username?></td>
            <td>
              <?php

                $roles=array_keys(Yii::$app->authManager->getAssignments($user->id));
              ?>
              
              <?=implode(",",$roles)?>
            </td>
            <td><?=$user->last_login?></td>
            <td>
            <a href="<?=Url::to(['/users/update','id'=>urlencode(base64_encode($user->id))])?>" data-toggle="tooltip" data-title="Update User" class="mr-1"><i class="fas fa-edit"></i></a>  
            <?php
            if($user!=null && $user->isLocked())
            {
            ?>
            <a href="<?=Url::to(['/users/unlock','id'=>urlencode(base64_encode($user->id))])?>"  data-toggle="tooltip" data-title="Reactivate/Unlock User" class="mr-1"><i class="fa fa-unlock"></i></a>  
            <?php
            }
            else
            {
            ?>
            <a href="<?=Url::to(['/users/lock','id'=> urlencode(base64_encode($user->id))])?>"  data-toggle="tooltip" data-title="Lock User" class="mr-1"><i class="fas fa-user-lock"></i></a>
            <?php
            }
            ?>
            <a href="<?=Url::to(['/users/reset-password','user'=> urlencode(base64_encode($user->id))])?>"  data-toggle="tooltip" data-title="Reset User Password" class="mr-1"><i class="fa fa-refresh"></i></a>
            <a href="#"  id=<?=$user->id?> data-toggle="tooltip" data-title="Delete User" class="mr-1  userdel text-danger"><i class="fa fa-trash"></i></a> 
      
            </td>
            </tr>
        
            <?php endforeach; ?>
            </tbody>
            </table>
        </section>
        </div></div>
 
<?=$this->render('userCreate')?>
</div>
<?php
$script = <<<JS
    $('document').ready(function(){
    $('button').addClass("btn-sm");
    $('.users').addClass("active");
    $('.priv').select2({
      width:'resolve',
      //maximumSelectionLength:2
    });
    $("#userTable").DataTable({
    responsive:true,
    dom: 'Bfrtip',
        buttons: [
            'csv',
            {
                extend: 'pdfHtml5',
                orientation:'landscape',
                pageSize:'LEGAL',
                title: 'Users list'
            },
            {
                extend: 'excelHtml5',
                title: 'Users list'
            },
            'print',
        ]
  });

  $(document).on('click', '.userdel', function(){
      var user = $(this).attr('id');
      Swal.fire({
  title: 'Delete Permanently ?',
  text: "You won't be able to revert to this, and the user will not be able to recover his account. consider locking the user instead, if this decision is for temporary reasons !",
  icon: 'question',
  showCancelButton: true,
  confirmButtonColor: "red",

  confirmButtonText: 'Delete'
}).then((result) => {
  if (result.isConfirmed) {
 
    $.ajax({
      url:'/users/delete',
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
