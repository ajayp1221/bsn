
/** 
 * controller for Promocode
 */

app.controller('Promocode.Redeem', ['$scope', 'toaster', '$http', '$aside', '$timeout', 'SweetAlert','FileUploader', function ($scope, toaster, $http, $aside, $timeout, SweetAlert,FileUploader) {

        /* Start: Define Variable  */
        $scope.sw = SweetAlert; /* For Action response */
        $scope.master = {}; /* For After submit Data on from*/
        $scope.isSendingData = false; /* Loder */
        $scope.isLoading = true; /* Loder */
        $scope.pager = {
            "totalRec": 78,
            "step": 10,
            "currentPg": 1,
            "totalPg": 8
        }; /**/
        /* Start: For oder and data per page*/
        $scope.sort1 = {
            "col":"id",
            "odr": "desc"
        };
        /* End: For oder and data per page */
        $scope.redeemCpn= {
            is_contact: 0
        }; 
        /* Set from value from view */
        $scope.messages = []; /* Set get value from controller */
        /* End: Define Variable  */
       
        
        
        /*  Start: Function for get record of MessagesController */
        $scope.getData = function () {
            console.log($scope);
            $scope.isLoading = true;
            $http.get('/messages.json?page=' + $scope.pager.currentPg + '&limit=' + $scope.pager.step + '&sort1='+$scope.sort1.col+'&direction='+$scope.sort1.odr).success(function (d) {
                $scope.messages = d.messages;
                $scope.isLoading = false;
                $scope.pager = d.pager;
                $scope.pager.totalPages = [];
                for (var i = 1; i <= d.pager.totalPg; i++) {
                    $scope.pager.totalPages.push({vl: i});
                }
            });
        };
        $scope.changePg = function (pg) {
            $scope.currentPgNo = pg;
            $scope.getData();
        };
        /*  End: Function for get record of MessagesController */
        
        $scope.sortBy = function(col){
            if(col == $scope.sort1.col && $scope.sort1.odr == 'asc'){
                $scope.sort1.odr = 'desc';
            }else{
                $scope.sort1.col = col;
                $scope.sort1.odr = 'asc';
            }
            $scope.getData();
        };
        $scope.changePg = function (pg) {
            $scope.pager.currentPg = pg;
            $scope.getData();
        };
        $scope.setLimit = function (l) {
            $scope.pager.step = l;
            $scope.getData();
        };
        
        
        /* Start: Form Submit Save Record */
        $scope.form = {
            reset: function (form) {
                $scope.cstData = angular.copy($scope.master);
                form.$setPristine(true);
            },
            submit: function (form) {
                var firstError = null;
                if (form.$invalid) {
                    var field = null, firstError = null;
                    for (field in form) {
                        if (field[0] != '$') {
                            if (firstError === null && !form[field].$valid) {
                                firstError = form[field].$name;
                            }
                            if (form[field].$pristine) {
                                form[field].$dirty = true;
                            }
                        }
                    }
                    angular.element('.ng-invalid[name=' + firstError + ']').focus();
                    SweetAlert.error("Please verify once again", "The form cannot be submitted because it contains errors, Errors are marked with a red.", "error");
                    return;
                } else {
                    $scope.isSendingData = true;
                    $http.post("/messages/verify-promo.json",$scope.redeemCpn).success(function(d){
                        if(d.result.error == 0){
                            SweetAlert.success("Success!", d.result.msg, "success");
                            $scope.getData();
                            $scope.redeemCpn = angular.copy($scope.master);
                            form.$setPristine(true);
                        }if(d.result.error == 2){
                            $scope.redeemCpn = {
                                is_contact :1,
                                promo_code:$scope.redeemCpn.promo_code
                            };
                            SweetAlert.success("Success!", d.result.msg, "success");
                        }if(d.result.error == 1){
                            SweetAlert.error("Error!", d.result.msg, "error");
                        }
                        $scope.isSendingData = false;
                    }).error(function(e){
                        SweetAlert.error("Some Network Issue!", "Please try once again...", "error");
                        $scope.isSendingData = false;
                    });
                }
            }
        };
        /* End: Form Submit Save Record */
        $scope.getData();
    }]);