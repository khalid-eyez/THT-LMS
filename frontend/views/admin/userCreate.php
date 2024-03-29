<?php
use common\models\User;
use frontend\models\RegisterUserForm;
?>
<div class="modal fade" id="membermodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
     <div class="modal-header bg-success pl-4 p-1"><div class="modal-title ml-1"><i class='fa fa-plus-circle'></i> Register User [ Employees | Non-members ONLY ] </div></div>
      <div class="modal-body pl-4 pr-4">
         <?php $model=new RegisterUserForm();?>
         <?=$this->render("_form",['model'=>$model])?>
</div>
</div>
</div>
</div>