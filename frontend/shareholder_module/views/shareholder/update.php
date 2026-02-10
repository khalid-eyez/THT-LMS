<?php

use yii\helpers\Html;
use common\models\CustomerShareholderForm;
use common\models\Shareholder;
/** @var yii\web\View $this */
/** @var common\models\CustomerShareholderForm $model */


?>
<div class="breadcomb-area bg-white">
    <div class="container bg-white">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 bg-white">
                <div class="wizard-wrap-int">

   <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <!-- Render the same _form.php but with $model pre-filled -->
    <?= $this->render('_form2', [
        'model' => $model,
    ]) ?>

</div></div></div></div></div>

