<div style="overflow-x:auto; width:100%;">
<table class="table table-bordered table-striped">
    <tbody>
        <tr>
            <th width="30%">Loan ID</th>
            <td><?= $model->loanID ?></td>
        </tr>

        <tr>
            <th>Customer</th>
            <td>
                <?= $model->customer->customerID ?>
                [<?= $model->customer->full_name ?>]
            </td>
        </tr>

        <tr>
            <th>Loan Type</th>
            <td><?= $model->loanType->type ?></td>
        </tr>

        <tr>
            <th>Loan Amount</th>
            <td><?= Yii::$app->formatter->asDecimal($model->loan_amount, 2) ?></td>
        </tr>

        <tr>
            <th>Topup Amount</th>
            <td><?= Yii::$app->formatter->asDecimal($model->topup_amount, 2) ?></td>
        </tr>
        <tr>
            <th>Repayment Frequency</th>
            <td><?= $model->displayRepaymentFrequency() ?></td>
        </tr>

        <tr>
            <th>Loan Duration</th>
            <td><?= $model->loan_duration_units ?> </td>
        </tr>

        <tr>
            <th>Processing Fee Rate</th>
            <td><?= $model->processing_fee_rate ?> %</td>
        </tr>

        <tr>
            <th>Processing Fee</th>
            <td><?= Yii::$app->formatter->asDecimal($model->processing_fee, 2) ?></td>
        </tr>

        <tr>
            <th>Interest Rate</th>
            <td><?= $model->interest_rate ?> %</td>
        </tr>

        <tr>
            <th>Penalty Rate</th>
            <td><?= $model->penalty_rate ?> %</td>
        </tr>

        <tr>
            <th>Penalty Grace Days</th>
            <td><?= $model->penalty_grace_days ?></td>
        </tr>

        <tr>
            <th>Status</th>
            <td>
                <span class="badge badge-info">
                    <?= $model->displayStatus() ?>
                </span>
            </td>
        </tr>

        <tr>
            <th>Initialized By</th>
            <td><?= $model->initializedby0->name ?> [<?= $model->initializedby0->username ?>]</td>
        </tr>

        <tr>
            <th>Approved By</th>
            <td><?=$model->approvedby0->name ?? '-' ?> [<?= $model->approvedby0->username ?? '-' ?>]</td>
        </tr>

        <tr>
            <th>Approved At</th>
            <td><?= Yii::$app->formatter->asDatetime($model->approved_at) ?></td>
        </tr>

        <tr>
            <th>Created At</th>
            <td><?= Yii::$app->formatter->asDatetime($model->created_at) ?></td>
        </tr>

        <tr>
            <th>Last Updated</th>
            <td><?= Yii::$app->formatter->asDatetime($model->updated_at) ?></td>
        </tr>
    </tbody>
</table>
</div>