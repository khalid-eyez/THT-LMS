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
										 <span class="bread-ntd"><?=$loan->loan_amount ?></span>
                                         <span class="bread-ntd badge badge-info"><?=$loan->status ?></span><br>
                                         <span class="bread-ntd"><?=$loan->customer->customerID ?> [<?=$loan->customer->full_name ?>]</span>
									</div>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-3">
								<div class="breadcomb-report" >
                                    <a data-toggle="tooltip" style="background-color: #0a6ab3!important" data-placement="left" title="Pay" class="btn btn-primary"><i class="fa fa-bank"></i></a>
									<a data-toggle="tooltip" style="background-color: #0a6ab3!important" data-placement="right" title="Approve" class="btn btn-primary"><i class="fa fa-check-circle"></i></a>
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
        <a class="nav-link active" data-toggle="tab" href="#tab1" role="tab">Tab 1</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#tab2" role="tab">Tab 2</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#tab3" role="tab">Tab 3</a>
    </li>
</ul>

<div class="tab-content mt-3">
    <div class="tab-pane show active" id="tab1" role="tabpanel">
        Content for Tab 1
    </div>
    <div class="tab-pane fade" id="tab2" role="tabpanel">
        Content for Tab 2
    </div>
    <div class="tab-pane fade" id="tab3" role="tabpanel">
        Content for Tab 3
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