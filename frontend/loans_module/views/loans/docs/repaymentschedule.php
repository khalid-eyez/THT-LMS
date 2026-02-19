<style> 


</style>
<div style="overflow-x:auto; width:100%;">
<table class="table table-striped">
    <tr class="heading"><th>#</th><th>Repayment Date</th><th class="">Loan Amount</th><th>Principal</th><th>Interest</th><th>Installment</th><th>Loan Balance</th></tr>
    <?php
      $count=1;
      $principal=0;
    $interest=0;
    $installment=0;
    $loan_balance=0;
      foreach($loan->repaymentSchedules as $due)
        {
        $principal+=$due->principle_amount;
        $interest+=$due->interest_amount;
        $installment+=$due->installment_amount;
        $loan_balance=$due->loan_balance;
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
</div>
