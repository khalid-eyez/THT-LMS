    <?php
    use yii\helpers\Html;
    use yii\bootstrap4\ActiveForm;
    use yii\helpers\Url;
    ?>
    
    <div class="card shadow-lg bg-white rounded" style="font-family:'Lucida Bright';">
    <div class="card-body text-center">
      <div class="container p-0"><i class="fa fa-user-circle fa-2x text-success"></i></div>
    <?php $form = ActiveForm::begin() ?>
       <div class="container-fluid" >
         <div class="row">
           <div class="col-sm-12">
            
               <?= $form->field($model, 'username')->textInput(['class'=>'form-control form-control-sm', 'placeholder'=>'Username'])->label(false) ?>
           </div>
        <div class="col-sm-12">
            
               <?= $form->field($model, 'password')->passwordInput(['class'=>'form-control form-control-sm', 'placeholder'=>'Password'])->label(false) ?>
           </div>
    <!--
           <div class='col-md-12' id='forget_password'>
         
           <a href="<?="" //Url::to(['/auth/requestpasswordreset'])  ?>">
           <span class="small"> Forgot password</span>
          </a>
           </div>
  -->
            
           <div class="col-md-4 mr-auto ml-auto">
             <?= Html::submitButton('<i class="fas fa-sign-in-alt"></i> Login', ['class'=>'btn btn-success btn-sm btn-block'])?>
           </div>

           </div>
         </div>
        
       </div>
    <?php ActiveForm::end() ?>
 
    </div>
    <!-- /.card-body -->
    <!--
   <span>Students' Registration <a href="/student/register">here</a><br>
   &nbsp;<img src="/img/announcement.gif" class="img-circle"  style="height:10%;width:10%;margin-bottom:1%"></img><i class="blinking text-danger">Deadline: 29/12/2021</i></span>-->
  </div>
  <!-- /.card -->
  <?php
$script = <<<JS
 // Dropzone.autoDiscover = false;
$(document).ready(function(){
  //alert("Heloo JQQUERY");

//function blinker() {
 //$('.blinking').fadeOut(500);
 //$('.blinking').fadeIn(500);
//}
//setInterval(blinker, 1500);

 
})
JS;
$this->registerJs($script);
?>