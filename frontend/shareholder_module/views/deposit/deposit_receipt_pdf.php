<?php
use yii\helpers\Html;

/** @var \common\models\Cashbook $cashbook */

// Total interest should be either credit or debit (whichever is > 0)
$totalDeposit = ($cashbook->credit > 0)
    ? $cashbook->credit
    : $cashbook->debit;
?>

<div style="width:100%; font-family: sans-serif; font-size:12px;">

    <!-- LOGO -->
    <div style="text-align:center; margin-bottom:15px;">
        <img src="<?= Yii::getAlias('@webroot/img/logo.png') ?>" style="height:60px;">
    </div>

    <!-- TITLE -->
    <div style="text-align:center; margin-bottom:25px;">
        <h2 style="margin:0;">Shareholder Deposit Receipt</h2>
        <div style="font-size:11px; color:#555;">
            Official confirmation of Deposit
        </div>
    </div>

    <!-- CARD 1: BASIC INFO -->
    <div style="margin-bottom:20px;">
        <table width="100%" cellpadding="6" cellspacing="0">
            <tr>
                <td width="40%"><strong>Reference No</strong></td>
                <td><?= Html::encode($cashbook->reference_no) ?></td>
            </tr>
            <tr>
                <td><strong>Payment Date</strong></td>
                <td><?= Yii::$app->formatter->asDate($cashbook->created_at) ?></td>
            </tr>
            <tr>
                <td><strong>Category</strong></td>
                <td><?= Html::encode($cashbook->category) ?></td>
            </tr>
        </table>
    </div>

    <!-- CARD 2: PAYMENT DETAILS -->
    <div style="margin-bottom:20px;">
        <table width="100%" cellpadding="6" cellspacing="0">
            <tr>
                <td width="40%"><strong>Description</strong></td>
                <td><?= Html::encode($cashbook->description) ?></td>
            </tr>
            <tr>
                <td><strong>Deposit Amount</strong></td>
                <td><?= Yii::$app->formatter->asDecimal(abs($totalDeposit), 2) ?></td>
            </tr>
           
        </table>
    </div>

    <!-- TOTAL PAID -->
    <div style="margin-bottom:30px;">
        <table width="100%" cellpadding="8" cellspacing="0">
            <tr>
                <td width="40%" style="font-size:14px;"><strong>TOTAL DEPOSIT</strong></td>
                <td style="font-size:16px; font-weight:bold;">
                    <?= Yii::$app->formatter->asDecimal(abs($totalDeposit), 2) ?> TZS
                </td>
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
        This document is system generated and serves as proof of shareholder deposit.
    </div>

</div>
