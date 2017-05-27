


app.controller('Settings.Store', ['$scope', 'toaster', '$http', '$aside', '$timeout', 'SweetAlert', function ($scope, toaster, $http, $aside, $timeout, SweetAlert) {
        $scope.nwSttng= {};
        $scope.getData = function () {
            $scope.isLoading = true;
            $http.post('/clients/settings.json').success(function (d) {
                if(d.rec1.meta_value==1){
                    $scope.nwSttng.active = true;
                }
                if(d.rec2!=null){
                    $scope.nwSttng.mobile = d.rec2.meta_value;
                }
                if(d.rec3!=null){
                    $scope.nwSttng.sms_time = d.rec3.meta_value;
                }
                $scope.isLoading = false;
            }).error(function(e){
                SweetAlert.error("Some Network Issue!", "Please try once again...", "error");
                $scope.isLoading = false;
            });
        };
        $scope.getData();
        var a = new Date();
        var dtp = jQuery('.timepicker').datetimepicker({
            datepicker:false,
            format:'H:i'
        });
        
        $scope.form = {
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
                    $scope.isLoading = true;
                    $http.post("/clients/setting.json", $scope.nwSttng).success(function (d) {
                        if (d.result.error == 0) {
                            SweetAlert.success("Success!", d.result.msg, "success");
                            $scope.getData();
                            var master = {
                                whos_send: 1,
                                send_date: moment().add(2, 'hour').format('YYYY/MM/DD HH:mm'),
                                exceptDefaulter: true
                            };
                            $scope.nwSttng = angular.copy(master);
                            form.$setPristine(true);
                        } else {
                            SweetAlert.error("Error!", d.result.msg, "error");
                        }
                        $scope.isLoading = false;
                    }).error(function (e) {
                        SweetAlert.error("Some Network Issue!", "Please try once again...", "error");
                        $scope.isLoading = false;
                    });
                    //your code for submit
                }
            }
        };
}]);



app.controller('Settings.mallsetting', ['$scope', '$http', 'SweetAlert', function ($scope, $http, SweetAlert) {
        $scope.nwSttng= {};
        $scope.getData = function () {
            $scope.isLoading = true;
            $http.post('/clients/mall-settings.json').success(function (d) {
                if(d.rec1.meta_value==1){
                    $scope.nwSttng.mallModeEnable = true;
                }
                if(d.rec2.meta_value==1){
                    $scope.nwSttng.showBillSubmitScreen = true;
                }
                $scope.isLoading = false;
            }).error(function(e){
                SweetAlert.error("Some Network Issue!", "Please try once again...", "error");
                $scope.isLoading = false;
            });
        };
        $scope.getData();
        var a = new Date();
        var dtp = jQuery('.timepicker').datetimepicker({
            datepicker:false,
            format:'H:i'
        });
        
        $scope.form = {
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
                    $scope.isLoading = true;
                    console.log($scope.nwSttng);
                    $http.post("/clients/mall-setting.json", $scope.nwSttng).success(function (d) {
                        if (d.result.error == 0) {
                            SweetAlert.success("Success!", d.result.msg, "success");
                            $scope.getData();
                            form.$setPristine(true);
                        } else {
                            SweetAlert.error("Error!", d.result.msg, "error");
                        }
                        $scope.isLoading = false;
                    }).error(function (e) {
                        SweetAlert.error("Some Network Issue!", "Please try once again...", "error");
                        $scope.isLoading = false;
                    });
                    //your code for submit
                }
            }
        };
}]);