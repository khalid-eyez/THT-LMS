
<b>Total Unpaid:</b> <?=yii::$app->formatter->asDecimal($overdues['total_unpaid']??0,2) ?> TZS<br>
<b>Total Penalty:</b> <?= yii::$app->formatter->asDecimal($overdues['total_penalties']??0,2) ?> TZS<br>
<b>Installment:</b> <?= yii::$app->formatter->asDecimal($overdues['installment']??0,2) ?> TZS
<hr>
<b>GRAND TOTAL:</b> <?= yii::$app->formatter->asDecimal($overdues['total_repayment']??0,2) ?> TZS
