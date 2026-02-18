<?php
use yii\helpers\Url;
use yii\helpers\Html;
?>

<div class="breadcomb-area">

		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="breadcomb-list">
						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
								<div class="breadcomb-wp">
									<div class="breadcomb-icon">
										 <div class="email-round-pro sm-res-ds-n lg-res-mg-bl">
                                    <div class="email-signle-gp">
                                        <input type="text" class="knob" value="0" data-rel="<?= $loan->repayment_ratio() ?>" data-linecap="round" data-width="90" data-bgcolor="#E4E4E4" data-fgcolor="#0784ad" data-thickness=".10" data-readonly="true" disabled>
                                    </div>
                                  
                                </div>
									</div>
									<div class="breadcomb-ctn">
										<h2><?=$loan->loanID ?></h2>
										 <span class="bread-ntd"><?=Yii::$app->formatter->asDecimal($loan->loan_amount) ?></span>
                                         <span class="bread-ntd badge badge-info"><?=$loan->status ?></span><br>
                                         <span class="bread-ntd"><?=$loan->customer->customerID ?> [<?=$loan->customer->full_name ?>]</span>
									</div>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-3">
								<div class="breadcomb-report" >
                                    <a href="<?=Url::toRoute(['/loans/loans/pay','loanID'=>$loan->id]) ?>" data-toggle="tooltip" style="background-color: #0a6ab3!important" data-placement="left" title="Disbursement" class="btn btn-primary pay"><i class="fa fa-bank"></i></a>
                                    <a href="<?=Url::toRoute(['/loans/loans/top-up','loanID'=>$loan->id]) ?>" data-toggle="tooltip" style="background-color: #0a6ab3!important" data-placement="top" title="Top Up" class="btn btn-primary pay"><i class="fa fa-plus-circle"></i></a>
                                    <a href="<?=Url::toRoute(['/loans/loans/repay','loanID'=>$loan->id]) ?>" data-toggle="tooltip" style="background-color: #0a6ab3!important" data-placement="top" title="Repayment" class="btn btn-primary pay"><i class="fa fa-money"></i></a>
									<a href="<?=Url::toRoute(['/loans/loans/approve','loanID'=>$loan->id]) ?>" data-toggle="tooltip" style="background-color: #0a6ab3!important" data-placement="top" title="Approve" class="btn btn-primary"><i class="fa fa-check-circle"></i></a>
                                    <a href="<?=Url::toRoute(['/loans/loans/disapprove','loanID'=>$loan->id]) ?>" data-toggle="tooltip" style="background-color: #0a6ab3!important" data-placement="top" title="Disapprove" class="btn btn-primary"><i class="fa fa-times-circle"></i></a>

                                    <!-- ✅ NEW: Update Status button -->
                                    <a href="#"
                                       class="btn btn-primary"
                                       style="background-color: #0a6ab3!important"
                                       data-toggle="modal"
                                       data-target="#updateStatusModal"
                                       data-placement="top"
                                       title="Update Status">
                                        <i class="fa fa-refresh"></i>
                                    </a>

                                    <a href="<?=Url::toRoute(['/loans/loans/download-summary','loanID'=>$loan->id]) ?>" data-toggle="tooltip" style="background-color: #0a6ab3!important" data-placement="right" title="Download Summary" class="btn btn-primary"><i class="fa fa-file-pdf-o"></i></a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Breadcomb area End-->

    <!-- ✅ Update Status Modal (theme-like / not "bootstrap plain") -->
    <div class="modal fade" id="updateStatusModal" role="dialog" aria-labelledby="updateStatusModalLabel" aria-hidden="true">
        <div class="modal-dialog modals-default" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h2 id="updateStatusModalLabel">Update Status</h2>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="font-size:22px;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form method="post" action="<?= Url::toRoute(['/loans/loans/update']) ?>">
                    <div class="modal-body">
                        <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>
                        <input type="hidden" name="loanID" value="<?= (int)$loan->id ?>">

                        <div class="form-group">
                            <label for="loan-status">Status</label>
                            <?= Html::dropDownList(
                                'status',
                                $loan->status,
                                $loan->optsStatus(),
                                [
                                    'class' => 'form-control',
                                    'id' => 'loan-status',
                                    'required' => true
                                ]
                            ) ?>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" style="background-color:#0a6ab3!important;border-color:#0a6ab3!important;">Update</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

	<!-- Wizard area Start-->
    <div class="wizard-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="wizard-wrap-int">
                     <ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" data-toggle="tab" href="#tab1" role="tab">Loan Info</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#tab2" role="tab">Customer Info</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#tab3" role="tab">Repayment Schedule</a>
    </li>
     <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#tab4" role="tab">Repayment Statement</a>
    </li>

    <!-- ✅ TAB 5 (Attachments) -->
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#tab5" role="tab">Attachments</a>
    </li>
</ul>

<div class="tab-content mt-3">
    <div class="tab-pane active" id="tab1" role="tabpanel">
        <?=$this->render('loaninfo',['model'=>$loan]) ?>
    </div>
    <div class="tab-pane fade" id="tab2" role="tabpanel">
       <?=$this->render('customerinfo',['model'=>$loan->customer]) ?>
    </div>
    <div class="tab-pane fade" id="tab3" role="tabpanel">
        <?=$this->render("/loans/docs/repaymentschedule",['loan'=>$loan]); ?>
    </div>
     <div class="tab-pane fade" id="tab4" role="tabpanel">
        <?=$this->render("/loans/docs/repaymentstatement",['loan'=>$loan]); ?>
    </div>

    <!-- ✅ TAB 5 CONTENT -->
    <div class="tab-pane fade" id="tab5" role="tabpanel">
        <?=$this->render('loan_attachments',['model'=>$loan]) ?>
    </div>
</div>
                           
                      
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php $this->registerJs("
    $('document').ready(function(){
        $('[data-toggle=\"tooltip\"]').tooltip();

        $('.notika-menu-wrap > li').each(function(){
            $(this).removeClass('active')
        });

        // safety: always open modal even if theme blocks the default trigger
        $(document).on('click','[data-target=\"#updateStatusModal\"]', function(e){
            e.preventDefault();
            $('#updateStatusModal').modal('show');
        });
    });
");
?>
