

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