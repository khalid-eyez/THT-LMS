
<?php
use common\models\Meeting;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


$this->params["pageTitle"]="File Cabinet";
?>
<div class="container-fluid mt-3 meet">

<div class="row">
<div class="col-xs-10 col-sm-10 p-1 mb-3">
<form action="/filecabinet/find-document" method="post">
<div class="form-inline col float-right " >
                <div class="input-group " style="width:100%!important" >
                
                  <input class="form-control  form-control-sm sh" name="keyword" type="search" placeholder="Search for files" aria-label="Search">
                  <div class="input-group-append">
                    <button type="submit" class="btn btn-sm btn-sidebar bg-success">
                      <i class="fas fa-search fa-fw"></i>
                    </button>
                  </div>
                  <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
                 
                </div>
                </div>
                </form>
                </div>

                <div class="col-xs-2 col-sm-2 p-1">
    <a class="float-right  btn btn-sm col btn-success p-1 mb-2" data-toggle="modal" data-target="#labelmodal"><i class="fa fa-plus-circle"></i> New Label</a>
</div></div>
         <?php
          if($labels==null)
          {
            ?>
            <div class="container text-success border  text-lg text-center">
               <span class="row bg-success p-1"></span>
               <span class="row ">
               <span class="col p-5">
                <i class="fa fa-info-circle"></i> No Labels Found
          </span>
                 
                

            </div>
            <?php
          }
          ?>
            <div class="row">
          <?php
           foreach($labels as $index=>$label)
           {

          ?>
          <div class="col-sm-4">
<div  class="card shadow-lg ">
    <div class="card-header p-1 bg-success text-sm"> 
      <i class="fa fa-folder-open"></i> <?=strtoupper($label->prefix)?> 
      <span class="float-right" style="font-size:11px">
      <?php if(!$label->isMeeting()){ ?>
    <a href="<?=Url::to(['/filecabinet/update-label','label'=>urlencode(base64_encode($label->prefID))])?>" data-toggle="tooltip" data-title="Update Label"><i class="fa fa-edit text-white"></i></a>
    <a href="#" id=<?=$label->prefID?> class="labeldel text-white" data-toggle="tooltip" data-title="Delete Label"><i class="fa fa-trash ml-1 "></i></a>
    <?php } ?>
       </span>
    </div>
    
    <div class="card-body text-center pb-1" style="font-family:lucida sans serif;font-size:11.5px">
    <a href="<?=Url::to(['/filecabinet/view','label'=>urlencode(base64_encode($label->prefID))])?>" data-toggle="tooltip" data-title="Open Label" class="viewlink text-dark">
    <span class="text-bold "><?=strtoupper($label->getTitle())?> </span><br>
    <span class=""><?=count($label->referencedocuments)?> files</span> 
    </a>
    <span class="float-right text-success col p-0 mt-2" >
    <a href="<?=Url::to(['/filecabinet/upload-document','label'=>urlencode(base64_encode($label->prefID))])?>" data-toggle="tooltip" data-title="Upload documents" ><i class="fa fa-upload text-success"></i></a>
  </span>
  
   </div>
           </div></div>
<?php } ?>
           </div>
              <?=$this->render('create',['model'=>$model])?>     
                        
        </div>
  
  
</div>
 </div>
</div>
</div>

 <?php
$script = <<<JS
    $('document').ready(function(){
    $('.cabinet').addClass("active");
    
    
    $(document).on('click', '.labeldel', function(e){
    e.stopPropagation();
      var label = $(this).attr('id');
      Swal.fire({
  title: 'Delete Label?',
  text: "You won't be able to revert to this ! And deleted documents are unrecoverable !",
  icon: 'question',
  showCancelButton: true,


  confirmButtonText: 'Delete'
}).then((result) => {
  if (result.isConfirmed) {
 
    $.ajax({
      url:'/filecabinet/delete-label',
      method:'post',
      async:false,
      dataType:'JSON',
      data:{label:label},
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

$('body').on('mouseover','.viewlink',function(){
  $(this).addClass('col');
  $(this).parent().addClass('border');
  $(this).parent().addClass('border-success');
  $(this).addClass('shadow');
  $(this).removeClass("text-dark");
})
$('body').on('mouseout','.viewlink',function(){
  $(this).removeClass('col');
  $(this).removeClass('shadow');
  $(this).parent().removeClass('border');
  $(this).parent().removeClass('border-success');
  $(this).addClass("text-dark");
})

   })
JS;
$this->registerJs($script);
?>
