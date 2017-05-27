
/** 
 * controller for Socialshares
 */

app.controller('ShareScreen.Index', ['$scope', 'toaster', '$http', '$aside', '$timeout', 'SweetAlert', function ($scope, toaster, $http, $aside, $timeout, SweetAlert,FileUploader) {
        $scope.getData = function () {
            $scope.isLoading = true;
            $http.post('/share-screen.json').success(function (data) {
                if(data.shareScreen.active){
                    data.shareScreen.active = true;
                }else{
                    data.shareScreen.active = false;
                }
                $scope.shareScreen = data.shareScreen;
                $scope.isLoading = false;
            });
        };
        $scope.getData();  
        $scope.form = {
            reset: function (form) {
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
                    return false;
                } else {
                    $scope.isLoading = true;
                    $http.post("/share-screen/add.json", $scope.shareScreen).success(function (d) {
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
                    
                    
                    
                }
            }
        };
        //init below
        $scope.getData();
    }]);