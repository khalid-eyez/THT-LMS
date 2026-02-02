<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\CustomerShareholderForm $model */

?>
 <div class="wizard-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="wizard-wrap-int">

    <div class="card shadow-sm">
        <div class="card-body">

            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>

        </div>
    </div>

</div></div></div></div></div>

