
<table class="table table-striped">
    <tr><th>#</th><th>Repayment Date</th><th class="">Loan Amount</th><th>Principal</th><th>Interest</th><th>Installment</th><th>Loan Balance</th></tr>
    <?php
      $count=1;
      foreach($loan->repaymentSchedules as $due)
        {
    ?>
     <tr>
        <td><?=$count++ ?></td>
        <td><?=Yii::$app->formatter->asDatetime($due->repayment_date,'php:d M Y') ?></td>
        <td><?=Yii::$app->formatter->asDecimal($due->loan_amount,2)?></td>
        <td><?= Yii::$app->formatter->asDecimal($due->principle_amount,2) ?></td>
        <td><?= Yii::$app->formatter->asDecimal($due->interest_amount,2) ?></td>
        <td><?= Yii::$app->formatter->asDecimal($due->installment_amount,2) ?></td>
        <td><?= Yii::$app->formatter->asDecimal($due->loan_balance,2) ?></td></tr>

    <?php
        }
    ?>
</table>
