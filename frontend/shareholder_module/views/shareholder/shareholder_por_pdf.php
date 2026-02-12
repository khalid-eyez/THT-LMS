<?php
use yii\helpers\Html;

/** @var \common\models\Shareholder $shareholder */

$customer = $shareholder->customer;

// Total deposits (excluding capital because your getDeposits() already filters it)
$totalDeposits = (float) $shareholder->totalDeposits(null, null);

// Initial capital
$initialCapital = (float) $shareholder->initialCapital;
?>

<div style="width:100%; font-family: sans-serif; font-size:12px;">

    <!-- LOGO -->
    <div style="text-align:center; margin-bottom:15px;">
        <img src="<?= Yii::getAlias('@webroot/img/logo.png') ?>" style="height:60px;">
    </div>

    <!-- TITLE -->
    <div style="text-align:center; margin-bottom:25px;">
        <h2 style="margin:0;">Shareholder Proof of Registration</h2>
        <div style="font-size:11px; color:#555;">
            Official confirmation of Shareholder Registration
        </div>
    </div>

    <!-- CARD 1: SHAREHOLDER INFO -->
    <div style="margin-bottom:20px;">
        <table width="100%" cellpadding="6" cellspacing="0">
            <tr>
                <td width="40%"><strong>Member ID</strong></td>
                <td><?= Html::encode($shareholder->memberID) ?></td>
            </tr>
            <tr>
                <td><strong>Shareholder No</strong></td>
                <td><?= Html::encode($shareholder->id) ?></td>
            </tr>
            <tr>
                <td><strong>Registration Date</strong></td>
                <td>
                    <?php
                    // If you don't have created_at on shareholders table, show "N/A"
                    $regDate = $shareholder->created_at ?? null;
                    echo $regDate ? Yii::$app->formatter->asDate($regDate) : 'N/A';
                    ?>
                </td>
            </tr>
            <tr>
                <td><strong>Status</strong></td>
                <td><?= ((int)$shareholder->isDeleted === 1) ? 'Deleted' : 'Active' ?></td>
            </tr>
        </table>
    </div>

    <!-- CARD 2: CUSTOMER DETAILS -->
    <div style="margin-bottom:20px;">
        <table width="100%" cellpadding="6" cellspacing="0">
            <tr>
                <td width="40%"><strong>Customer ID</strong></td>
                <td><?= Html::encode($customer?->customerID) ?></td>
            </tr>
            <tr>
                <td><strong>Full Name</strong></td>
                <td><?= Html::encode($customer?->full_name) ?></td>
            </tr>
            <tr>
                <td><strong>Birth Date</strong></td>
                <td><?= $customer?->birthDate ? Yii::$app->formatter->asDate($customer->birthDate) : '' ?></td>
            </tr>
            <tr>
                <td><strong>Gender</strong></td>
                <td><?= Html::encode($customer?->gender) ?></td>
            </tr>
            <tr>
                <td><strong>Contacts</strong></td>
                <td><?= Html::encode($customer?->contacts) ?></td>
            </tr>
            <tr>
                <td><strong>Address</strong></td>
                <td><?= Html::encode($customer?->address) ?></td>
            </tr>
            <tr>
                <td><strong>NIN</strong></td>
                <td><?= Html::encode($customer?->NIN) ?></td>
            </tr>
            <tr>
                <td><strong>TIN</strong></td>
                <td><?= Html::encode($customer?->TIN) ?></td>
            </tr>
            <tr>
                <td><strong>Customer Status</strong></td>
                <td><?= Html::encode($customer?->status) ?></td>
            </tr>
        </table>
    </div>

    <!-- CARD 3: SHARE CAPITAL & DEPOSITS -->
    <div style="margin-bottom:30px;">
        <table width="100%" cellpadding="6" cellspacing="0">
            <tr>
                <td width="40%"><strong>Initial Capital</strong></td>
                <td><?= Yii::$app->formatter->asDecimal($initialCapital, 2) ?> TZS</td>
            </tr>
            <tr>
                <td><strong>Shares</strong></td>
                <td><?= Yii::$app->formatter->asInteger((int)$shareholder->shares) ?></td>
            </tr>
            <tr>
                <td><strong>Total Deposits (Excl. Capital)</strong></td>
                <td><?= Yii::$app->formatter->asDecimal($totalDeposits, 2) ?> TZS</td>
            </tr>
        </table>
    </div>

    <!-- SIGNATURE & STAMP -->
    <div style="margin-top:50px;">
        <table width="100%" cellpadding="6" cellspacing="0">
            <tr>
                <td width="50%" style="text-align:center;">
                    <div style="margin-bottom:40px;">
                        <strong>Processed By: </strong><?= ucfirst(Html::encode(Yii::$app->user->identity?->name)) ?>
                    </div>
                </td>
                <td width="50%" style="text-align:center;">
                    <div style="margin-bottom:40px;">______________________________</div>
                    <strong>Official Stamp & Signature</strong>
                </td>
            </tr>
        </table>
    </div>

    <!-- FOOTER NOTE -->
    <div style="margin-top:30px; text-align:center; font-size:10px; color:#666;">
        This document is system generated and serves as proof of shareholder registration.
    </div>

</div>
