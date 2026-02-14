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
                                <li><a href="/loans">Loans List</a>
                                </li>
                                <li><a href="<?=Url::toRoute(['/loans/create-loan']) ?>">New Application</a>

                                </li>
                                <li><a href="<?=Url::toRoute(['/loans/loans/applications']) ?>">Pending Applications</a>
                                
                                </li>
                                 <li><a href="<?=Url::toRoute(['/loans/loans/loan-calculator']) ?>">Loan Calculator</a>
                                </li>
                                 <li><a href="<?=Url::toRoute(['/loans/loans/categories']) ?>">Loan Categories</a>
                                </li>
                           </ul>
                        </div>
                        <div id="Interface" class="tab-pane notika-tab-menu-bg animated flipInX" style="margin-bottom:0px!important;">
                            <ul class="notika-main-menu-dropdown">
                                <li><a href="/shareholder/settings/settings">Settings</a>
                                </li>
                            </ul>
                        </div>
                        <div id="Charts" class="tab-pane notika-tab-menu-bg animated flipInX" style="margin-bottom:0px!important;">
                            <ul class="notika-main-menu-dropdown">
                                <li><a href="/cashbook/cashbook/cashbook-reporter">Cashbook</a>
                                </li>
                                <li><a href="/loans/loans/loan-search" class="">Repayment Schedule</a>
                                </li>
                                <li><a href="/loans/loans/loan-search-two">Repayment Statement</a>
                                </li>
                                <li><a href="/loans/loans/excutive-summary-reporter">Excutive Summary</a>
                                </li>
                                 <li><a href="<?=Url::toRoute(['/shareholder/shareholder/deposits-summary']) ?>">Shareholder Deposits Summary</a>
                                </li>
                                <li><a href="<?=Url::toRoute(['/shareholder/shareholder/interest-summary-reporter']) ?>">Shareholder Interests Summary</a>
                                </li>
                            </ul>
                        </div>
                        <div id="Tables" class="tab-pane notika-tab-menu-bg animated flipInX" style="margin-bottom:0px!important;">
                            <ul class="notika-main-menu-dropdown">
                                <li><a href="/loans/customer/index">Customers List</a>
                                </li>
                                <li><a href="data-table.html"></a>
                                </li>
                            </ul>
                        </div>
                        <div id="Forms" class="tab-pane notika-tab-menu-bg animated flipInX" style="margin-bottom:0px!important;">
                            <ul class="notika-main-menu-dropdown">
                                <li><a href="<?=Url::toRoute(['/shareholder/shareholder/index']) ?>">Shareholders List</a>
                                </li>
                                <li><a href="<?=Url::toRoute(['/shareholder/shareholder/create']) ?>">New Shareholder</a>
                                </li>
                                 <li><a href="<?=Url::toRoute(['/shareholder/deposit/claims']) ?>">Interest Claims</a>
                                </li>
                               
                                 
                            </ul>
                        </div>
                        <div id="Appviews" class="tab-pane notika-tab-menu-bg animated flipInX" style="margin-bottom:0px!important;">
                            <ul class="notika-main-menu-dropdown">
                               <li><a href="/shareholder/settings/settings">Settings</a>
                                </li>
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