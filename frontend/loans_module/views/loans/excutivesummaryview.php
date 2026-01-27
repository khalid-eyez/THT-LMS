<?php
use yii;

[$start, $end] = explode(' - ', $model->date_range);

$report = $model->buildLoansExecutiveSummaryReport();
$loans  = $report['loans'] ?? [];
$totals = $report['totals'] ?? [];

/**
 * Decide which columns to show
 * Column key => ['label' => '', 'total_key' => '']
 */
$columns = [
    'loan_amount' => [
        'label' => 'LOAN AMOUNT',
        'total_key' => 'loan_amount_total',
        'always' => true, // always show
    ],
    'topup_amount_total' => [
        'label' => 'TOP UP',
        'total_key' => 'topup_amount_total',
    ],
    'installment_total' => [
        'label' => 'INSTALLMENT',
        'total_key' => 'installment_total',
    ],
    'paid_amount_total' => [
        'label' => 'PAID',
        'total_key' => 'paid_amount_total',
    ],
    'unpaid_amount_total' => [
        'label' => 'UNPAID',
        'total_key' => 'unpaid_amount_total',
    ],
    'penalty_amount_total' => [
        'label' => 'PENALTY',
        'total_key' => 'penalty_amount_total',
    ],
    'prepayment_total' => [
        'label' => 'PREPAYMENT',
        'total_key' => 'prepayment_total',
    ],
    'balance' => [
        'label' => 'BALANCE',
        'total_key' => 'balance_total',
        'always' => true, // always show
    ],
];

// Filter columns whose totals are zero (unless forced)
$visibleColumns = [];
foreach ($columns as $key => $config) {
    if (!empty($config['always']) || !empty($totals[$config['total_key']])) {
        $visibleColumns[$key] = $config;
    }
}
?>

<style>
    #summarytable td {
        border: none;
        padding-top: 6px;
        padding-bottom: 6px;
        vertical-align: middle;
    }
    #heading td {
        font-weight: bold;
        background-color: rgba(2,106,189,0.05);
        white-space: nowrap;
    }
</style>

<div class="card-box">

    <!-- Date range header -->
    <div class="row mb-3">
        <div class="col-sm-12">
            Start Date: <?= date('d M Y', strtotime($start)) ?><br>
            End Date: <?= date('d M Y', strtotime($end)) ?>
        </div>
    </div>

    <?php if (!empty($loans)) : ?>

        <table id="summarytable" class="table table-striped nowrap">
            <tr id="heading">
                <td>CUSTOMER ID</td>
                <td>LOAN ID</td>

                <?php foreach ($visibleColumns as $col): ?>
                    <td><?= $col['label'] ?></td>
                <?php endforeach; ?>
            </tr>

            <?php foreach ($loans as $row) : ?>
                <tr>
                    <td><?= $row['customerID'] ?></td>
                    <td><?= $row['loanID'] ?></td>

                    <?php foreach ($visibleColumns as $key => $col): ?>
                        <td>
                            <?= yii::$app->formatter->asDecimal($row[$key] ?? 0, 2) ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>

            <!-- Totals row -->
            <tr style="font-weight:bold">
                <td colspan="2" style="text-align:right">TOTAL:</td>

                <?php foreach ($visibleColumns as $key => $col): ?>
                    <td>
                        <?= yii::$app->formatter->asDecimal($totals[$col['total_key']] ?? 0, 2) ?>
                    </td>
                <?php endforeach; ?>
            </tr>
        </table>

    <?php else : ?>
        <div class="alert alert-warning">
            No loans found for the selected date range.
        </div>
    <?php endif; ?>

</div>
