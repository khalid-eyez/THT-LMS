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
                        <li class="active" ><a data-toggle="tab" href="#Home"><i class="notika-icon notika-app"></i> Dashboard</a>
                        </li>
                        <li class="loans"><a  data-toggle="tab" href="#mailbox"><i class="notika-icon notika-house "></i> Loans</a>
                        </li>
                        <li><a data-toggle="tab" href="#Interface"><i class="notika-icon notika-edit"></i> Repayments</a>
                        </li>
                        <li><a  data-toggle="tab" href="#Charts"><i class="notika-icon notika-bar-chart"></i> Reports</a>
                        </li>
                        <li><a  data-toggle="tab" href="#Tables"><i class="notika-icon notika-windows"></i> Customers</a>
                        </li>
                        <li><a  data-toggle="tab" href="#Forms"><i class="notika-icon notika-form"></i> Shareholders</a>
                        </li>
                        <li><a  data-toggle="tab" href="#Appviews"><i class="notika-icon notika-app"></i> Settings</a>
                        </li>
                        <li><a  data-toggle="tab" href="#Page"><i class="notika-icon notika-support"></i> Account</a>
                        </li>
                    </ul>
                    <div class="tab-content custom-menu-content">
                       
                        <div id="mailbox" class="tab-pane notika-tab-menu-bg animated flipInX">
                            <ul class="notika-main-menu-dropdown">
                                <li><a href="/loans">List</a>
                                </li>
                                <li><a href="<?=Url::toRoute(['/loans/create-loan']) ?>">New Loan</a>
                                </li>
                           </ul>
                        </div>
                        <div id="Interface" class="tab-pane notika-tab-menu-bg animated flipInX">
                            <ul class="notika-main-menu-dropdown">
                                <li><a href="/loans/create-loan">Animations</a>
                                </li>
                                <li><a href="google-map.html">Google Map</a>
                                </li>
                                <li><a href="data-map.html">Data Maps</a>
                                </li>
                                <li><a href="code-editor.html">Code Editor</a>
                                </li>
                                <li><a href="image-cropper.html">Images Cropper</a>
                                </li>
                                <li><a href="wizard.html">Wizard</a>
                                </li>
                            </ul>
                        </div>
                        <div id="Charts" class="tab-pane notika-tab-menu-bg animated flipInX">
                            <ul class="notika-main-menu-dropdown">
                                <li><a href="flot-charts.html">Cashbook</a>
                                </li>
                                <li><a href="bar-charts.html">Repayment Schedule</a>
                                </li>
                                <li><a href="line-charts.html">Repayment Statement</a>
                                </li>
                                <li><a href="area-charts.html">Excutive Summary</a>
                                </li>
                            </ul>
                        </div>
                        <div id="Tables" class="tab-pane notika-tab-menu-bg animated flipInX">
                            <ul class="notika-main-menu-dropdown">
                                <li><a href="normal-table.html">Normal Table</a>
                                </li>
                                <li><a href="data-table.html">Data Table</a>
                                </li>
                            </ul>
                        </div>
                        <div id="Forms" class="tab-pane notika-tab-menu-bg animated flipInX">
                            <ul class="notika-main-menu-dropdown">
                                <li><a href="<?=Url::toRoute(['/shareholder/shareholder/create']) ?>">New Shareholder</a>
                                </li>
                                <li><a href="<?=Url::toRoute(['/shareholder/shareholder/index']) ?>">Manage Shareholder</a>
                                </li>
                                <li><a href="<?=Url::toRoute(['/shareholder/shareholder/claims']) ?>">Shareholder Claims</a>
                                </li>
                                 <li><a href="<?=Url::toRoute(['/shareholder/shareholder/claims2']) ?>">Claims progress</a>
                                </li>
                            </ul>
                        </div>
                        <div id="Appviews" class="tab-pane notika-tab-menu-bg animated flipInX">
                            <ul class="notika-main-menu-dropdown">
                                <li><a href="notification.html">Notifications</a>
                                </li>
                                <li><a href="alert.html">Alerts</a>
                                </li>
                                <li><a href="modals.html">Modals</a>
                                </li>
                                <li><a href="buttons.html">Buttons</a>
                                </li>
                                <li><a href="tabs.html">Tabs</a>
                                </li>
                                <li><a href="accordion.html">Accordion</a>
                                </li>
                                <li><a href="dialog.html">Dialogs</a>
                                </li>
                                <li><a href="popovers.html">Popovers</a>
                                </li>
                                <li><a href="tooltips.html">Tooltips</a>
                                </li>
                                <li><a href="dropdown.html">Dropdowns</a>
                                </li>
                            </ul>
                        </div>
                        <div id="Page" class="tab-pane notika-tab-menu-bg animated flipInX">
                            <ul class="notika-main-menu-dropdown">
                                <li><a href="contact.html">Contact</a>
                                </li>
                                <li><a href="invoice.html">Invoice</a>
                                </li>
                                <li><a href="typography.html">Typography</a>
                                </li>
                                <li><a href="color.html">Color</a>
                                </li>
                                <li><a href="login-register.html">Login Register</a>
                                </li>
                                <li><a href="404.html">404 Page</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>