
<?php
use common\models\Meeting;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\models\ReferenceDocUploader;


$this->params["pageTitle"]="Search results";
?>
<div class="container-fluid mt-3 meet">
         <?php
          if($results==null)
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
 
          <span class="ml-2">Found <?=count($results)?> Files</span>       
    </div>
        
        <div class="card-body filefolder" style="font-family:lucida sans serif;font-size:12px">
            
                    <div class="row text-bold text-md "><div class="col-sm-1">#</div><div class="col-sm-2">Reference</div><div class="col-sm-3">Label</div><div class="col-sm-3">Title</div><div class="col-sm-1">Size</div><div class="col-sm-1">Upl. Date</div><div class="col-sm-1"></div></div>
                    
                        <?php
                        $count=0;
                        $docs=$results;
  
                          foreach($docs as $doc)
                          {
                        ?>
                          <div class="row doc"><div class="col-sm-1"><?=++$count?></div><div class="col-sm-2"><?=strtoupper($doc->getReference())?></div><div class="col-sm-3"><?=ucfirst(strtolower($doc->getLabel()))?></div><div class="col-sm-3"><?=ucfirst(strtolower($doc->docTitle))?></div><div class="col-sm-1"><?=$doc->fileSize()?></div><div class="col-sm-1"><?=$doc->getUploadDate()?></div>
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
