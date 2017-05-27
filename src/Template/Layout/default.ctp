<!DOCTYPE html>
<html lang="en" data-ng-app="app">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="description" content="{{app.description}}">
    <meta name="keywords" content="app, responsive, angular, bootstrap, dashboard, admin">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="HandheldFriendly" content="true" />
    <meta name="apple-touch-fullscreen" content="yes" />
    <title data-ng-bind="pageTitle()">Business Zakoopi For Manage Your Business</title>
    <!-- Google fonts -->
    <link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
    
    <?php
//        $this->Shrink->css([
//            '/bower_components/bootstrap/dist/css/bootstrap.min.css',
//            '/bower_components/font-awesome/css/font-awesome.min.css',
//            '/bower_components/themify-icons/themify-icons.css',
//        ]);
//        echo $this->Shrink->fetch('css');
        
//        $this->Shrink->js([
//        ]);
//        echo $this->Shrink->fetch('js');
    ?>
    
    <!-- Bootstrap -->
    <link rel="stylesheet" href="/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/bower_components/font-awesome/css/font-awesome.min.css">
    <!-- Themify Icons -->
    <link rel="stylesheet" href="/bower_components/themify-icons/themify-icons.css">
    <!-- Flag Icons -->
    <link rel="stylesheet" href="/bower_components/flag-icon-css/css/flag-icon.min.css">
    <!-- Loading Bar -->
    <link rel="stylesheet" href="/bower_components/angular-loading-bar/build/loading-bar.min.css">
    <!-- Animate Css -->
    <link rel="stylesheet" href="/bower_components/animate.css/animate.min.css">
    <!-- Perfect Scrollbar Css -->
    <link rel="stylesheet" href="/bower_components/perfect-scrollbar/css/perfect-scrollbar.min.css">
    <!-- Date Range Picker Css -->
    <link rel="stylesheet" href="/bower_components/bootstrap-daterangepicker/daterangepicker-bs3.css">
    <!-- Angular ui-switch Css -->
    <link rel="stylesheet" href="/bower_components/angular-ui-switch/angular-ui-switch.min.css">
    <!-- Angular Toaster Css -->
    <link rel="stylesheet" href="/bower_components/AngularJS-Toaster/toaster.css">
    <!-- Angular Ng-Aside Css -->
    <link rel="stylesheet" href="/bower_components/angular-aside/dist/css/angular-aside.min.css">
    <!-- Angular Notification Icons Css -->
    <link rel="stylesheet" href="/bower_components/angular-notification-icons/dist/angular-notification-icons.min.css">
    <!-- V-Accordion Css -->
    <link rel="stylesheet" href="/bower_components/v-accordion/dist/v-accordion.min.css">
    <!-- V-Button Css -->
    <link rel="stylesheet" href="/bower_components/v-button/dist/v-button.min.css">
    <!-- Sweet Alert Css -->
    <link rel="stylesheet" href="/bower_components/sweetalert/lib/sweet-alert.css">
    <!-- Ladda Buttons Css -->
    <link rel="stylesheet" href="/bower_components/ladda/dist/ladda-themeless.min.css">
    <!-- xDan DateTimepicker -->
    <link rel="stylesheet" href="/bower_components/datetimepicker/jquery.datetimepicker.css">
    <!-- comiseo DateRangepicker -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.css">
    <link rel="stylesheet" href="/js/customLibs/daterangepicker/jquery.comiseo.daterangepicker.css">
    
    <!-- Angular Awesome Slider Css -->
    <link rel="stylesheet" href="/bower_components/angular-awesome-slider/dist/css/angular-awesome-slider.min.css">
    <!-- Slick Carousel Css -->
    <link rel="stylesheet" href="/bower_components/slick-carousel/slick/slick.css">
    <link rel="stylesheet" href="/bower_components/slick-carousel/slick/slick-theme.css">
    <!-- Packet CSS -->
    <link rel="stylesheet" href="/css/styles.css">
    <link rel="stylesheet" href="/css/plugins.css">
    <!-- Packet Theme -->
    <link rel="stylesheet" data-ng-href="/css/themes/{{ app.layout.theme }}.css" />
    <!-- Favicon -->
    <?= $this->Html->meta('icon','favicon.ico?_=cp'); ?>
<!--    <link rel="shortcut icon" href="favicon.ico" />-->
</head>
<body ng-controller="AppCtrl">
    <div ui-view id="app" ng-class="{'app-mobile' : app.isMobile, 'app-navbar-fixed' : app.layout.isNavbarFixed, 'app-sidebar-fixed' : app.layout.isSidebarFixed, 'app-sidebar-closed':app.layout.isSidebarClosed, 'app-footer-fixed':app.layout.isFooterFixed}"></div>

    <!--<script type="text/javascript" src="/bower_components/angular/angular.min.js"></script>-->
    <?php
        $this->Shrink->js([
            '/bower_components/jquery/dist/jquery.min.js',
            '/bower_components/fastclick/lib/fastclick.js',
            '/bower_components/components-modernizr/modernizr.js',
            '/bower_components/moment/min/moment.min.js',
            '/bower_components/perfect-scrollbar/js/min/perfect-scrollbar.jquery.min.js',
            '/bower_components/bootstrap-daterangepicker/daterangepicker.js',
            '/bower_components/sweetalert/lib/sweet-alert.min.js',
            '/bower_components/spin.js/spin.js',
            '/bower_components/ladda/dist/ladda.min.js',
            '/bower_components/slick-carousel/slick/slick.min.js',
            '/bower_components/angular/angular.min.js',
            '/bower_components/angular-cookies/angular-cookies.min.js',
            '/bower_components/angular-animate/angular-animate.min.js',
            '/bower_components/angular-touch/angular-touch.min.js',
            '/bower_components/angular-sanitize/angular-sanitize.min.js',
            '/bower_components/angular-ui-router/release/angular-ui-router.min.js',
            '/bower_components/ngstorage/ngStorage.min.js',
            '/bower_components/angular-translate/angular-translate.min.js',
            '/bower_components/angular-translate-loader-url/angular-translate-loader-url.min.js',
            '/bower_components/angular-translate-loader-static-files/angular-translate-loader-static-files.min.js',
            '/bower_components/angular-translate-storage-local/angular-translate-storage-local.min.js',
            '/bower_components/angular-translate-storage-cookie/angular-translate-storage-cookie.min.js',
            '/bower_components/oclazyload/dist/ocLazyLoad.min.js',
            '/bower_components/angular-breadcrumb/dist/angular-breadcrumb.min.js',
            '/bower_components/angular-swipe/dist/angular-swipe.min.js',
            '/bower_components/angular-bootstrap/ui-bootstrap-tpls.min.js',
            '/bower_components/angular-loading-bar/build/loading-bar.min.js',
            '/bower_components/angular-scroll/angular-scroll.min.js',
            '/bower_components/angular-fullscreen/src/angular-fullscreen.js',
            '/bower_components/ng-bs-daterangepicker/dist/ng-bs-daterangepicker.min.js',
            '/bower_components/angular-truncate/src/truncate.js',
            '/bower_components/angular-moment/angular-moment.min.js',
            '/bower_components/angular-ui-switch/angular-ui-switch.min.js',
            '/bower_components/angular-ladda/dist/angular-ladda.min.js',
            '/bower_components/AngularJS-Toaster/toaster.js',
            '/bower_components/angular-aside/dist/js/angular-aside.min.js',
            '/bower_components/v-accordion/dist/v-accordion.min.js',
            '/bower_components/v-button/dist/v-button.min.js',
            '/bower_components/angular-sweetalert-promised/SweetAlert.min.js',
            '/bower_components/angular-notification-icons/dist/angular-notification-icons.min.js',
            '/bower_components/angular-awesome-slider/dist/angular-awesome-slider.min.js',
            '/bower_components/angular-slick-carousel/dist/angular-slick.min.js',
            '/bower_components/angular-read-more/dist/readmore.min.js',
            '/js/customLibs/jquery.xdan.datetimepicker.js',
            
        ]);
        echo $this->Shrink->fetch('js');
    ?>
    <script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
    <!-- comiseo DateRangepicker -->
    <script src="/js/customLibs/daterangepicker/jquery.comiseo.daterangepicker.js"></script>
    <script src="//maps.googleapis.com/maps/api/js?libraries=places"></script>
    <script type="text/javascript">
        $authUser = <?= json_encode($authUser) ?>;
        $storeId = <?= $AppSelectedStoreID ?> ;
        $storeSlug = "<?= $AppSelectedStoreSlug ?>";
        $flasMsg = '<?= $flMsg = $this->Flash->render() ?>' == '' ? null : '<?= $flMsg ?>' ;
    </script>
    
    <?php
        $this->Shrink->js([
            '/js/app.js',
            '/js/main.js',
            '/js/config.constant.js',
            '/js/config.router.js',
            '/js/directives/toggle.js',
            '/js/directives/perfect-scrollbar.js',
            '/js/directives/empty-links.js',
            '/js/directives/sidebars.js',
            '/js/directives/off-click.js',
            '/js/directives/full-height.js',
            '/js/directives/panel-tools.js',
            '/js/directives/char-limit.js',
            '/js/directives/dismiss.js',
            '/js/directives/compare-to.js',
            '/js/directives/select.js',
            '/js/directives/messages.js',
            '/js/directives/chat.js',
            '/js/directives/touchspin.js',
            '/js/directives/file-upload.js',
            '/js/directives/letter-icon.js',
            '/js/directives/landing-header.js',
            '/js/controllers/mainCtrl.js',
            '/js/controllers/inboxCtrl.js',
            '/js/controllers/bootstrapCtrl.js'
        ]);
        echo $this->Shrink->fetch('js');
    ?>
</body>
</html>