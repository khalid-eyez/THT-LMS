<?php
use common\models\GoalsSearch;
use common\models\ObjectivesSearch;
use common\models\StrategiesSearch;
use common\models\TargetsSearch;
$this->params['pageTitle']="Organisation Monitor";


?>



  <ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link active" data-toggle="tab" href="#monitor"><span>Monitor</span></a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#goals"><span>Goals</span></a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#strategies"><span>Strategies</span></a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#targets"><span>Targets</span></a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#objectives"><span>Objectives</span></a>
  </li>
</ul>

<!-- Tab panes -->
<div class="tab-content mt-3">
  <div class="tab-pane  container active" id="monitor">
    monitor
 
  </div>
  <div class="tab-pane container fade" id="goals">
   <?php 
     $searchModel = new GoalsSearch();
     $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
   ?>
<?=$this->render('@frontend/views/goals/index',['dataProvider'=>$dataProvider])?>
  </div>
  <div class="tab-pane container fade" id="strategies">

  <?php 
     $searchModel = new StrategiesSearch();
     $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
   ?>
<?=$this->render('@frontend/views/strategies/index',['dataProvider'=>$dataProvider])?>
  </div>
  <div class="tab-pane container fade" id="targets">

  <?php 
     $searchModel = new TargetsSearch();
     $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
   ?>
<?=$this->render('@frontend/views/targets/index',['dataProvider'=>$dataProvider])?>
</div>
<div class="tab-pane container fade" id="objectives">

<?php 
     $searchModel = new ObjectivesSearch;
     $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
   ?>
<?=$this->render('@frontend/views/objectives/index',['dataProvider'=>$dataProvider])?>
</div>
</div>
</div>
<?php
$script = <<<JS
    $('.monitor').addClass('active');
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