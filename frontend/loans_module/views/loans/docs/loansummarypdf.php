<?php
use yii\helpers\Html;
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Loan Summary</title>

</head>
<body>
    <style> 
        body{
            font-size:12px;
        }
        .schedule tr, .schedule td, .schedule th{
            text-align: left;
        }
    </style>
   
<p align="center"><img src="<?= Yii::getAlias('@webroot/img/logo.png') ?>" style="width:120px;height:90px"></img></p>
<h3 style="text-align: center;margin-top:30px;margin-bottom:3px">Loan Summary</h3>
<hr style="margin-bottom: 50px; margin-top:0px">

<!-- LOAN + CUSTOMER INFO SIDE BY SIDE -->
<table width="100%" cellspacing="0" cellpadding="0">
<tr>

<!-- Loan Information -->
<td width="50%" valign="top">
    <table width="100%" cellspacing="2" cellpadding="4">
        <tr>
            <th style="text-align: left; width: 35%;">Loan ID</th>
            <td><?= Html::encode($loan->loanID) ?></td>
        </tr>
        <tr>
            <th style="text-align: left;">Loan Amount</th>
            <td><?= Yii::$app->formatter->asDecimal($loan->loan_amount) ?></td>
        </tr>
        <?php if ($loan->topup_amount > 0): ?>
        <tr>
            <th style="text-align: left;">Top-Up Amount</th>
            <td><?= Yii::$app->formatter->asDecimal($loan->topup_amount) ?></td>
        </tr>
        <?php endif; ?>
        <tr>
            <th style="text-align: left;">Repayment Frequency</th>
            <td><?= Html::encode(ucwords(strtolower($loan->repayment_frequency))) ?></td>
        </tr>
        <tr>
            <th style="text-align: left;">Loan Duration</th>
            <td><?= $loan->loan_duration_units ?> Periods</td>
        </tr>
        <tr>
            <th style="text-align: left;">Interest Rate</th>
            <td><?= Yii::$app->formatter->asPercent($loan->interest_rate / 100, 2) ?></td>
        </tr>
        <tr>
            <th style="text-align: left;">Penalty Rate</th>
            <td><?= Yii::$app->formatter->asPercent($loan->penalty_rate / 100, 2) ?></td>
        </tr>
        <tr>
            <th style="text-align: left;">Penalty Grace Days</th>
            <td><?= $loan->penalty_grace_days ?> Days</td>
        </tr>
        <tr>
            <th style="text-align: left;">Status</th>
            <td><?= Html::encode($loan->status) ?></td>
        </tr>
    </table>
</td>

<!-- Customer Information -->
<td width="50%" valign="top">
    <table width="100%" cellspacing="2" cellpadding="4">
        <tr>
            <th style="text-align: left; width: 35%;">Customer ID</th>
            <td><?= Html::encode($loan->customer->customerID) ?></td>
        </tr>
        <tr>
            <th style="text-align: left;">Full Name</th>
            <td><?= Html::encode($loan->customer->full_name) ?></td>
        </tr>
        <tr>
            <th style="text-align: left;">Birth Date</th>
            <td><?= Yii::$app->formatter->asDate($loan->customer->birthDate, 'php:d M Y') ?></td>
        </tr>
        <tr>
            <th style="text-align: left;">Gender</th>
            <td><?= Html::encode($loan->customer->gender) ?></td>
        </tr>
        <tr>
            <th style="text-align: left;">Contacts</th>
            <td><?= Html::encode($loan->customer->contacts) ?></td>
        </tr>
        <tr>
            <th style="text-align: left;">Address</th>
            <td><?= Html::encode($loan->customer->address) ?></td>
        </tr>
        <tr>
            <th style="text-align: left;">NIN</th>
            <td><?= Html::encode($loan->customer->NIN) ?></td>
        </tr>
        <?php if (!empty($loan->customer->TIN)): ?>
        <tr>
            <th style="text-align: left;">TIN</th>
            <td><?= Html::encode($loan->customer->TIN) ?></td>
        </tr>
        <?php endif; ?>
    </table>
</td>

</tr>
</table>

<!-- Repayment Schedule -->
<h4 style="background-color: #0a6ab3; color: #fff; padding: 4px; margin-top: 15px;">Repayment Schedule</h4>
<table width="100%" cellspacing="2" cellpadding="4" class="schedule">
    <tr>
        <th style="white-space: nowrap;">#</th>
        <th style="white-space: nowrap;">Date</th>
        <th>Amount</th>
        <th>Principal</th>
        <th>Interest</th>
        <th>Installment</th>
        <th>Balance</th>
    </tr>
    <?php 
    $count = 1; 
    $principal=0;
    $interest=0;
    $installment=0;
    $loan_balance=0;
    foreach ($loan->repaymentSchedules as $due){
        
        $principal+=$due->principle_amount;
        $interest+=$due->interest_amount;
        $installment+=$due->installment_amount;
        $loan_balance=$due->loan_balance;
    ?>
    <tr>
        <td style="white-space: nowrap;"><?= $count++ ?></td>
        <td style="white-space: nowrap;"><?= Yii::$app->formatter->asDate($due->repayment_date, 'php:d M Y') ?></td>
        <td><?= Yii::$app->formatter->asDecimal($due->loan_amount, 2) ?></td>
        <td><?= Yii::$app->formatter->asDecimal($due->principle_amount, 2) ?></td>
        <td><?= Yii::$app->formatter->asDecimal($due->interest_amount, 2) ?></td>
        <td><?= Yii::$app->formatter->asDecimal($due->installment_amount, 2) ?></td>
        <td><?= Yii::$app->formatter->asDecimal($due->loan_balance, 2) ?></td>
    </tr>
    <?php } ?>
       <tr>
        <th></th>
        <th>TOTALS</th>
        <th><?= Yii::$app->formatter->asDecimal($loan_balance,2) ?></th>
        <th><?= Yii::$app->formatter->asDecimal($principal,2) ?></th>
        <th><?= Yii::$app->formatter->asDecimal($interest,2) ?></th>
        <th><?= Yii::$app->formatter->asDecimal($installment,2) ?></th>
        <th><?= Yii::$app->formatter->asDecimal($loan_balance,2) ?></th>
    </tr>
</table>


<table width="100%" style="margin-top: 150px;text-align:left">
    <tr>
        <th style="text-align: left;">Approved By</th>
        <td><?= Html::encode($loan->approvedby0->name ?? '_____________________________________________') ?></td>
    </tr>
    <tr>
        <th style="text-align: left;">Date</th>
        <td><?= $loan->approved_at ? Yii::$app->formatter->asDate($loan->approved_at) : '________________' ?></td>
    </tr>
    <tr>
        <th style="text-align: left;">Signature</th>
        <td>___________________________________________________</td>
    </tr>
</table>

<table width="100%" style="margin-top: 40px">
       <tr>
        <th style="text-align: left;">Customer Name</th>
        <td><?=ucfirst($loan->customer->full_name) ?></td>
    </tr>
      <tr>
        <th style="text-align: left;">Date</th>
        <td><?= $loan->approved_at ? Yii::$app->formatter->asDate($loan->approved_at) : '________________' ?></td>
    </tr>
    <tr>
        <th style="text-align: left;">Signature</th>
        <td>________________________________________</td>
    </tr>
  
 
</table>

</body>
</html>
