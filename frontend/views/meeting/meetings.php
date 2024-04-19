
<?php
use common\models\Meeting;
use yii\helpers\Url;
use yii\bootstrap\Modal;


$this->params["pageTitle"]="Meetings";
?>
<div class="container-fluid mt-3 meet">
<div class="row">
<div class="col-10 col-sm-10 p-1 mb-2">
<div class="form-inline col float-right " >
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
                
    <?php
    if(yii::$app->user->identity->canCallMeetings())
    {
    ?>
    
        <div class="col-xs-2 col-sm-2 p-1">
    <a class="float-right  btn btn-sm col btn-success p-1 mb-2" data-toggle="modal" data-target="#meetingmodal"><i class="fa fa-plus-circle"></i> New meeting</a>
</div>

<?php
    }
?>
</div>
        <div class="accordion" id="accordionExample">
         <?php
          if($meetings==null)
          {
            ?>
            <div class="container text-success border  text-lg text-center">
               <span class="row bg-success p-1"></span>
               <span class="row ">
               <span class="col p-5">
                <i class="fa fa-info-circle"></i> No Viewable Meetings Found
          </span>
                 
                

            </div>
            <?php
          }
          ?>
         <?php
         
            foreach($meetings as $meeting)
            {
              
         ?>
<div class="card shadow-lg">
    <div class="card-header p-0" data-toggle="collapse" data-target="#collapse<?=$meeting->meetingID?>" aria-expanded="true" aria-controls="collapse<?=$meeting->meetingID?>">
 
          
              
                <div class="row bg-success text-sm">
               
                    <div class="col  p-1 ml-3">
                   <i class="fas fa-comment-alt"></i> <?=$meeting->meetingTitle?>
                   <?php
                     if($meeting->isCaller(yii::$app->user->identity->id))
                     {
                   ?>
                   <span class="float-right pr-3" style="font-size:11px">
                   <a href="<?=Url::to(['/meeting/upload','meeting'=>urlencode(base64_encode($meeting->meetingID))])?>" class="nocoll"><i class="fa fa-upload border p-1 m-1 text-white" data-toggle="tooltip" data-title="Upload Meeting Documents"></i></a>
                   <a href="<?=Url::to(['/meeting/update-meeting','id'=>urlencode(base64_encode($meeting->meetingID))])?>" class="nocoll"><i class="fa fa-edit border p-1 text-white" data-toggle="tooltip" data-title="Edit Meeting"></i></a>
                   <?php if(!$meeting->isCancelled()){ ?>
                   <a href="<?=Url::to(['/meeting/meeting-cancel','meeting'=>urlencode(base64_encode($meeting->meetingID))])?>" class="nocoll"><i class="fa fa-times border m-1 p-1 text-white" data-toggle="tooltip" data-title="Cancel Meeting"></i></a>
                   <?php }else{ ?>
                    <a href="<?=Url::to(['/meeting/meeting-uncancel','meeting'=>urlencode(base64_encode($meeting->meetingID))])?>" class="nocoll"><i class="fa fa-check border m-1 p-1 text-white" data-toggle="tooltip" data-title="Uncancel Meeting"></i></a>
                    <?php } ?>
                   <a href="<?=Url::to(['/meeting/attendance','meeting'=>urlencode(base64_encode($meeting->meetingID))])?>" class="nocoll"><i class="fa fa-list border text-white p-1" data-toggle="tooltip" data-title="Meeting Attendance"></i></a>
                   <a href="#" class="meetdel nocoll" id=<?=$meeting->meetingID?>><i class="fa fa-trash-alt border p-1 m-1 text-white"  data-toggle="tooltip" data-title="Delete Meeting"></i></a>
                </span>
                   <?php
                     }
                    ?>
                   </div>
                  </div>
                  <div class="row  text-sm text-muted">
                    <div class="col p-2 ml-3">
                     <b>Description: </b><?=$meeting->description?>
                   </div>
                  </div>
                  <div class="row  text-sm text-muted">
                    <div class="col-sm-7  p-1 pl-2 ml-3 mb-3">
                     <b>Type: </b><?=$meeting->type0->name?>
                   </div>
                   <div class="col-sm-4 ml-3">
                  
                    <span class="float-right">
                      <?php if($meeting->meetingStatus()=="Upcoming"){?>

                        <img class="brand-image" style="position:absolute;right:0;width:50px;height:50px;top:0" src="/img/new2.jpg" />
                        <?php }else if($meeting->meetingStatus()=="Cancelled"){ ?>

                          <img class="brand-image" style="position:absolute;right:0;width:65px;height:50px;top:0" src="/img/canc3.PNG" />
                           <?php if($meeting->getCancelReason()!=null){ ?>
                              <a href="<?=Url::to(['/meeting/cancel-reason','meeting'=>urlencode(base64_encode($meeting->meetingID))])?>" data-toggle="tooltip" data-title="View Cancel Reason" class="border border-success text-success nocoll p-1 float-right text-sm" style="position:absolute;bottom:0;right:20%"><i class="fa fa-eye"></i> Reason</a>
      
                            <?php } ?>
                        <?php }else if($meeting->meetingStatus()=="Expired"){ ?>
                          <img class="brand-image" style="position:absolute;right:0;width:70px;height:40px;top:0;opacity:.6;" src="/img/expired.PNG" />
                          <?php }else{ ?>
                            <img class="brand-image" style="position:absolute;right:0;width:65px;height:50px;top:0" src="/img/updated.PNG" />
                            <?php } ?>
                    </span>
                   </div>
                  </div>
                  <div class="row  text-sm text-muted">
                    <div class="col  p-1 pl-2 ml-3 mb-3">
                     <b>Duration: </b><?=$meeting->duration?> Hour(s)
                   </div>
                  </div>
                        
                 
           
          
     
    
    </div>
        <div id="collapse<?=$meeting->meetingID?>" class="collapse" aria-labelledby="heading<?=$meeting->meetingID?>" data-parent="#accordionExample">
        <div class="card-body" >
            <div class="row m-2">
               <div class="col-sm-12 border">
                  <div class="row  p-0 text-sm text-success" style="background-color:rgba(187,187,221,.3)">
                     <span class="col"><i class="fa fa-file-alt"></i> Meeting Documents</span>
                  </div>
                  <div class="row">
         <div class="col p-3  money" style="font-size:11px">
         <div class="row text-bold "><div class="col-sm-1">#</div><div class="col-sm-2">Title</div><div class="col-sm-2">Size</div><div class="col-sm-2">Uploaded On</div><div class="col-sm-3">Uploaded By</div><div class="col-sm-2">Toolbar</div></div>
                   <?php
                    $documents=$meeting->meetingdocuments;
                    $count=0;
                    foreach($documents as $document)
                    {
                   ?>
                     <div class="row text-muted ">
                      <div class="col-sm-1"><?=++$count?></div>
                      <div class="col-sm-2"><?=$document->title?></div>
                      <div class="col-sm-2"><?=$document->fileSize()?></div>
                     <div class="col-sm-2"><?=$document->dateUploaded?></div>
                     <div class="col-sm-3"><?=$meeting->getCaller()?></div>
                     
                     <div class="col-sm-2">
                     <a href="<?=Url::to(['/meeting/download','file'=>urlencode(base64_encode($document->fileID))])?>"  data-toggle="tooltip" data-title="Download Document"><i class="fa fa-download text-muted"></i></a>
                      <?php
                     if($meeting->isCaller(yii::$app->user->identity->id))
                     {
                   ?>
                      <a href="#" class="docdel" id=<?=$document->docID?> ><i class="fa fa-trash-alt ml-1 text-muted"  data-toggle="tooltip" data-title="Delete Document" style="font-size:11px"></i></a>
                   <?php
                     }
                   ?>
                    </div>
      
                    </div>
                   <?php
                    }

                   ?>

                  </div>
                  </div>



               </div>


            </div>
            <div class="row m-2">
               <div class="col-sm-12 border">
                  <div class="row  p-0 text-sm text-success" style="background-color:rgba(187,187,221,.3)">
                     <span class="col"><i class="fa fa-group"></i> Expected Participants</span>
                  </div>
                  <div class="row">

                    <div class="col p-3   money" style="font-size:11px">
                    <div class="row text-bold "><div class="col-sm-1">#</div><div class="col-sm-2">Name | Post</div><div class="col-sm-2">Branch</div><div class="col-sm-2">E-mail</div><div class="col-sm-3">Rank</div><div class="col-sm-2">Status</div></div>
                        <?php
                        $count=0;
                          foreach($meeting->getPartcipants() as $participant)
                          {
                        ?>
                          <div class="row text-muted"><div class="col-sm-1"><?=++$count?></div><div class="col-sm-2"><?=($participant->member!=null)?$participant->member->fullName():$participant->getRank()?></div><div class="col-sm-2"><?=$participant->getBranch()->branch_short?></div><div class="col-sm-2"><?=$participant->username?></div><div class="col-sm-3"><?=$participant->getRank()?></div><div class="col-sm-2">
                            <?php
                            $status=$participant->getParticipantStatus($meeting->meetingID);
                            if($status=="Cancelled")
                            {
                              if($meeting->isCaller(yii::$app->user->identity->id))
                              {
                            ?>
                              <a href="<?=Url::to(['/meeting/participation-cancel-reason','meeting'=>urlencode(base64_encode($meeting->meetingID)),'member'=>urlencode(base64_encode($participant->memberID))])?>" class="text-muted" data-toggle="tooltip" data-title="View cancel reason"><?=$status?>  <i class="fa fa-eye"></i></a>
                              <?php }else{ ?>

                                <?=$status?> <span class="<?=$meeting->getCancelStatus($participant->memberID)=="disapproved"?"text-danger":"text-success"?>">(<?=$meeting->getCancelStatus($participant->memberID)!=null?$meeting->getCancelStatus($participant->memberID):""?>)</span>

                                <?php } ?>

                            <?php }else{?>
                              <?=$status?> 
                              <?php }?>
                          </div></div>
                        <?php
                          }

                        ?>
                    </div>

                  </div>



               </div>


            </div>



        </div></div>
        <div class="card-footer p-2 bg-white border-top">
            <div class="row">
                <div class="col-sm-10 text-sm">

          
                  
                <div class="row">
                    <div class="col-sm-3"><b class="text-muted"><i class="fa fa-clock-o"></i> Time : </b><span><?=date_format(date_create($meeting->meetingTime),"d-m-Y H:i:s")?></span></div>
                    <div class="col-sm-7 p-0"><b class="text-muted ml-3 "><i class="fa fa-map-marker"></i> Venue : </b><span><?=$meeting->venue?></span></div>
                    <div class="col-sm-2 p-0"><b class="text-muted">Called on: </b><span><?=date_format(date_create($meeting->dateAnnounced),"d-m-Y")?></span></div>
                  </div>
                
                </div>
                <div class="col-sm-2">
                 <?php if($meeting->isExpired() && !$meeting->isAttended() && !$meeting->isCancelled()){?>
                <a href="<?=Url::to(['/meeting/sign-attendance','meeting'=>urlencode(base64_encode($meeting->meetingID))])?>" class="btn btn-sm btn-success float-right ml-2" data-toggle="tooltip" data-title="Sign Attendance"><span><i class="fa fa-pen text-white"></i></span></a>
                <?php } ?>
                <?php if(!$meeting->isExpired() && !$meeting->isCancelled()){?>
                    <?php if(!$meeting->isUserCancelled()){?>
                    <a href="<?=Url::to(['/meeting/cancel-participation','meeting'=>urlencode(base64_encode($meeting->meetingID))])?>" class="btn btn-sm btn-success float-right ml-2" data-toggle="tooltip" data-title="Cancel participation" ><span><i class="fa fa-times"></i></span></a>
                    <?php } ?>
                    <?php if(!$meeting->isConfirmed()){?>
                    <a href="<?=Url::to(['/meeting/confirm-participation','meeting'=>urlencode(base64_encode($meeting->meetingID))])?>" class="btn btn-sm btn-success float-right ml-2" data-toggle="tooltip" data-title="Confirm participation"><span><i class="fa fa-check text-white"></i></span></a>
                    <?php } ?>
                    <?php if(!$meeting->isCaller(yii::$app->user->identity->id)){?>
                    <a href="<?=Url::to(['/meeting/invitation-downloader','meeting'=>urlencode(base64_encode($meeting->meetingID))])?>" class="btn btn-sm btn-success float-right ml-2" data-toggle="tooltip" data-title="Download official Invitation" ><span><i class="fa fa-download"></i></span></a>
                    <?php } ?>
                    <?php } ?>
                </div>
            </div>
            <div class="row mt-2">
                  <div class="col text-center pb-0" style="font-size:10px"><span class="text-muted">By <?=array_keys(Yii::$app->authManager->getAssignments($meeting->announcedBy0->id))[0]?> | <?=$meeting->announcedBy0->getBranch()->branch_short?></span></div>
                  </div>
        </div>
    </div>
    <?php

            }
    ?>
</div>
<?=$this->render("create",['model'=>new Meeting()])?>
 </div>
</div>
</div>

 <?php
$script = <<<JS
    $('document').ready(function(){
    $('.meetings').addClass("active");
    
    $(".sh").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $(".meet .card").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
  $(document).on('click', '.nocoll', function(e){
    e.stopPropagation();
  })

  $(document).on('click', '.meetdel', function(e){
    e.stopPropagation();
      var meet = $(this).attr('id');
      Swal.fire({
  title: 'Delete Meeting?',
  text: "You won't be able to revert to this !",
  icon: 'question',
  showCancelButton: true,
  confirmButtonColor: "green",

  confirmButtonText: 'Delete'
}).then((result) => {
  if (result.isConfirmed) {
 
    $.ajax({
      url:'/meeting/delete',
      method:'post',
      async:false,
      dataType:'JSON',
      data:{meet:meet},
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
      url:'/meeting/delete-doc',
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
