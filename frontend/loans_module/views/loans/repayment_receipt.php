<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var common\models\RepaymentStatement $payment_details */
?>

  <div class="wizard-area">
    <div class="container p-5">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                <div class="wizard-wrap-int"
                     style="">

                    <!-- CARD 1: Loan & Date -->
                    <div class="card shadow-sm" style="width:100%">
                        <div class="card-header bg-primary text-white" style="padding:8px">
                            <h5 class="">Repayment Confirmation</h5>
                        </div>

                        <div class="card-body">
                            <table class="table table-striped table-bordered table-sm mb-0">
                                <tbody>
                                <tr>
                                    <th style="width:40%">Loan ID</th>
                                    <td><?= Html::encode($payment_details['statement']->loan->loanID) ?></td>
                                </tr>
                                <tr>
                                    <th>Payment Date</th>
                                    <td>
                                        <?= Yii::$app->formatter->asDate(
                                            $payment_details['statement']->payment_date
                                        ) ?>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- CARD 2: Breakdown (NO HEADER) -->
                    <div class="card shadow-sm" style="width:100%">
                        <div class="card-body">
                            <table class="table table-striped table-bordered table-sm mb-0">
                                <tbody>
                                <tr>
                                    <th style="width:40%">Installment Paid</th>
                                    <td>
                                        <?= Yii::$app->formatter->asDecimal(
                                            abs($payment_details['statement']->installment), 2
                                        ) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Overdues Paid</th>
                                    <td class="text-danger">
                                        <?= Yii::$app->formatter->asDecimal(
                                            abs($payment_details['statement']->unpaid_amount), 2
                                        ) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Penalty Paid</th>
                                    <td>
                                        <?= Yii::$app->formatter->asDecimal(
                                            abs($payment_details['statement']->penalty_amount), 2
                                        ) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Prepayment</th>
                                    <td>
                                        <?= Yii::$app->formatter->asDecimal(
                                            $payment_details['statement']->prepayment, 2
                                        ) ?>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- CARD 3: TOTAL PAID (NO HEADER) -->
                    <div class="card shadow-sm border-success" style="width:100%">
                        <div class="card-body ">
                            <table class="table table-bordered table-sm mb-0 table-striped">
                                <tr><th style="width:40%">TOTAL PAID</th><td>
                                <?= Yii::$app->formatter->asDecimal(
                                    $payment_details['statement']->paid_amount, 2
                                ) ?>
                            </td></tr>
                            </table>
                           
                        </div>
                    </div>

                    <!-- CONFIRM BUTTON -->
                  
                        <?= Html::a(
                            'Confirm Repayment',
                            Url::to(['/loans/loans/repayment-confirm',
                                'scheduleID' => $payment_details['repayment_due']->id,
                                'paid_amount'=> $payment_details['statement']->paid_amount,
                                'payment_date'=> $payment_details['statement']->payment_date,
                                'payment_doc'=>$payment_details['payment_doc']
                            ]),
                            ['class' => 'btn btn-primary pull-right']
                        ) ?>
                          <?= Html::a(
                            'Cancel Repayment',
                            Url::to(['/loans/loans/repayment-confirm',
                                'id' => $payment_details['statement']->loan->loanID
                            ]),
                            ['class' => 'btn btn-info pull-right ','style'=>'margin-right:2px']
                        ) ?>
                   

                </div>

            </div>
        </div>
    </div>
</div>

