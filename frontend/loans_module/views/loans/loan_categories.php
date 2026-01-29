<?php
/**
 * Loan setup page
 * Yii2 view – Bootstrap 4 (assets already loaded)
 */

use common\models\LoanCategory;
use common\models\LoanType;

$model = new LoanCategory();
?>

<div class="wizard-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="wizard-wrap-int">

                    <!-- Tabs -->
                    <ul class="nav nav-tabs" id="loanTabs" role="tablist">
                        <li class="nav-item">
                            <a
                                class="nav-link active"
                                id="loan-categories-tab"
                                data-toggle="tab"
                                href="#loan-categories"
                                role="tab"
                                aria-controls="loan-categories"
                                aria-selected="true"
                            >
                                Loan Categories
                            </a>
                        </li>

                        <li class="nav-item">
                            <a
                                class="nav-link"
                                id="loan-types-tab"
                                data-toggle="tab"
                                href="#loan-types"
                                role="tab"
                                aria-controls="loan-types"
                                aria-selected="false"
                            >
                                Loan Types
                            </a>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content border-left border-right border-bottom p-4" id="loanTabsContent">

                        <!-- Loan Categories -->
                        <div
                            class="tab-pane active"
                            id="loan-categories"
                            role="tabpanel"
                            aria-labelledby="loan-categories-tab"
                        >
                            <?= $this->render('categories', [
                                'categories' => LoanCategory::find()->orderBy(['id'=>SORT_DESC])->all(),
                                'model' => $model,
                            ]) ?>
                        </div>

                        <!-- Loan Types -->
                        <div
                            class="tab-pane fade"
                            id="loan-types"
                            role="tabpanel"
                            aria-labelledby="loan-types-tab"
                        >
                               <?=$this->render('loan_types',[
                                'model'=>new LoanType(),
                                'loanTypes'=>LoanType::find()->orderBy(['id'=>SORT_DESC])->all()
                                ]) ?>
                        </div>

                    </div><!-- /tab-content -->

                </div><!-- /wizard-wrap-int -->
            </div>
        </div>
    </div>
</div>

<?php
// GLOBAL: force Yii2 validation errors to show in red (works for ActiveForm + your modal)
$this->registerCss("
    /* Yii2 default error containers */
    .help-block,
    .help-block-error,
    .invalid-feedback,
    .field-loancategory-categoryname .help-block {
        color: #dc3545 !important;
    }

    /* Common Yii2 error wrapper */
    .has-error .form-control {
        border-color: #dc3545 !important;
    }

    /* If you use invalid-feedback, ensure it’s visible */
    .invalid-feedback { display: block; }
");
?>

