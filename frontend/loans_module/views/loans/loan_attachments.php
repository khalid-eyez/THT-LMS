<?php
/**
 * Loan Attachments (responsive, no table)
 *
 * @var yii\web\View $this
 * @var common\models\CustomerLoan $model
 */

use yii\helpers\Html;

$attachments = $model->loanAttachments;
?>

<div class="container-fluid mt-5" style="margin-top:30px; padding-left:100px;padding-right:100px">

    <?php if (empty($attachments)): ?>

        <div class="row">
            <div class="col-12 text-center text-muted py-4">
                No attachments uploaded for this loan.
            </div>
        </div>

    <?php else: ?>

        <?php foreach ($attachments as $i => $att): ?>
            <?php
                // uploaded_doc contains full path / URL
                $url = $att->uploaded_doc;
            ?>

            <div class="row align-items-center py-2 border-bottom" style="border-bottom:solid 0.5px rgba(100,100,100,.2); padding:10px">

                <!-- Attachment label -->
                <div class="col-8 col-sm-9">
                    <strong>
                        <?= Html::encode('Attachment ' . ($i + 1)) ?>
                    </strong>
                </div>

                <!-- Download icon -->
                <div class="col-4 col-sm-3 text-right">
                    <?= Html::a(
                        '<i class="fa fa-file fa-lg"></i>',
                        $url,
                        [
                            'data-title' => 'Download attachment',
                            'data-toggle'=>'tooltip',
                            'encode' => false,
                            'target' => '_blank',
                            'download' => true,
                            'class' => 'text-primary',
                            'rel' => 'noopener',
                        ]
                    ) ?>
                </div>

            </div>

        <?php endforeach; ?>

    <?php endif; ?>

</div>
