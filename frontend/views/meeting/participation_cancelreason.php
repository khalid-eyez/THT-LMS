<?php  
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use frontend\models\ReposUpload;
use yii\helpers\Url;
use frontend\assets\AppAsset;
use common\widgets\Alert;


AppAsset::register($this);
$this->params['pageTitle']="Participation Cancel Reason";
?>


    <div class="modal-content">
     <div class="modal-header bg-success pl-4 p-1">
        <div class="modal-title ml-1">
        Cancel Reason - <?=$meeting->getParticipationCancel($member)->member->fullName()?>
    </div>
    <div class="float-right pr-3">
        <?php if(!$meeting->isCancelApproved($member)){?>
        <a href="<?=Url::to(['/meeting/approve-participation-cancel','meeting'=>urlencode(base64_encode($meeting->meetingID)),'member'=>urlencode(base64_encode($member))])?>" class="text-white border p-1 text-sm" data-toggle="tooltip" data-title="Approve"><i class="fa fa-check"></i></a>
        <?php } ?>
        <?php if($meeting->isCancelApproved($member) || $meeting->getCancelStatus($member)=="pending"){?>
        <a href="<?=Url::to(['/meeting/disapprove-participation-cancel','meeting'=>urlencode(base64_encode($meeting->meetingID)),'member'=>urlencode(base64_encode($member))])?>" class="text-white border p-1 text-sm" data-toggle="tooltip" data-title="Disapprove"><i class="fa fa-times"></i></a>
        <?php } ?>
    </div>
    </div>
      <div class="modal-body pl-4 pr-4">
            <div class="row">
            <div class="col pl-5 "><?=$meeting->getParticipationCancel($member)->reason?></div>
            <div class="col-sm-3 float-right">
            <?php if($meeting->getCancelStatus($member)=="pending"){?>

            <img class="brand-image" style="position:absolute;right:0;width:95px;height:50px;top:0" src="/img/pending.png" />
            <?php }else if($meeting->getCancelStatus($member)=="approved"){ ?>

            <img class="brand-image" style="position:absolute;right:0;width:90px;height:55px;top:0" src="/img/approved.jpeg" />
      
            <?php }else{ ?>
            <img class="brand-image" style="position:absolute;right:0;width:80px;height:70px;top:0;opacity:.6;" src="/img/rejected.png" />
             <?php } ?>

            </div>
            </div>
            <div class="row mt-3 ">
            <div class="col-sm-12 text-success border-bottom">
                <i class="fa fa-file-text"></i> Justificative Document
            </div>
            <div class="col-sm-12 p-5">
                <?php if($meeting->getParticipationCancel($member)->file!=null){ ?>
                   <a class="text-dark" href="/storage/meetingRepos/<?=$meeting->getParticipationCancel($member)->file->fileName?>"><i class="fa fa-download"></i> Download Document</a>
                <?php }else{ ?>
                      <span><i class="fa fa-info-circle"></i> Document Not Found</span>
                    <?php }?>
            </div>
            </div>
        </div>
    </div>
    </div>
  </div>
<?php
$script = <<<JS
    $('document').ready(function(){
     $('.meetings').addClass("bg-success");

  
        })

    JS;
    $this->registerJs($script);
