<?php
use yii\helpers\Url;
$this->params['pageTitle']="Repository";
?>
<div class="container-fluid docx">
   <div class="row border-bottom pt-3 pb-3">
      <div class="col-xs-10 col-sm-10 p-1">
      <div class="form-inline col float-right" >
  <div class="input-group " style="width:100%!important" >
    <input class="form-control  form-control-sm sh" type="search" placeholder="Search" aria-label="Search">
    <div class="input-group-append">
      <button class="btn btn-sm btn-sidebar bg-success">
        <i class="fas fa-search fa-fw"></i>
      </button>
    </div>
  </div>
</div>


      </div>
      <div class="col-xs-2 col-sm-2 p-1"><a href="#" class="btn col btn-sm bg-success  float-right" data-toggle="modal" data-target="#reposmodal"><i class="fa fa-upload"></i> New Document</a></div>
   </div>
   <?php
   if(!empty($docs)) 
   {

   foreach($docs as $index=>$doc)
   {
   ?>
   <div class="container border-bottom p-2 ">
     <div class="row" style="background-color:rgba(204,204,221,.3)"><div class="col-sm-12 text-success"><i class="fa fa-file-text"></i> <?=$doc->docTitle?></div></div>
     <div class="row"><div class="col-sm-12 p-3 pl-5" ><?=$doc->docDescription?></div></div>
     <div class="row"><div class="col-sm-12 text-primary text-sm pl-5"><a href='<?=($doc->file0!=null)?"/storage/repos/".$doc->file0->fileName:"#"?>'><i class="fa fa-eye"></i>View Document</a></div></div>
     <div class="row"><div class="col-sm-12 text-success p-2">
      <?php if($doc->isUploader(yii::$app->user->identity->id)){?>
        <a   href="#" class="docdel" id=<?=$doc->docID?>><i class="fa fa-trash float-right p-1 text-success" data-toggle="tooltip" data-title="Delete document"></i></a>
      <?php } ?>
      <?php if($doc->isUploader(yii::$app->user->identity->id)){?>
        <a   href="<?=Url::to(["/repos/update","doc"=>urlencode(base64_encode($doc->docID))])?>"><i class="fa fa-edit text-success float-right p-1" data-toggle="tooltip" data-title="Update Document"></i></a>
     <?php } ?>
     <a href="<?=Url::to(["/repos/download","file"=>urlencode(base64_encode($doc->file))])?>"><i class="fa fa-download text-success float-right p-1" data-toggle="tooltip" data-title="Download document"></i></a></div></div>
     <div class="row"><div class="col-sm-12 text-muted text-sm text-center ">Uploaded on: <?=$doc->uploadTime?> by <?=($doc->userID!=null)?array_keys(Yii::$app->authManager->getAssignments($doc->userID))[0]:"Unknown"?></div></div>
    </div>
   <?php
   }
  }
  else
  {
   ?>
   <div class="container-fluid text-center text-lg text-success border mt-3 p-5"><i class="fa fa-info-circle"></i> No Documents Found</div>
   <?php
  }
   ?>

</div>
<?=$this->render('newDoc')?>
<?php
$script = <<<JS
    $('document').ready(function(){
      $('.repository').addClass("active");
$(".sh").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $(".docx .container").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });

  $(document).on('click', '.docdel', function(){
      var doc = $(this).attr('id');
      Swal.fire({
  title: 'Delete Document?',
  text: "You won't be able to revert to this !",
  icon: 'question',
  showCancelButton: true,
  confirmButtonColor: "green",

  confirmButtonText: 'Delete'
}).then((result) => {
  if (result.isConfirmed) {
 
    $.ajax({
      url:'/repos/delete',
      method:'post',
      async:false,
      dataType:'JSON',
      data:{doc:doc},
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
