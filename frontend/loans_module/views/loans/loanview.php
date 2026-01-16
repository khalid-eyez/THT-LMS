<?php
use yii\helpers\Url;
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
                                        <input type="text" class="knob" value="0" data-rel="45" data-linecap="round" data-width="90" data-bgcolor="#E4E4E4" data-fgcolor="#0784ad" data-thickness=".10" data-readonly="true" disabled>
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
                                    <a href="<?=Url::toRoute(['/loans/loans/top-up','loanID'=>$loan->id]) ?>" data-toggle="tooltip" style="background-color: #0a6ab3!important" data-placement="top" title="Top Up" class="btn btn-primary"><i class="fa fa-check-circle"></i></a>
									<a href="<?=Url::toRoute(['/loans/loans/approve','loanID'=>$loan->id]) ?>" data-toggle="tooltip" style="background-color: #0a6ab3!important" data-placement="top" title="Approve" class="btn btn-primary"><i class="fa fa-check-circle"></i></a>
                                    <a href="<?=Url::toRoute(['/loans/loans/disapprove','loanID'=>$loan->id]) ?>" data-toggle="tooltip" style="background-color: #0a6ab3!important" data-placement="top" title="Disapprove" class="btn btn-primary"><i class="fa fa-times-circle"></i></a>
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
        statement
    </div>
</div>
                           
                      
                    </div>
                </div>
            </div>
        </div>
    </div>
     <?php $this->registerJs("
                    
                    $('document').ready(function(){
                        //$('.loans').addClass('active');
                         $('.notika-menu-wrap > li').each(function(){
                         $(this).removeClass('active')
                         })
                        
                       //$('input').wrap('<div class='col-lg-6'></div>'); 
                    })
                    "
                    );
                ?>