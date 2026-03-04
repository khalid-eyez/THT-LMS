<style>
/* Hide on small & medium devices */
@media (max-width: 991px){
    .desktop-header-only{
        display: none !important;
    }
}

/* Optional: force visible on big screens */
@media (min-width: 992px){
    .desktop-header-only{
        display: block !important;
    }
}
</style>

<div class="header-top-area desktop-header-only" style="background-color: white!important;">
    <div class="container" style=" color:rgba(5, 125, 176)!important">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="logo-area" style="font-weight: bold;font-size:22px">
                    <a href="#"><img src="/img/logo.png" alt="" style="height:100%;width:20%" /></a>H2H LMIS
                </div>
            </div>

            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                <div class="header-top-menu">
                    <ul class="nav navbar-nav pull-right">
                        <li data-toggle="tooltip" data-placement="left" title="Log out">
                            <a href="/auth/logout" style="color:rgba(5, 125, 176)!important" role="button" aria-expanded="false" class="nav-link">
                                <span><i class="fa fa-sign-out fa-3x"></i></span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
