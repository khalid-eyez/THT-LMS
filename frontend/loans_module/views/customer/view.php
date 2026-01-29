<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var common\models\Customer $customer */

$shareholder = $customer->shareholder;
$loans = $customer->customerLoans ?: [];

/* shareholder aggregates */
$totalDeposits = 0;
$totalDepositInterest = 0;

if ($shareholder) {
    foreach ($shareholder->deposits as $deposit) {
        $totalDeposits += (float) $deposit->amount;
        foreach ($deposit->depositInterests as $interest) {
            $totalDepositInterest += (float) $interest->amount;
        }
    }
}

/* general avatar image */
$avatarUrl = Yii::getAlias('@web/images/avatar2.png');

/* update url for ajax load */
$updateUrl = Url::to(['update', 'id' => $customer->id]);

/* delete + index urls */
$deleteUrl = Url::to(['/loans/customer/delete', 'id' => $customer->id]);
$indexUrl  = Url::to(['/loans/customer/index']);
?>

<div class="wizard-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="container">
                    <div class="row">

                        <!-- LEFT: CUSTOMER INFO ONLY -->
                        <div class="col-lg-3 col-md-4 col-sm-5 col-xs-12 col-12">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                    <!-- Customer Info -->
                                    <div class="statistic-right-area notika-shadow mg-tb-30 sm-res-mg-t-0">

                                        <!-- Avatar -->
                                        <div class="text-center mb-3">
                                            <?= Html::img($avatarUrl, [
                                                'alt' => 'Customer Avatar',
                                                'class' => 'img-circle',
                                                'style' => 'width:110px;height:110px;object-fit:cover;',
                                            ]) ?>
                                        </div>

                                        <p><strong>Customer ID:</strong> <?= Html::encode($customer->customerID) ?></p>
                                        <p><strong>Full Name:</strong> <?= Html::encode(ucfirst($customer->full_name)) ?></p>
                                        <p><strong>NIN:</strong> <?= Html::encode($customer->NIN) ?></p>
                                        <p><strong>TIN:</strong> <?= Html::encode($customer->TIN) ?? "-" ?></p>
                                        <p><strong>Birth Date:</strong> <?= Html::encode(Yii::$app->formatter->asDate($customer->birthDate, 'dd MMM yyyy')) ?></p>
                                        <p><strong>Address:</strong> <?= Html::encode($customer->address) ?></p>
                                        <p><strong>Contacts:</strong> <?= Html::encode($customer->contacts) ?></p>
                                        <p><strong>Status:</strong> <?= Html::encode(ucfirst($customer->displayStatus())) ?></p>

                                        <hr>

                                        <div class="mt-3 text-center">
                                            <?= Html::a(
                                                'Apply Loan',
                                                ['/loans/loans/create-loan-reg', 'customerID' => $customer->id],
                                                ['class' => 'btn btn-success btn-sm mr-1 pay']
                                            ) ?>

                                            <!-- UPDATE -->
                                            <?= Html::a(
                                                'Update',
                                                $updateUrl,
                                                [
                                                    'class' => 'btn btn-primary btn-sm mr-1 js-customer-update',
                                                    'data-url' => $updateUrl,
                                                ]
                                            ) ?>

                                            <!-- DELETE (AJAX GET) -->
                                            <?= Html::a(
                                                'Delete',
                                                'javascript:void(0)',
                                                [
                                                    'class' => 'btn btn-danger btn-sm js-customer-delete',
                                                    'data-url' => $deleteUrl,
                                                ]
                                            ) ?>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- RIGHT: LOANS CARD + SHAREHOLDER CARD (below) -->
                        <div class="col-lg-9 col-md-8 col-sm-7 col-xs-12">

                            <!-- CARD 1: LOAN HISTORY + FINANCIAL SUMMARY -->
                            <div class="sale-statistic-inner notika-shadow mg-tb-30">

                                <!-- Loan History -->
                                <h5 class="mb-3 bg-primary" style="margin-top:6px; margin-bottom:12px; padding:4px">
                                    <i class="fa fa-history"></i> Loan History
                                </h5>

                                <?php if (!empty($loans)): ?>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover table-sm mb-4">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Loan ID</th>
                                                    <th>Amount</th>
                                                    <th>Loan Type</th>
                                                    <th>Duration</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach ($loans as $loan): ?>
                                                <tr>
                                                    <td><?= Html::encode($loan->loanID) ?></td>
                                                    <td><?= Yii::$app->formatter->asDecimal($loan->loan_amount) ?></td>
                                                    <td><?= Html::encode($loan->loanType->name ?? '-') ?></td>
                                                    <td><?= Html::encode($loan->loan_duration_units) ?></td>
                                                    <td><?= Html::encode($loan->displayStatus()) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted">No loans found.</p>
                                <?php endif; ?>

                                <!-- Financial Summary -->
                                <h5 class="mt-4 mb-2 bg-primary" style="margin-top:24px;margin-bottom:12px; padding:4px">
                                    <i class="fa fa-bar-chart"></i> Financial Summary
                                </h5>
                                <p><strong>Total Loans:</strong> <?= count($loans) ?></p>
                                <p><strong>Active Loans:</strong>
                                    <?= count(array_filter($loans, function ($loan) {
                                        return method_exists($loan, 'isStatusActive') && $loan->isStatusActive();
                                    })) ?>
                                </p>

                            </div>

                            <!-- CARD 2: SHAREHOLDER INFORMATION -->
                            <?php if ($shareholder): ?>
                                <div class="sale-statistic-inner notika-shadow mg-tb-30">

                                    <h5 class="mb-3 bg-primary" style="margin-top:6px; margin-bottom:12px; padding:4px">
                                        <i class="fa fa-users"></i> Shareholder Information
                                    </h5>

                                    <p><strong>Member ID:</strong> <?= Html::encode($shareholder->memberID) ?></p>
                                    <p><strong>Initial Capital:</strong>
                                        <?= Yii::$app->formatter->asDecimal($shareholder->initialCapital) ?>
                                    </p>
                                    <p><strong>Total Deposits:</strong>
                                        <?= Yii::$app->formatter->asDecimal($totalDeposits) ?>
                                    </p>
                                    <p><strong>Total Interest:</strong>
                                        <?= Yii::$app->formatter->asDecimal($totalDepositInterest) ?>
                                    </p>

                                </div>
                            <?php endif; ?>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ✅ UPDATE MODAL (content loaded via ajax) -->
<div class="modal fade animated rubberBand" style="margin-top: 15px;" id="customerUpdateModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header bg-primary" style="padding:9px;">
                <i class="fa fa-edit"></i> Update Customer
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff; opacity:1;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body" id="customerUpdateModalBody" style="min-height:150px;">
                <div class="text-center text-muted" style="padding:20px;">
                    Loading...
                </div>
            </div>

        </div>
    </div>
</div>

<?php
$this->registerJs(<<<JS
(function () {

    // ---------- UPDATE (modal load) ----------
    $(document).off('click.customerUpdateModal');

    $(document).on('click.customerUpdateModal', '.js-customer-update', function (e) {
        e.preventDefault();

        var url = $(this).data('url') || $(this).attr('href');
        if (!url) return false;

        $('#customerUpdateModalBody').html('<div class="text-center text-muted" style="padding:20px;">Loading...</div>');
        $('#customerUpdateModal').modal('show');

        // show loader after modal appears
        // $('#customerUpdateModal').one('shown.bs.modal', function () {
        //     if ($('#global-loader').length) $('#global-loader').show();
        // });

        $.ajax({
            url: url,
            type: 'GET',
            cache: false,
            success: function (html) {
                $('#customerUpdateModalBody').html(html);
            },
            error: function () {
                $('#customerUpdateModalBody').html('<div class="alert alert-danger" style="margin:0;">Failed to load the update form.</div>');
            },
            complete: function () {
                if ($('#global-loader').length) $('#global-loader').hide();
            }
        });

        return false;
    });

    // ---------- DELETE (GET -> then load index into .content) ----------
    $(document).off('click.customerDelete');

    $(document).on('click.customerDelete', '.js-customer-delete', function (e) {
        e.preventDefault();

        var delUrl = $(this).data('url');
        if (!delUrl) return false;

        if (!confirm('Are you sure you want to delete this customer?')) {
            return false;
        }

        // one toast while loading
        if (window.toastr) {
            toastr.clear();
            toastr.remove();
            toastr.info('Deleting customer, please wait...');
        }

        if ($('#global-loader').length) $('#global-loader').show();

        $.ajax({
            url: delUrl,
            type: 'GET',
            cache: false,
            success: function () {
                // load index into .content
                $('.content').load('{$indexUrl}', function () {
                    if (window.toastr) {
                        toastr.clear();
                        toastr.remove();
                        toastr.success('Customer deleted successfully!');
                    }
                });
            },
            error: function () {
                if (window.toastr) {
                    toastr.clear();
                    toastr.remove();
                    toastr.error('Delete failed');
                } else {
                    alert('Delete failed');
                }
            },
            complete: function () {
                if ($('#global-loader').length) $('#global-loader').hide();
            }
        });

        return false;
    });

})();
JS
);
?>
