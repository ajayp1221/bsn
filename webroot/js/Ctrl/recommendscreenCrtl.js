
/** 
 * controller for Recommendscreen
 */

app.controller('RecommendscreenCrtl.Index', ['$scope', 'toaster', '$http', '$aside', '$timeout', 'SweetAlert','FileUploader', function ($scope, toaster, $http, $aside, $timeout, SweetAlert,FileUploader) {

        /* Start: Define Variable  */
        $scope.sw = SweetAlert; /* For Action response */
        $scope.master = {}; /* For After submit Data on from*/
        $scope.isSendingData = false; /* Loder */
        $scope.pager = {
            "totalRec": 78,
            "step": 10,
            "currentPg": 1,
            "totalPg": 8
        };
        /* Set from value from view */
        $scope.pushmessages = []; /* Set get value from controller */
        /* End: Define Variable  */
        
        /*  Start: Function for get record of pushmessages */
        
        $scope.getData = function () {
            $scope.isSendingData = true;
            $http.get('/recommend-screen.json').success(function (d) {
                if(d.recommendScreen.active){
                    d.recommendScreen.active = true;
                }else{
                    d.recommendScreen.active = false;
                }if(d.recommendScreen.embedcode){
                    d.recommendScreen.discountOn = 1;
                }else{
                    d.recommendScreen.discountOn = 0;
                }
                $scope.isSendingData = false;
                $scope.newRS = d.recommendScreen;
                $scope.newRS.discountOnText = d.result.b.meta_value;
            });
        };
        /*  End: Function for get record of pushmessages */
        
        /* Start: Form Submit Save Record */
        $scope.form = {
            reset: function (form) {
                $scope.newRS = angular.copy($scope.master);
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
                    $http.post("/recommend-screen/add.json",$scope.newRS).success(function(d){
                        console.log($scope.newRS);
                        if(d.result.error == 0){
                            SweetAlert.success("Success!", d.result.msg, "success");
                            $scope.getData();
                            form.$setPristine(true);
                        }else{
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