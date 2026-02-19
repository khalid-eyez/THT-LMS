<!-- Mobile Menu start -->
 <?php 
 use yii\helpers\Url;
 ?>
<style> /* Hide default Menu text */
.mean-container .meanmenu-reveal span{
    display:block;
}

.mean-container .meanmenu-reveal span.meanmenu-text{
    display:none!important;
}

/* Add logo */
.mean-container .mean-bar::before{
    content:"";
    position:absolute;
    left:10px;
    top:50%;
    transform:translateY(-50%);
    width:140px;
    height:40px;
    background:url('/img/logo.png') no-repeat left;
    background-size:contain;
}
.mean-container .mean-bar{
    background:white !important;
    border-bottom:2px solid rgba(5,125,176,.2);
}
.mean-container a.meanmenu-reveal span{
    background:rgba(5,125,176) !important;
}

.mean-container a.meanmenu-reveal{
    color:rgba(5,125,176) !important;
    border-color:rgba(5,125,176) !important;
}
.mean-container a.meanmenu-reveal{
    right:10px !important;
    left:auto !important;
}
</style>
<div class="mobile-menu-area " style="margin-bottom: 15px; background-color:white; ">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="mobile-menu">
                    <nav id="dropdown">
                        <ul class="mobile-menu-nav">

                            <!-- Dashboard -->
                            <li>
                                <a href="/loans/dashboard">Dashboard</a>
                            </li>

                            <!-- Loans -->
                            <li>
                                <a data-toggle="collapse" data-target="#LoansMob" href="#">Loans</a>
                                <ul id="LoansMob" class="collapse dropdown-header-top">
                                    <li><a href="/loans">Loans List</a></li>
                                    <li><a href="<?= Url::toRoute(['/loans/create-loan']) ?>">New Application</a></li>
                                    <li><a href="<?= Url::toRoute(['/loans/loans/applications']) ?>">Pending Applications</a></li>
                                    <li><a href="<?= Url::toRoute(['/loans/loans/loan-calculator']) ?>">Loan Calculator</a></li>
                                    <li><a href="<?= Url::toRoute(['/loans/loans/categories']) ?>">Loan Categories</a></li>
                                </ul>
                            </li>

                            <!-- Reports -->
                            <li>
                                <a data-toggle="collapse" data-target="#ReportsMob" href="#">Reports</a>
                                <ul id="ReportsMob" class="collapse dropdown-header-top">
                                    <li><a href="/cashbook/cashbook/cashbook-reporter">Cashbook</a></li>
                                    <li><a href="/loans/loans/loan-search">Repayment Schedule</a></li>
                                    <li><a href="/loans/loans/loan-search-two">Repayment Statement</a></li>
                                    <li><a href="/loans/loans/excutive-summary-reporter">Excutive Summary</a></li>
                                    <li><a href="<?= Url::toRoute(['/shareholder/shareholder/deposits-summary']) ?>">Shareholder Deposits Summary</a></li>
                                    <li><a href="<?= Url::toRoute(['/shareholder/shareholder/interest-summary-reporter']) ?>">Shareholder Interests Summary</a></li>
                                </ul>
                            </li>

                            <!-- Customers -->
                            <li>
                                <a data-toggle="collapse" data-target="#CustomersMob" href="#">Customers</a>
                                <ul id="CustomersMob" class="collapse dropdown-header-top">
                                    <li><a href="/loans/customer/index">Customers List</a></li>
                                </ul>
                            </li>

                            <!-- Shareholders -->
                            <li>
                                <a data-toggle="collapse" data-target="#ShareholdersMob" href="#">Shareholders</a>
                                <ul id="ShareholdersMob" class="collapse dropdown-header-top">
                                    <li><a href="<?= Url::toRoute(['/shareholder/shareholder/index']) ?>">Shareholders List</a></li>
                                    <li><a href="<?= Url::toRoute(['/shareholder/shareholder/create']) ?>">New Shareholder</a></li>
                                    <li><a href="<?= Url::toRoute(['/shareholder/deposit/claims']) ?>">Interest Claims</a></li>
                                </ul>
                            </li>

                            <!-- Settings -->
                            <li>
                                <a data-toggle="collapse" data-target="#SettingsMob" href="#">Settings</a>
                                <ul id="SettingsMob" class="collapse dropdown-header-top">
                                    <li><a href="/shareholder/settings/settings">Settings</a></li>
                                </ul>
                            </li>

                            <!-- Account -->
                            <li>
                                <a data-toggle="collapse" data-target="#AccountMob" href="#">Account</a>
                                <ul id="AccountMob" class="collapse dropdown-header-top">
                                    <li><a href="/admin/auth/view-profile">Profile Info</a></li>
                                    <li><a href="/admin/auth/changepassword-ajax">Change Password</a></li>
                                    <li><a href="/auth/logout" role="button" aria-expanded="false" class="nav-link no-ajax">
                                    <span><i class="fa fa-sign-out"></i> Log Out</span>
                                    </a></li>
                                </ul>
                            </li>

                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Mobile Menu end -->
