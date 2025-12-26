<?php
use common\models\User;
use frontend\models\RegisterUserForm;
$this->params['pageTitle']="Update User";
?>
<div class="container p-5">
<div class="container pl-5 pr-5">
<div class="card card-sm" id="membermodal" tabindex="-1" >

     <div class="card-header bg-primary pl-4 p-1"><div class="ml-1"><i class='fa fa-edit'></i> Update User </div></div>
      <div class="card-body pl-4 pr-4">
         <?=$this->render("_formUpdate",['model'=>$model,'user'=>$user])?>
</div>
</div>
</div>
</div>
<?php
$script = <<<JS
    $('.updpriv').select2({
      width:'resolve',
      //maximumSelectionLength:2
    });
    $('.users').addClass("active");
JS;
$this->registerJs($script);
?>