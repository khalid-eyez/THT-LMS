<?php
use \common\models\User;
use yii\helpers\Url;
$this->params['pageTitle']="Add Children";

?>
<div class="container-fluid mt-3">
 <?php 
 if($item==null)
 {
 ?>
 <div class="container p-5 text-lg text-center mb-3 text-bold text-danger border border-danger"><i class="fa fa-exclamation-triangle"></i> Item Not Found</div></div>
 <?php
 return null;
 }
 ?>
<div class="container shadow-sm mb-2">
  <div class="row  p-2">
    <div class="col-sm-12  p-3">
      <span class="text-bold"><?=$item->name?></span>
      <a href="#" name="<?=$item->name?>" type="<?=base64_decode(urldecode($_GET['type']))?>" class="itemviewdel float-right text-danger ml-1" data-toggle="tooltip" data-title="Delete Item"><i class="fa fa-trash"></i> </a>
    </div>
    <div class="col-sm-12 p-3">
      <span class="text-bold">Description:</span>
      <p class="">
      <?=$item->description?>
      </p>
    </div>
    <div class="col-sm-12 p-3">
      <span class="text-bold">Rule:</span><?=$item->ruleName?>
      <?php
      if($item->ruleName!=null)
      {
      ?>
      <i data-toggle="tooltip" data-title="Remove rule" class="float-right fa fa-minus border border-danger text-danger p-1"></i>
      <?php
      }
      ?>
    </div>
  </div>
  </div>
  <div class="container shadow-sm mb-2">
  <div class="row  p-2">
    <div class="col-sm-12  p-3 border-bottom">

       <span class="text-bold">Child roles | permissions</span>
       <a data-toggle="tooltip" parent="<?=$item->name?>" type="<?=base64_decode(urldecode($_GET['type']))?>" data-title="Remove All Children From This Role" class="rmchildren float-right btn btn-sm btn-danger "><i class="fa fa-minus"></i></a>
       <a href="<?=Url::toRoute(['/access/add-children','name'=>$_GET['item'],'type'=>$_GET['type']])?>" data-toggle="tooltip" data-title="Add New Child Roles | Permissions" class="float-right btn btn-sm btn-success mr-1 "><i class="fa fa-plus"></i></a>
      </div>
  </div>
  <?=$children?>





</div>
<div class="container shadow-sm">
  <div class="row  p-2">
    <div class="col-sm-12  p-3 border-bottom">

       <span class="text-bold">Assigned Users</span>
       <a href="<?=Url::toRoute(['/access/deassign-all-users','item'=>$_GET['item']])?>" data-toggle="tooltip" data-title="Remove All Users From This Role" class="float-right btn btn-sm btn-danger "><i class="fa fa-minus"></i></a>
       <a data-toggle="tooltip" data-title="Assign New User " class="float-right btn btn-sm btn-success mr-1 "><i class="fa fa-plus"></i></a>
      </div>
      <?php
      foreach($users as $user)
      {
        $userreal=User::findOne($user);
        if($userreal==null){continue;}
      ?>
<div class="col-sm-12  p-3 border-bottom">
  <?=$userreal->username?>
  <i data-toggle="tooltip" data-title="Un-assign user from this item" class="float-right fa fa-minus border border-danger text-danger p-1"></i>
</div>
      <?php
      }
      ?>
  </div>
</div>
</div>
<?php
$script = <<<JS
    $('.access').addClass('active');
    $('.permis').select2();
    $('.permi').select2();
    
    $(".sh").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $(".rl .pr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });

  $(".ph").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $(".perm .pr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });

  $(".rulesearch").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $(".rulescont .therule").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });

  ////////////////////////////////////////////////////////
  //////////deleting rule/////////////////////////////////

  $(document).on('click', '.rmchildren', function(e){
    e.stopPropagation();
      var parent = $(this).attr('parent');
      var type=$(this).attr('type');
      Swal.fire({
  title: 'Remove All Children?',
  text: "You won't be able to revert to this !",
  icon: 'question',
  showCancelButton: true,
  confirmButtonColor: "red",

  confirmButtonText: 'Remove'
}).then((result) => {
  if (result.isConfirmed) {
 
    $.ajax({
      url:'/access/remove-children',
      method:'post',
      async:false,
      dataType:'JSON',
      data:{parent:parent,type:type},
      success:function(data){
        if(data.removed){
      setTimeout(() => {
        Swal.fire(
              'Removed !',
              data.removed,
              'success'
    )
        window.location.reload();
      }, 500);
     
   

        }
        else
        {
          Swal.fire(
              'Removing failed !',
              data.failure,
              'error'
    )
 
        }
      }
    })
   
  }
})

})
//////////////////////////////////////////////////
///removing all rules////////////////////////////

$(document).on('click', '.rulesdel', function(e){
  e.stopPropagation();
  var parent=$('.rulescont');
      Swal.fire({
  title: 'Remove All Rules?',
  text: "You won't be able to revert to this !",
  icon: 'question',
  showCancelButton: true,
  confirmButtonColor: "red",

  confirmButtonText: 'Remove All'
}).then((result) => {
  if (result.isConfirmed) {
 
    $.ajax({
      url:'/access/remove-all-rules',
      method:'post',
      async:false,
      dataType:'JSON',
      success:function(data){
        if(data.removed){
      parent.addClass('bg-danger');
      setTimeout(() => {
          parent.fadeOut('slow','swing',function(){
          parent.remove();
        })
        
      }, 500);
     
   

        }
        else
        {
          Swal.fire(
              'Removing failed !',
              data.failure,
              'error'
    )
 
        }
      }
    })
   
  }
})

})
//////////////////////////////////////////
//////////delete item - permission or role//////////////////

$(document).on('click', '.itemviewdel', function(e){
    e.stopPropagation();
      var name = $(this).attr('name');
      var type = $(this).attr('type');
      var parent=$(this).parent().parent().parent().parent();
      Swal.fire({
  title: 'Delete Item?',
  text: "You won't be able to revert to this !",
  icon: 'question',
  showCancelButton: true,
  confirmButtonColor: "red",

  confirmButtonText: 'Delete'
}).then((result) => {
  if (result.isConfirmed) {
 
    $.ajax({
      url:'/access/delete-item',
      method:'post',
      async:false,
      dataType:'JSON',
      data:{name:name,type:type},
      success:function(data){
        if(data.deleted){
      parent.addClass('bg-danger');
      setTimeout(() => {
          parent.fadeOut('slow','swing',function(){
          parent.remove();
          window.location.reload();
        })
        
      }, 500);
     
   

        }
        else
        {
          Swal.fire(
              'Removing failed !',
              data.failure,
              'error'
    )
 
        }
      }
    })
   
  }
})

})
//////////////////////////////////////////////
////////removing single children


$(document).on('click', '.itemdel', function(e){
    e.stopPropagation();
      var child = $(this).attr('name');
      var params=new URLSearchParams(window.location.search);
      var parentitem=params.get('item');
      var parenttype = params.get('type');
      var parent=$(this).parent().parent();
      Swal.fire({
  title: 'Remove Child?',
  text: "You won't be able to revert to this !",
  icon: 'question',
  showCancelButton: true,
  confirmButtonColor: "red",

  confirmButtonText: 'Delete'
}).then((result) => {
  if (result.isConfirmed) {
 
    $.ajax({
      url:'/access/remove-child',
      method:'post',
      async:false,
      dataType:'JSON',
      data:{parent:parentitem,item:child,type:parenttype},
      success:function(data){
        if(data.removed){
      parent.addClass('bg-danger');
      setTimeout(() => {
          parent.fadeOut('slow','swing',function(){
          parent.remove();
        })
        
      }, 500);
     
   

        }
        else
        {
          Swal.fire(
              'Removing failed !',
              data.failure,
              'error'
    )
 
        }
      }
    })
   
  }
})

})








//////////////////////////////////////////////
var children = $('.child');


  $('body').on('shown.bs.collapse','.child',function(e){
    $(this).children(":first").css('backgroundColor','rgba(210, 211, 247,.3)');
    //$(this).children(":first").addClass('shadow-sm');
    $(this).children(":first").addClass('p-2');
  })

  $('body').on('hidden.bs.collapse','.child',function(){
    $(this).children(":first").css('backgroundColor','white');
    $(this).children(":first").removeClass('shadow-sm');
    $(this).children(":first").removeClass('p-2');
  })

  $('.child').each(function()
  {
    $(this).mouseover(function(){
      $(this).css('backgroundColor','rgba(210, 211, 247,.3)');
      $(this).addClass('shadow-sm');
    });

    $(this).mouseout(function(){
      $(this).css('backgroundColor','white');
      $(this).removeClass('shadow-sm');
    })
  })

  $('.pr').mouseover(function(){
      $(this).css('backgroundColor','rgba(210, 211, 247,.3)');
      //$(this).addClass('shadow');
    });

    $('.pr').mouseout(function(){
      $(this).css('backgroundColor','');
      //$(this).removeClass('shadow');
    })
  // $('body').on('mouseover','.child',function(){
  //   $(this).children(":first").css('backgroundColor','rgba(210, 211, 247,.3)');
  //   $(this).children(":first").addClass('shadow-sm');
  //   $(this).children(":first").addClass('p-2');
  // })

   
JS;
$this->registerJs($script);
?>