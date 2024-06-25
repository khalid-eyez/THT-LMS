<?php

use yii\helpers\Html;
use common\models\Meeting;
?>
<div class="modal fade" id="rulemodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
     <div class="modal-header bg-success pl-4 p-1"><div class="modal-title ml-1"><i class='fa fa-plus-circle'></i> New Rule</div></div>
      <div class="modal-body pl-4 pr-4">

    <?= $this->render('rule_form', [
        'model' => $model,
    ]) ?>

</div>
</div>
</div>
</div>
