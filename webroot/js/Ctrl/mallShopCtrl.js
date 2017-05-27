app.controller('MallShop.IndexCrtl', ['$scope', '$http', 'SweetAlert','toaster', function ($scope, $http, SweetAlert,toaster) {
        
        $scope.nwShop= {};
        $scope.currentPgNo = 1;
        $scope.options = [
            {id: 1, name: 'Active'},
            {id: 0, name: 'Deactive'}
        ];
        
        $scope.getData = function () {
            $scope.isLoading = true;
            $http.post('/mall-shop.json').success(function (d) {
                $scope.shops = d.mallShop;
                $scope.pager = d.pager;
                $scope.pager.totalPages = [];
                for (var i = 1; i <= d.pager.totalPg; i++) {
                    $scope.pager.totalPages.push({vl: i});
                }
                $scope.isLoading = false;
            }).error(function(e){
                SweetAlert.error("Some Network Issue!", "Please try once again...", "error");
                $scope.isLoading = false;
            });
        };
        $scope.getData();
        
        $scope.changePg = function (pg) {
            $scope.currentPgNo = pg;
            $scope.getData();
        };
        
        $scope.changeStatus = function (v, id) {
            var res = $http.post("/mall-shop/change-status.json", {id: id, status: v});
            res.success(function (data) {
                if (data.response.error == 0) {
                    toaster.pop('success', 'Updated...', data.response.msg);
                }
                if (data.response.error == 1) {
                    toaster.pop('error', 'Some issue occured...', data.response.msg);
                }
            });
        };
        
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
                    $http.post("/mall-shop/add.json", $scope.nwShop).success(function (d) {
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