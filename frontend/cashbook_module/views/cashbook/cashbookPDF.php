<?php
use Yii;

[$start, $end] = ($model->date_range==null)?[date("Y-m-d H:i:s"),date("Y-m-d H:i:s")]:explode(' - ', $model->date_range);
?>

<style>
    body {
        font-family: sans-serif;
        font-size: 12px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    #cashtable td {
        padding: 6px;
    }

    #heading td {
        font-weight: bold;
        background-color: #2890c5;
        color: #ffffff;
        text-align: center;
    }

    .text-right {
        text-align: right;
    }

    .text-left {
        text-align: left;
    }

    .bold td {
        font-weight: bold;
        border-top:solid 1px black
    }
</style>
<p align="center"><img src="<?= Yii::getAlias('@webroot/img/logo.png') ?>" style="width:120px;height:90px"></img></p>
<h3 style="text-align: center;margin-top:30px;margin-bottom:3px">CASHBOOK REPORT</h3>
<hr style="margin-bottom: 50px; margin-top:0px">
<div>

    <!-- Header -->
    <table style="margin-bottom:30px;">
        <tr>
            <td class="text-left">
                <strong>Start Date:</strong> <?= date('d M Y', strtotime($start)) ?><br>
                <strong>End Date:</strong> <?= date('d M Y', strtotime($end)) ?>
            </td>
            <td class="text-right">
                <strong>Currency:</strong> TZS
            </td>
        </tr>
    </table>

<?php
$records = $model->getCashFlows();
if (!empty($records)) {

    $obalance = $records[0]->openingBalance();
    $balancedisp = ($obalance < 0)
        ? Yii::$app->formatter->asDecimal(abs($obalance), 2) . ' C'
        : Yii::$app->formatter->asDecimal(abs($obalance), 2) . ' D';
?>

    <!-- Opening Balance -->
    <table style="margin-bottom:30px;">
        <tr>
            <td class="text-right">
               <strong> Opening Balance: </strong><?= $balancedisp ?>
            </td>
        </tr>
    </table>

    <!-- Cashbook Table -->
    <table id="cashtable">
        <tr id="heading">
            <td>DATE</td>
            <td>REFERENCE</td>
            <td>DESCRIPTION</td>
            <td>DEBIT</td>
            <td>CREDIT</td>
            <td>BALANCE</td>
        </tr>

        <?php
        $totaldebit = 0;
        $totalcredit = 0;
        $finalcumul = 0;

        foreach ($records as $record) {
            $totaldebit += $record->debit;
            $totalcredit += $record->credit;
            $finalcumul = $record->balance;
        ?>
        <tr>
            <td><?= date('d M Y', strtotime($record->created_at)) ?></td>
            <td><?= $record->reference_no ?></td>
            <td><?= $record->description ?></td>
            <td class="text-right"><?= Yii::$app->formatter->asDecimal(abs($record->debit), 2) ?></td>
            <td class="text-right"><?= Yii::$app->formatter->asDecimal(abs($record->credit), 2) ?></td>
            <td class="text-right">
                <?= ($record->balance < 0)
                    ? Yii::$app->formatter->asDecimal(abs($record->balance), 2) . ' C'
                    : Yii::$app->formatter->asDecimal(abs($record->balance), 2) . ' D'
                ?>
            </td>
        </tr>
        <?php } ?>

        <!-- Totals -->
        <tr class="bold">
            <td colspan="3" class="text-right">TOTAL</td>
            <td class="text-right"><?= Yii::$app->formatter->asDecimal(abs($totaldebit), 2) ?></td>
            <td class="text-right"><?= Yii::$app->formatter->asDecimal(abs($totalcredit), 2) ?></td>
            <td class="text-right">
                <?= ($finalcumul < 0)
                    ? Yii::$app->formatter->asDecimal(abs($finalcumul), 2) . ' Cr'
                    : Yii::$app->formatter->asDecimal(abs($finalcumul), 2) . ' Dr'
                ?>
            </td>
        </tr>
    </table>

<?php } ?>
</div>
