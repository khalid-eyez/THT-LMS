<?php
use common\models\User;
use frontend\models\RegisterUserForm;
?>
<div class="card " id="membermodal" tabindex="-1" >

     <div class="card-header bg-success pl-4 p-1"><div class="ml-1"><i class='fa fa-plus-circle'></i> Register User [ Employees | Non-members ONLY ] </div></div>
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
      maximumSelectionLength:2
    });
JS;
$this->registerJs($script);
?>