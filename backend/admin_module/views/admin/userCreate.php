<?php
use frontend\admin_module\models\RegisterUserForm;
$this->params['pageTitle']="Add User";
?>
<div class="modal fade" id="membermodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
     <div class="modal-header bg-primary pl-4 p-1"><div class="modal-title ml-1"><i class='fa fa-plus-circle'></i> Register User </div></div>
      <div class="modal-body pl-4 pr-4">
         <?php $model=new RegisterUserForm();?>
         <?=$this->render("_form",['model'=>$model])?>
</div>
</div>
</div>
</div>