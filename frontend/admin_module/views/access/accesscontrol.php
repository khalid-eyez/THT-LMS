<?php

$this->params['pageTitle']="Access Control";

?>



  <ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link active" data-toggle="tab" href="#roles"><span data-toggle="tooltip" data-title="A role represents a collection of permissions (e.g. creating members, managing branches). A role may be assigned to one or multiple users. To check if a user has a specified permission, we may check if the user is assigned with a role that contains that permission.">Roles</span></a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#permissions"><span data-toggle="tooltip" data-title="A permission is what a user is authorized to access or an action that a user is authorized to perform on a resource">Permissions</span></a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#rules"><span data-toggle="tooltip" data-title="A rule represents a condition during access check to determine if the corresponding role or permission applies to the current user. During access checking, if the user does not meet the condition, he/she will be considered not having that permission.">Rules</span></a>
  </li>
</ul>

<!-- Tab panes -->
<div class="tab-content mt-3">
  <div class="tab-pane  container active" id="roles">
    <div class="row">
      <div class="col-sm-4">
      <div class="form-inline mb-3 " >
                <div class="input-group " style="width:100%!important" >
                  <input class="form-control  sh" type="search" placeholder="Search" aria-label="Search">
                  <div class="input-group-append">
                    <button class="btn btn-sm btn-sidebar bg-primary">
                      <i class="fas fa-search fa-fw"></i>
                    </button>
                  </div>
                </div>
                </div>
      </div>
      <div class="col-sm-8">

       <a class="btn btn-danger rmad float-right ml-1"><i class="fa fa-trash"></i> Remove Auth. Data</a>
       <a class="btn btn-danger rmass float-right ml-1"><i class="fa fa-trash"></i> Remove Assignments</a>
       <a class="btn btn-danger rmroles float-right ml-1"><i class="fa fa-trash"></i> Remove Roles</a>
       <a class="btn btn-primary float-right" data-toggle="modal" data-target="#rolemodal"><i class="fa fa-plus-circle"></i> Add Role</a>
      </div>
    </div>
  <div id="accordion" class="rl">
 <?=$roles?>

</div> 
  </div>
  <div class="tab-pane container fade" id="permissions">
  <div class="row">
      <div class="col-sm-8">
      <div class="form-inline mb-3 " >
                <div class="input-group " style="width:100%!important" >
                  <input class="form-control  ph" type="search" placeholder="Search" aria-label="Search">
                  <div class="input-group-append">
                    <button class="btn btn-sm btn-sidebar bg-primary">
                      <i class="fas fa-search fa-fw"></i>
                    </button>
                  </div>
                </div>
                </div>
      </div>
      <div class="col-sm-4">
       <a class="btn btn-primary" data-toggle="modal" data-target="#permmodal"><i class="fa fa-plus-circle"></i> Add Permission</a>
       <a class="btn btn-danger rmpermissions"><i class="fa fa-trash"></i> Remove All</a>
      </div>
    </div>
  <div id="accordion" class="perm">
 <?=$permissions?>

</div> 
  </div>
  <div class="tab-pane container fade" id="rules">
  <div class="row">
      <div class="col-sm-8">
      <div class="form-inline mb-3 " >
                <div class="input-group " style="width:100%!important" >
                  <input class="form-control  rulesearch" type="search" placeholder="Search" aria-label="Search">
                  <div class="input-group-append">
                    <button class="btn btn-sm btn-sidebar bg-primary">
                      <i class="fas fa-search fa-fw"></i>
                    </button>
                  </div>
                </div>
                </div>
      </div>
      <div class="col-sm-4">
       <a class="btn btn-primary" data-toggle="modal" data-target="#rulemodal"><i class="fa fa-plus-circle"></i> Add Rule</a>
       <a class="btn btn-danger rulesdel"><i class="fa fa-trash"></i> Remove All</a>
      </div>
    </div>
    <div class="container-fluid shadow rulescont">
    <?php
    if($rules==null)
    {
      ?>
         <div class="container border border-primary p-5 text-lg text-primary text-center text-bold">
          <i class="fa fa-info-circle"></i> No Rules Found
        </div>
      <?php
    }
    else
    {
       foreach($rules as $rule)
       {

    ?>
        <div class="row border-bottom p-2 therule"><div class="col"><?=$rule->name?> 
        <a href="#" id="<?=$rule->name?>" class="text-danger float-right ruledel" data-toggle="tooltip" data-title="Remove Rule">
          <i class="fa fa-trash"></i>
      </a></div></div>
    <?php
       }
    }
    ?>
  </div>
</div>
</div>
<?=$this->render('createRole',['model'=>$rolemodel]);?>
<?=$this->render('createPermission',['model'=>$permmodel]);?>
<?=$this->render('rulecreate',['model'=>$rulemodel]);?>
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

  $(document).on('click', '.ruledel', function(e){
    e.stopPropagation();
      var rule = $(this).attr('id');
      var parent=$(this).parent().parent();
      Swal.fire({
  title: 'Remove Rule?',
  text: "You won't be able to revert to this !",
  icon: 'question',
  showCancelButton: true,
  confirmButtonColor: "red",

  confirmButtonText: 'Remove'
}).then((result) => {
  if (result.isConfirmed) {
 
    $.ajax({
      url:'/access/remove-rule',
      method:'post',
      async:false,
      dataType:'JSON',
      data:{name:rule},
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

//////////////////////////////////////////////////
///removing all permissions////////////////////////////

$(document).on('click', '.rmpermissions', function(e){
  e.stopPropagation();
      Swal.fire({
  title: 'Remove All Permissions?',
  text: "You won't be able to revert to this !",
  icon: 'question',
  showCancelButton: true,
  confirmButtonColor: "red",

  confirmButtonText: 'Remove All'
}).then((result) => {
  if (result.isConfirmed) {
 
    $.ajax({
      url:'/access/remove-all-permissions',
      method:'post',
      async:false,
      dataType:'JSON',
      success:function(data){
      setTimeout(() => {
        window.location.reload();
        
      }, 500);
     
    
      }
    })
   
  }
})

})
//////////////////////////////////////////////////
///removing all assignments////////////////////////////

$(document).on('click', '.rmass', function(e){
  e.stopPropagation();
      Swal.fire({
  title: 'Remove All Role Assignments ?',
  text: "You won't be able to revert to this !",
  icon: 'question',
  showCancelButton: true,
  confirmButtonColor: "red",

  confirmButtonText: 'Remove All'
}).then((result) => {
  if (result.isConfirmed) {
 
    $.ajax({
      url:'/access/remove-all-roles-assignments',
      method:'post',
      async:false,
      dataType:'JSON',
      success:function(data){
      setTimeout(() => {
        window.location.reload();
        
      }, 300);
     
    
      }
    })
   
  }
})

})
//////////////////////////////////////////////////
///removing all assignments////////////////////////////

$(document).on('click', '.rmad', function(e){
  e.stopPropagation();
      Swal.fire({
  title: 'Remove All Authorization Data [roles, permissions, rules & assignments] ?',
  text: "You won't be able to revert to this !",
  icon: 'question',
  showCancelButton: true,
  confirmButtonColor: "red",

  confirmButtonText: 'Remove All'
}).then((result) => {
  if (result.isConfirmed) {
 
    $.ajax({
      url:'/access/remove-all-auth-data',
      method:'post',
      async:false,
      dataType:'JSON',
      success:function(data){
      setTimeout(() => {
        window.location.reload();
        
      }, 300);
     
    
      }
    })
   
  }
})

})
//////////////////////////////////////////////////
///removing all roles////////////////////////////

$(document).on('click', '.rmroles', function(e){
  e.stopPropagation();
      Swal.fire({
  title: 'Remove All Roles?',
  text: "You won't be able to revert to this !",
  icon: 'question',
  showCancelButton: true,
  confirmButtonColor: "red",

  confirmButtonText: 'Remove All'
}).then((result) => {
  if (result.isConfirmed) {
 
    $.ajax({
      url:'/access/remove-all-roles',
      method:'post',
      async:false,
      dataType:'JSON',
      success:function(data){
      setTimeout(() => {
        window.location.reload();
        
      }, 500);
     
    
      }
    })
   
  }
})

})
//////////////////////////////////////////
//////////delete item - permission or role//////////////////

$(document).on('click', '.itemdel', function(e){
    e.stopPropagation();
      var name = $(this).attr('name');
      var type = $(this).attr('type');
      var parent=$(this).parent().parent();
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