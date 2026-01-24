<?php
use yii\helpers\Html;
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>
<style>
    tr.totals th{
       border-top: 1px solid #393939; 
       background-color: #f8f9fa;
    }
</style>
 <style>
    body {
        font-family: sans-serif;
        font-size: 12px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }
      .heading td {
        font-weight: bold;
        background-color: #2890c5;
        color: #ffffff;
    }
    .totals th{
       text-align:left;
    }
</style>
<p align="center"><img src="<?= Yii::getAlias('@webroot/img/logo.png') ?>" style="width:120px;height:90px"></img></p>
<h3 style="text-align: center;margin-top:30px;margin-bottom:3px">Customer Loan Repayment Statement</h3>
<hr style="margin-bottom: 50px; margin-top:0px">

<!-- LOAN + CUSTOMER INFO SIDE BY SIDE -->
<table width="100%" cellspacing="0" cellpadding="0" >
<tr>

<!-- Loan Information -->
<td width="50%" valign="top">
    <table width="100%" cellspacing="2" cellpadding="4">
        <tr>
            <th style="text-align: left; width: 35%;">Loan ID</th>
            <td><?= Html::encode($loan->loanID) ?></td>
        </tr>
          <tr>
            <th style="text-align: left; width: 35%;">Customer Name</th>
            <td><?= Html::encode($loan->customer->full_name) ?></td>
        </tr>
            <tr>
            <th style="text-align: left; width: 35%;">Customer ID</th>
            <td><?= Html::encode($loan->customer->customerID) ?></td>
        </tr>
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
            <th style="text-align: left;">Status</th>
            <td><?= Html::encode($loan->status) ?></td>
        </tr>
    </table>
</td>

<!-- Customer Information -->
<td width="50%" valign="top">
    <table width="100%" cellspacing="2" cellpadding="4" >
        <tr>
            <th style="text-align: left; width: 35%;">Loan Amount</th>
            <td><?= Yii::$app->formatter->asDecimal($loan->loan_amount+$loan->topup_amount,2) ?></td>
        </tr>
        <tr>
            <th style="text-align: left;">Interest Amount</th>
            <td><?=Yii::$app->formatter->asDecimal($loan->getRepaymentSchedules()->sum('interest_amount'),2) ?></td>
        </tr>
        <tr>
            <th style="text-align: left;">Total Repayment</th>
            <td><?= Yii::$app->formatter->asDecimal($loan->getRepaymentSchedules()->sum('installment_amount'),2) ?></td>
        </tr>
           <tr>
            <th style="text-align: left;">Currency</th>
            <td>TZS</td>
        </tr>
       
      
    </table>
</td>

</tr>
</table>


<table width="100%" cellspacing="2" cellpadding="3" style="margin-top:40px">
    <tr class="heading">
       <td>#</td>
        <td>Date</td>
        <td class="">Loan</td>
        <td>Top-up</td>
        <td>Principal</td>
        <td>Interest</td>
        <td>Installment</td>
        <td>Paid</td>
        <td>Unpaid</td>
        <td>Penalty</td>
        <td>Prepayment</td>
        <td>Balance</td>
    </tr>
    <?php
      $count=1;
      $principal=0;
    $interest=0;
    $installment=0;
    $paid=0;
    $unpaid=0;
    $penalties=0;
    $prepayment=0;
    $topup=0;
    $balance=0;
      foreach($loan->repaymentStatements as $due)
        {
        $principal+=$due->principal_amount;
        $interest+=$due->interest_amount;
        $installment+=$due->installment;
        $balance=$due->balance;
        $topup+=$due->topup_amount;
        $prepayment+=$due->prepayment;
        $penalties+=$due->penalty_amount;
        $paid+=$due->paid_amount;
        $unpaid+=$due->unpaid_amount;


    ?>
     <tr>
        <td><?=$count++ ?></td>
        <td><?=Yii::$app->formatter->asDatetime($due->payment_date,'php:d M Y') ?></td>
        <td><?=Yii::$app->formatter->asDecimal($due->loan_amount,2)?></td>
        <td><?=Yii::$app->formatter->asDecimal($due->topup_amount,2)?></td>
        <td><?= Yii::$app->formatter->asDecimal($due->principal_amount,2) ?></td>
        <td><?= Yii::$app->formatter->asDecimal($due->interest_amount,2) ?></td>
        <td><?= Yii::$app->formatter->asDecimal($due->installment,2) ?></td>
        <td><?= Yii::$app->formatter->asDecimal($due->paid_amount,2) ?></td>
        <td><?=Yii::$app->formatter->asDecimal($due->unpaid_amount,2)?></td>
        <td><?=Yii::$app->formatter->asDecimal($due->penalty_amount,2)?></td>
        <td><?= Yii::$app->formatter->asDecimal($due->prepayment,2) ?></td>
        <td><?= Yii::$app->formatter->asDecimal($due->balance,2) ?></td>
</tr>

    <?php
        }
    ?>
       <tr class="totals">
        <th></th>
        <th>TOTALS</th>
        <th><?= Yii::$app->formatter->asDecimal($balance,2) ?></th>
        <th><?= Yii::$app->formatter->asDecimal($topup,2) ?></th>
        <th><?= Yii::$app->formatter->asDecimal($principal,2) ?></th>
        <th><?= Yii::$app->formatter->asDecimal($interest,2) ?></th>
        <th><?= Yii::$app->formatter->asDecimal($installment,2) ?></th>
        <th><?= Yii::$app->formatter->asDecimal($paid,2) ?></th>
        <th><?= Yii::$app->formatter->asDecimal($unpaid,2) ?></th>
        <th><?= Yii::$app->formatter->asDecimal($penalties,2) ?></th>
        <th><?= Yii::$app->formatter->asDecimal($prepayment,2) ?></th>
        <th><?= Yii::$app->formatter->asDecimal($balance,2) ?></th>
    </tr>
</table>






</body>
</html>
