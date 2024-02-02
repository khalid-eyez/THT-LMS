<?php  
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use frontend\models\ReposUpload;
use yii\helpers\Url;
use frontend\assets\AppAsset;
use common\widgets\Alert;


AppAsset::register($this);
$this->params['pageTitle']="Cancel Reason";
?>


    <div class="modal-content">
     <div class="modal-header bg-success pl-4 p-1"><div class="modal-title ml-1"><i class='fa fa-times  p-1'></i> Cancel Reason - <?=$meeting->meetingTitle?></div></div>
      <div class="modal-body pl-4 pr-4">
    
            <?=$meeting->getCancelReason()?>
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
