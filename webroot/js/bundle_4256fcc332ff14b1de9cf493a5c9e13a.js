/* /js/app.js */
/** 
  * declare 'packet' module with dependencies
*/

angular.module("packet", [
	'ngAnimate',
	'ngCookies',
	'ngStorage',
	'ngSanitize',
	'ngTouch',
	'ui.router',
	'ui.bootstrap',
	'angularMoment',
	'oc.lazyLoad',
	'swipe',
	'ngBootstrap',
	'truncate',
	'uiSwitch',
	'toaster',
	'ngAside',
	'vAccordion',
	'vButton',
	'oitozero.ngSweetAlert',
	'angular-notification-icons',
	'angular-ladda',
	'angularAwesomeSlider',
	'slickCarousel',
	'cfp.loadingBar',
	'ncy-angular-breadcrumb',
	'duScroll',
	'pascalprecht.translate',
	'FBAngular',
        'hm.readmore'
]);
/* /js/main.js */
var app = angular.module('app', ['packet']);
app.run(['$rootScope', '$state', '$stateParams','$location','$http',
function ($rootScope, $state, $stateParams,$location,$http) {
    
    $rootScope.selectedStore = {};
    $rootScope.pastelColour = function(input_str) {

        //TODO: adjust base colour values below based on theme
        var baseRed = 128;
        var baseGreen = 128;
        var baseBlue = 128;

        //lazy seeded random hack to get values from 0 - 256
        //for seed just take bitwise XOR of first two chars
        var seed = input_str.charCodeAt(0) ^ input_str.charCodeAt(1);
        var rand_1 = Math.abs((Math.sin(seed++) * 10000)) % 256;
        var rand_2 = Math.abs((Math.sin(seed++) * 10000)) % 256;
        var rand_3 = Math.abs((Math.sin(seed++) * 10000)) % 256;

        //build colour
        var red = Math.round((rand_1 + baseRed) / 2);
        var green = Math.round((rand_2 + baseGreen) / 2);
        var blue = Math.round((rand_3 + baseBlue) / 2);

        return { red: red, green: green, blue: blue };
    };
    
    //HeartBeat <Himanshu>
    $rootScope.heartBeat = function(){
        $http.get('/heart-beat.json').success(function(d){
           if(d.result == null){
               if($state.current.templateUrl != "/views/clients/login.html"){
                   $authUser = null;
//                   alert('Pissed off...    :(');
                  $state.go('login.signin')
               }
           }else{
               setTimeout(function(){$rootScope.heartBeat();},10000);
           } 
        });
    };
    $rootScope.heartBeat();
    
    // Authenication Lock <Himanshu>
    if ( $authUser == null ) {
      // no logged user, we should be going to #login
      if ( $state.current.templateUrl == "/views/clients/login.html" ) {
        // already going to #login, no redirect needed
      } else {
        // not going to #login, we should redirect now
        $location.path("/views/clients/login.html" );
      }
    }         
    
	
    // Attach Fastclick for eliminating the 300ms delay between a physical tap and the firing of a click event on mobile browsers
    FastClick.attach(document.body);

    // Set some reference to access them from any scope
    $rootScope.$state = $state;
    $rootScope.$stateParams = $stateParams;

    // GLOBAL APP SCOPE
    // set below basic information
    $rootScope.app = {
        name: 'Zakoopi for Business', // name of your project
        author: 'Zakoopi Team', // author's name or company name
        description: 'Zakoopi for Business app is for limited circulation and intended for use by Zakoopi\'s business clients. The app helps fashion retailers to exponentially expand their business, acquire new customers, engage with existing ones and market themselves smartly.', // brief description
        version: '1.0', // current version
        year: ((new Date()).getFullYear()), // automatic current year (for copyright information)
        isMobile: (function () {// true if the browser is a mobile device
            var check = false;
            if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                check = true;
            };
            return check;
        })(),
        defaultLayout: {
            isNavbarFixed: true, //true if you want to initialize the template with fixed header
            isSidebarFixed: true, // true if you want to initialize the template with fixed sidebar
            isSidebarClosed: false, // true if you want to initialize the template with closed sidebar
            isFooterFixed: false, // true if you want to initialize the template with fixed footer
            isBoxedPage: false, // true if you want to initialize the template with boxed layout
            theme: 'lyt1-theme-' + (Math.floor(Math.random() * 6) + 1), // indicate the theme chosen for your project
            logo: '/images/logo.png', // relative path of the project logo
            logoCollapsed: '/images/logo-collapsed.png' // relative path of the collapsed logo
        },
        layout: ''
    };
    $rootScope.app.layout = angular.copy($rootScope.app.defaultLayout);
    $rootScope.user = $authUser;
    $rootScope.mystores = [];
    
    // Get my stores <Himanshu>
    $http.post('/stores/get-my-stores.json').success(function(data){
        $rootScope.mystores = data.result;
    });
    $http.get('/stores/get-selected-store.json').success(function(d){
        $rootScope.selectedStore = d.data;
    });
    $rootScope.setStore = function(e){
      $http.post('/stores/set-store/'+e+'/.json').success(function(data){
            window.location.reload();
      });
    };
    
    
    
    $rootScope.logout = function(a,b){
      console.log(a,b);  
    };
}]);
// translate config
app.config(['$translateProvider',
function ($translateProvider) {

    // prefix and suffix information  is required to specify a pattern
    // You can simply use the static-files loader with this pattern:
    $translateProvider.useStaticFilesLoader({
        prefix: '/js/i18n/',
        suffix: '.json'
    });

    // Since you've now registered more then one translation table, angular-translate has to know which one to use.
    // This is where preferredLanguage(langKey) comes in.
    $translateProvider.preferredLanguage('en');

    // Store the language in the local storage
    $translateProvider.useLocalStorage();

    // Enable sanitize
    $translateProvider.useSanitizeValueStrategy('sanitize');

}]);
// Angular-Loading-Bar
// configuration
app.config(['cfpLoadingBarProvider',
function (cfpLoadingBarProvider) {
    cfpLoadingBarProvider.includeBar = true;
    cfpLoadingBarProvider.includeSpinner = false;

}]);
// Angular-breadcrumb
// configuration
app.config(function ($breadcrumbProvider) {
    $breadcrumbProvider.setOptions({
        template: '<ul class="breadcrumb"><li><a ui-sref="app.dashboard"><i class="fa fa-home margin-right-5 text-large text-dark"></i>Home</a></li><li ng-repeat="step in steps">{{step.ncyBreadcrumbLabel}}</li></ul>'
    });
});
// ng-storage
//set a prefix to avoid overwriting any local storage variables
app.config(['$localStorageProvider',
    function ($localStorageProvider) {
        $localStorageProvider.setKeyPrefix('PacketLtr1');
    }]);
//filter to convert html to plain text
app.filter('htmlToPlaintext', function () {
      return function (text) {
          return String(text).replace(/<[^>]+>/gm, '');
      };
  }
);
//Custom UI Bootstrap Calendar Popup Template
app.run(["$templateCache", function ($templateCache) {
    $templateCache.put("uib/template/datepicker/popup.html",
            "<ul class=\"dropdown-menu clip-datepicker\"  ng-style=\"{display: (isOpen && 'block') || 'none', top: position.top+'px', left: position.left+'px'}\" ng-keydown=\"keydown($event)\">\n" +
            "	<li ng-transclude></li>\n" +
            "	<li ng-if=\"showButtonBar\" style=\"padding:10px 9px 2px\">\n" +
            "		<span class=\"btn-group pull-left\">\n" +
            "			<button type=\"button\" class=\"btn btn-sm btn-primary btn-o\" ng-click=\"select('today')\">{{ getText('current') }}</button>\n" +
            "			<button type=\"button\" class=\"btn btn-sm btn-primary btn-o\" ng-click=\"select(null)\">{{ getText('clear') }}</button>\n" +
            "		</span>\n" +
            "		<button type=\"button\" class=\"btn btn-sm btn-primary pull-right\" ng-click=\"close()\">{{ getText('close') }}</button>\n" +
            "	</li>\n" +
            "</ul>\n" +
        "");
}]);
/* /js/config.constant.js */


/**
 * Config constant
 */
app.constant('APP_MEDIAQUERY', {
    'desktopXL': 1200,
    'desktop': 992,
    'tablet': 768,
    'mobile': 480
});
app.constant('JS_REQUIRES', {
    //*** Scripts
    scripts: {
        //*** Javascript Plugins
        'd3': '/bower_components/d3/d3.min.js',

        //*** jQuery Plugins
        'chartjs': '/bower_components/chartjs/Chart.min.js',
        'ckeditor-plugin': '/bower_components/ckeditor/ckeditor.js',
        'jquery-nestable-plugin': ['/bower_components/jquery-nestable/jquery.nestable.js'],
        'touchspin-plugin': ['/bower_components/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js', '/bower_components/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css'],
        'jquery-appear-plugin': ['/bower_components/jquery-appear/build/jquery.appear.min.js'],
        'spectrum-plugin': ['/bower_components/spectrum/spectrum.js', '/bower_components/spectrum/spectrum.css'],

        //*** Controllers
        'dashboardCtrl': '/js/controllers/dashboardCtrl.js',
        'iconsCtrl': '/js/controllers/iconsCtrl.js',
        'vAccordionCtrl': '/js/controllers/vAccordionCtrl.js',
        'ckeditorCtrl': '/js/controllers/ckeditorCtrl.js',
        'laddaCtrl': '/js/controllers/laddaCtrl.js',
        'ngTableCtrl': '/js/controllers/ngTableCtrl.js',
        'cropCtrl': '/js/controllers/cropCtrl.js',
        'asideCtrl': '/js/controllers/asideCtrl.js',
        'toasterCtrl': '/js/controllers/toasterCtrl.js',
        'sweetAlertCtrl': '/js/controllers/sweetAlertCtrl.js',
        'mapsCtrl': '/js/controllers/mapsCtrl.js',
        'chartsCtrl': '/js/controllers/chartsCtrl.js',
        'calendarCtrl': '/js/controllers/calendarCtrl.js',
        'nestableCtrl': '/js/controllers/nestableCtrl.js',
        'validationCtrl': ['/js/controllers/validationCtrl.js'],
        'userCtrl': ['/js/controllers/userCtrl.js'],
        'selectCtrl': '/js/controllers/selectCtrl.js',
        'wizardCtrl': '/js/controllers/wizardCtrl.js',
        'uploadCtrl': '/js/controllers/uploadCtrl.js',
        'treeCtrl': '/js/controllers/treeCtrl.js',
        'inboxCtrl': '/js/controllers/inboxCtrl.js',
        'xeditableCtrl': '/js/controllers/xeditableCtrl.js',
        'chatCtrl': '/js/controllers/chatCtrl.js',
        'dynamicTableCtrl': '/js/controllers/dynamicTableCtrl.js',
        'notificationIconsCtrl': '/js/controllers/notificationIconsCtrl.js',
        'dateRangeCtrl': '/js/controllers/daterangeCtrl.js',
        'notifyCtrl': '/js/controllers/notifyCtrl.js',
        'sliderCtrl': '/js/controllers/sliderCtrl.js',
        'knobCtrl': '/js/controllers/knobCtrl.js',
//        'gMaps': '//maps.googleapis.com/maps/api/js?libraries=places',
        
        //*** CakeControllers <Himanshu>
        'pagesCtrl': '/js/Ctrl/pagesCtrl.js',
        'clientsCtrl': '/js/Ctrl/clientsCtrl.js',
        'campaignsCtrl': '/js/Ctrl/campaignsCtrl.js',
        'albumsCtrl': '/js/Ctrl/albumsCtrl.js',
        'customersCtrl': '/js/Ctrl/customersCtrl.js',
        'socialsharesCtrl': '/js/Ctrl/socialsharesCtrl.js',
        'pushmessagesCrtl': '/js/Ctrl/pushmessagesCrtl.js',
        'recommendscreenCrtl': '/js/Ctrl/recommendscreenCrtl.js',
        'storeCtrl': '/js/Ctrl/storeCtrl.js',
        'bannersCtrl': '/js/Ctrl/bannersCtrl.js',
        'templatemessagesCtrl': '/js/Ctrl/templatemessagesCtrl.js',
        'productsCrtl': '/js/Ctrl/productsCrtl.js',
        'questionsCtrl': '/js/Ctrl/questionsCtrl.js',
        'promocodeCrtl': '/js/Ctrl/promocodeCrtl.js',
        'smsledgerCtrl': '/js/Ctrl/smsledgerCtrl.js',
        'feedbackCtrl': '/js/Ctrl/feedbackCtrl.js',
        'settingCtrl': '/js/Ctrl/settingCtrl.js',
        'analyticsCtrl': '/js/Ctrl/analyticsCtrl.js',
        'loyaltyCtrl': '/js/Ctrl/loyaltyCtrl.js',
        'mallShopCtrl': '/js/Ctrl/mallShopCtrl.js',
        'mallVoucherCounterCtrl': '/js/Ctrl/mallVoucherCounterCtrl.js',
        'mallVerificationcountersCtrl': '/js/Ctrl/mallVerificationcountersCtrl.js',
        'ShareScreenCrtl' : '/js/Ctrl/ShareScreenCrtl.js'
        
        
    },
    //*** angularJS Modules
    modules: [{
        name: 'toaster',
        files: ['/bower_components/AngularJS-Toaster/toaster.js', '/bower_components/AngularJS-Toaster/toaster.css']
    }, {
        name: 'angularBootstrapNavTree',
        files: ['/bower_components/angular-bootstrap-nav-tree/dist/abn_tree_directive.js', '/bower_components/angular-bootstrap-nav-tree/dist/abn_tree.css']
    }, {
        name: 'ngTable',
        files: ['/bower_components/ng-table/dist/ng-table.min.js', '/bower_components/ng-table/dist/ng-table.min.css']
    }, {
        name: 'ui.mask',
        files: ['/bower_components/angular-ui-utils/mask.min.js']
    }, {
        name: 'ngImgCrop',
        files: ['/bower_components/ngImgCrop/compile/minified/ng-img-crop.js', '/bower_components/ngImgCrop/compile/minified/ng-img-crop.css']
    }, {
        name: 'angularFileUpload',
        files: ['/bower_components/angular-file-upload/angular-file-upload.min.js']
    }, {
        name: 'monospaced.elastic',
        files: ['/bower_components/angular-elastic/elastic.js']
    }, {
        name: 'ngMap',
        files: ['/bower_components/ngmap/build/scripts/ng-map.min.js']
    }, {
        name: 'chart.js',
        files: ['/bower_components/angular-chart.js/dist/angular-chart.min.js', '/bower_components/angular-chart.js/dist/angular-chart.min.css']
    }, {
        name: 'flow',
        files: ['/bower_components/ng-flow/dist/ng-flow-standalone.min.js']
    }, {
        name: 'ckeditor',
        files: ['/bower_components/angular-ckeditor/angular-ckeditor.min.js']
    }, {
        name: 'mwl.calendar',
        files: ['/bower_components/angular-bootstrap-calendar/dist/js/angular-bootstrap-calendar-tpls.js', '/bower_components/angular-bootstrap-calendar/dist/css/angular-bootstrap-calendar.min.css', '/js/config/config-calendar.js']
    }, {
        name: 'ng-nestable',
        files: ['/bower_components/ng-nestable/src/angular-nestable.js']
    }, {
        name: 'ngNotify',
        files: ['/bower_components/ng-notify/dist/ng-notify.min.js', '/bower_components/ng-notify/dist/ng-notify.min.css']
    }, {
        name: 'xeditable',
        files: ['/bower_components/angular-xeditable/dist/js/xeditable.min.js', '/bower_components/angular-xeditable/dist/css/xeditable.css', '/js/config/config-xeditable.js']
    }, {
        name: 'checklist-model',
        files: ['/bower_components/checklist-model/checklist-model.js']
    }, {
        name: 'ui.knob',
        files: ['/bower_components/ng-knob/dist/ng-knob.min.js']
    }, {
        name: 'ngAppear',
        files: ['/bower_components/angular-appear/build/angular-appear.min.js']
    }, {
        name: 'countTo',
        files: ['/bower_components/angular-count-to-0.1.1/dist/angular-filter-count-to.min.js']
    }, {
        name: 'angularSpectrumColorpicker',
        files: ['/bower_components/angular-spectrum-colorpicker/dist/angular-spectrum-colorpicker.min.js']
    }]
});
/* /js/config.router.js */


/**
 * Config for the router
 */
app.config(['$stateProvider', '$urlRouterProvider', '$controllerProvider', '$compileProvider', '$filterProvider', '$provide', '$ocLazyLoadProvider', 'JS_REQUIRES',
function ($stateProvider, $urlRouterProvider, $controllerProvider, $compileProvider, $filterProvider, $provide, $ocLazyLoadProvider, jsRequires) {

    app.controller = $controllerProvider.register;
    app.directive = $compileProvider.directive;
    app.filter = $filterProvider.register;
    app.factory = $provide.factory;
    app.service = $provide.service;
    app.constant = $provide.constant;
    app.value = $provide.value;

    // LAZY MODULES

    $ocLazyLoadProvider.config({
        debug: false,
        events: true,
        modules: jsRequires.modules
    });


    // APPLICATION ROUTES
    // -----------------------------------
    // For any unmatched url, redirect to /app/dashboard
    $urlRouterProvider.otherwise("/login/signin");
    //
    // Set up the states
    $stateProvider.state('app', {
        url: "/app",
        templateUrl: "/views/app.html",
        resolve: loadSequence('chartjs', 'chart.js', 'chatCtrl'),
        abstract: true
    }).state('app.dashboard', {
        url: "/dashboard",
        templateUrl: "/views/pages/angloader.html",
        resolve: loadSequence('d3', 'ui.knob', 'countTo', 'pagesCtrl'),
        title: 'Dashboard',
        controller: 'Pages.DashboardCtrl',
        ncyBreadcrumb: {
            label: 'Dashboard'
        }
    }).state('app.allmessages', {
        url: "/allmessages/:cmpid",
        templateUrl: "/views/pages/allmessages.html",
        resolve: loadSequence('d3', 'ui.knob', 'countTo', 'pagesCtrl'),
        title: 'Dashboard',
        controller: 'Pages.AllmessagesCtrl',
        ncyBreadcrumb: {
            label: 'Dashboard'
        }
    }).state('error', {
        url: '/error',
        template: '<div ui-view class="fade-in-up"></div>'
    }).state('error.404', {
        url: '/404',
        templateUrl: "/views/utility_404.html",
    }).state('error.500', {
        url: '/500',
        templateUrl: "/views/utility_500.html",
    })
    
    //
    .state('campaigns',{
        url: "/campaigns",
        templateUrl: "/views/app.html",
        resolve: loadSequence('chartjs', 'chart.js', 'chatCtrl'),
//        template: '<div ui-view class="fade-in-up"></div>'
        ncyBreadcrumb: {
            label: "Campaigns"
        }
    }).state('campaigns.smsregular',{
        url: '/smsregular',
        templateUrl: '/views/campaigns/smsregular.html',
        resolve: loadSequence('campaignsCtrl','recommendscreenCrtl','monospaced.elastic','ui.mask','touchspin-plugin'),
        title: 'Campaigns | B\'bday & Anniversary',
        controller: 'Campaigns.SmsRegularCtrl',
        ncyBreadcrumb: {
            label: "Regular",
        }
    }).state('campaigns.sms',{
        url: '/sms',
        templateUrl: '/views/campaigns/sms.html',
        resolve: loadSequence('campaignsCtrl','monospaced.elastic','ui.mask','touchspin-plugin','selectCtrl'),
        title: 'Campaigns | Scheduled',
        controller: 'Campaigns.SmsCtrl',
        ncyBreadcrumb: {
            label: "B\'day & Anniversary SMS"
        }
    }).state('campaigns.recommend',{
        url: '/recommend',
        templateUrl: '/views/campaigns/recommend.html',
        resolve: loadSequence('campaignsCtrl','monospaced.elastic','ui.mask','touchspin-plugin','selectCtrl'),
        title: 'Campaigns | Recommend Friends',
        controller: 'Campaigns.RecommendCtrl',
        ncyBreadcrumb: {
            label: "Recommend Friends (SMS)"
        }
    }).state('campaigns.emailregular',{
        url: '/emailregular',
        templateUrl: '/views/campaigns/emailregular.html',
        resolve: loadSequence('campaignsCtrl','ckeditor-plugin','ckeditor','ckeditorCtrl'),
        title: 'Campaigns | B\'day & Anniversary',
        controller: 'Campaigns.EmailRegularCtrl',
        ncyBreadcrumb: {
            label: "Schedule SMS"
        }
    }).state('campaigns.email',{
        url: '/email',
        templateUrl: '/views/campaigns/email.html',
        resolve: loadSequence('campaignsCtrl','monospaced.elastic','ui.mask','touchspin-plugin','selectCtrl','ckeditor-plugin','ckeditor','ckeditorCtrl'),
        title: 'Campaigns | Scheduled',
        controller: 'Campaigns.EmailCtrl',
        ncyBreadcrumb: {
            label: "B\'day & Anniversary E-Mail"
        }
    }).state('campaigns.lastvisit',{
        url: '/lastvisit',
        templateUrl: '/views/campaigns/lastvisit.html',
        resolve: loadSequence('campaignsCtrl','monospaced.elastic','ui.mask','touchspin-plugin','selectCtrl','ckeditor-plugin','ckeditor','ckeditorCtrl'),
        title: 'Campaigns | Auto Reminder',
        controller: 'Campaigns.LastvisitCtrl',
        ncyBreadcrumb: {
            label: "Auto Reminder"
        }
    })
/**
 * Marketing -> Analytics -> Campaigns
 */    
    .state('campaigns.analytics',{
        url: '/analytics',
        templateUrl: '/views/campaigns/analytics.html',
        resolve: loadSequence('chartjs','campaignsCtrl','monospaced.elastic','ui.mask','touchspin-plugin','selectCtrl','ckeditor-plugin','ckeditor','ckeditorCtrl'),
        title: 'Campaigns | Analytics',
        controller: 'Campaigns.AnalyticsCtrl',
        ncyBreadcrumb: {
            label: "Analytics"
        }
    })
    .state('campaigns.refered',{
        url: '/refered',
        templateUrl: '/views/campaigns/refered.html',
        resolve: loadSequence('campaignsCtrl','monospaced.elastic','ui.mask','touchspin-plugin'),
        title: 'Campaigns | Refered Friend',
        controller: 'Campaigns.Refered',
        ncyBreadcrumb: {
            label: "Refered"
        }
    })
    
/**
 * Marketing -> SocialShare
 */    

    .state('socialshare',{
        url: "/socialshare",
        templateUrl: "/views/app.html",
        resolve: loadSequence('chartjs', 'chart.js', 'chatCtrl'),
//        template: '<div ui-view class="fade-in-up"></div>'
        ncyBreadcrumb: {
            label: "Social Share"
        }
    }).state('socialshare.create',{
        url: '/create',
        templateUrl: '/views/socialshares/create.html',
        resolve: loadSequence('socialsharesCtrl','monospaced.elastic', 'ui.mask', 'touchspin-plugin', 'selectCtrl'),
        title: 'Create Post',
        controller: 'Socialshares.CreateCtrl',
        ncyBreadcrumb: {
            label: "Create Post"
        }
    }).state('socialshare.all',{
        url: '/all',
        templateUrl: '/views/socialshares/all.html',
        resolve: loadSequence('socialsharesCtrl','monospaced.elastic', 'ui.mask', 'touchspin-plugin', 'selectCtrl'),
        title: 'Create Post',
        controller: 'Socialshares.IndexCtrl',
        ncyBreadcrumb: {
            label: "Create Post"
        }
    })

    
/**
 * Album Method Start
 */

    .state('app.albums', {
        url: '/albums',
        template: '<div ui-view class="fade-in-up"></div>',
        title: 'Forms',
        ncyBreadcrumb: {
            label: 'Forms'
        }
    })
    .state('app.albums.add', {
        url: '/add',
        templateUrl: "/views/albums/add_new_album.html",
        resolve: loadSequence('albumsCtrl','angularFileUpload', 'uploadCtrl'),
        controller: 'Albums.AddAlbumCtrl',
        title: 'Add New Album',
        ncyBreadcrumb: {
            label: 'Add New Album'
        }
    })
    .state('app.albums.upload', {
        url: '/upload',
        templateUrl: "/views/albums/form_file_upload.html",
        resolve: loadSequence('albumsCtrl','angularFileUpload', 'uploadCtrl'),
        controller: 'Albums.IndexCtrl',
        title: 'Multiple File Upload',
        ncyBreadcrumb: {
            label: 'File Upload'
        }
    })
    .state('app.albums.send', {
        url: '/send',
        templateUrl: "/views/albums/send_file.html",
        resolve: loadSequence('albumsCtrl','monospaced.elastic','ui.mask','touchspin-plugin','ckeditor-plugin','ckeditor','ckeditorCtrl'),
        controller: 'Albums.Sendphotos',
        title: 'Send Photos',
        ncyBreadcrumb: {
            label: 'Send Photos'
        }
    })

/**
 * Album Method End
 */

/**
 * Customer Methods Start
 */

    .state('app.customers', {
        url: '/customers',
        template: '<div ui-view class="fade-in-up"></div>',
        title: 'Forms',
        ncyBreadcrumb: {
            label: 'Customers'
        }
    })
    .state('app.customers.list', {
        url: '/list',
        templateUrl: "/views/customers/index.html",
        resolve: loadSequence('angularFileUpload', 'customersCtrl'),
        controller: 'Customers.IndexCtrl',
        title: 'List',
        ncyBreadcrumb: {
            label: 'List'
        }
    })
    .state('app.customers.mesaurement', {
        url: '/mesaurement',
        templateUrl: "/views/customers/mesaurement.html",
        resolve: loadSequence('customersCtrl'),
        controller: 'Customers.MesaurementCtrl',
        title: 'Mesaurement',
        ncyBreadcrumb: {
            label: 'Mesaurement'
        }
    })
    .state('app.customers.tt', {
        url: '/tt',
        templateUrl: "/views/customers/index2.html",
        resolve: loadSequence('customersCtrl'),
        controller: 'Customers.tCtrl',
        title: 'Test',
        ncyBreadcrumb: {
            label: 'Test'
        }
    })
/**
 * Customer Methods End
 */

/**
 * PushNotification Methods Start
 */
    .state('appData',{
        url: "/app-data",
        templateUrl: "/views/app.html",
        resolve: loadSequence('chartjs', 'chart.js', 'chatCtrl'),
//        template: '<div ui-view class="fade-in-up"></div>'
        ncyBreadcrumb: {
            label: "App Data"
        }
    }).state('appData.pushmessages',{
        url : '/push-messages',
        templateUrl: "/views/pushmessages/index.html",
        resolve: loadSequence('angularFileUpload','pushmessagesCrtl'),
        controller: 'Pushmessages.Index',
        title: 'Send PushNotification for Android and Ios',
        ncyBreadcrumb: {
            label: 'PushNotification'
        }
    }).state('appData.image',{
        url : '/image',
        templateUrl: "/views/banners/index.html",
        resolve: loadSequence('bannersCtrl','angularFileUpload', 'uploadCtrl'),
        controller: 'Banners.IndexCtrl',
        title: 'Images',
        ncyBreadcrumb: {
            label: 'Image'
        }
    }).state('appData.banner',{
        url : '/banner',
        templateUrl: "/views/banners/banners.html",
        resolve: loadSequence('bannersCtrl','angularFileUpload', 'uploadCtrl'),
        controller: 'Banners.ImageCtrl',
        title: 'Banner image',
        ncyBreadcrumb: {
            label: 'Banner images'
        }
    }).state('appData.store',{
        url : '/store',
        templateUrl: "/views/stores/index.html",
        resolve: loadSequence('storeCtrl'),
        controller: 'Store.IndexCtrl',
        title: 'Store Info',
        ncyBreadcrumb: {
            label: 'Store Info'
        }
    })
/**
 * PushNotification Methods End
 */

/**
 * Redeem Methods Start
 */
    
    .state('redeem',{
        url: "/redeem",
        templateUrl: "/views/app.html",
        resolve: loadSequence('chartjs', 'chart.js', 'chatCtrl'),
//        template: '<div ui-view class="fade-in-up"></div>'
        ncyBreadcrumb: {
            label: "Redeem"
        }
    })
    .state('redeem.coupon',{
        url : '/coupon',
        templateUrl: "/views/promo/redeem_coupon.html",
        resolve: loadSequence('promocodeCrtl','angularFileUpload', 'uploadCtrl'),
        controller: 'Promocode.Redeem',
        title: 'Redeem Promo Code',
        ncyBreadcrumb: {
            label: 'Redeem Promo Code'
        }
    })
/**
 * Settings Methods Start
 */
    
    .state('settings',{
        url: "/settings",
        templateUrl: "/views/app.html",
        resolve: loadSequence('chartjs', 'chart.js', 'chatCtrl'),
//        template: '<div ui-view class="fade-in-up"></div>'
        ncyBreadcrumb: {
            label: "Settings"
        }
    })
    .state('settings.store',{
        url : '/store',
        templateUrl: "/views/Settings/index.html",
        resolve: loadSequence('settingCtrl'),
        controller: 'Settings.Store',
        title: 'Daily Report',
        ncyBreadcrumb: {
            label: 'Store Settings'
        }
    })
    .state('settings.mallsetting',{
        url : '/mallsetting',
        templateUrl: "/views/Settings/mallsetting.html",
        resolve: loadSequence('settingCtrl'),
        controller: 'Settings.mallsetting',
        title: 'Mall Setting',
        ncyBreadcrumb: {
            label: 'Store Mall Settings'
        }
    })
/**
 * PushNotification Methods End
 */




///**
// * PushNotification Methods Start
// */
//        .state('app.recommend-screen',{
//            url : '/recommend-screen',
//            templateUrl: "/views/RecommendScreen/index.html",
//            resolve: loadSequence('angularFileUpload','recommendscreenCrtl','monospaced.elastic','ui.mask','touchspin-plugin'),
//            controller: 'RecommendscreenCrtl.Index',
//            title: 'Recommend friends Screen',
//            ncyBreadcrumb: {
//                label: 'Recommend friends Screen'
//            }
//        }) 
///**
// * PushNotification Methods End
// */


/**
 * Customer Feedback Start
 */
    .state('cstfback',{
        url: "/customer-feedback",
        templateUrl: "/views/app.html",
        resolve: loadSequence('chartjs', 'chart.js', 'chatCtrl'),
//        template: '<div ui-view class="fade-in-up"></div>'
        ncyBreadcrumb: {
            label: "Customer Feedback"
        }
    }).state('cstfback.tabscreens',{
        url: "/edit-tab-screens",
//        templateUrl: "/views/app.html",
//        resolve: loadSequence('chartjs', 'chart.js', 'chatCtrl'),
        template: '<div ui-view class="fade-in-up"></div>',
        ncyBreadcrumb: {
            label: "Edit Tab Screens"
        }
    }).state('cstfback.analytics',{
        url: "/analytics",
//        templateUrl: "/views/app.html",
        resolve: loadSequence('chartjs', 'chart.js'),
        template: '<div ui-view class="fade-in-up"></div>',
        ncyBreadcrumb: {
            label: "Analytics"
        }
    }).state('cstfback.analytics.nps',{
        url: '/nps',
        templateUrl: '/views/Analytics/nps.html',
        resolve: loadSequence('d3','ui.knob','angularFileUpload','monospaced.elastic', 'ui.mask', 'touchspin-plugin','analyticsCtrl'),
        title: 'Net Promoters Score',
        controller: 'Analytics.NpsCtrl',
        ncyBreadcrumb: {
            label: "Net Promoters Score"
        }
    }).state('cstfback.analytics.customersrepeat',{
        url: '/repeat-customers',
        templateUrl: '/views/Analytics/repeat-customers.html',
        resolve: loadSequence('d3','ui.knob','angularFileUpload','monospaced.elastic', 'ui.mask', 'touchspin-plugin','analyticsCtrl'),
        title: 'Repeat Customer Analysis',
        controller: 'Analytics.RepeatCustomersCtrl',
        ncyBreadcrumb: {
            label: "Repeat Customer Analysis"
        }
    }).state('cstfback.analytics.feedbackquestions',{
        url: '/feedback-questions/:question_code',
        templateUrl: '/views/Analytics/feedback-questions.html',
        resolve: loadSequence('d3','ui.knob','angularFileUpload','monospaced.elastic', 'ui.mask', 'touchspin-plugin','analyticsCtrl'),
        title: 'Feedback Questions Analysis',
        controller: 'Analytics.FeedbackQuestionsCtrl',
        ncyBreadcrumb: {
            label: "Feedback Questions Analysis"
        }
    }).state('cstfback.tabscreens.welcome',{
        url: '/welcome-screen',
        templateUrl: '/views/templatemessages/index.html',
        resolve: loadSequence('templatemessagesCtrl','monospaced.elastic', 'ui.mask', 'touchspin-plugin', 'selectCtrl'),
        title: 'Welcome Screen',
        controller: 'templatemessagesCtrl.IndexCtrl',
        ncyBreadcrumb: {
            label: "Welcome Screen"
        }
    }).state('cstfback.tabscreens.questions',{
        url: '/feedback-questions',
        templateUrl: '/views/questions/index.html',
        resolve: loadSequence('questionsCtrl','angularFileUpload','monospaced.elastic', 'ui.mask', 'touchspin-plugin', 'selectCtrl'),
        title: 'Feedback Questions',
        controller: 'questionsCtrl.Index',
        ncyBreadcrumb: {
            label: "Feedback Questions"
        }
    }).state('cstfback.tabscreens.recommend',{
        url: '/recommend-friends',
        templateUrl: '/views/RecommendScreen/index.html',
        resolve: loadSequence('angularFileUpload','recommendscreenCrtl','monospaced.elastic','ui.mask','touchspin-plugin'),
        controller: 'RecommendscreenCrtl.Index',
        title: 'Recommend Friends',
        ncyBreadcrumb: {
            label: "Recommend Friends"
        }
    }).state('cstfback.tabscreens.socialshr',{
        url: '/social-share',
        templateUrl: '/views/ShareScreen/index.html',
        resolve: loadSequence('ShareScreenCrtl'),
        controller: 'ShareScreen.Index',
        title: 'Share Screen',
        ncyBreadcrumb: {
            label: "Share Screen"
        }
    }).state('cstfback.tabscreens.products',{
        url: '/manage-products',
        templateUrl: '/views/Products/index.html',
        resolve: loadSequence('xeditable','angularFileUpload','productsCrtl','monospaced.elastic','ui.mask','touchspin-plugin'),
        controller: 'productsCrtl.Index',
        title: 'Manage Products',
        ncyBreadcrumb: {
            label: "Manage Products"
        }
    })


/**
 * Customer Feedback End
 */


/**
 * SmsLedger Logs Start
 */
    .state('smslogs',{
        url: "/smslogs",
        templateUrl: "/views/app.html",
        resolve: loadSequence('chartjs', 'chart.js', 'chatCtrl'),
        ncyBreadcrumb: {
            label: "SMS Logs"
        }
    })
    .state('smslogs.list',{
        url : '/list',
        templateUrl: "/views/Smsledger/index.html",
        resolve: loadSequence('chartjs', 'chart.js', 'smsledgerCtrl'),
        controller: 'smsledgerCtrl.Index',
        title: 'Timeline',
        ncyBreadcrumb: {
            label: 'Timeline'
        }
    }).state('smslogs.credits',{
        url : '/credits',
        templateUrl: "/views/Smsledger/credits.html",
        resolve: loadSequence('chartjs', 'chart.js', 'smsledgerCtrl'),
        controller: 'smsledgerCtrl.Credits',
        title: 'Credits Added',
        ncyBreadcrumb: {
            label: 'Credits Added'
        }
    }).state('smslogs.debits',{
        url : '/debits/:flag',
        templateUrl: "/views/Smsledger/debits.html",
        resolve: loadSequence('chartjs', 'chart.js', 'smsledgerCtrl'),
        controller: 'smsledgerCtrl.Debits',
        title: 'Debits',
        ncyBreadcrumb: {
            label: 'Debits'
        }
    })
    
/**
 * Feedback Start
 */
    .state('feedback',{
        url: "/feedback",
        templateUrl: "/views/app.html",
        resolve: loadSequence('chartjs', 'chart.js', 'chatCtrl'),
        ncyBreadcrumb: {
            label: "Feedback"
        }
    })
    .state('feedback.publish',{
        url : '/publish',
        templateUrl: "/views/feedback/index.html",
        resolve: loadSequence('chartjs', 'chart.js', 'feedbackCtrl'),
        controller: 'feedbackCtrl.Index',
        title: 'Publish',
        ncyBreadcrumb: {
            label: 'Publish'
        }
    })
/**
 * Feedback End
 */


/**
 * Feedback Start
 */
    .state('loyalty',{
        url: "/loyalty",
        templateUrl: "/views/app.html",
        resolve: loadSequence('chartjs', 'chart.js', 'chatCtrl'),
        ncyBreadcrumb: {
            label: "Loyalty"
        }
    })
    .state('loyalty.index',{
        url : '/index',
        templateUrl: "/views/loyalty/index.html",
        resolve: loadSequence('loyaltyCtrl'),
        controller: 'Loyalty.index',
        title: 'Index',
        ncyBreadcrumb: {
            label: 'Index'
        }
    })
    .state('loyalty.ledger',{
        url : '/ledger/:number/:code',
        templateUrl: "/views/loyalty/ledger.html",
        resolve: loadSequence('loyaltyCtrl'),
        controller: 'Loyalty.ledger',
        title: 'Ledger',
        ncyBreadcrumb: {
            label: 'Ledger'
        }
    })
/**
 * Feedback End
 */




    .state('clients',{
        url: "/clients",
        templateUrl: "/views/app.html",
        resolve: loadSequence('chartjs', 'chart.js', 'chatCtrl'),
        ncyBreadcrumb: {
            label: "Clients"
        }
    })
    .state('clients.changepwd',{
        url : '/changepwd',
        templateUrl: "/views/clients/changepwd.html",
        resolve: loadSequence('clientsCtrl'),
        controller: 'Clients.ChangePwdCtrl',
        title: 'Clients',
        ncyBreadcrumb: {
            label: 'Change Password'
        }
    })
    // Login routes

    .state('login', {
        url: '/login',
        template: '<div ui-view class="fade-in-right-big smooth"></div>',
        abstract: true
    }).state('login.signin', {
        url: '/signin',
        templateUrl: "/views/clients/login.html",
        resolve: loadSequence('clientsCtrl'),
        controller: 'Clients.LoginCtrl',
        onEnter: function($state){
            if($authUser != null){ $state.go('app.dashboard')}
          }
    }).state('login.forgot', {
        url: '/forgot',
        templateUrl: "/views/clients/forgotpwd.html",
        resolve: loadSequence('clientsCtrl'),
        controller: 'Clients.ForgotCtrl',
    }).state('login.registration', {
        url: '/registration',
        templateUrl: "/views/login_registration.html"
    }).state('login.lockscreen', {
        url: '/lock',
        templateUrl: "/views/login_lock_screen.html"
    })
    
    
    /*
     * Mall Shop 
     */
    .state('app.mall', {
        url: '/mall',
        template: '<div ui-view class="fade-in-up"></div>',
        title: 'Forms',
        ncyBreadcrumb: {
            label: 'Mall Shop'
        }
    })
    .state('app.mall.shop', {
        url: '/shop',
        templateUrl: "/views/MallShop/index.html",
        resolve: loadSequence('mallShopCtrl'),
        controller: 'MallShop.IndexCrtl',
        title: 'List',
        ncyBreadcrumb: {
            label: 'List'
        }
    })
    .state('app.mall.vouchercounter', {
        url: '/vouchercounter',
        templateUrl: "/views/MallVouchercounters/index.html",
        resolve: loadSequence('mallVoucherCounterCtrl'),
        controller: 'MallVoucherCounter.IndexCrtl',
        title: 'MallVoucher',
        ncyBreadcrumb: {
            label: 'MallVoucher'
        }
    })
    .state('app.mall.verificationcounters', {
        url: '/verificationcounters',
        templateUrl: "/views/MallVerificationcounters/index.html",
        resolve: loadSequence('mallVerificationcountersCtrl'),
        controller: 'mallVerificationcounters.IndexCrtl',
        title: 'MallVerificationcountersCtrl',
        ncyBreadcrumb: {
            label: 'MallVerificationcounters'
        }
    });

	// Landing Page route
//	.state('landing', {
//	    url: '/landing-page',
//	    template: '<div ui-view class="fade-in-right-big smooth"></div>',
//	    abstract: true,
//	    resolve: loadSequence('jquery-appear-plugin', 'ngAppear', 'countTo')
//	}).state('landing.welcome', {
//	    url: '/welcome',
//	    templateUrl: "/views/landing_page.html"
//	});
    // Generates a resolve object previously configured in constant.JS_REQUIRES (config.constant.js)
    function loadSequence() {
        var _args = arguments;
        return {
            deps: ['$ocLazyLoad', '$q',
			function ($ocLL, $q) {
			    var promise = $q.when(1);
			    for (var i = 0, len = _args.length; i < len; i++) {
			        promise = promiseThen(_args[i]);
			    }
			    return promise;

			    function promiseThen(_arg) {
			        if (typeof _arg == 'function')
			            return promise.then(_arg);
			        else
			            return promise.then(function () {
			                var nowLoad = requiredData(_arg);
			                if (!nowLoad)
			                    return $.error('Route resolve: Bad resource name [' + _arg + ']');
			                return $ocLL.load(nowLoad);
			            });
			    }

			    function requiredData(name) {
			        if (jsRequires.modules)
			            for (var m in jsRequires.modules)
			                if (jsRequires.modules[m].name && jsRequires.modules[m].name === name)
			                    return jsRequires.modules[m];
			        return jsRequires.scripts && jsRequires.scripts[name];
			    }
			}]
        };
    }
}]);
/* /js/directives/toggle.js */

/** 
  * A simple but useful and efficient directive to toggle a class to an element.   
*/
app.factory('ToggleHelper', ['$rootScope',
function ($rootScope) {
    return {

        events: {
            toggle: "clip-two.toggle",
            toggleByClass: "clip-two.toggleByClass",
            togglerLinked: "clip-two.linked",
            toggleableToggled: "clip-two.toggled"
        },

        commands: {
            alternate: "toggle",
            activate: "on",
            deactivate: "off"
        },

        toggle: function (target, command) {
            if (command == null) {
                command = "toggle";
            }
            $rootScope.$emit(this.events.toggle, target, command);
        },

        toggleByClass: function (targetClass, command) {
            if (command == null) {
                command = "toggle";
            }
            $rootScope.$emit(this.events.toggleByClass, targetClass, command);
        },

        notifyToggleState: function (elem, attrs, toggleState) {
            $rootScope.$emit(this.events.toggleableToggled, attrs.id, toggleState, attrs.exclusionGroup);
        },

        toggleStateChanged: function (elem, attrs, toggleState) {
            this.updateElemClasses(elem, attrs, toggleState);
            this.notifyToggleState(elem, attrs, toggleState);
        },

        applyCommand: function (command, oldState) {
            switch (command) {
                case this.commands.activate:
                    return true;
                case this.commands.deactivate:
                    return false;
                case this.commands.alternate:
                    return !oldState;
            }
        },

        updateElemClasses: function (elem, attrs, active) {

            if (active) {
                if (attrs.activeClass) {
                    elem.addClass(attrs.activeClass);
                }
                if (attrs.inactiveClass) {
                    elem.removeClass(attrs.inactiveClass);
                }
                var parent = elem.parent();
                if (attrs.parentActiveClass) {
                    parent.addClass(attrs.parentActiveClass);
                }
                if (attrs.parentInactiveClass) {
                    parent.removeClass(attrs.parentInactiveClass);
                }
            } else {
                if (attrs.inactiveClass) {
                    elem.addClass(attrs.inactiveClass);
                }
                if (attrs.activeClass) {
                    elem.removeClass(attrs.activeClass);
                }
                var parent = elem.parent();
                if (attrs.parentInactiveClass) {
                    parent.addClass(attrs.parentInactiveClass);
                }
                if (attrs.parentActiveClass) {
                    parent.removeClass(attrs.parentActiveClass);
                }
            }
        }
    };
}]).run(["$rootScope", "ToggleHelper",
function ($rootScope, ToggleHelper) {

    $rootScope.toggle = function (target, command) {
        if (command == null) {
            command = "toggle";
        }
        ToggleHelper.toggle(target, command);
    };

    $rootScope.toggleByClass = function (targetClass, command) {
        if (command == null) {
            command = "toggle";
        }
        ToggleHelper.toggleByClass(targetClass, command);
    };
}]).directive('ctToggle', ["$rootScope", "ToggleHelper",
function ($rootScope, ToggleHelper) {
    return {
        restrict: "A",
        link: function (scope, elem, attrs) {
            var command = attrs.ctToggle || ToggleHelper.commands.alternate;
            var target = attrs.target;
            var targetClass = attrs.targetClass;
            var bubble = attrs.bubble === "true" || attrs.bubble === "1" || attrs.bubble === 1 || attrs.bubble === "" || attrs.bubble === "bubble";

            if ((!target) && attrs.href) {
                target = attrs.href.slice(1);
            }

            if (!(target || targetClass)) {
                throw "'target' or 'target-class' attribute required with 'ct-toggle'";
            }
            elem.on("click tap", function (e) {

                var angularElem = angular.element(e.target);
                if (!angularElem.hasClass("disabled")) {
                    if (target != null) {
                        ToggleHelper.toggle(target, command);
                    }
                    if (targetClass != null) {
                        ToggleHelper.toggleByClass(targetClass, command);
                    }
                    if (!bubble) {
                        e.preventDefault();
                        return false;
                    } else {
                        return true;
                    }
                }

            });
            var unbindUpdateElemClasses = $rootScope.$on(ToggleHelper.events.toggleableToggled, function (e, id, newState) {
                if (id === target) {
                    ToggleHelper.updateElemClasses(elem, attrs, newState);
                }
            });

            if (target != null) {
                $rootScope.$emit(ToggleHelper.events.togglerLinked, target);
            }

            scope.$on('$destroy', unbindUpdateElemClasses);
        }
    };
}]).directive('toggleable', ["$rootScope", "ToggleHelper",
function ($rootScope, ToggleHelper) {
    return {
        restrict: "A",
        link: function (scope, elem, attrs) {
            var toggleState = false;

            if (attrs["default"]) {
                switch (attrs["default"]) {
                    case "active":
                        toggleState = true;
                        break;
                    case "inactive":
                        toggleState = false;
                }
                ToggleHelper.toggleStateChanged(elem, attrs, toggleState);
            }

            var unbindToggle = $rootScope.$on(ToggleHelper.events.toggle, function (e, target, command) {
                var oldState;
                if (target === attrs.id) {
                    oldState = toggleState;
                    toggleState = ToggleHelper.applyCommand(command, oldState);
                    if (oldState !== toggleState) {
                        ToggleHelper.toggleStateChanged(elem, attrs, toggleState);
                    }
                }
            });

            var unbindToggleByClass = $rootScope.$on(ToggleHelper.events.toggleByClass, function (e, targetClass, command) {
                var oldState;
                if (elem.hasClass(targetClass)) {
                    oldState = toggleState;
                    toggleState = ToggleHelper.applyCommand(command, oldState);
                    if (oldState !== toggleState) {
                        ToggleHelper.toggleStateChanged(elem, attrs, toggleState);
                    }
                }
            });

            var unbindToggleableToggled = $rootScope.$on(ToggleHelper.events.toggleableToggled, function (e, target, newState, sameGroup) {
                if (newState && (attrs.id !== target) && (attrs.exclusionGroup === sameGroup) && (attrs.exclusionGroup != null)) {
                    toggleState = false;
                    ToggleHelper.toggleStateChanged(elem, attrs, toggleState);
                }
            });

            var unbindTogglerLinked = $rootScope.$on(ToggleHelper.events.togglerLinked, function (e, target) {
                if (attrs.id === target) {
                    ToggleHelper.notifyToggleState(elem, attrs, toggleState);
                }
            });

            scope.$on('$destroy', function () {
                unbindToggle();
                unbindToggleByClass();
                unbindToggleableToggled();
                unbindTogglerLinked();
            });
        }
    };
}]);
/* /js/directives/perfect-scrollbar.js */
app.directive('perfectScrollbar', ['$parse', '$window',
function ($parse, $window) {
    var psOptions = ['wheelSpeed', 'wheelPropagation', 'minScrollbarLength', 'useBothWheelAxes', 'useKeyboard', 'suppressScrollX', 'suppressScrollY', 'scrollXMarginOffset', 'scrollYMarginOffset', 'includePadding'//, 'onScroll', 'scrollDown'
    ];

    return {
        restrict: 'EA',
        transclude: true,
        template: '<div><div ng-transclude></div></div>',
        replace: true,
        link: function ($scope, $elem, $attr) {
            var jqWindow = angular.element($window);
            var options = {};
            if (!$scope.app.isMobile) {
                for (var i = 0, l = psOptions.length; i < l; i++) {
                    var opt = psOptions[i];
                    if ($attr[opt] !== undefined) {
                        options[opt] = $parse($attr[opt])();
                    }
                }

                $scope.$evalAsync(function () {
                    $elem.perfectScrollbar(options);
                    var onScrollHandler = $parse($attr.onScroll);
                    $elem.scroll(function () {
                        var scrollTop = $elem.scrollTop();
                        var scrollHeight = $elem.prop('scrollHeight') - $elem.height();
                        $scope.$apply(function () {
                            onScrollHandler($scope, {
                                scrollTop: scrollTop,
                                scrollHeight: scrollHeight
                            });
                        });
                    });
                });

                function update(event) {
                    $scope.$evalAsync(function () {
                        if ($attr.scrollDown == 'true' && event != 'mouseenter') {
                            setTimeout(function () {
                                $($elem).scrollTop($($elem).prop("scrollHeight"));
                            }, 100);
                        }
                        $elem.perfectScrollbar('update');
                    });
                }

                // This is necessary when you don't watch anything with the scrollbar
                $elem.bind('mousemove', update);

                // Possible future improvement - check the type here and use the appropriate watch for non-arrays
                if ($attr.refreshOnChange) {
                    $scope.$watchCollection($attr.refreshOnChange, function () {
                        update();
                    });
                }

                // this is from a pull request - I am not totally sure what the original issue is but seems harmless
                if ($attr.refreshOnResize) {
                    jqWindow.on('resize', update);
                }

                $elem.bind('$destroy', function () {
                    jqWindow.off('resize', update);
                    $elem.perfectScrollbar('destroy');
                });
            }
        }
    };
}]);

/* /js/directives/empty-links.js */

/** 
  * Prevent default action on empty links.
*/
app.directive('a', function () {
    return {
        restrict: 'E',
        link: function (scope, elem, attrs) {
            if (attrs.ngClick || attrs.href === '' || attrs.href === '#') {
                elem.on('click', function (e) {
                    e.preventDefault();
                });
            }
        }
    };
});
/* /js/directives/sidebars.js */

/**
 * A set of directives for left and right sidebar.
 */
app.directive('sidebar', ['$document', '$rootScope',
function ($document, $rootScope) {
    return {
        replace: false,
        restrict: "C",
        link: function (scope, elem, attrs) {
            var shouldCloseOnOuterClicks = true;

            if (attrs.closeOnOuterClicks == 'false' || attrs.closeOnOuterClicks == '0') {
                shouldCloseOnOuterClicks = false;
            }

            var isAncestorOrSelf = function (element, target) {
                var parent = element;

                while (parent.length > 0) {
                    if (parent[0] === target[0]) {
                        parent = null;
                        return true;
                    }
                    parent = parent.parent();
                }

                parent = null;
                return false;
            };

            var closeOnOuterClicks = function (e) {
                if (!isAncestorOrSelf(angular.element(e.target), elem)) {

                    $rootScope.toggle(attrs.id, 'off');
                    e.preventDefault();
                    return false;
                }
            };

            var clearCb1 = angular.noop();

            if (shouldCloseOnOuterClicks) {
                clearCb1 = $rootScope.$on('clip-two.toggled', function (e, id, active) {

                    if (id == attrs.id) {

                        if (active) {
                            setTimeout(function () {
                                $document.on('click tap', closeOnOuterClicks);
                            }, 300);
                        } else {
                            $document.off('click tap', closeOnOuterClicks);
                        }
                    }
                });
            }

            scope.$on('$destroy', function () {
                clearCb1();
                $document.off('click tap', closeOnOuterClicks);
            });

        }
    };
}]).directive('searchForm', function () {
    return {
        restrict: 'AC',
        link: function (scope, elem, attrs) {
            var wrap = $('.app-aside');
            var searchForm = elem.children('form');
            var formWrap = elem.parent();

            $(".s-open").on('click', function (e) {
                searchForm.prependTo(wrap);
                e.preventDefault();
                $(document).on("mousedown touchstart", closeForm);
            });
            $(".s-remove").on('click', function (e) {
                searchForm.appendTo(elem);
                e.preventDefault();
            });
            var closeForm = function (e) {
                if (!searchForm.is(e.target) && searchForm.has(e.target).length === 0) {
                    $(".s-remove").trigger('click');
                    $(document).off("mousedown touchstart", closeForm);
                }
            };
        }
    };
    function isSidebarClosed() {
        return $('.app-sidebar-closed').length;
    }

    function isSidebarFixed() {
        return $('.app-sidebar-fixed').length;
    }

}).directive('appAside', ['$window', '$rootScope', '$timeout', 'APP_MEDIAQUERY',
function ($window, $rootScope, $timeout, mq) {
    var $html = $('html'), $win = $($window), _this, wrap = $('.app-aside');
    return {
        restrict: 'AC',

        link: function (scope, elem, attrs, controllers) {
            var eventObject = isTouch() ? 'click' : 'mouseenter';
            var ul = "";
            var menuTitle;
            var wrap = $('.app-aside');
            var space = 0;
            elem.on('click', 'li > a', function (e) {
                _this = $(this);
                if (isSidebarClosed() && !isMobile() && !_this.closest("ul").hasClass("sub-menu"))
                    return;

                _this.closest("ul").find(".open").not(".active").children("ul").not(_this.next()).slideUp(200).parent('.open').removeClass("open");
                if (_this.next().is('ul') && _this.parent().toggleClass('open')) {

                    _this.next().slideToggle(200, function () {
                        $win.trigger("resize");

                    });
                    e.stopPropagation();
                    e.preventDefault();
                } else {
                    $rootScope.toggle('sidebar', 'off');

                }
            });
            elem.on(eventObject, 'nav a', function (e) {
                if (!isSidebarClosed() || isMobile())

                    return;
                _this = $(this);

                if (!_this.parent().hasClass('hover') && !_this.closest("ul").hasClass("sub-menu")) {

                    wrapLeave();
                    _this.parent().addClass('hover');
                    menuTitle = _this.find(".item-inner").clone();
                    if (_this.parent().hasClass('active')) {

                        menuTitle.addClass("active");
                    }
                    if ($('#app').hasClass('lyt-3')) {
                        space = $('#sidebar > .sidebar-container').position().top - $('header').outerHeight() + _this.position().top;
                    }

                    var offset = $('#sidebar > .sidebar-container > div').position().top + $('.nav-user-wrapper').outerHeight() + $('header').outerHeight();
                    var itemTop = isSidebarFixed() && !isBoxedPage() ? _this.parent().position().top + offset + space : (_this.parent().offset().top - $('header').outerHeight());
                    menuTitle.css({
                        position: isSidebarFixed() && !isBoxedPage() ? 'fixed' : 'absolute',
                        height: _this.parent().outerHeight(),
                        top: itemTop,
                        borderBottomRightRadius: '10px',
                        lineHeight: _this.parent().outerHeight() + 'px',
                        padding: 0
                    }).appendTo(wrap);
                    if (_this.next().is('ul')) {
                        ul = _this.next().clone(true);
                        menuTitle.css({
                            borderBottomRightRadius: 0
                        });
                        ul.appendTo(wrap).css({
                            top: itemTop + _this.parent().outerHeight(),
                            position: isSidebarFixed() && !isBoxedPage() ? 'fixed' : 'absolute',
                        });
                        if (_this.parent().position().top + _this.outerHeight() + offset + ul.height() > $win.height() && isSidebarFixed() && !isBoxedPage()) {
                            ul.css('bottom', 0);
                        } else {
                            ul.css('bottom', 'auto');
                        }

                        wrap.find('.sidebar-container').scroll(function () {
                            if (isSidebarFixed() && !isBoxedPage())
                                wrapLeave();
                        });

                        setTimeout(function () {

                            if (!wrap.is(':empty')) {
                                $(document).on('click tap', wrapLeave);
                            }
                        }, 300);

                    } else {
                        ul = "";
                        return;
                    }

                }
            });

            wrap.on('mouseleave', function (e) {
                $(document).off('click tap', wrapLeave);
                $('.hover', wrap).removeClass('hover');
                $('> .item-inner', wrap).remove();
                $('> ul', wrap).remove();
            });

            function wrapLeave() {
                wrap.trigger('mouseleave');
            }


            $rootScope.$on('$locationChangeSuccess', function () {
                var newPath;
                newPath = window.location.hash;
                angular.forEach(elem.find('.main-navigation-menu a'), function (domLink) {
                    var link = angular.element(domLink);
                    var menu;
                    if (domLink.hash === newPath && (!isSidebarClosed() || isMobile())) {

                        if (link.closest("ul").hasClass("sub-menu")) {
                            menu = link.closest("ul");
                            var activeMenu = menu;
                            menu.slideDown(200).parent().siblings().children('.sub-menu').slideUp(200, function () {
                                $(this).parent().removeClass("open");
                            });
                        } else {
                            $('.sub-menu').slideUp(200, function () {
                                $(this).parent().removeClass("open");
                            });
                        }

                    }
                    activeMenu = null;
                    menu = null;
                });
            });

        }
    };

    function isTouch() {
        return $html.hasClass('touch');
    }

    function isMobile() {
        return $win.width() < mq.desktop;
    }

    function isSidebarClosed() {
        return $('.app-sidebar-closed').length;
    }

    function isSidebarFixed() {
        return $('.app-sidebar-fixed').length;
    }

    function isBoxedPage() {
        return $('.app-boxed-page').length;
    }

}]).directive('sidebarToggler', ['$window', '$timeout',
function ($window, $timeout) {
    return {
        restrict: 'C',

        link: function (scope, elem, attrs) {
            elem.on('click', function () {
                $('.main-content').on('webkitTransitionEnd mozTransitionEnd oTransitionEnd otransitionend transitionend', function () {
                    //window.dispatchEvent(new Event('resize'));
                    $timeout(function () {
                        var evt = $window.document.createEvent('UIEvents');
                        evt.initUIEvent('resize', true, false, $window, 0);
                        $window.dispatchEvent(evt);
                    }, 500);
                    $('.main-content').off('webkitTransitionEnd mozTransitionEnd oTransitionEnd otransitionend transitionend');
                });

            });
        }
    };
}]).directive('ctSticky', function ($window, $timeout) {
    return {
        restrict: 'A',
        scope: {
            ctStickyDisabled: '&'
        },
        link: function ($scope, $element, $attributes) {
            $timeout(function () {
                var actualPadding = 90, maxPadding = 60, newPadding, isSticky;
                var setPadding = function () {
                    newPadding = actualPadding - $window.scrollY;

                    if ($window.scrollY < maxPadding) {
                        $element.css({
                            paddingTop: actualPadding - $window.scrollY
                        });
                    } else {
                        $element.css({
                            paddingTop: 30
                        });
                    }
                };
                if ($attributes.ctStickyDisabled) {
                    $scope.$watch($scope.ctStickyDisabled, function (newVal, oldVal) {
                        if (newVal && !oldVal) {
                            isSticky = false;
                            $element.attr('style', function (i, style) {
                                return style.replace(/padding[^;]+;?/g, '');
                            });
                        } else if (!newVal) {
                            isSticky = true;
                            setPadding();
                        }
                    });
                }
                angular.element($window).on("scroll", function () {
                    if (isSticky) {
                        setPadding();
                    }
                });
            });
        }
    };
});

/* /js/directives/off-click.js */

/** 
  * It's like click, but when you don't click on your element. 
*/
app.directive('offClick', ['$rootScope', '$parse', function ($rootScope, $parse) {
    var id = 0;
    var listeners = {};
    // add variable to detect touch users moving..
    var touchMove = false;

    // Add event listeners to handle various events. Destop will ignore touch events
    document.addEventListener("touchmove", offClickEventHandler, true);
    document.addEventListener("touchend", offClickEventHandler, true);
    document.addEventListener('click', offClickEventHandler, true);

    function targetInFilter(target, elms) {
        if (!target || !elms) return false;
        var elmsLen = elms.length;
        for (var i = 0; i < elmsLen; ++i) {
            var currentElem = elms[i];
            var containsTarget = false;
            try {
                containsTarget = currentElem.contains(target);
            } catch (e) {
                // If the node is not an Element (e.g., an SVGElement) node.contains() throws Exception in IE,
                // see https://connect.microsoft.com/IE/feedback/details/780874/node-contains-is-incorrect
                // In this case we use compareDocumentPosition() instead.
                if (typeof currentElem.compareDocumentPosition !== 'undefined') {
                    containsTarget = currentElem === target || Boolean(currentElem.compareDocumentPosition(target) & 16);
                }
            }

            if (containsTarget) {
                return true;
            }
        }
        return false;
    }

    function offClickEventHandler(event) {
        // If event is a touchmove adjust touchMove state
        if( event.type === 'touchmove' ){
            touchMove = true;
            // And end function
            return false;
        }
        // This will always fire on the touchend after the touchmove runs...
        if( touchMove ){
            // Reset touchmove to false
            touchMove = false;
            // And end function
            return false;
        }
        var target = event.target || event.srcElement;
        angular.forEach(listeners, function (listener, i) {
            if (!(listener.elm.contains(target) || targetInFilter(target, listener.offClickFilter))) {
                $rootScope.$evalAsync(function () {
                    listener.cb(listener.scope, {
                        $event: event
                    });
                });
            }

        });
    }

    return {
        restrict: 'A',
        compile: function ($element, attr) {
            var fn = $parse(attr.offClick);
            return function (scope, element) {
                var elmId = id++;
                var offClickFilter;
                var removeWatcher;

                offClickFilter = document.querySelectorAll(scope.$eval(attr.offClickFilter));

                if (attr.offClickIf) {
                    removeWatcher = $rootScope.$watch(function () {
                        return $parse(attr.offClickIf)(scope);
                    }, function (newVal) {
                        if (newVal) {
                            on();
                        } else if (!newVal) {
                            off();
                        }
                    });
                } else {
                    on();
                }

                attr.$observe('offClickFilter', function (value) {
                    offClickFilter = document.querySelectorAll(scope.$eval(value));
                });

                scope.$on('$destroy', function () {
                    off();
                    if (removeWatcher) {
                        removeWatcher();
                    }
                    element = null;
                });

                function on() {
                    listeners[elmId] = {
                        elm: element[0],
                        cb: fn,
                        scope: scope,
                        offClickFilter: offClickFilter
                    };
                }

                function off() {
                    listeners[elmId] = null;
                    delete listeners[elmId];
                }
            };
        }
    };
}]);
/* /js/directives/full-height.js */

/**
 * Make element 100% height of browser window.
 */
app.directive('ctFullheight', ['$window', '$rootScope', '$timeout', 'APP_MEDIAQUERY',
function ($window, $rootScope, $timeout, mq) {
    return {
        restrict: "AE",
        scope: {
            ctFullheightIf: '&'
        },
        link: function (scope, elem, attrs) {
            var $win = $($window);
            var $document = $(document);
            var exclusionItems;
            var exclusionHeight;
            var setHeight = true;
            var page;

            scope.initializeWindowSize = function () {
                $timeout(function () {
                    exclusionHeight = 0;
                    if (attrs.ctFullheightIf) {
                        scope.$watch(scope.ctFullheightIf, function (newVal, oldVal) {
                            if (newVal && !oldVal) {
                                setHeight = true;
                            } else if (!newVal) {
                                $(elem).css('height', 'auto');
                                setHeight = false;
                            }
                        });
                    }

                    if (attrs.ctFullheightExclusion) {
                        var exclusionItems = attrs.ctFullheightExclusion.split(',');
                        angular.forEach(exclusionItems, function (_element) {
                            exclusionHeight = exclusionHeight + $(_element).outerHeight(true);
                        });
                    }
                    if (attrs.ctFullheight == 'window') {
                        page = $win;
                    } else {
                        page = $document;
                    }

                    scope.$watch(function () {
                        scope.__height = page.height();
                    });
                    if (setHeight) {
                        $(elem).css('height', 'auto');
                        if (page.innerHeight() < $win.innerHeight()) {
                            page = $win;
                        }
                        $(elem).css('height', page.innerHeight() - exclusionHeight);
                    }
                }, 300);
            };

            scope.initializeWindowSize();
            scope.$watch('__height', function (newHeight, oldHeight) {
                scope.initializeWindowSize();
            });
            $win.on('resize', function () {
                scope.initializeWindowSize();
            });

        }
    };
}]);

/* /js/directives/panel-tools.js */

/**
 * Add several features to panels.
 */
app.directive('ctPaneltool', function () {
    var templates = {
        /* jshint multistr: true */
        collapse: "<a href='#' class='btn btn-transparent btn-sm' panel-collapse='' tooltip-placement='top' uib-tooltip='Collapse' ng-click='{{panelId}} = !{{panelId}}' ng-init='{{panelId}}=false'>" + "<i ng-if='{{panelId}}' class='ti-plus'></i>" + "<i ng-if='!{{panelId}}' class='ti-minus'></i>" + "</a>",
        refresh: "<a href='#' class='btn btn-transparent btn-sm' panel-refresh='' tooltip-placement='top' uib-tooltip='Refresh' data-spinner='{{spinner}}'>" + "<i class='fa fa-circle-o'></i>" + "</a>",
        expand: "<a href='#' class='btn btn-transparent btn-sm hidden-sm hidden-xs' panel-expand=''>" + "<i class='fa fa-expand' ng-show='!isPanelFullscreen'></i><i class='fa fa-compress' ng-show='isPanelFullscreen'></i>" + "</a>",
        dismiss: "<a href='#' class='btn btn-transparent btn-sm' panel-dismiss='' tooltip-placement='top' uib-tooltip='Close'>" + "<i class='ti-close'></i>" + "</a>"
    };

    return {
        restrict: 'E',
        template: function (elem, attrs) {
            var temp = '';
            if (attrs.toolCollapse)
                temp += templates.collapse.replace(/{{panelId}}/g, (elem.parent().parent().attr('id')));
            if (attrs.toolRefresh)
                temp += templates.refresh.replace(/{{spinner}}/g, attrs.toolRefresh);
            if (attrs.toolExpand)
                temp += templates.expand;
            if (attrs.toolDismiss)
                temp += templates.dismiss;
            return temp;
        }
    };
});
app.directive('panelExpand', function ($compile) {
    
    return {
        restrict: 'A',
        replace: false,
        terminal: true, //this setting is important, see explanation below
        priority: 1001, //this setting is important, see explanation below
        scope: true,
        link: function link(scope, element, attrs) {
            scope.isPanelFullscreen = false;
            scope.fullscreenTooltip = 'Expand';
            var panel = element.closest('.panel');
            element.attr('uib-tooltip', '{{fullscreenTooltip}}');
            element.attr('tooltip-placement', 'top');
            element.attr('ng-click', 'toggleFullScreen()');
            element.removeAttr("panel-expand");
            //remove the attribute to avoid indefinite loop
            element.removeAttr("data-panel-expand");

            //also remove the same attribute with data- prefix in case users specify data-common-things in the html
            var el = $compile("<div fullscreen='isPanelFullscreen' only-watched-property class='panel-fullscreen'></div>")(scope);
            panel.before(el).appendTo(el);

            scope.toggleFullScreen = function () {
                scope.isPanelFullscreen = !scope.isPanelFullscreen;
                if (scope.isPanelFullscreen) {

                    scope.fullscreenTooltip = 'Compress';
                } else {
                    scope.fullscreenTooltip = 'Expand';
                }

            };
            $compile(element)(scope);

        }
    };
});
app.directive('panelRefresh', function () {
    

    return {
        restrict: 'A',
        controller: ["$scope", "$element",
		function ($scope, $element) {

		    var refreshEvent = 'panel-refresh', csspinnerClass = 'csspinner', defaultSpinner = 'load1';

		    // method to clear the spinner when done
		    function removeSpinner() {
		        this.removeClass(csspinnerClass);
		    }

		    // catch clicks to toggle panel refresh
		    $element.on('click', function () {
		        var $this = $(this), panel = $this.parents('.panel').eq(0), spinner = $this.data('spinner') || defaultSpinner;

		        // start showing the spinner
		        panel.addClass(csspinnerClass + ' ' + spinner);

		        // attach as public method
		        panel.removeSpinner = removeSpinner;

		        // Trigger the event and send the panel object
		        $this.trigger(refreshEvent, [panel]);

		    });

		}]

    };
});
app.directive('panelDismiss', function () {
    
    return {
        restrict: 'A',
        controller: ["$scope", "$element",
		function ($scope, $element) {
		    var parent = $(this).closest('.panel');
		    $element.on('click', function () {

		        var parent = $(this).closest('.panel');

		        destroyPanel();

		        function destroyPanel() {
		            var col = parent.parent();
		            parent.fadeOut(300, function () {
		                $(this).remove();
		                if (col.is('[class*="col-"]') && col.children('*').length === 0) {
		                    col.remove();
		                }
		            });
		        }

		    });
		}]

    };
}); (function ($, window, document) {
    

    $(document).on('panel-refresh', '.panel', function (e, panel) {

        // perform any action when a .panel triggers a the refresh event
        setTimeout(function () {
            // when the action is done, just remove the spinner class
            panel.removeSpinner();
        }, 3000);

    });

}(jQuery, window, document));

/* /js/directives/char-limit.js */

app.directive('maxlength', function () {
    return {
        restrict: 'A',
        link: function ($scope, $element, $attributes) {

            var limit = $attributes.maxlength;
            $element.bind('keyup', function (event) {
                var element = $element.closest(".form-group");

                element.toggleClass('has-warning', limit - $element.val().length <= 10);
                element.toggleClass('has-error', $element.val().length >= limit);
            });

            $element.bind('keypress', function (event) {
                // Once the limit has been met or exceeded, prevent all keypresses from working
                if ($element.val().length >= limit) {
                    // Except backspace
                    if (event.keyCode != 8) {
                        event.preventDefault();
                    }
                }
            });
        }
    };
});

/* /js/directives/dismiss.js */

/** 
  * A directive used for "close buttons" (eg: alert box).
  * It hides its parent node that has the class with the name of its value.
*/
app.directive('ctDismiss', function () {
    return {
        restrict: 'A',
        link: function (scope, elem, attrs) {
            elem.on('click', function (e) {
                elem.parent('.' + attrs.ctDismiss).hide();
                e.preventDefault();
            });

        }
    };
});
/* /js/directives/compare-to.js */

/** 
  * Password-check directive.
*/
app.directive('compareTo', function () {
    return {
        require: "ngModel",
        scope: {
            otherModelValue: "=compareTo"
        },
        link: function (scope, element, attributes, ngModel) {

            ngModel.$validators.compareTo = function (modelValue) {
                return modelValue == scope.otherModelValue;
            };

            scope.$watch("otherModelValue", function () {
                ngModel.$validate();
            });
        }
    };
});
/* /js/directives/select.js */

/**
 * Create a custom CSS3 Select Elements.
 * You must use it as a class.
 * Combined with the class .cs-skin-slide it creates a slide <select>
 */
app.factory('SelectFx', ["$http",
function ($http) {
    function hasParent(e, p) {
        if (!e)
            return false;
        var el = e.target || e.srcElement || e || false;
        while (el && el != p) {
            el = el.parentNode || false;
        }
        return (el !== false);
    };

    /**
	 * extend obj function
	 */
    function extend(a, b) {
        for (var key in b) {
            if (b.hasOwnProperty(key)) {
                a[key] = b[key];
            }
        }
        return a;
    }

    /**
	 * SelectFx function
	 */
    function SelectFx(el, options) {
        this.el = el[0];
        this.options = extend({}, this.options);
        extend(this.options, options);
        this._init();
    }

    function classReg(className) {
        return new RegExp("(^|\\s+)" + className + "(\\s+|$)");
    }

    // classList support for class management
    // altho to be fair, the api sucks because it won't accept multiple classes at once
    var hasClass, addClass, removeClass;

    if ('classList' in document.documentElement) {
        hasClass = function (elem, c) {
            return elem.classList.contains(c);
        };
        addClass = function (elem, c) {
            elem.classList.add(c);
        };
        removeClass = function (elem, c) {
            elem.classList.remove(c);
        };
    } else {
        hasClass = function (elem, c) {
            return classReg(c).test(elem.className);
        };
        addClass = function (elem, c) {
            if (!hasClass(elem, c)) {
                elem.className = elem.className + ' ' + c;
            }
        };
        removeClass = function (elem, c) {
            elem.className = elem.className.replace(classReg(c), ' ');
        };
    }

    function toggleClass(elem, c) {
        var fn = hasClass(elem, c) ? removeClass : addClass;
        fn(elem, c);
    }

    var classie = {
        // full names
        hasClass: hasClass,
        addClass: addClass,
        removeClass: removeClass,
        toggleClass: toggleClass,
        // short names
        has: hasClass,
        add: addClass,
        remove: removeClass,
        toggle: toggleClass
    };

    // transport
    if (typeof define === 'function' && define.amd) {
        // AMD
        define(classie);
    } else {
        // browser global
        window.classie = classie;
    }

    /**
	 * SelectFx options
	 */
    SelectFx.prototype.options = {
        // if true all the links will open in a new tab.
        // if we want to be redirected when we click an option, we need to define a data-link attr on the option of the native select element
        newTab: true,
        // when opening the select element, the default placeholder (if any) is shown
        stickyPlaceholder: true,
        // callback when changing the value
        onChange: function (val) {
            return false;
        }
    };

    /**
	 * init function
	 * initialize and cache some vars
	 */
    SelectFx.prototype._init = function () {

        var selectDisabled = false;
        var createSelect = true;
        if (this.el.hasAttribute("disabled")) {
            this.el.className = this.el.className + " disabled";
            selectDisabled = true;
        };

        if (this._styleExist(this.el.previousSibling)) {
            createSelect = false;
        }
        // check if we are using a placeholder for the native select box
        // we assume the placeholder is disabled and selected by default
        var selectedOpt = this.el.querySelectorAll('option[selected]')[this.el.querySelectorAll('option[selected]').length - 1];


        this.hasDefaultPlaceholder = selectedOpt && selectedOpt.disabled;

        // get selected option (either the first option with attr selected or just the first option)
        this.selectedOpt = selectedOpt || this.el.querySelector('option');

        // create structure
        this._createSelectEl();

        // all options
        this.selOpts = [].slice.call(this.selEl.querySelectorAll('li[data-option]'));

        // total options
        this.selOptsCount = this.selOpts.length;

        // current index
        this.current = this.selOpts.indexOf(this.selEl.querySelector('li.cs-selected')) || -1;

        // placeholder elem
        this.selPlaceholder = this.selEl.querySelector('span.cs-placeholder');

        if (!selectDisabled) {
            // init events
            this._initEvents(createSelect);
        }

    };
    /**
	 * creates the structure for the select element
	 */
    SelectFx.prototype._createSelectEl = function () {

        var self = this, options = '', createOptionHTML = function (el) {
            var optclass = '', classes = '', link = '';

            if (el.getAttribute('selected')) {

                classes += 'cs-selected ';

            }
            // extra classes
            if (el.getAttribute('data-class')) {
                classes += el.getAttribute('data-class');
            }
            // link options
            if (el.getAttribute('data-link')) {
                link = 'data-link=' + el.getAttribute('data-link');
            }

            if (classes !== '') {
                optclass = 'class="' + classes + '" ';
            }

            return '<li ' + optclass + link + ' data-option data-value="' + el.value + '"><span>' + el.textContent + '</span></li>';
        };

        [].slice.call(this.el.children).forEach(function (el) {
            if (el.disabled) {
                return;
            }

            var tag = el.tagName.toLowerCase();

            if (tag === 'option') {
                options += createOptionHTML(el);
            } else if (tag === 'optgroup') {
                options += '<li class="cs-optgroup"><span>' + el.label + '</span><ul>';
                [].slice.call(el.children).forEach(function (opt) {
                    options += createOptionHTML(opt);
                });
                options += '</ul></li>';
            }
        });

        if (this._styleExist(this.el.previousSibling)) {
            this.selEl = this.el.parentNode;
            this.selEl.tabIndex = this.el.tabIndex;

            this.el.previousSibling.innerHTML = '<ul>' + options + '</ul>';

            return;
        } else {

            var opts_el = '<div class="cs-options"><ul>' + options + '</ul></div>';
            this.selEl = document.createElement('div');
            this.selEl.className = this.el.className;
            this.selEl.tabIndex = this.el.tabIndex;
            this.selEl.innerHTML = '<span class="cs-placeholder">' + this.selectedOpt.textContent + '</span>' + opts_el;
            this.el.parentNode.appendChild(this.selEl);
            this.selEl.appendChild(this.el);
        }

    };
    /**
	 * initialize the events
	 */
    SelectFx.prototype._initEvents = function (a) {

        var self = this;
        if (a) {
            // open/close select
            this.selPlaceholder.addEventListener('click', function () {
                self._toggleSelect();
            });
        }
        // clicking the options
        this.selOpts.forEach(function (opt, idx) {
            opt.addEventListener('click', function () {
                self.current = idx;
                self._changeOption();
                // close select elem
                self._toggleSelect();
            });
        });

        // close the select element if the target itÂ´s not the select element or one of its descendants..
        document.addEventListener('click', function (ev) {
            var target = ev.target;
            if (self._isOpen() && target !== self.selEl && !hasParent(target, self.selEl)) {
                self._toggleSelect();
            }
        });

        // keyboard navigation events
        this.selEl.addEventListener('keydown', function (ev) {
            var keyCode = ev.keyCode || ev.which;

            switch (keyCode) {
                // up key
                case 38:
                    ev.preventDefault();
                    self._navigateOpts('prev');
                    break;
                    // down key
                case 40:
                    ev.preventDefault();
                    self._navigateOpts('next');
                    break;
                    // space key
                case 32:
                    ev.preventDefault();
                    if (self._isOpen() && typeof self.preSelCurrent != 'undefined' && self.preSelCurrent !== -1) {
                        self._changeOption();
                    }
                    self._toggleSelect();
                    break;
                    // enter key
                case 13:
                    ev.preventDefault();
                    if (self._isOpen() && typeof self.preSelCurrent != 'undefined' && self.preSelCurrent !== -1) {
                        self._changeOption();
                        self._toggleSelect();
                    }
                    break;
                    // esc key
                case 27:
                    ev.preventDefault();
                    if (self._isOpen()) {
                        self._toggleSelect();
                    }
                    break;
            }
        });
    };
    /**
	 * navigate with up/dpwn keys
	 */
    SelectFx.prototype._navigateOpts = function (dir) {
        if (!this._isOpen()) {
            this._toggleSelect();
        }

        var tmpcurrent = typeof this.preSelCurrent != 'undefined' && this.preSelCurrent !== -1 ? this.preSelCurrent : this.current;

        if (dir === 'prev' && tmpcurrent > 0 || dir === 'next' && tmpcurrent < this.selOptsCount - 1) {
            // save pre selected current - if we click on option, or press enter, or press space this is going to be the index of the current option
            this.preSelCurrent = dir === 'next' ? tmpcurrent + 1 : tmpcurrent - 1;
            // remove focus class if any..
            this._removeFocus();
            // add class focus - track which option we are navigating
            classie.add(this.selOpts[this.preSelCurrent], 'cs-focus');
        }
    };
    /**
	 * open/close select
	 * when opened show the default placeholder if any
	 */
    SelectFx.prototype._toggleSelect = function () {
        // remove focus class if any..
        this._removeFocus();

        if (this._isOpen()) {
            if (this.current !== -1) {
                // update placeholder text
                this.selPlaceholder.textContent = this.selOpts[this.current].textContent;
            }
            classie.remove(this.selEl, 'cs-active');
        } else {
            if (this.hasDefaultPlaceholder && this.options.stickyPlaceholder) {
                // everytime we open we wanna see the default placeholder text
                this.selPlaceholder.textContent = this.selectedOpt.textContent;
            }
            classie.add(this.selEl, 'cs-active');
        }
    };
    /**
	 * detect if .cs-options wrapper is active for each select
	 */
    SelectFx.prototype._styleExist = function (e) {
        return (' ' + e.className + ' ').indexOf(' cs-options ') > -1;
    };
    /**
	 * change option - the new value is set
	 */
    SelectFx.prototype._changeOption = function () {

        // if pre selected current (if we navigate with the keyboard)...
        if (typeof this.preSelCurrent != 'undefined' && this.preSelCurrent !== -1) {
            this.current = this.preSelCurrent;
            this.preSelCurrent = -1;
        }

        // current option
        var opt = this.selOpts[this.current];

        // update current selected value
        this.selPlaceholder.textContent = opt.textContent;

        // change native select elementÂ´s value
        this.el.value = opt.getAttribute('data-value');
        var event = new Event('change');
        this.el.dispatchEvent(event);

        // remove class cs-selected from old selected option and add it to current selected option
        var oldOpt = this.selEl.querySelector('li.cs-selected');
        if (oldOpt) {
            classie.remove(oldOpt, 'cs-selected');
        }
        classie.add(opt, 'cs-selected');

        // if thereÂ´s a link defined
        if (opt.getAttribute('data-link')) {
            // open in new tab?
            if (this.options.newTab) {
                window.open(opt.getAttribute('data-link'), '_blank');
            } else {
                window.location = opt.getAttribute('data-link');
            }
        }

        // callback
        this.options.onChange(this.el.value);
    };
    /**
	 * returns true if select element is opened
	 */
    SelectFx.prototype._isOpen = function (opt) {
        return classie.has(this.selEl, 'cs-active');
    };
    /**
	 * removes the focus class from the option
	 */
    SelectFx.prototype._removeFocus = function (opt) {
        var focusEl = this.selEl.querySelector('li.cs-focus')
        if (focusEl) {
            classie.remove(focusEl, 'cs-focus');
        }
    };

    return SelectFx;
}]);

app.directive('csSelect', ["SelectFx", "$timeout",
function (SelectFx, $timeout) {
    return {
        restrict: 'AC',
        link: function ($scope, $element, $attributes) {

            $scope.$watch(function () {
                return $element.find('option').length;
            }, function (newValue, oldValue) {
                if (newValue !== oldValue) {

                    new SelectFx($element);
                }
            });
            $timeout(function () { new SelectFx($element); });


        }
    };
}]);

/* /js/directives/messages.js */

/** 
  * Returns the id of the selected e-mail. 
*/
app.directive('messageItem', ['$location', function ($location) {
    return {
        restrict: 'EA',
        link: function (scope, elem, attrs) {
            elem.on("click tap", function (e) {
                var id = attrs.messageItem;
            });
        }
    };
}]);

/* /js/directives/chat.js */
(function () {
    
    app.directive('clipChat', ClipChat);

    function ClipChat() {
        var chatTemplate = '<div>' + '<ol class="discussion">' + '<li class="messages-date" ng-repeat-start="message in newChatArray()" ng-if="displayDate($index) || $index == 0">{{message.date | amDateFormat:\'dddd, MMM D, h:mm a\'}}</li>' + '<li ng-class="{\'self\' : message.idUser == idSelf, \'other\' : message.idUser !== idSelf, \'nextSame\': newChatArray()[$index+1].idUser == message.idUser && !nextDate($index)}" ng-repeat-end>' + '<div class="message">' + '<div class="message-name" ng-if="newChatArray()[$index-1].idUser !== message.idUser || displayDate($index)">{{  message.user }}</div>' + '<div class="message-text">{{ message.content }}</div>' + '<div class="message-avatar"><img ng-src="{{ message.avatar }}" alt=""></div>' + '</div>' + '</li>' + '</ol>';
        var directive = {
            restrict: 'EA',
            template: chatTemplate,
            replace: true,
            scope: {
                messages: "=",
                idSelf: "=",
                idOther: "="
            },
            link: function ($scope, $element, $attrs) {
                $scope.newChatArray = function () {
                    var filtered = [];
                    for (var i = 0; i < $scope.messages.length; i++) {
                        var item = $scope.messages[i];
                        if ((item.idUser == $scope.idSelf || item.idOther == $scope.idSelf) && (item.idUser == $scope.idOther || item.idOther == $scope.idOther)) {
                            filtered.push(item);
                        }
                    }

                    return filtered;
                };

                $scope.displayDate = function (i) {
                    var prevDate, nextDate, diffMs, diffMins;
                    var messages = $scope.newChatArray();
                    if (i === 0) {


                        if (messages.length > 1) {
                            prevDate = new Date(messages[i].date);
                            nextDate = new Date(messages[i + 1].date);
                            diffMs = (nextDate - prevDate);
                            diffMins = Math.round(((diffMs % 86400000) % 3600000) / 60000);
                        } else {
                            return true
                        }
                    } else {
                        prevDate = new Date(messages[i - 1].date);
                        nextDate = new Date(messages[i].date);
                        diffMs = (nextDate - prevDate);
                        diffMins = Math.round(((diffMs % 86400000) % 3600000) / 60000);

                    }
                    if (diffMins > 1) {
                        return true;
                    } else {
                        return false;
                    }
                };
                $scope.nextDate = function (i) {
                    var prevDate, nextDate, diffMs, diffMins;
                    var messages = $scope.newChatArray();
                    if (i < messages.length - 1) {

                        prevDate = new Date(messages[i].date);
                        nextDate = new Date(messages[i + 1].date);
                        diffMs = (nextDate - prevDate);
                        diffMins = Math.round(((diffMs % 86400000) % 3600000) / 60000);

                    }
                    if (diffMins > 1) {
                        return true;
                    } else {
                        return false;
                    }
                };

            }
        };

        return directive;
    }
    app.directive('chatSubmit', SubmitChat);

    function SubmitChat() {
        var submitTemplate = '<form ng-submit="submitChat()">' + '<div class="message-bar">' + '<div class="message-inner">' + '<a href="#" class="link icon-only"><i class="fa fa-camera"></i></a>' + '<div class="message-area"><input placeholder="Message" ng-model="ngModel" /></div>' + '<a translate="offsidebar.chat.SEND" href="#" class="link ng-scope" ng-click="submitChat()">Send</a>' + '</div>' + '</div>' + '</form>' + '</div>';
        var directive = {
            restrict: 'EA',
            template: submitTemplate,
            replace: true,
            scope: {
                submitFunction: "=",
                ngModel: "="
            },
            link: function ($scope, $element, $attrs) {

                $scope.submitChat = function () {
                    $scope.submitFunction();


                    if (typeof $attrs.scrollElement !== "undefined") {
                        var scrlEl = angular.element($attrs.scrollElement);
                        var lastElement = scrlEl.find('.discussion > li:last');
                        if (lastElement.length)
                            scrlEl.scrollToElementAnimated(lastElement);
                    }

                };
            }
        };

        return directive;
    }


})();

/* /js/directives/touchspin.js */

app.directive('touchspin', function () {
    return {
        restrict: 'EA',
        link: function (scope, elem, attr) {
            var tsOptions = [
				'initval',
				'min',
				'max',
				'step',
				'forcestepdivisibility',
				'decimals',
				'stepinterval',
				'stepintervaldelay',
				'verticalbuttons',
				'verticalupclass',
				'verticaldownclass',
				'prefix',
				'postfix',
				'prefix_extraclass',
				'postfix_extraclass',
				'booster',
				'boostat',
				'maxboostedstep',
				'mousewheel',
				'buttondown_class',
				'buttonup_class'
            ];
            var options = {};
            for (var i = 0, l = tsOptions.length; i < l; i++) {
                var opt = tsOptions[i];
                if (attr[opt] !== undefined) {
                    options[opt] = attr[opt];
                }
            }
            elem.TouchSpin(options);
        }
    };
});
/* /js/directives/file-upload.js */



app


    // Angular File Upload module does not include this directive
    // Only for example


    /**
    * The ng-thumb directive
    * @author: nerv
    * @version: 0.1.2, 2014-01-09
    */
    .directive('ngThumb', ['$window', function ($window) {
        var helper = {
            support: !!($window.FileReader && $window.CanvasRenderingContext2D),
            isFile: function (item) {
                return angular.isObject(item) && item instanceof $window.File;
            },
            isImage: function (file) {
                var type = '|' + file.type.slice(file.type.lastIndexOf('/') + 1) + '|';
                return '|jpg|png|jpeg|bmp|gif|'.indexOf(type) !== -1;
            }
        };

        return {
            restrict: 'A',
            template: '<canvas/>',
            link: function (scope, element, attributes) {
                if (!helper.support) return;

                var params = scope.$eval(attributes.ngThumb);

                if (!helper.isFile(params.file)) return;
                if (!helper.isImage(params.file)) return;

                var canvas = element.find('canvas');
                var reader = new FileReader();

                reader.onload = onLoadFile;
                reader.readAsDataURL(params.file);

                function onLoadFile(event) {
                    var img = new Image();
                    img.onload = onLoadImage;
                    img.src = event.target.result;
                }

                function onLoadImage() {
                    var width = params.width || this.width / this.height * params.height;
                    var height = params.height || this.height / this.width * params.width;
                    canvas.attr({ width: width, height: height });
                    canvas[0].getContext('2d').drawImage(this, 0, 0, width, height);
                }
            }
        };
    }]);

/* /js/directives/letter-icon.js */

app.directive('letterIcon', function () {
    return {
        restrict: 'AE',
        template: '<div class="letter-icon-wrapper"><span class="letter-icon">' + '{{letter}}</span></div>',
        scope: {},
        replace: true,
        link: function (scope, elem, attrs) {
            var parseColourString = function (s) {

                // Tokenise input
                var m = s.match(/^\#|^rgb\(|[\d\w]+$|\d{3}/g);

                // Other variables
                var value, values;
                var valid = true, double = false;

                // If no matches, return false
                if (!m)
                    return false;

                // If hex value
                if (m.length < 3) {
                    // Get the value
                    value = m[m.length - 1];

                    // Split into parts, either x,x,x or xx,xx,xx
                    values = value.length == 3 ? double = true && value.split('') : value.match(/../g);

                    // Convert to decimal values - if #nnn, double up on values 345 => 334455
                    values.forEach(function (v, i) {
                        values[i] = parseInt(double ? '' + v + v : v, 16);
                    });

                    // Otherwise it's rgb, get the values
                } else {
                    values = m.length == 3 ? m.slice() : m.slice(1);
                }

                // Check that each value is between 0 and 255 inclusive and return the result
                values.forEach(function (v) {
                    valid = valid ? v >= 0 && v <= 255 : false;
                });

                // If string is invalid, return false, otherwise return an array of the values
                return valid && values;
            };
            if (attrs.size && (attrs.size == 'sm' || attrs.size == 'lg')) {
                elem.addClass('size-' + attrs.size);
            }

            if (attrs.customClass) {
                if (attrs.customClass.charAt(0) === '.')
                    attrs.customClass = attrs.customClass.substr(1);
                elem.addClass(attrs.customClass);
            }

            if (attrs.border) {
                elem.addClass('border');
            }
            if (attrs.box && (attrs.box == 'round' || attrs.box == 'circle')) {
                elem.addClass('box-' + attrs.box);
            }
            if (attrs.color && (parseColourString(attrs.color) !== false || attrs.color !== 'auto')) {
                var boxColor;
                elem.removeClass(function (index, css) {
                    return (css.match(/(^|\s)letter-color-\S+/g) || []).join(' ');
                });
                boxColor = parseColourString(attrs.color);
                elem.css({
                    backgroundColor: 'rgb(' + boxColor + ')'
                });
            }
            if (attrs.colorHover && (parseColourString(attrs.colorHover) !== false || attrs.colorHover == 'auto')) {
                if (attrs.colorHover == 'auto') {
                    angular.element(elem).add(elem.closest("a")).on('mouseenter', function () {
                        elem.addClass('hover');
                    }).on('mouseleave', function () {
                        elem.removeClass('hover');
                    });
                } else {
                    var hoverColor, originalColor;
                    hoverColor = parseColourString(attrs.colorHover);
                    if (attrs.color && attrs.color !== 'auto') {
                        originalColor = attrs.color;

                    } else {
                        originalColor = elem.css("background-color");
                    }
                    angular.element(elem).add(elem.closest("a")).on('mouseenter', function () {
                        elem.css({
                            backgroundColor: 'rgb(' + hoverColor + ')'
                        });
                    }).on('mouseleave', function () {
                        elem.css({
                            backgroundColor: originalColor
                        });
                    });
                }
            }

            attrs.$observe('icon', function (val) {
                if (attrs.icon) {
                    elem.append('<i class="' + attrs.icon + '"></i>');
                }
            });
            attrs.$observe('data', function (val) {
                var string = val.trim(), letter = '';

                if (attrs.color && attrs.color == 'auto') {

                    elem.removeClass(function (index, css) {
                        return (css.match(/(^|\s)letter-color-\S+/g) || []).join(' ');
                    });
                    elem.addClass('letter-color-' + string.charAt(0).toLowerCase());

                }
                if (attrs.charCount && !isNaN(attrs.charCount)) {
                    var newString = string.split(/(?=[A-Z])/), count = parseInt(attrs.charCount);

                    if (count > newString.length) {
                        count = newString.length;
                    }
                    for (var i = 0; i < count; i++) {
                        letter = letter + newString[i].charAt(0);

                    }
                    scope.letter = letter.toUpperCase();
                } else {
                    scope.letter = string.charAt(0).toUpperCase();
                }
            });

        }
    };
});

/* /js/directives/landing-header.js */

app.directive('landingHeader', function ($window) {
    return {
        restrict: 'A',
        link: function ($scope, $element, $attributes) {
            angular.element($window).bind("scroll", function () {
                if (this.pageYOffset >= 60) {
                    $element.addClass('min');
                } else {
                    $element.removeClass('min');
                }
            });

        }
    };
});

/* /js/controllers/mainCtrl.js */

/**
 * Clip-Two Main Controller
 */
app.controller('AppCtrl', ['$rootScope', '$scope', '$state', '$swipe', '$translate', '$localStorage', '$window', '$document', '$timeout', 'cfpLoadingBar', 'Fullscreen',
function ($rootScope, $scope, $state, $swipe, $translate, $localStorage, $window, $document, $timeout, cfpLoadingBar, Fullscreen) {

    // Loading bar transition
    // -----------------------------------
    var $win = $($window), $body = $('body');

    $scope.horizontalNavbarCollapsed = true;
    $scope.menuInit = function (value) {
        $scope.horizontalNavbarCollapsed = value;
    };
    $scope.menuToggle = function (value) {
        $scope.horizontalNavbarCollapsed = !$scope.horizontalNavbarCollapsed;
    };
    $rootScope.$on('$stateChangeStart', function (event, toState, toParams, fromState, fromParams) {
        //start loading bar on stateChangeStart
        cfpLoadingBar.start();
        $scope.horizontalNavbarCollapsed = true;
        if (toState.name == "app.pagelayouts.boxedpage") {
            $body.addClass("app-boxed-page");
        } else {
            $body.removeClass("app-boxed-page");
        }

    });
    $rootScope.$on('$stateChangeSuccess', function (event, toState, toParams, fromState, fromParams) {

        //stop loading bar on stateChangeSuccess
        event.targetScope.$watch("$viewContentLoaded", function () {

            cfpLoadingBar.complete();
        });

        // scroll top the page on change state
        $('#app .main-content').css({
            position: 'relative',
            top: 'auto'
        });

        $('footer').show();

        window.scrollTo(0, 0);

        if (angular.element('.email-reader').length) {
            angular.element('.email-reader').animate({
                scrollTop: 0
            }, 0);
        }

        // Save the route title
        $rootScope.currTitle = $state.current.title;

    });

    // State not found
    $rootScope.$on('$stateNotFound', function (event, unfoundState, fromState, fromParams) {
        //$rootScope.loading = false;
        console.log(unfoundState.to);
        // "lazy.state"
        console.log(unfoundState.toParams);
        // {a:1, b:2}
        console.log(unfoundState.options);
        // {inherit:false} + default options
    });

    $rootScope.pageTitle = function () {
        return $rootScope.app.name + ' - ' + ($rootScope.currTitle || $rootScope.app.description);
    };
    var defaultlayout = $scope.app.defaultLayout;
    // save settings to local storage
    if (angular.isDefined($localStorage.lay)) {
        $scope.app.layout = angular.copy($localStorage.lay);

    }

    $scope.resetLayout = function () {
        $scope.loading_reset = true;
        // start loading
        $timeout(function () {
            delete $localStorage.lay;
            $scope.app.layout = angular.copy($rootScope.app.defaultLayout);
            $scope.loading_reset = false;
            // stop loading
        }, 500);

    };
    $scope.saveLayout = function () {
        $scope.loading_save = true;
        // start loading
        $timeout(function () {
            $localStorage.lay = angular.copy($scope.app.layout);
            $scope.loading_save = false;
            // stop loading
        }, 500);

    };
    $scope.setLayout = function () {

        $scope.app.layout.isNavbarFixed = false;
        $scope.app.layout.isSidebarClosed = false;
        $scope.app.layout.isSidebarFixed = false;
        $scope.app.layout.isFooterFixed = false;
        $scope.app.layout.isBoxedPage = false;

    };

    //global function to scroll page up
    $scope.toTheTop = function () {

        $document.scrollTopAnimated(0, 600);

    };

    // angular translate
    // ----------------------
    $scope.language = {
        // Handles language dropdown
        listIsOpen: false,
        // list of available languages
        available: {
            'en': 'English',
            'it_IT': 'Italiano',
            'de_DE': 'Deutsch'
        },
        // display always the current ui language
        init: function () {
            var proposedLanguage = $translate.proposedLanguage() || $translate.use();
            var preferredLanguage = $translate.preferredLanguage();
            // we know we have set a preferred one in app.config
            $scope.language.selected = $scope.language.available[(proposedLanguage || preferredLanguage)];
        },
        set: function (localeId, ev) {
            $translate.use(localeId);
            $scope.language.selected = $scope.language.available[localeId];
            $scope.language.listIsOpen = !$scope.language.listIsOpen;
        }
    };

    $scope.language.init();

    // Fullscreen
    $scope.isFullscreen = false;
    $scope.goFullscreen = function () {
        $scope.isFullscreen = !$scope.isFullscreen;
        if (Fullscreen.isEnabled()) {
            Fullscreen.cancel();
        } else {
            Fullscreen.all();
        }

        // Set Fullscreen to a specific element (bad practice)
        // Fullscreen.enable( document.getElementById('img') )

    };

    // Function that find the exact height and width of the viewport in a cross-browser way
    var viewport = function () {
        var e = window, a = 'inner';
        if (!('innerWidth' in window)) {
            a = 'client';
            e = document.documentElement || document.body;
        }
        return {
            width: e[a + 'Width'],
            height: e[a + 'Height']
        };
    };
    // function that adds information in a scope of the height and width of the page
    $scope.getWindowDimensions = function () {
        return {
            'h': viewport().height,
            'w': viewport().width
        };
    };
    // Detect when window is resized and set some variables
    $scope.$watch($scope.getWindowDimensions, function (newValue, oldValue) {
        $scope.windowHeight = newValue.h;
        $scope.windowWidth = newValue.w;

        if (newValue.w >= 992) {
            $scope.isLargeDevice = true;
        } else {
            $scope.isLargeDevice = false;
        }
        if (newValue.w < 992) {
            $scope.isSmallDevice = true;
        } else {
            $scope.isSmallDevice = false;
        }
        if (newValue.w <= 768) {
            $scope.isMobileDevice = true;
        } else {
            $scope.isMobileDevice = false;
        }
    }, true);
    // Apply on resize
    $win.on('resize', function () {

        $scope.$apply();
        if ($scope.isLargeDevice) {
            $('#app .main-content').css({
                position: 'relative',
                top: 'auto',
                width: 'auto'
            });
            $('footer').show();
        }
    });
}]);

/* /js/controllers/inboxCtrl.js */

/**
 * controller for Messages
 */
app.controller('InboxCtrl', ["$scope", "$state", "$interval",
function ($scope, $state, $interval) {
    $scope.noAvatarImg = "assets/images/default-user.png";
    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();
    $scope.messages = [{
        "from": "John Stark",
        "date": new Date(y, m, d - 1, 19, 0),
        "subject": "Reference Request - Nicole Bell",
        "email": "stark@example.com",
        "avatar": "/images/avatar-6.jpg",
        "starred": false,
        "sent": false,
        "spam": false,
        "read": false,
        "content": "<p>Hi Peter, <br>Thanks for the e-mail. It is always nice to hear from people, especially from you, Scott.</p> <p>I have not got any reply, a positive or negative one, from Seibido yet.<br>Let's wait and hope that it will make a BOOK.</p> <p>Have you finished your paperwork for Kaken and writing academic articles?<br>If you have some free time in the near future, I want to meet you and explain to you our next project.</p> <p>Why not drink out in Hiroshima if we are accepted?<br>We need to celebrate ourselves, don't we?<br>Let's have a small end-of-the-year party!</p> <p>Sincerely, K. Nakagawa</p>",
        "id": 50223456
    }, {
        "from": "James Patterson",
        "date": new Date(y, m, d - 1, 18, 43),
        "subject": "Position requirements",
        "email": "patterson@example.com",
        "avatar": "/images/avatar-9.jpg",
        "starred": true,
        "sent": false,
        "read": true,
        "spam": false,
        "content": "<p>Dear Mr. Clarks</p> <p>I am interested in the Coordinator position advertised on XYZ. My resume is enclosed for your review. Given my related experience and excellent capabilities I would appreciate your consideration for this job opening. My skills are an ideal match for this position.</p> <p> <strong>Your Requirements:</strong> </p> <ul> <li>Responsible for evening operations in Student Center and other facilities, including managing registration, solving customer problems, dealing with risk management and emergencies, enforcement of department policies.</li> <li>Assists with hiring, training, and management of staff. Coordinate statistics and inventory.</li> <li>Experience in the supervision of student staff and strong interpersonal skills are also preferred.</li> <li>Valid Minnesota driver's license with good driving record. Ability to travel to different sites required.</li> <li>Experience in collegiate programming and management.</li> </ul> <p> <strong>My Qualifications:</strong> </p> <ul> <li>Register students for courses, design and manage program software, solve customer problems, enforce department policies, and serve as a contact for students, faculty, and staff.</li> <li>Hiring, training, scheduling and management of staff, managing supply inventory, and ordering.</li> <li>Minnesota driver's license with NTSA defensive driving certification.</li> <li>Extensive experience in collegiate programming and management.</li> <li>Excellent interpersonal and communication skills.</li> </ul> <p>I appreciate your taking the time to review my credentials and experience. Again, thank you for your consideration.</p> <p>Sincerely,<br> <br> James</p>",
        "id": 50223457
    }, {
        "from": "Mary Ferguson",
        "date": new Date(y, m, d - 1, 17, 51),
        "subject": "Employer's job requirements",
        "email": "mary@example.com",
        "avatar": "/images/avatar-8.jpg",
        "starred": false,
        "sent": false,
        "read": true,
        "spam": false,
        "content": "<p>Dear Mr. Clarks</p> <p>In response to your advertisement in the<em> Milliken Valley Sentinel </em> for Vice President, Operations, please consider the following:</p> <p> <strong>Develop and implement strategic operational plans.</strong> <br> 15+ years aggressive food company production management experience. Planned, implemented, coordinated, and revised all production operations in plant of 250+ employees.</p> <p> <strong>Manage people, resources and processes.</strong> <br> Developed and published weekly processing and packaging schedules to meet annual corporate sales demands of up to $50 million. Met all production requirements and minimized inventory costs.</p> <p> <strong>Coach and develop direct reports.</strong> <br> Designed and presented training programs for corporate, divisional and plant management personnel. Created employee involvement program resulting in $100,000+ savings annually.</p> <p> <strong>Ensure operational service groups meet needs of external and internal customers.</strong> <br> Chaired cross-functional committee of 16 associates that developed and implemented processes, systems and procedures plant-wide. Achieved year end results of 12% increase in production, 6% reduction in direct operational costs and increased customer satisfaction rating from 85% to 93.5%.</p> <p>I welcome the opportunity to visit with you about this position. My resume has been uploaded, per your instructions. I may be reached at the number above. Thanks again for your consideration.</p> <p>Sincerely,<br> <br> Mary Ferguson</p>",
        "id": 50223458
    }, {
        "from": "Jane Fieldstone",
        "date": new Date(y, m, d - 1, 17, 38),
        "subject": "Job Offer",
        "email": "fieldstone@example.com",
        "starred": false,
        "sent": false,
        "read": true,
        "spam": false,
        "content": "<p>Dear Mr. Clarks,</p> <p>As we discussed on the phone, I am very pleased to accept the position of Marketing Manager with Smithfield Pottery. Thank you for the opportunity. I am eager to make a positive contribution to the company and to work with everyone on the Smithfield team.</p> <p>As we discussed, my starting salary will be $38,000 and health and life insurance benefits will be provided after 90 days of employment.</p> <p>I look forward to starting employment on July 1, 20XX. If there is any additional information or paperwork you need prior to then, please let me know.</p> <p>Again, thank you.</p> <p> <br> Jane Fieldstone</p>",
        "id": 50223459
    }, {
        "from": "Steven Thompson",
        "date": new Date(y, m, d - 1, 12, 2),
        "subject": "Personal invitation",
        "email": "thompson@example.com",
        "avatar": "/images/avatar-3.jpg",
        "starred": false,
        "sent": false,
        "spam": false,
        "content": "<p>Dear Peter,</p> <p>Good Day!</p> <p>We would like to invite you to the coming birthday party of our son Francis John Waltz Jr. as he is celebrating his first birthday. The said party will be on November 27, 2010 at Toy Kingdom just along Almond road. All kids are expected to wear their beautiful fancy dress.</p> <p>We will be delighted with your presence in this party together with your family. We will be arranging transportation for all the guests for your convenience in going to the venue of the said party promptly.</p> <p>It is a great honor for us to see you at the party and please do confirm your attendance before the party in the given number so that we will arrange the service accordingly.</p> <p>Best regards,</p> <p>Mr. and Mrs. Thompson</p>",
        "id": 50223460
    }, {
        "from": "Michael A. Faust",
        "date": new Date(y, m, d - 1, 11, 22),
        "subject": "Re: Group Meeting",
        "email": "faust@example.com",
        "starred": true,
        "sent": false,
        "read": true,
        "spam": false,
        "content": "<p>Dear Sir</p><p>It was my pleasure to be introduced to you by Mr. Elliot Carson last Tuesday. I am delighted to make your acquaintance. Mr. Carson has highly recommended you as an esteemed businessman with integrity and good reputation.</p><p>Hence, it would be my pleasure to extend an invitation to you to join our Texas Businessmen Fellowship every last Saturday of the month from 6pm to 9pm at Texas Holiday Inn. This fellowship was set up by Texan businessmen who are sincere in assisting one another in honest business dealings and to look out for one another as a brother for another.</p><p>Attendance and membership are by invitation only. We share about the business trends and strategies as well as pitfalls to avoid so that it would make our members sharper in our business acumen. Every member is free to share his business knowledge, skills and tips. We want all members to succeed as a businessman.</p><p>As you are highly recommended by Mr. Carson, one of our founders, we shall be pleased to have you join us this month. Dress code: Smart casual. There would be a dinner at the fellowship so that members can be in a relaxed environment to mingle with one another.</p><p>We look forward to your confirmation to this invitation so that the necessary preparations can be done.</p><p>Respectfully yours,</p><p>Michael A. Faust</p>",
        "id": 50223461
    }, {
        "from": "Nicole Bell",
        "date": new Date(y, m, d - 1, 10, 31),
        "subject": "Congratulations ",
        "email": "nicole@example.com",
        "avatar": "/images/avatar-2.jpg",
        "starred": false,
        "sent": false,
        "read": true,
        "spam": false,
        "content": "<p>Dear friend Peter,</p><p>I am feeling very happy today to congratulate you as you got promotion. I got the news two days before that you are promoted from the post of junior manager to the post of senior manager. You really deserved that promotion. You were a employee of that company since 10 years. In these 10 years you have served the company a lot. With your skills, hard work, intelligence you have contributed to the companies success. Due to all these reasons you had to get promotion.</p><p>Personally I am very happy to see you getting successful in your life. This time also it was very delightful to hear about your success. I hope god bless you and give you pink of health. I will always ask god to give you everything that you need in your life. He may bless you with lot of happiness in your future. </p><p>Give my love to your children and regards to your parents.</p><p>Your’s affectionately,</p><p>Nicole Bell.</p>",
        "id": 50223462
    }, {
        "from": "Google Geoff",
        "date": new Date(y, m, d - 1, 9, 38),
        "subject": "JobSearch information letter",
        "email": "mutating@example.com",
        "starred": false,
        "sent": false,
        "spam": true,
        "content": "<p>Dear recipient,</p><p>Avangar Technologies announces the beginning of a new unprecendented global employment campaign. reviser yeller winers butchery twenties Due to company's exploding growth Avangar is expanding business to the European region. During last employment campaign over 1500 people worldwide took part in Avangar's business and more than half of them are currently employed by the company. And now we are offering you one more opportunity to earn extra money working with Avangar Technologies. druggists blame classy gentry Aladdi</p><p>We are looking for honest, responsible, hard-working people that can dedicate 2-4 hours of their time per day and earn extra Â£300-500 weekly. All offered positions are currently part-time and give you a chance to work mainly from home.</p><p>lovelies hockey Malton meager reordered</p><p>Please visit Avangar's corporate web site (http://www.avangar.com/sta/home/0077.htm) for more details regarding these vacancies.</p>",
        "id": 50223463
    }, {
        "from": "Shane Michaels",
        "date": new Date(y, m, d - 2, 20, 32),
        "subject": "Marketing agreement between two companies",
        "email": "shane@example.com",
        "starred": false,
        "sent": false,
        "read": true,
        "spam": false,
        "content": "<p>This letter is with regards to the advertisement given in the yesterdays newspaper &amp; we feel proud to introduce ourselves as M/s ABC advertising agency. We are ready to take up your proposal of doing marketing work for your company. We will charge $10,000 for a week for this work of marketing. This price includes print material like posters, handbills, radio announcements, advertisements in local newspaper as well as on television channels &amp; also street-to-street mike announcements. Your company will give the wordings of the announcement &amp; the payment can be made after the work gets complete. Mode of payment will be through cheques &amp; payment should be made in three installments, first on agreement, second at the time when work commences &amp; lastly when the work is completed.</p><p>Yours sincerely,</p><p>Shane Michaels</p>",
        "id": 50223464
    }, {
        "from": "Kenneth Ross",
        "date": new Date(y, m, d - 2, 19, 59),
        "subject": "Sincere request to keep in touch.",
        "email": "kenneth@example.com",
        "avatar": "/images/avatar-5.jpg",
        "starred": false,
        "sent": false,
        "read": true,
        "spam": false,
        "content": "<p>Dear Mr. Clarks,</p><p>I was shocked to see my letter after having just left and  part away from college just a couple of weeks ago. Well it’s my style to bring back together and  hold on to our college group who seems to get separated and  simply go along their own ways. Just giving it a sincere try, who wish to live life just like those college days, to share and  support for every minute crust and  fragments happening in the life.</p><p>So without any compulsion and  without any special invitation this is a one time offer cum request cum order to keep in touch and  also to live forever as best buddies. Hoping to see you at Café da Villa on this Sunday evening to celebrate our new beginning in a grand way.</p><p>Thanking you,</p>",
        "id": 50223465
    }];


    var incomingMessages = [
		{
		    "from": "Nicole Bell",
		    "date": new Date(),
		    "subject": "New Project",
		    "email": "nicole@example.com",
		    "avatar": "/images/avatar-2.jpg",
		    "starred": false,
		    "sent": false,
		    "read": false,
		    "spam": false,
		    "content": "Hi there! Are you available around 2pm today? I’d like to talk to you about a new project",
		    "id": 50223466
		},
		{
		    "from": "Steven Thompson",
		    "date": new Date(),
		    "subject": "Apology",
		    "email": "thompson@example.com",
		    "avatar": "/images/avatar-3.jpg",
		    "starred": false,
		    "sent": false,
		    "read": false,
		    "spam": false,
		    "content": "<p>Hi Peter,</p> <p>I am very sorry for my behavior in the staff meeting this morning.</p> <p>I cut you off in the middle of your presentation, and criticized your performance in front of the staff. This was not only unprofessional, but also simply disrespectful. I let my stress about a personal matter impact my management of the office.</p>",
		    "id": 50223467
		},
		{
		    "from": "Mary Ferguson",
		    "date": new Date(),
		    "subject": "Congratulations ",
		    "email": "mary@example.com",
		    "avatar": "/images/avatar-8.jpg",
		    "starred": false,
		    "sent": false,
		    "read": false,
		    "spam": false,
		    "content": "<p>Dear Ms. Clarks,</p> I am a friend of Emily Little and she encouraged me to forward my resume to you. I know Emily through a local children's theater, for which I was a lighting assistant this semester. I also see her at college music performances, as I am in the orchestra.</p>",
		    "id": 50223468
		}
    ];


    $scope.scopeVariable = 1;
    var loadMessage = function () {
        $scope.messages.push(incomingMessages[$scope.scopeVariable - 1]);
        $scope.scopeVariable++;
    };

    //Put in interval, first trigger after 10 seconds
    var add = $interval(function () {
        if ($scope.scopeVariable < 4) {
            loadMessage();
        }
    }, 10000);

    $scope.stopAdd = function () {
        if (angular.isDefined(add)) {
            $interval.cancel(add);
            add = undefined;
        }
    };
}]);
app.controller('ViewMessageCrtl', ['$scope', '$stateParams',
function ($scope, $stateParams) {
    function getById(arr, id) {
        for (var d = 0, len = arr.length; d < len; d += 1) {
            if (arr[d].id == id) {

                return arr[d];
            }
        }
    }


    $scope.message = getById($scope.messages, $stateParams.inboxID);

}]);

/* /js/controllers/bootstrapCtrl.js */

/**
 * controllers for UI Bootstrap components
 */
app.controller('AlertDemoCtrl', ["$scope", function ($scope) {
    $scope.alerts = [{
        type: 'danger',
        msg: 'Oh snap! Change a few things up and try submitting again.'
    }, {
        type: 'success',
        msg: 'Well done! You successfully read this important alert message.'
    }];

    $scope.addAlert = function () {
        $scope.alerts.push({
            msg: 'Another alert!'
        });
    };

    $scope.closeAlert = function (index) {
        $scope.alerts.splice(index, 1);
    };
}]).controller('ButtonsCtrl', ["$scope", function ($scope) {
    $scope.singleModel = 1;

    $scope.radioModel = 'Middle';

    $scope.checkModel = {
        left: false,
        middle: true,
        right: false
    };
}]).controller('ProgressDemoCtrl', ["$scope", function ($scope) {
    $scope.max = 200;

    $scope.random = function () {
        var value = Math.floor((Math.random() * 100) + 1);
        var type;

        if (value < 25) {
            type = 'success';
        } else if (value < 50) {
            type = 'info';
        } else if (value < 75) {
            type = 'warning';
        } else {
            type = 'danger';
        }

        $scope.showWarning = (type === 'danger' || type === 'warning');

        $scope.dynamic = value;
        $scope.type = type;
    };
    $scope.random();

    $scope.randomStacked = function () {
        $scope.stacked = [];
        var types = ['success', 'info', 'warning', 'danger'];

        for (var i = 0, n = Math.floor((Math.random() * 4) + 1) ; i < n; i++) {
            var index = Math.floor((Math.random() * 4));
            $scope.stacked.push({
                value: Math.floor((Math.random() * 30) + 1),
                type: types[index]
            });
        }
    };
    $scope.randomStacked();
}]).controller('TooltipDemoCtrl', ["$scope", function ($scope) {
    $scope.dynamicTooltip = 'I am a dynamic Tooltip text';
    $scope.dynamicTooltipText = 'I am a dynamic Tooltip Popup text';
    $scope.htmlTooltip = 'I\'ve been made <b>bold</b>!';
}]).controller('PopoverDemoCtrl', ["$scope", function ($scope) {
    $scope.dynamicPopover = 'Hello, World!';
    $scope.dynamicPopoverTitle = 'Title';
    $scope.popoverType = 'bottom';
}]).controller('PaginationDemoCtrl', ["$scope", "$log", function ($scope, $log) {
    $scope.totalItems = 64;
    $scope.currentPage = 4;

    $scope.setPage = function (pageNo) {
        $scope.currentPage = pageNo;
    };

    $scope.pageChanged = function () {
        $log.log('Page changed to: ' + $scope.currentPage);
    };

    $scope.maxSize = 5;
    $scope.bigTotalItems = 175;
    $scope.bigCurrentPage = 1;
}]).controller('RatingDemoCtrl', ["$scope", function ($scope) {
    $scope.rate = 7;
    $scope.max = 10;
    $scope.isReadonly = false;

    $scope.hoveringOver = function (value) {
        $scope.overStar = value;
        $scope.percent = 100 * (value / $scope.max);
    };

    $scope.ratingStates = [{
        stateOn: 'glyphicon-ok-sign',
        stateOff: 'glyphicon-ok-circle'
    }, {
        stateOn: 'glyphicon-star',
        stateOff: 'glyphicon-star-empty'
    }, {
        stateOn: 'glyphicon-heart',
        stateOff: 'glyphicon-ban-circle'
    }, {
        stateOn: 'glyphicon-heart'
    }, {
        stateOff: 'glyphicon-off'
    }];
}]).controller('DropdownCtrl', ["$scope", "$log", function ($scope, $log) {
    $scope.items = ['The first choice!', 'And another choice for you.', 'but wait! A third!'];

    $scope.status = {
        isopen: false
    };

    $scope.toggled = function (open) {
        $log.log('Dropdown is now: ', open);
    };

    $scope.toggleDropdown = function ($event) {
        $event.preventDefault();
        $event.stopPropagation();
        $scope.status.isopen = !$scope.status.isopen;
    };
}]).controller('TabsDemoCtrl', ["$scope", "SweetAlert", function ($scope, SweetAlert) {
    $scope.tabs = [{
        title: 'Dynamic Title 1',
        content: 'Dynamic content 1'
    }, {
        title: 'Dynamic Title 2',
        content: 'Dynamic content 2',
        disabled: false
    }];

    $scope.alertMe = function () {
        setTimeout(function () {
            SweetAlert.swal({
                title: 'You\'ve selected the alert tab!',
                confirmButtonColor: '#007AFF'
            });
        });
    };
}]).controller('AccordionDemoCtrl', ["$scope", function ($scope) {
    $scope.oneAtATime = true;

    $scope.groups = [{
        title: 'Dynamic Group Header - 1',
        content: 'Dynamic Group Body - 1'
    }, {
        title: 'Dynamic Group Header - 2',
        content: 'Dynamic Group Body - 2'
    }];

    $scope.items = ['Item 1', 'Item 2', 'Item 3'];

    $scope.addItem = function () {
        var newItemNo = $scope.items.length + 1;
        $scope.items.push('Item ' + newItemNo);
    };

    $scope.status = {
        isFirstOpen: true,
        isFirstDisabled: false
    };
}]).controller('DatepickerDemoCtrl', ["$scope", "$log", function ($scope, $log) {
    $scope.today = function () {
        $scope.dt = new Date();
    };
    $scope.today();

    $scope.clear = function () {
        $scope.dt = null;
    };

    // Disable weekend selection
    $scope.disabled = function (date, mode) {
        return (mode === 'day' && (date.getDay() === 0 || date.getDay() === 6));
    };

    $scope.toggleMin = function () {
        $scope.minDate = $scope.minDate ? null : new Date();
    };
    $scope.maxDate = new Date(2020, 5, 22);
    $scope.open = function ($event) {
        $event.preventDefault();
        $event.stopPropagation();

        $scope.opened = !$scope.opened;
    };
    $scope.endOpen = function ($event) {
        $event.preventDefault();
        $event.stopPropagation();
        $scope.startOpened = false;
        $scope.endOpened = !$scope.endOpened;
    };
    $scope.startOpen = function ($event) {
        $event.preventDefault();
        $event.stopPropagation();
        $scope.endOpened = false;
        $scope.startOpened = !$scope.startOpened;
    };

    $scope.dateOptions = {
        formatYear: 'yy',
        startingDay: 1
    };

    $scope.formats = ['dd-MMMM-yyyy', 'yyyy/MM/dd', 'dd.MM.yyyy', 'shortDate'];
    $scope.format = $scope.formats[0];

    $scope.hstep = 1;
    $scope.mstep = 15;

    // Time Picker
    $scope.options = {
        hstep: [1, 2, 3],
        mstep: [1, 5, 10, 15, 25, 30]
    };

    $scope.ismeridian = true;
    $scope.toggleMode = function () {
        $scope.ismeridian = !$scope.ismeridian;
    };

    $scope.update = function () {
        var d = new Date();
        d.setHours(14);
        d.setMinutes(0);
        $scope.dt = d;
    };

    $scope.changed = function () {
        $log.log('Time changed to: ' + $scope.dt);
    };

    $scope.clear = function () {
        $scope.dt = null;
    };

}]).controller('DropdownCtrl', ["$scope", "$log", function ($scope, $log) {
    $scope.items = ['The first choice!', 'And another choice for you.', 'but wait! A third!'];

    $scope.status = {
        isopen: false
    };

    $scope.toggled = function (open) {
        $log.log('Dropdown is now: ', open);
    };

    $scope.toggleDropdown = function ($event) {
        $event.preventDefault();
        $event.stopPropagation();
        $scope.status.isopen = !$scope.status.isopen;
    };
}]).controller('ModalDemoCtrl', ["$scope", "$uibModal", "$log", function ($scope, $uibModal, $log) {

    $scope.items = ['item1', 'item2', 'item3'];

    $scope.open = function (size) {

        var modalInstance = $uibModal.open({
            templateUrl: 'myModalContent.html',
            controller: 'ModalInstanceCtrl',
            size: size,
            resolve: {
                items: function () {
                    return $scope.items;
                }
            }
        });

        modalInstance.result.then(function (selectedItem) {
            $scope.selected = selectedItem;
        }, function () {
            $log.info('Modal dismissed at: ' + new Date());
        });
    };
}]);

// Please note that $uibModalInstance represents a modal window (instance) dependency.
// It is not the same as the $uibModal service used above.

app.controller('ModalInstanceCtrl', ["$scope", "$uibModalInstance", "items", function ($scope, $uibModalInstance, items) {

    $scope.items = items;
    $scope.selected = {
        item: $scope.items[0]
    };

    $scope.ok = function () {
        $uibModalInstance.close($scope.selected.item);
    };

    $scope.cancel = function () {
        $uibModalInstance.dismiss('cancel');
    };
}]);
