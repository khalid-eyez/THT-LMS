<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var common\models\User $user */
$user = Yii::$app->user->identity;

$avatarUrl = Yii::getAlias('@web/images/avatar2.png'); // provide image here

$statusText = 'Unknown';
if ($user) {
    if ((int)$user->status === \common\models\User::STATUS_ACTIVE)   $statusText = 'Active';
    if ((int)$user->status === \common\models\User::STATUS_INACTIVE) $statusText = 'Inactive';
    if ((int)$user->status === \common\models\User::STATUS_DELETED)  $statusText = 'Deleted';
}
?>

<div class="wizard-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <div class="wizard-wrap-int">
                <div class="sale-statistic-inner notika-shadow mg-tb-30" style="padding:18px;">

                    <!-- Avatar -->
                    <div class="text-center" style="margin-bottom:14px;">
                        <?= Html::img($avatarUrl, [
                            'alt' => 'Profile Avatar',
                            'class' => 'img-circle',
                            'style' => 'width:120px;height:120px;object-fit:cover;',
                        ]) ?>
                    </div>

                    <h5 class="bg-primary" style="margin-top:6px;margin-bottom:12px;padding:6px;">
                        <i class="fa fa-user"></i> My Profile
                    </h5>

                    <?php if (!$user): ?>
                        <p class="text-danger">No logged-in user found.</p>
                    <?php else: ?>
                        <p><strong>Username:</strong> <?= Html::encode($user->username) ?></p>
                        <p><strong>Name:</strong> <?= Html::encode($user->name ?: '-') ?></p>
                        <p><strong>Status:</strong> <?= Html::encode($statusText) ?></p>
                        <p><strong>Date Registered:</strong>
                            <?= Html::encode(Yii::$app->formatter->asDate($user->created_at, 'dd MMM yyyy')) ?>
                        </p>

                        <hr>

                        <div class="text-center" style="margin-top:10px;">
                            <?= Html::a(
                                '<i class="fa fa-edit"></i> Update Profile',
                                ['/users/update', 'id' => urlencode(base64_encode($user->id))],
                                ['class' => 'btn btn-primary', 'encode' => false]
                            ) ?>
                        </div>
                    <?php endif; ?>

                </div>

            </div>
        </div>
    </div>
</div></div>
