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