<div style="overflow-x:auto; width:100%;">
<table class="table table-striped">
    <tr>
        <th>#</th>
        <th>Date</th>
        <th class="">Loan</th>
        <th>Top-up</th>
        <th>Principal</th>
        <th>Interest</th>
        <th>Installment</th>
        <th>Paid</th>
        <th>Unpaid</th>
        <th>Penalty</th>
        <th>Prepayment</th>
        <th>Balance</th>
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
       <tr>
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
</div>
