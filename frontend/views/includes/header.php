<div class="container-fluid hd">

<div class="row border-bottom">
 
   
 <div class="col-sm-6 p-1">
    <img src="/img/logo.jpg" class="img-circle " style="height:90%;width:12%"/>
 
  

    <span class="text-lg text-success">THTU Management Information System - [THTU-MIS]</span>

     

</div>
<div class="col-sm-6 d-none d-md-block pt-4 text-lg text-sm text-success text-right  tm" >
  
</div>
</div>
</div>
<?php
$script = <<<JS
    $('document').ready(function(){

    
        $.ajax({
      url:'/member/get-time',
      method:'post',
      async:false,
      dataType:'JSON',
      data:{},
      success:function(data){

        $('.tm').html("<span>"+data.time+"</span>");

      }
    })


    })
JS;
$this->registerJs($script);
?>