<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var common\models\Customer $customer */

$shareholder = $customer->shareholder;
$loans = $customer->customerLoans ?: [];

/* shareholder aggregates (use model functions) */
$totalDeposits = 0.0;

// Total interest are the approved AND paid ones
$totalInterestPaidApproved = 0.0;

// Payable Interests = approved but not paid yet
$totalInterestApproved = 0.0;
$payableInterests = 0.0;

// Claimable (not yet claimed at all)
$claimableInterest = 0.0;

if ($shareholder) {
    
        $totalDeposits += (float) $shareholder->totalDeposits(null, null);
    

    $totalInterestPaidApproved = (float) ($shareholder->totalPaidApprovedInterest ?? 0);
    $totalInterestApproved     = (float) ($shareholder->totalApprovedInterests ?? 0);
    $totalApprovable           = (float) ($shareholder->getApprovableInterest() ?? 0);

    $payableInterests  = max(0, $totalInterestApproved - $totalInterestPaidApproved);
    $claimableInterest = (float) ($shareholder->totalClaimableInterests ?? 0);
}

/* general avatar image */
$avatarUrl = Yii::getAlias('@web/images/avatar2.png');

/* update url for ajax load */
$updateUrl = Url::to(['update', 'id' => $customer->id]);

/* delete + index urls */
$deleteUrl = Url::to(['/loans/customer/delete', 'id' => $customer->id]);
$indexUrl  = Url::to(['/loans/customer/index']);

/* apply loan */
$applyLoanUrl = Url::to(['/loans/loans/create-loan-reg', 'customerID' => $customer->id]);

/* ✅ deposit create URL (load into modal) */
$depositCreateUrl = $shareholder
    ? Url::to(['/shareholder/deposit/create', 'shareholder_id' => $shareholder->id])
    : null;

/* shareholder statement URLs */
$depositsStatementUrl = $shareholder
    ? Url::to(['/shareholder/deposit/shareholder-deposits', 'shareholderID' => $shareholder->id])
    : null;

/* ✅ NEW: interest statement modal URL */
$interestStatementModalUrl = $shareholder
    ? Url::to(['/shareholder/deposit/shareholder-interest-statement', 'shareholderID' => $shareholder->id])
    : null;

$approveUrl = $shareholder
    ? Url::to(['/shareholder/shareholder/approve-interest', 'shareholderID' => $shareholder->id])
    : null;

/* claim interest */
$claimInterestUrl = $shareholder
    ? Url::to(['/shareholder/shareholder/claim-interest', 'shareholderID' => $shareholder->id])
    : null;

/* ✅ pay interests URL (THIS will load the form via AJAX and also be the submit URL) */
$payInterestUrl = $shareholder
    ? Url::to(['/shareholder/shareholder/pay-interests', 'shareholderID' => $shareholder->id])
    : null;

/* disable logic */
$isPayableActive   = ($payableInterests > 0);
$isClaimableActive = ($claimableInterest > 0);
?>

<style>
.icon-actions{
    display:flex;
    gap:8px;
    justify-content:center;
    flex-wrap:wrap;
}
.icon-actions .btn{
    width:34px;
    height:34px;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding:0;
}
.share-row{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:10px;
}
.share-row .right-actions{
    display:flex;
    gap:6px;
    align-items:center;
}
.share-row .right-actions .btn{
    width:30px;
    height:30px;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding:0;
}
.btn-soft-disabled{
    opacity: .55;
}
</style>

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

                                    <div class="statistic-right-area notika-shadow mg-tb-30 sm-res-mg-t-0">

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

                                        <div class="mt-3 icon-actions">

                                            <?= Html::a(
                                                '<i class="fa fa-money"></i>',
                                                $applyLoanUrl,
                                                [
                                                    'class' => 'btn btn-success btn-sm pay',
                                                    'title' => 'Apply loan',
                                                    'data-toggle' => 'tooltip',
                                                    'data-pjax' => '0',
                                                ]
                                            ) ?>

                                            <?= Html::a(
                                                '<i class="fa fa-edit"></i>',
                                                $updateUrl,
                                                [
                                                    'class' => 'btn btn-primary btn-sm js-customer-update',
                                                    'data-url' => $updateUrl,
                                                    'title' => 'Update customer',
                                                    'data-toggle' => 'tooltip',
                                                    'data-pjax' => '0',
                                                ]
                                            ) ?>

                                            <?php if ($shareholder && $depositCreateUrl): ?>
                                                <?= Html::a(
                                                    '<i class="fa fa-plus-circle"></i>',
                                                    'javascript:void(0)',
                                                    [
                                                        'class' => 'btn btn-info btn-sm js-deposit-create',
                                                        'data-url' => $depositCreateUrl,
                                                        'title' => 'Record deposit',
                                                        'data-toggle' => 'tooltip',
                                                        'data-pjax' => '0',
                                                    ]
                                                ) ?>
                                            <?php endif; ?>

                                            <?= Html::a(
                                                '<i class="fa fa-trash"></i>',
                                                'javascript:void(0)',
                                                [
                                                    'class' => 'btn btn-danger btn-sm js-customer-delete',
                                                    'data-url' => $deleteUrl,
                                                    'title' => 'Delete customer',
                                                    'data-toggle' => 'tooltip',
                                                ]
                                            ) ?>

                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- RIGHT: LOANS CARD + SHAREHOLDER CARD -->
                        <div class="col-lg-9 col-md-8 col-sm-7 col-xs-12">

                            <div class="sale-statistic-inner notika-shadow mg-tb-30">

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

                            <?php if ($shareholder): ?>
                                <div class="sale-statistic-inner notika-shadow mg-tb-30">

                                    <h5 class="mb-3 bg-primary" style="margin-top:6px; margin-bottom:12px; padding:4px">
                                        <i class="fa fa-users"></i> Shareholder Information
                                    </h5>

                                    <p><strong>Member ID:</strong> <?= Html::encode($shareholder->memberID) ?></p>

                                    <p class="share-row">
                                        <span><strong>Initial Capital:</strong> <?= Yii::$app->formatter->asDecimal($shareholder->initialCapital) ?></span>
                                    </p>

                                    <p class="share-row">
                                        <span><strong>Total Deposits:</strong> <?= Yii::$app->formatter->asDecimal($totalDeposits) ?></span>
                                        <span class="right-actions">
                                            <?= Html::a(
                                                '<i class="fa fa-file-text-o"></i>',
                                                'javascript:void(0)',
                                                [
                                                    'class' => 'btn btn-default btn-xs js-deposits-statement',
                                                    'data-url' => $depositsStatementUrl,
                                                    'title' => 'View deposits statement',
                                                    'data-toggle' => 'tooltip',
                                                    'data-pjax' => '0',
                                                ]
                                            ) ?>
                                        </span>
                                    </p>

                                    <p class="share-row">
                                        <span><strong>Total Interest:</strong> <?= Yii::$app->formatter->asDecimal($totalInterestPaidApproved) ?></span>
                                        <span class="right-actions">
                                            <?= Html::a(
                                                '<i class="fa fa-line-chart"></i>',
                                                'javascript:void(0)',
                                                [
                                                    'class' => 'btn btn-default btn-xs js-interest-statement',
                                                    'data-url' => $interestStatementModalUrl,
                                                    'title' => 'Interest statement',
                                                    'data-toggle' => 'tooltip',
                                                    'data-pjax' => '0',
                                                ]
                                            ) ?>
                                        </span>
                                    </p>

                                    <p class="share-row">
                                        <span><strong>Approvable Interest:</strong> <?= Yii::$app->formatter->asDecimal($totalApprovable) ?></span>
                                        <span class="right-actions">
                                            <?= Html::a(
                                                '<i class="fa fa-check"></i>',
                                                $approveUrl ?: 'javascript:void(0)',
                                                [
                                                    'class' => 'btn btn-default btn-xs',
                                                    'title' => 'Approve Claim',
                                                    'data-toggle' => 'tooltip',
                                                    'data-pjax' => '0',
                                                ]
                                            ) ?>
                                        </span>
                                    </p>

                                    <p class="share-row">
                                        <span><strong>Payable Interests:</strong> <?= Yii::$app->formatter->asDecimal($payableInterests) ?></span>
                                        <span class="right-actions">
                                            <?= Html::a(
                                                '<i class="fa fa-credit-card"></i>',
                                                'javascript:void(0)',
                                                [
                                                    'class' => 'btn btn-default btn-xs js-interest-pay-modal ' . ($isPayableActive ? '' : 'btn-soft-disabled'),
                                                    'data-url' => $payInterestUrl,
                                                    'title' => $isPayableActive ? 'Pay payable interests' : 'No payable interests to pay',
                                                    'data-toggle' => 'tooltip',
                                                    'data-pjax' => '0',
                                                    'onclick' => $isPayableActive ? null : 'return false;',
                                                ]
                                            ) ?>
                                        </span>
                                    </p>

                                    <p class="share-row">
                                        <span><strong>Claimable Interest:</strong> <?= Yii::$app->formatter->asDecimal($claimableInterest) ?></span>
                                        <span class="right-actions">
                                            <?= Html::a(
                                                '<i class="fa fa-money"></i>',
                                                ($claimInterestUrl && $isClaimableActive) ? $claimInterestUrl : 'javascript:void(0)',
                                                [
                                                    'class' => 'btn btn-default btn-xs ' . ($isClaimableActive ? '' : 'btn-soft-disabled'),
                                                    'title' => $isClaimableActive ? 'Claim interest' : 'No claimable interest yet',
                                                    'data-toggle' => 'tooltip',
                                                    'data-pjax' => '0',
                                                    'onclick' => $isClaimableActive ? null : 'return false;',
                                                ]
                                            ) ?>
                                        </span>
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

<!-- ✅ UPDATE MODAL -->
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
                <div class="text-center text-muted" style="padding:20px;">Loading...</div>
            </div>

        </div>
    </div>
</div>

<!-- ✅ DEPOSIT CREATE MODAL -->
<div class="modal fade animated rubberBand" style="margin-top: 15px;" id="depositCreateModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header bg-primary" style="padding:9px;">
                <i class="fa fa-plus-circle"></i> Record Deposit
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff; opacity:1;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body" id="depositCreateModalBody" style="min-height:150px;">
                <div class="text-center text-muted" style="padding:20px;">Loading...</div>
            </div>

        </div>
    </div>
</div>

<!-- ✅ DEPOSITS STATEMENT MODAL -->
<div class="modal fade animated rubberBand" style="margin-top: 15px;" id="depositsStatementModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header bg-primary" style="padding:9px;">
                <i class="fa fa-file-text-o"></i> Deposits Statement
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff; opacity:1;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body" id="depositsStatementModalBody" style="min-height:150px;">
                <div class="text-center text-muted" style="padding:20px;">Loading...</div>
            </div>

        </div>
    </div>
</div>

<!-- ✅ NEW: INTEREST STATEMENT MODAL -->
<div class="modal fade animated rubberBand" style="margin-top: 15px;" id="interestStatementModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header bg-primary" style="padding:9px;">
                <i class="fa fa-line-chart"></i> Interest Statement
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff; opacity:1;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body" id="interestStatementModalBody" style="min-height:150px;">
                <div class="text-center text-muted" style="padding:20px;">Loading...</div>
            </div>

        </div>
    </div>
</div>

<!-- ✅ INTEREST PAYMENT MODAL (AJAX LOADED) -->
<div class="modal fade animated rubberBand" style="margin-top: 15px;" id="interestPaymentModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">

            <div class="modal-header bg-primary" style="padding:9px;">
                <i class="fa fa-credit-card"></i> Pay Interest
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff; opacity:1;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body" id="interestPaymentModalBody" style="min-height:150px;">
                <div class="text-center text-muted" style="padding:20px;">Loading...</div>
            </div>

        </div>
    </div>
</div>

<?php
$this->registerJs(<<<JS
(function () {

    function initTooltips(scope){
        var \$s = scope ? $(scope) : $(document);
        if (\$.fn.tooltip) \$s.find('[data-toggle="tooltip"]').tooltip();
    }
    $(document).ready(function(){ initTooltips(document); });

    function showLoader() {
        if ($('#global-loader').length) $('#global-loader').show();
    }
    function hideLoader() {
        if ($('#global-loader').length) $('#global-loader').hide();
    }

    // ---------- UPDATE MODAL ----------
    $(document).off('click.customerUpdateModal');
    $(document).on('click.customerUpdateModal', '.js-customer-update', function (e) {
        e.preventDefault();

        var url = $(this).data('url') || $(this).attr('href');
        if (!url) return false;

        $('#customerUpdateModalBody').html('<div class="text-center text-muted" style="padding:20px;">Loading...</div>');
        $('#customerUpdateModal').modal('show');

        showLoader();

        $.ajax({
            url: url,
            type: 'GET',
            cache: false,
            success: function (html) {
                $('#customerUpdateModalBody').html(html);
                initTooltips('#customerUpdateModal');
            },
            error: function () {
                $('#customerUpdateModalBody').html('<div class="alert alert-danger" style="margin:0;">Failed to load the update form.</div>');
            },
            complete: function () {
                hideLoader();
            }
        });

        return false;
    });

    // ---------- DEPOSIT CREATE MODAL ----------
    $(document).off('click.depositCreateModal');
    $(document).on('click.depositCreateModal', '.js-deposit-create', function (e) {
        e.preventDefault();

        var url = $(this).data('url');
        if (!url) return false;

        $('#depositCreateModalBody').html('<div class="text-center text-muted" style="padding:20px;">Loading...</div>');
        $('#depositCreateModal').modal('show');

        showLoader();

        $.ajax({
            url: url,
            type: 'GET',
            cache: false,
            success: function (html) {
                $('#depositCreateModalBody').html(html);
                initTooltips('#depositCreateModal');
            },
            error: function () {
                $('#depositCreateModalBody').html('<div class="alert alert-danger" style="margin:0;">Failed to load deposit form.</div>');
            },
            complete: function () {
                hideLoader();
            }
        });

        return false;
    });

    // ---------- DELETE ----------
    $(document).off('click.customerDelete');
    $(document).on('click.customerDelete', '.js-customer-delete', function (e) {
        e.preventDefault();

        var delUrl = $(this).data('url');
        if (!delUrl) return false;

        if (!confirm('Are you sure you want to delete this customer?')) {
            return false;
        }

        if (window.toastr) {
            toastr.clear();
            toastr.remove();
            toastr.info('Deleting customer, please wait...');
        }

        showLoader();

        $.ajax({
            url: delUrl,
            type: 'GET',
            cache: false,
            success: function () {
                $('.content').load('{$indexUrl}', function () {
                    if (window.toastr) {
                        toastr.clear();
                        toastr.remove();
                        toastr.success('Customer deleted successfully!');
                    }
                    initTooltips(document);
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
                hideLoader();
            }
        });

        return false;
    });

    // ---------- DEPOSITS STATEMENT MODAL ----------
    $(document).off('click.depositsStatementModal');
    $(document).on('click.depositsStatementModal', '.js-deposits-statement', function (e) {
        e.preventDefault();

        var url = $(this).data('url');
        if (!url) return false;

        $('#depositsStatementModalBody').html('<div class="text-center text-muted" style="padding:20px;">Loading...</div>');
        $('#depositsStatementModal').modal('show');

        showLoader();

        $.ajax({
            url: url,
            type: 'GET',
            cache: false,
            success: function (html) {
                $('#depositsStatementModalBody').html(html);
                initTooltips('#depositsStatementModalBody');
            },
            error: function () {
                $('#depositsStatementModalBody').html('<div class="alert alert-danger" style="margin:0;">Failed to load deposits statement.</div>');
            },
            complete: function () {
                hideLoader();
            }
        });

        return false;
    });

    // ---------- NEW: INTEREST STATEMENT MODAL ----------
    $(document).off('click.interestStatementModal');
    $(document).on('click.interestStatementModal', '.js-interest-statement', function (e) {
        e.preventDefault();

        var url = $(this).data('url');
        if (!url) return false;

        $('#interestStatementModalBody').html('<div class="text-center text-muted" style="padding:20px;">Loading...</div>');
        $('#interestStatementModal').modal('show');

        showLoader();

        $.ajax({
            url: url,
            type: 'GET',
            cache: false,
            success: function (html) {
                $('#interestStatementModalBody').html(html);
                initTooltips('#interestStatementModalBody');
            },
            error: function () {
                $('#interestStatementModalBody').html('<div class="alert alert-danger" style="margin:0;">Failed to load interest statement.</div>');
            },
            complete: function () {
                hideLoader();
            }
        });

        return false;
    });

    // ---------- DEPOSITS STATEMENT SEARCH (inside modal) ----------
    $(document).off('submit.depositsStatementSearch');
    $(document).on('submit.depositsStatementSearch', '#depositsStatementModal .loan-search-bar form', function (e) {
        e.preventDefault();

        var form = $(this);
        var url  = form.attr('action');

        $.ajax({
            url: url,
            type: 'POST',
            data: form.serialize(),
            beforeSend: function () { showLoader(); },
            success: function (response) {
                $('#depositsStatementModal .cashbook').html(response);
                initTooltips('#depositsStatementModal');
            },
            error: function (xhr) {
                $('#depositsStatementModal .cashbook').html(
                    '<div class="alert alert-danger">Failed to load data.</div>'
                );
                console.error(xhr.responseText);
            },
            complete:function(){ hideLoader(); }
        });

        return false;
    });

    // ---------- PAY INTEREST MODAL ----------
    $(document).off('click.interestPayModal');
    $(document).on('click.interestPayModal', '.js-interest-pay-modal', function (e) {
        e.preventDefault();

        var url = $(this).data('url');
        if (!url) return false;

        $('#interestPaymentModalBody').html('<div class="text-center text-muted" style="padding:20px;">Loading...</div>');
        $('#interestPaymentModal').modal('show');

        showLoader();

        $.ajax({
            url: url,
            type: 'GET',
            cache: false,
            success: function (html) {
                $('#interestPaymentModalBody').html(html);

                var \$form = $('#interestPaymentModalBody').find('form');
                if (\$form.length) \$form.attr('action', url);

                initTooltips('#interestPaymentModal');
            },
            error: function () {
                $('#interestPaymentModalBody').html('<div class="alert alert-danger" style="margin:0;">Failed to load interest payment form.</div>');
            },
            complete: function () {
                hideLoader();
            }
        });

        return false;
    });

})();
JS
);
?>
