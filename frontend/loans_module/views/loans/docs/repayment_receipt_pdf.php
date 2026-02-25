<?php
use yii\helpers\Html;

/** @var array $paid */
$statement = $paid['statement'];
?>

<div style="width:100%; font-family: sans-serif; font-size:12px;">

    <!-- LOGO -->
    <div style="text-align:center; margin-bottom:15px;">
        <img src="<?= Yii::getAlias('@webroot/img/logo.png') ?>" style="height:60px;">
    </div>

    <!-- TITLE -->
    <div style="text-align:center; margin-bottom:25px;">
        <h2 style="margin:0;">Loan Repayment Receipt</h2>
        <div style="font-size:11px; color:#555;">
            Official confirmation of payment
        </div>
    </div>

    <!-- CARD 1: BASIC INFO -->
    <div style="margin-bottom:20px;">
        <table width="100%" cellpadding="6" cellspacing="0">
            <tr>
                <td width="40%"><strong>Loan ID</strong></td>
                <td><?= Html::encode($statement->loan->loanID) ?></td>
            </tr>
            <tr>
                <td><strong>Payment Date</strong></td>
                <td>
                    <?= Yii::$app->formatter->asDate($statement->payment_date) ?>
                </td>
            </tr>
            <tr>
                <td><strong>Reference No</strong></td>
                <td><?= Html::encode($paid['reference']) ?></td>
            </tr>
        </table>
    </div>

    <!-- CARD 2: PAYMENT BREAKDOWN -->
    <div style="margin-bottom:20px;">
        <table width="100%" cellpadding="6" cellspacing="0">
            <tr>
                <td width="40%"><strong>Principal Paid</strong></td>
                <td>
                    <?= Yii::$app->formatter->asDecimal(abs($statement->paid_amount), 2) ?>
                </td>
            </tr>
            <tr>
                <td><strong>Overdue Paid</strong></td>
                <td>
                    <?= Yii::$app->formatter->asDecimal(abs(($statement->unpaid_amount<0)?$statement->unpaid_amount:0), 2) ?>
                </td>
            </tr>
            <tr>
                <td><strong>Penalty Paid</strong></td>
                <td>
                    <?= Yii::$app->formatter->asDecimal(abs($statement->penalty_amount), 2) ?>
                </td>
            </tr>
            <tr>
                <td><strong>Prepayment</strong></td>
                <td>
                    <?= Yii::$app->formatter->asDecimal($statement->prepayment, 2) ?>
                </td>
            </tr>
        </table>
    </div>

    <!-- TOTAL PAID -->
    <div style="margin-bottom:30px;">
        <table width="100%" cellpadding="8" cellspacing="0">
            <tr>
                <td width="40%" style="font-size:14px;"><strong>TOTAL PAID</strong></td>
                <td style="font-size:16px; font-weight:bold;">
                    <?= Yii::$app->formatter->asDecimal($statement->paid_amount, 2) ?> TZS
                </td>
            </tr>
        </table>
    </div>

    <!-- SIGNATURE & STAMP -->
    <div style="margin-top:50px;">
        <table width="100%" cellpadding="6" cellspacing="0">
            <tr>
                <td width="50%" style="text-align:center;">
                    <div style="margin-bottom:40px;"><strong>Processed By: </strong><?=ucfirst(Html::encode(yii::$app->user->identity?->name)) ?></div>
                    
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
        This document is system generated and serves as proof of loan repayment.
    </div>

</div>
