
/** 
 * controller for Clients
 */
app.controller('Pages.DashboardCtrl', ['$scope', 'toaster', '$http', function ($scope, toaster, $http) {
        $scope.toaster = {
            type: 'info',
            title: '',
            text: $flasMsg
        };
        $scope.flash = function () {
            if ($flasMsg != null) {
                toaster.pop($scope.toaster.type, $scope.toaster.title, $scope.toaster.text);
                $flasMsg = null;
            }
        };

        $scope.isNanBlank = function (val) {
            if (isNaN(val)) {
                return '--';
            }
            return val;
        }
        $scope.smsSort = {
            col:'id',
            odr: 'desc'
        };
        $scope.smsData = [];
        $scope.emailData = [];
        $scope.smsLedgerData = [];
        $scope.isLoading = false;
        $scope.smsLedgerTotals = {
            crSum : 0,
            drSum : 0
        };
        $scope.calcDrAndCrSum = function(){
          var i;
          for(i in $scope.smsLedgerData){
              if($scope.smsLedgerData[i][2] != '0'){
                  $scope.smsLedgerTotals.drSum += parseInt($scope.smsLedgerData[i][2],10);
              }else if($scope.smsLedgerData[i][1] != '0'){
                  $scope.smsLedgerTotals.crSum += parseInt($scope.smsLedgerData[i][1],10);
              }
          } 
        };
        $scope.loadSMSLedger = function(){
            $scope.ledgerLoading = true;
            $http.get('/pages/get-sms-ledger.json').success(function(d){
                $scope.smsLedgerData = d.data;
                $scope.ledgerLoading = false;
                $scope.calcDrAndCrSum();
            }).error(function(){
                $scope.ledgerLoading = false;
                toaster.pop("warn", "Opps! Error occured...", "Please try again later when network issues will be fixed...");
            });
            
        };
        
        $scope.emailSort = {
            col:'id',
            odr: 'desc'
        };
        
        $scope.currentPgNo1 = 1;
        $scope.loadEmail = function () {
            $scope.isLoading = true;
            $scope.emailData = [];
            $http.post('/pages/get-email-campaigns.json?page='+$scope.currentPgNo1+'&sort='+$scope.emailSort.col+'&direction='+$scope.emailSort.odr).success(function (data) {
                $scope.emailData = data.results;
                $scope.isLoading = false;
                $scope.pager1 = data.pager1;
                $scope.pager1.totalEmailPages = [];
                for (var i = 1; i <= data.pager1.totalPg; i++) {
                    $scope.pager1.totalEmailPages.push({vl: i});
                }
            });
        };
        $scope.changeEmailPg = function (pg) {
            $scope.currentPgNo1 = pg;
            $scope.loadEmail();
        };
        
        
        $scope.sortEmailBy = function(col){
            if(col == $scope.emailSort.col && $scope.emailSort.odr == 'asc'){
                $scope.emailSort.odr = 'desc';
            }else{
                $scope.emailSort.col = col;
                $scope.emailSort.odr = 'asc';
            }
            $scope.loadEmail();
        };
        
        
        $scope.currentPgNo = 1;
        $scope.loadSMS = function () {
            $scope.isLoading = true;
            $scope.smsData = [];
            $http.post('/pages/get-sms-campaigns.json?page='+$scope.currentPgNo+'&sort='+$scope.smsSort.col+'&direction='+$scope.smsSort.odr).success(function (data) {
                $scope.smsData = data.results;
                $scope.isLoading = false;
                $scope.pager = data.pager;
                $scope.pager.totalPages = [];
                for (var i = 1; i <= data.pager.totalPg; i++) {
                    $scope.pager.totalPages.push({vl: i});
                }
            });
        };
        $scope.changePg = function (pg) {
            $scope.currentPgNo = pg;
            $scope.loadSMS();
        };
        $scope.sortSmsBy = function(col){
            console.log($scope.smsSort);
            console.log(col);
            if(col == $scope.smsSort.col && $scope.smsSort.odr == 'asc'){
                $scope.smsSort.odr = 'desc';
            }else{
                $scope.smsSort.col = col;
                $scope.smsSort.odr = 'asc';
            }
            $scope.loadSMS();
        };

        $scope.loadSMSLedger();
        //    console.log($http);


    }]);

app.controller('ConversionsCtrl',['$scope', 'toaster', '$http', function ($scope, toaster, $http){
        $scope.labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
	$scope.series = ['Transactions', 'Unique Visitors'];
	$scope.data = [[65, 59, 80, 81, 56, 55, 40, 84, 64, 120, 132, 87], [172, 175, 193, 194, 161, 175, 153, 190, 175, 231, 234, 250]];
	$scope.colors = [{
		fillColor: 'rgba(91,155,209,0.5)',
		strokeColor: 'rgba(91,155,209,1)'
	}, {
		fillColor: 'rgba(91,155,209,0.5)',
		strokeColor: 'rgba(91,155,209,0.5)'
	}];

	// Chart.js Options - complete list at http://www.chartjs.org/docs/
	$scope.options = {
		maintainAspectRatio: false,
		showScale: false,
		scaleLineWidth: 0,
		responsive: true,
		scaleFontFamily: "'Helvetica', 'Arial', sans-serif",
		scaleFontSize: 11,
		scaleFontColor: "#aaa",
		scaleShowGridLines: true,
		tooltipFontSize: 11,
		tooltipFontFamily: "'Helvetica', 'Arial', sans-serif",
		tooltipTitleFontFamily: "'Helvetica', 'Arial', sans-serif",
		tooltipTitleFontSize: 12,
		scaleGridLineColor: 'rgba(0,0,0,.05)',
		scaleGridLineWidth: 1,
		bezierCurve: true,
		bezierCurveTension: 0.5,
		scaleLineColor: 'transparent',
		scaleShowVerticalLines: false,
		pointDot: false,
		pointDotRadius: 4,
		pointDotStrokeWidth: 1,
		pointHitDetectionRadius: 20,
		datasetStroke: true,
		datasetStrokeWidth: 2,
		datasetFill: true,
		animationEasing: "easeInOutExpo"
	};
}]);

app.controller('Pages.AllmessagesCtrl', ['$scope', 'toaster', '$http','$stateParams', function ($scope, toaster, $http,$stateParams) {
     $scope.cmpid = $stateParams.cmpid;
     $scope.isLoading = true;
     $scope.cmp = null;
     $http.post("/campaigns/view/"+$scope.cmpid+".json").success(function(data){
         $scope.cmp = data.campaign;
         $scope.isLoading = false;
     });
     
     $scope.currentPg = 1;
     $scope.pager = null;
     $scope.limit = 100;
     $scope.messages = [];
     $scope.sortbyOdr = 'asc';
     $scope.sortbyCol = 'name';
     $scope.getMsg = function(){
         $scope.isLoading = true;
         $http.get('/campaigns/get-messages/'+$scope.cmpid+'.json?page='+$scope.currentPg+'&limit='+$scope.limit+'&sort='+$scope.sortbyCol+'&direction='+$scope.sortbyOdr).success(function(d){
             $scope.messages = d.messages;
             d.pager.totalPages = [];
             for(var i = 1; i <= d.pager.totalPg; i++){
                d.pager.totalPages.push({vl:i});
             }
             $scope.pager = d.pager;
             $scope.isLoading = false;
         });
         
     };
     $scope.changePg = function(pg){
         console.log(pg);
         $scope.currentPg = pg;
         $scope.getMsg();
     };
     $scope.setLimit = function(l){
         $scope.limit = l;
         $scope.currentPg = 1;
         $scope.getMsg();
     };
     $scope.sortBy = function(col){
         if($scope.sortbyCol == col){
             if($scope.sortbyOdr == 'asc'){
                 $scope.sortbyOdr = 'desc';
             }else{
                 $scope.sortbyOdr = 'asc';
             }
         }else{
             $scope.sortbyCol = col;
             $scope.sortbyOdr = 'asc';
         }
         $scope.getMsg();
     };
     
     $scope.getMsg();
     
}]);
