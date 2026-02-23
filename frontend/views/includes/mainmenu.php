  <?php 
     use yii\helpers\Url;
  ?>
  <style>
    .notika-menu-wrap > li.active > a{
        color:rgba(5, 125, 176)!important;
    }
    .notika-menu-wrap > li>a{
        color:white!important
    }
      .notika-menu-wrap > li:hover a{
        color:rgba(5, 125, 176)!important
    }
  </style>
  <div class="main-menu-area mg-tb-40">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <ul class="nav nav-tabs notika-menu-wrap menu-it-icon-pro " >
                        <li class="active" ><a href="/loans/dashboard"><i class="notika-icon notika-app"></i> Dashboard</a>
                        </li>
                        <li class="loans"><a  data-toggle="tab" href="#mailbox"><i class="notika-icon notika-house "></i> Loans</a>
                        </li>
                        
                        </li>
                        <li><a  data-toggle="tab" href="#Charts"><i class="notika-icon notika-bar-chart"></i> Reports</a>
                        </li>
                        <li><a  data-toggle="tab" href="#Tables"><i class="notika-icon notika-windows"></i> Customers</a>
                        </li>
                        <li><a  data-toggle="tab" href="#Forms"><i class="notika-icon notika-edit"></i> Shareholders</a>
                        </li>
                        <li><a  data-toggle="tab" href="#Appviews"><i class="notika-icon notika-settings"></i> Settings</a>
                        </li>
                        <li><a  data-toggle="tab" href="#Page"><i class="notika-icon notika-support"></i> Account</a>
                        </li>
                        <li><a style="border:solid 1px rgba(255,255,255,.5);max-width:163px;overflow:hidden;padding:5px!important;margin-top:8px;border-radius:7px!important"><marquee><?= yii::$app->user->identity->username ?></marquee></a>
                        </li>
                    </ul>
                    <div class="tab-content custom-menu-content">
                       
                        <div id="mailbox" class="tab-pane notika-tab-menu-bg animated flipInX" style="margin-bottom:0px!important;">
                            <ul class="notika-main-menu-dropdown">
                                <?php if(yii::$app->user->can("view_loans_list")){?>
                                <li><a href="/loans">Loans List</a>
                                </li>
                                <?php } ?>
                                <?php if(yii::$app->user->can("create_loan_application")){?>
                                <li><a href="<?=Url::toRoute(['/loans/create-loan']) ?>">New Application</a>

                                </li>
                                <?php } ?>
                                <?php if(yii::$app->user->can("view_loan_applications")){?>
                                <li><a href="<?=Url::toRoute(['/loans/loans/applications']) ?>">Pending Applications</a>
                                
                                </li>
                                <?php } ?>
                                <?php if(yii::$app->user->can("use_loan_calculator")){?>
                                 <li><a href="<?=Url::toRoute(['/loans/loans/loan-calculator']) ?>">Loan Calculator</a>
                                </li>
                                <?php } ?>
                                <?php if(yii::$app->user->can("view_loan_categories")){?>
                                 <li><a href="<?=Url::toRoute(['/loans/loans/categories']) ?>">Loan Categories</a>
                                </li>
                                <?php } ?>
                           </ul>
                        </div>
                        <div id="Interface" class="tab-pane notika-tab-menu-bg animated flipInX" style="margin-bottom:0px!important;">
                            <ul class="notika-main-menu-dropdown">
                                <?php if(yii::$app->user->can("jj")){?>
                                <li><a href="/shareholder/settings/settings">Settings</a>
                                </li>
                                <?php } ?>
                            </ul>
                        </div>
                        <div id="Charts" class="tab-pane notika-tab-menu-bg animated flipInX" style="margin-bottom:0px!important;">
                            <ul class="notika-main-menu-dropdown">
                                <?php if(yii::$app->user->can("view_cashbook_report")){?>
                                <li><a href="/cashbook/cashbook/cashbook-reporter">Cashbook</a>
                                </li>
                                <?php } ?>
                                <?php if(yii::$app->user->can("view_repayment_schedule")){?>
                                <li><a href="/loans/loans/loan-search" class="">Repayment Schedule</a>
                                </li>
                                <?php } ?>
                                <?php if(yii::$app->user->can("view_repayment_statement")){?>
                                <li><a href="/loans/loans/loan-search-two">Repayment Statement</a>
                                </li>
                                <?php } ?>
                                <?php if(yii::$app->user->can("view_executive_summary")){?>
                                <li><a href="/loans/loans/excutive-summary-reporter">Excutive Summary</a>
                                </li>
                                <?php } ?>
                                <?php if(yii::$app->user->can("view_deposits_summary")){?>
                                 <li><a href="<?=Url::toRoute(['/shareholder/shareholder/deposits-summary']) ?>">Shareholder Deposits Summary</a>
                                </li>
                                <?php } ?>
                                <?php if(yii::$app->user->can("view_interest_summary_report")){?>
                                <li><a href="<?=Url::toRoute(['/shareholder/shareholder/interest-summary-reporter']) ?>">Shareholder Interests Summary</a>
                                </li>
                                <?php } ?>
                            </ul>
                        </div>
                        <div id="Tables" class="tab-pane notika-tab-menu-bg animated flipInX" style="margin-bottom:0px!important;">
                            <ul class="notika-main-menu-dropdown">
                                <?php if(yii::$app->user->can("view_customers_list")){?>
                                <li><a href="/loans/customer/index">Customers List</a>
                                </li>
                                <?php } ?>
                              
                            </ul>
                        </div>
                        <div id="Forms" class="tab-pane notika-tab-menu-bg animated flipInX" style="margin-bottom:0px!important;">
                            <ul class="notika-main-menu-dropdown">
                                <?php if(yii::$app->user->can("view_shareholders_list")){?>
                                <li><a href="<?=Url::toRoute(['/shareholder/shareholder/index']) ?>">Shareholders List</a>
                                </li>
                                <?php } ?>
                                <?php if(yii::$app->user->can("register_shareholder")){?>
                                <li><a href="<?=Url::toRoute(['/shareholder/shareholder/create']) ?>">New Shareholder</a>
                                </li>
                                <?php } ?>
                                <?php if(yii::$app->user->can("view_interest_claims")){?>
                                 <li><a href="<?=Url::toRoute(['/shareholder/deposit/claims']) ?>">Interest Claims</a>
                                </li>
                                <?php } ?>
                               
                                 
                            </ul>
                        </div>
                        <div id="Appviews" class="tab-pane notika-tab-menu-bg animated flipInX" style="margin-bottom:0px!important;">
                            <ul class="notika-main-menu-dropdown">
                                 <?php if(yii::$app->user->can("view_settings")){?>
                               <li><a href="/shareholder/settings/settings">Settings</a>
                                </li>
                                 <?php } ?>
                            </ul>
                        </div>
                        <div id="Page" class="tab-pane notika-tab-menu-bg animated flipInX" style="margin-bottom:0px!important;">
                            <ul class="notika-main-menu-dropdown">
                                <li><a href="/admin/auth/view-profile">Profile Info</a>
                                </li>
                                <li><a href="/admin/auth/changepassword-ajax">Change Password</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>