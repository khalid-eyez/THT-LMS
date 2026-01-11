<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\CustomerShareholderForm $model */

$this->title = 'Register Shareholder';
$this->params['breadcrumbs'][] = ['label' => 'Shareholders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shareholder-create">

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><?= Html::encode($this->title) ?></h4>
        </div>

        <div class="card-body">

            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>

        </div>
    </div>

</div>

