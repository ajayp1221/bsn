
/** 
  * controller for Clients
*/
app.controller('Clients.LoginCtrl', ['$scope', 'toaster', function ($scope, toaster) {
    $scope.toaster = {
        type: 'info',
        title: '',
        text: $flasMsg
    };
    $scope.flash = function(){
        if($flasMsg != null){
            toaster.pop($scope.toaster.type, $scope.toaster.title, $scope.toaster.text);
            $flasMsg = null;
        }
    };
}]);

app.controller('Clients.ForgotCtrl', ['$scope', '$http', 'SweetAlert', function ($scope, $http, SweetAlert) {
    $scope.clntData = {};
    
    $scope.form = {
            reset: function (form) {
                $scope.clntData = angular.copy($scope.master);
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
                    $http.post("/clients/forgetpwd.json",$scope.clntData).success(function(d){
                        console.log($scope.clntData);
                        if(d.result.error == 0){
                            SweetAlert.success("Success!", d.result.msg, "success");
                        }else{
                            SweetAlert.error("Error!", d.result.msg, "error");
                        }
                        $scope.isSendingData = false;
                    }).error(function(e){
                        SweetAlert.error("Some Network Issue!", "Please try once again...", "error");
                        $scope.isSendingData = false;
                    });
                    //your code for submit
                }

            }
        };
}]);
app.controller('Clients.ChangePwdCtrl', ['$scope', '$http', 'SweetAlert', function ($scope, $http, SweetAlert) {
    $scope.clntData = {};
    $scope.master = {};
    $scope.form = {
            reset: function (form) {
                $scope.clntData = angular.copy($scope.master);
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
                    $http.post("/clients/changepwd.json",$scope.clntData).success(function(d){
                        if(d.result.error == 0){
                            $scope.clntData.reset();
                            SweetAlert.success("Success!", d.result.msg, "success");
                        }else{
                            SweetAlert.error("Error!", d.result.msg, "error");
                        }
                        $scope.isSendingData = false;
                    }).error(function(e){
                        SweetAlert.error("Some Network Issue!", "Please try once again...", "error");
                        $scope.isSendingData = false;
                    });
                    //your code for submit
                }
            }
        };
}]);