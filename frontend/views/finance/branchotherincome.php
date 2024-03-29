<?php

use yii\helpers\Html;
use common\models\Branchotherincomes;
?>
<div class="modal fade" id="branchotherincomemodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
     <div class="modal-header bg-success pl-4 p-1"><div class="modal-title ml-1"><i class='fa fa-arrow-down'></i> Other Income </div></div>
      <div class="modal-body pl-4 pr-4">

    <?= $this->render('branchotherincomeform', [
        'model' => new Branchotherincomes,
    ]) ?>

</div>
</div>
</div>
</div>
