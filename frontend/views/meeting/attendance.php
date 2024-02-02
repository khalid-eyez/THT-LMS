
<?php
use common\models\Meeting;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


$this->params["pageTitle"]="Meeting Attendance";
?>
<div class="container-fluid mt-3 meet">
         <?php
          if($meeting->getPartcipants()==null)
          {
            ?>
            <div class="container text-success border  text-lg text-center">
               <span class="row bg-success p-1"></span>
               <span class="row ">
               <span class="col p-5">
                <i class="fa fa-info-circle"></i> No Participants Found
          </span>
                 
                

            </div>
            <?php
          }
          ?>
<div class="card shadow-lg">
    <div class="card-header p-1 bg-success text-sm">
 
          
           
                   <i class="fas fa-comments pl-1"></i> <?=$meeting->meetingTitle?>
                   <?php
                     if($meeting->isCaller(yii::$app->user->identity->member->memberID))
                     {
                   ?>
                   <span class="float-right pr-3" style="font-size:12px">
                   <a href="<?=Url::to(['/meeting/download-excel-attendance','meeting'=>urlencode(base64_encode($meeting->meetingID))])?>" class="text-white"><i class="fa fa-download text-white p-1" data-toggle="tooltip" data-title="Download Excel attendance" ></i> Excel</a>
                   <a href="<?=Url::to(['/meeting/download-pdf-attendance','meeting'=>urlencode(base64_encode($meeting->meetingID))])?>" class="text-white"><i class="fa fa-download text-white p-1" data-toggle="tooltip" data-title="Download PDF attendance"></i> PDF</a>
                </span>
                   <?php
                     }
                    ?>
               
    </div>
        
        <div class="card-body" style="font-family:lucida sans serif;font-size:11.5px">
            
                
             

        <?php $form = ActiveForm::begin(['method'=>'post']); ?>
                    <div class="row text-bold"><div class="col-sm-1">#</div><div class="col">Full Name</div><div class="col">Branch</div><div class="col">E-mail</div><div class="col">Rank</div><div class="col-sm-1"></div></div>
                    
                        <?php
                        $count=0;
                        $participants=$meeting->getPartcipants();
  
                          foreach($participants as $participant)
                          {
                        ?>
                          <div class="row"><div class="col-sm-1"><?=++$count?></div><div class="col"><?=$participant->fullName()?></div><div class="col"><?=$participant->branch()?></div><div class="col"><?=$participant->email?></div><div class="col"><?=$participant->getRank()?></div><div class="col-sm-1">
                            <?php if($meeting->attendances!=null && in_array($participant->memberID,$meeting->attendances)){ ?>
                          <?= $form->field($meeting, 'attendances[]')->checkbox(['value' =>$participant->memberID,'uncheck'=>null,'checked'=>'checked'],false)->label(false) ?>
                            <?php 
                            }
                            else
                            {

              
                            ?>
                              <?= $form->field($meeting, 'attendances[]')->checkbox(['value' =>$participant->memberID,'uncheck'=>null],false)->label(false) ?>
                            <?php 
                            }
                            ?>
                          </div></div>
                        <?php
                          }

                        ?>
                        
                    <?= Html::submitButton('<i class="fa fa-save"></i> Save', ['class' => 'btn btn-sm btn-success float-right mt-4 col-sm-1']) ?>
                    <?php ActiveForm::end(); ?>
        </div>
  
  
</div>
 </div>
</div>
</div>

 <?php
$script = <<<JS
    $('document').ready(function(){
    $('.meetings').addClass("active");
    
  

   })
JS;
$this->registerJs($script);
?>
