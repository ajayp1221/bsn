
/** 
 * controller for Campaigns
 */

app.controller('smsledgerCtrl.Index', ['$scope', '$http', '$aside', '$timeout', function ($scope, $http, $aside, $timeout) {
        $scope.smsledgers = [];
        $scope.sort = {
            col: 'id',
            odr: 'desc'
        };
        $scope.pager = {
            "totalRec": 78,
            "step": 10,
            "currentPg": 1,
            "totalPg": 8
        };
        $scope.isLoading = false;
        $scope.getSmsLedger = function () {
            $scope.isLoading = true;
            $http.get('/smsledger.json?page=' + $scope.pager.currentPg + '&limit=' + $scope.pager.step + '&sort=' + $scope.sort.col + '&direction=' + $scope.sort.odr, {keyword: $scope.srchkey}).success(function (d) {
                $scope.smsledgers = d.smsledger;
                $scope.pager = d.pager;
                $scope.pager.totalPages = [];
                for (var i = 1; i <= d.pager.totalPg; i++) {
                    $scope.pager.totalPages.push({vl: i});
                }
                $scope.isLoading = false;
            });
        };
        $scope.getSmsLedger();
        $scope.changePg = function (pg) {
            $scope.pager.currentPg = pg;
            $scope.getSmsLedger();
        };
        $scope.sortBy = function (col) {
            if (col == $scope.sort.col && $scope.sort.odr == 'asc') {
                $scope.sort.odr = 'desc';
            } else {
                $scope.sort.col = col;
                $scope.sort.odr = 'asc';
            }
            $scope.getSmsLedger();
        };
        $scope.setLimit = function (l) {
            $scope.pager.currentPg = 1;
            $scope.pager.step = l;
            $scope.getSmsLedger();
        };
    }]);


app.controller('smsledgerCtrl.Credits', ['$scope', '$http', '$aside', '$timeout', function ($scope, $http, $aside, $timeout) {
        $scope.smsledgers = [];
        $scope.sort = {
            col: 'id',
            odr: 'desc'
        };
        $scope.pager = {
            "totalRec": 78,
            "step": 10,
            "currentPg": 1,
            "totalPg": 8
        };
        $scope.isLoading = false;
        $scope.getSmsLedger = function () {
            $scope.isLoading = true;
            $http.get('/smsledger/get-credits.json?page=' + $scope.pager.currentPg + '&limit=' + $scope.pager.step + '&sort=' + $scope.sort.col + '&direction=' + $scope.sort.odr, {keyword: $scope.srchkey}).success(function (d) {
                $scope.smsledgers = d.smsledger;
                $scope.pager = d.pager;
                $scope.pager.totalPages = [];
                for (var i = 1; i <= d.pager.totalPg; i++) {
                    $scope.pager.totalPages.push({vl: i});
                }
                $scope.isLoading = false;
            });
        };
        $scope.getSmsLedger();
        $scope.changePg = function (pg) {
            $scope.pager.currentPg = pg;
            $scope.getSmsLedger();
        };
        $scope.sortBy = function (col) {
            if (col == $scope.sort.col && $scope.sort.odr == 'asc') {
                $scope.sort.odr = 'desc';
            } else {
                $scope.sort.col = col;
                $scope.sort.odr = 'asc';
            }
            $scope.getSmsLedger();
        };
        $scope.setLimit = function (l) {
            $scope.pager.currentPg = 1;
            $scope.pager.step = l;
            $scope.getSmsLedger();
        };
    }]);



app.controller('smsledgerCtrl.Debits', ['$scope', '$http', '$aside', '$timeout', '$state', function ($scope, $http, $aside, $timeout, $state) {
        $scope.flag = $state.params.flag;
        $scope.isLoading = false;
        $scope.smsledgers = [];
        $scope.sort = {
            col: 'id',
            odr: 'desc'
        };
        $scope.pager = {
            "totalRec": 78,
            "step": 10,
            "currentPg": 1,
            "totalPg": 8
        };
        $scope.getDetails = function($event,extra){
            console.log($event);
            $http.post('/smsledger/get-gshup-details',{extra:extra}).success(function(d){
                $($event.currentTarget).parent().html(d);
            }).error(function(e){
                $($event.currentTarget).append('Some network issue, please try again...');
            });
        };
        $scope.getData = function () {
            $scope.isLoading = true;
            $http.post('/smsledger/query-debits.json?page=' + $scope.pager.currentPg + '&limit=' + $scope.pager.step + '&sort=' + $scope.sort.col + '&direction=' + $scope.sort.odr, {comments: $scope.flag}).success(function (d) {
                $scope.pager = d.pager;
                $scope.smsledgers = d.smsledger;
                $scope.isLoading = false;
                $scope.pager.totalPages = [];
                for (var i = 1; i <= d.pager.totalPg; i++) {
                    $scope.pager.totalPages.push({vl: i});
                }
            }).error(function () {
                $scope.isLoading = false;
            });
        };
        $scope.sortBy = function (col) {
            console.log($scope.sort);
            console.log(col);
            if (col == $scope.sort.col && $scope.sort.odr == 'asc') {
                $scope.sort.odr = 'desc';
            } else {
                $scope.sort.col = col;
                $scope.sort.odr = 'asc';
            }
            $scope.getData();
        };
        $scope.changePg = function (pg) {
            $scope.pager.currentPg = pg;
            $scope.getData();
        };
        $scope.setLimit = function (l) {
            // Reset Pager and start search
            $scope.pager.currentPg = 1;

            $scope.pager.step = l;
            $scope.getData();
        };
        $scope._init = function () {
            $scope.getData();
        };
        $scope._init();
}]);