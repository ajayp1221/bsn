    <header id="header" >
        <div class="headerbar">
            <div class="headerbar-left">
                <ul class="header-nav header-nav-options">
                    <li class="header-nav-brand" >
                        <div class="brand-holder">
                            <a href="/admin/clients">
                                <span class="text-lg text-bold text-primary">Business Zakoopi</span>
                            </a>
                        </div>
                    </li>
                    <li>
                        <a class="btn btn-icon-toggle menubar-toggle" data-toggle="menubar" href="javascript:void(0);">
                            <i class="fa fa-bars"></i>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="headerbar-right">
                <ul class="header-nav header-nav-profile">
                    <li class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle ink-reaction" data-toggle="dropdown">
                            <img src="/materialadmin/assets/img/avatar1.jpg?1403934956" alt="<?= $authuser->first_name." ".$authuser->last_name?>" />
                            <span class="profile-info">
                                <?= $authuser->first_name." ".$authuser->last_name?>
                                <small>Administrator</small>
                            </span>
                        </a>
                        <ul class="dropdown-menu animation-dock">
                            <li><a href="/admin/users/logout"><i class="fa fa-fw fa-power-off text-danger"></i> Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div><!--end #header-navbar-collapse -->
        </div>
    </header>
    <!-- END HEADER-->