<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\TblAuditEntry */

$this->title = 'Create Tbl Audit Entry';
$this->params['breadcrumbs'][] = ['label' => 'Tbl Audit Entries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbl-audit-entry-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
