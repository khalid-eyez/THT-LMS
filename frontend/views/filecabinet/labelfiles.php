
<?php
use common\models\Meeting;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\models\ReferenceDocUploader;


$this->params["pageTitle"]="Label Documents";
?>
<div class="container-fluid mt-3 meet">
<div class="row">
<div class="col-xs-10 col-sm-10 p-1 mb-3">
<div class="form-inline col float-right " >
                <div class="input-group " style="width:100%!important" >
                  <input class="form-control  form-control-sm ch" type="search" placeholder="Search for files" aria-label="Search">
                  <div class="input-group-append">
                    <button class="btn btn-sm btn-sidebar bg-success">
                      <i class="fas fa-search fa-fw"></i>
                    </button>
                  </div>
                </div>
                </div>
                </div>
                <div class="col-xs-2 col-sm-2 p-1">
    <a class="float-right  btn btn-sm col btn-success p-1 mb-2" data-toggle="modal" data-target="#docmodal"><i class="fa fa-upload"></i> Upload</a>
</div></div>
<?=$this->render('uploadDocModal',['model'=>new ReferenceDocUploader,'label'=>$label])?>
         <?php
          if($label->referencedocuments==null)
          {
            ?>
            <div class="container text-success border  text-lg text-center">
               <span class="row bg-success p-1"></span>
               <span class="row ">
               <span class="col p-5">
                <i class="fa fa-info-circle"></i> No Documents Found
          </span>
                 
                

            </div>
            <?php
            return false;
          }
          ?>
<div class="card shadow-lg">
    <div class="card-header p-1 bg-success text-sm">
 
          <span class="ml-2"><?=$label->prefix?> - <?=$label->getTitle()?></span>       
    </div>
        
        <div class="card-body filefolder" style="font-family:lucida sans serif;font-size:12px">
            
                    <div class="row text-bold text-md "><div class="col-sm-1">#</div><div class="col">Reference</div><div class="col">Title</div><div class="col">Size</div><div class="col">Upload Date</div><div class="col-sm-1"></div></div>
                    
                        <?php
                        $count=0;
                        $docs=$label->referencedocuments;
  
                          foreach($docs as $doc)
                          {
                        ?>
                          <div class="row doc"><div class="col-sm-1"><?=++$count?></div><div class="col"><?=strtoupper($doc->getReference())?></div><div class="col"><?=ucfirst(strtolower($doc->docTitle))?></div><div class="col"><?=$doc->fileSize()?></div><div class="col"><?=$doc->getUploadDate()?></div>
                          <div class="col-sm-1 text-muted" style="font-family:lucida sans serif;font-size:11px">
                          <a href="<?=Url::to(['/filecabinet/download','file'=>urlencode(base64_encode($doc->fileID))])?>" data-toggle="tooltip" data-title="Download Document"><i class="fa fa-download text-muted"></i></a>
                          <a href="<?=Url::to(['/filecabinet/update-doc','doc'=>urlencode(base64_encode($doc->docID))])?>" data-toggle="tooltip" data-title="Update Document"><i class="fa fa-edit m-1 text-muted"></i></a>
                           <a href="#" id=<?=$doc->docID?> data-toggle="tooltip" data-title="Delete Document" class="docdel"><i class="fa fa-trash text-muted"></i></a>
                          </div>
                        </div>
                        <?php
                          }

                        ?>
                        
        </div>
  
  
</div>
 </div>
</div>
</div>

 <?php
$script = <<<JS
    $('document').ready(function(){
    $('.cabinet').addClass("active");
    
    $(".ch").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $(".filefolder .doc").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });

  //delete document

  $(document).on('click', '.docdel', function(e){
    e.stopPropagation();
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
      url:'/filecabinet/delete-doc',
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
