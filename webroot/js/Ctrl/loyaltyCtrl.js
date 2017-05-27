

app.controller('Loyalty.ledger',['$scope', '$http', 'SweetAlert','$state',function($scope, $http, SweetAlert,$state){
        $scope.data = [];
        $scope.number = $state.params.number;
        $scope.getData = function () {
            $scope.isLoading = true;
            $http.post("/loyalty/ledger/" + btoa(btoa(btoa($state.params.code))) + ".json").success(function (d) {
                if(d.result.error==0){
                    $scope.data = d.result.data;
                    $scope.isLoading = false;
                }else{
                    $scope.isLoading = false;
                    SweetAlert.error("Error", d.result.msg, "error");
                }
            }).error(function(e){
                SweetAlert.error("Some Network Issue!", "Please try once again...", "error");
                $scope.isLoading = false;
            });
        };
        $scope.editing = false;
        $scope.newField = {};
        $scope.editAppKey = function(field) {
            $scope.editing = $scope.data.indexOf(field);
            $scope.newField = angular.copy(field);
        };
        $scope.saveField = function(index) {
            if ($scope.editing !== false) {
                $scope.data[$scope.editing] = $scope.newField;
                $scope.editing = false;
            }
        };
        $scope.save = function(d) {
            $scope.isLoading = true;
            $http({
                url: '/loyalty/update.json',
                method: "POST",
                data: $.param(d),
                headers: {
                  'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                }
            }).success(function(e){
                if(e.result.error==0){
                    SweetAlert.success("Success", e.result.msg, "success");
                    $scope.getData();
                    $scope.isLoading = false;
                }else{
                    SweetAlert.success("Error", e.result.msg, "error");
                    $scope.isLoading = false;
                }
            }).error(function(e){
                SweetAlert.error("Some Network Issue!", "Please try once again...", "error");
                $scope.isLoading = false;
            });
        };
        $scope.cancel = function(index) {
            if ($scope.editing !== false) {
                $scope.data[$scope.editing] = $scope.newField;
                $scope.editing = false;
            }
        };
        $scope.getData();
}]);

app.controller('LoyaltySettingsCtrl', ['$scope', '$http', '$uibModalInstance','$prVm', 'toaster', function ($scope, $http, $uibModalInstance,$prVm,toaster) {
        $uibModalInstance.closed.then(function(){
            $scope.prvm.ratioPointsCr = angular.copy($scope.prvm.settingsBkp.ratioPointsCr);
            $scope.prvm.ratioPointsDr = angular.copy($scope.prvm.settingsBkp.ratioPointsDr);
            $scope.prvm.rMinLimit = angular.copy($scope.prvm.settingsBkp.rMinLimit);
            $scope.prvm.aMaxLimit = angular.copy($scope.prvm.settingsBkp.aMaxLimit);
//            $prVm.isLoading = true;
            $scope.prvm.$apply();
//            window.location.reload();
        });
        
        $scope.prvm = $prVm;
        $scope.ok = function (e) {
            $scope.prvm.$apply();
            $scope.showPrg = true;
            $prVm.isLoading = true;
            $('#lSettingsFrm').submit();
            toaster.pop('warn', 'Settings are being saved...');
        };
        $scope.cancel = function (e) {
            $uibModalInstance.dismiss();
        };
}]);
app.controller('Loyalty.index', ['$scope', '$http', '$aside', 'SweetAlert', function ($scope, $http, $aside, SweetAlert) {
        $scope.lylt2Data = {};
        $scope.lyltData = {};
        $scope.nwSttng= {};
        $scope.layalty = [];
        $scope.pager = null;
        $scope.currentPgNo = 1;
        
        $scope.isLoading = false;
        
        $scope.ratioPointsCr = 1;
        $scope.ratioPointsDr = 1;
        $scope.rMinLimit = 500;
        $scope.aMaxLimit = 4000;
        $scope.lsettings = {};
        $scope.$watch('100 / ratioPointsCr', function(v) {$scope.conversionRatioCr = v.toFixed(2);});
        $scope.$watch('ratioPointsDr / 100', function(v) {$scope.conversionRatioDr = v.toFixed(2);});
        
        $scope.pointToAmount = function(points,type){
            if(type == 'cr')
                return Math.round(points * $scope.conversionRatioCr);
            if(type == 'dr')
                return Math.round(points * $scope.conversionRatioDr);
        };
        $scope.amountToPoint = function(amount,type){
            if(type == 'cr')
                return Math.round(amount / $scope.conversionRatioCr);
            if(type == 'dr')
                return Math.round(amount / $scope.conversionRatioDr);
        };
        
        $scope.rPoints = 0;
        $scope.rAmount = 0;
        $scope.updateRAmount = function(e){
            console.log(e);
            var v = $(e.currentTarget).val();
            $scope.rAmount = $scope.pointToAmount(v,'dr');
        };
        $scope.updateRPoints = function(e){
            console.log(e);
            var v = $(e.currentTarget).val();
            $scope.rPoints = $scope.amountToPoint(v,'dr');
        };
        $scope.aPoints = 0;
        $scope.aAmount = 0;
        $scope.updateAAmount = function(e){
            console.log(e);
            var v = $(e.currentTarget).val();
            $scope.aAmount = $scope.pointToAmount(v,'cr');
        };
        $scope.updateAPoints = function(e){
            console.log(e);
            var v = $(e.currentTarget).val();
            $scope.aPoints = $scope.amountToPoint(v,'cr');
        };
        $scope.addPointValidation = function(d,e){
            if($scope.aPoints > $scope.aMaxLimit){
                alert("You are exceeding limit! You can add maximum of " + $scope.aMaxLimit + " points");
                return false;
            }
            return true;
        };
        $scope.redeemPointValidation = function(d,e){
            if($scope.rPoints < $scope.rMinLimit){
                alert("Please redeem at least " + $scope.rMinLimit + " points...");
                return false;
            }
            return true;
        };
        $scope.searchKeyword = "";
        $scope.getData = function () {
            $scope.isLoading = true;
            if($scope.searchKeyword != ""){
                $scope.urlParamString = $scope.currentPgNo + "&s=" + $scope.searchKeyword;
            }else{
                $scope.urlParamString = $scope.currentPgNo;
            }
            $http.post("/loyalty.json?page=" + $scope.urlParamString).success(function (d) {
                $scope.layalty = d;
                $scope.pager = d.pager;
                $scope.pager.totalPages = [];
                
                $scope.lsettings = d.lsettings;
                if($scope.lsettings['loyalty-conversion-ratio-cr'] != undefined){
                    $scope.ratioPointsCr = 100 / $scope.lsettings['loyalty-conversion-ratio-cr'][0]['meta_value'];
                }
                if($scope.lsettings['loyalty-conversion-ratio-dr'] != undefined){
                    $scope.ratioPointsDr = 100 * $scope.lsettings['loyalty-conversion-ratio-dr'][0]['meta_value'];
                }
                if($scope.lsettings['loyalty-min-balance-4-redemption'] != undefined){                 
                    $scope.rMinLimit = $scope.lsettings['loyalty-min-balance-4-redemption'][0]['meta_value'];
                }
                if($scope.lsettings['loyalty-max-award-a-day'] != undefined){
                    $scope.aMaxLimit = $scope.lsettings['loyalty-max-award-a-day'][0]['meta_value'];
                }
                try{
                    if($scope.lsettings['loyalty-message-cr'][0]['meta_value'] != ""){
                        console.log(14);
                        $scope.lyltData.comments = $scope.lsettings['loyalty-message-cr'][0]['meta_value'];
                    }else{
                        console.log(15);
                        $scope.lyltData.comments = 'Hi! [[Pts]] loyalty points have been added to your account on [[Date]]. Available points are [[Bal]] - [[layalty.strName]]';
                    }
                    if($scope.lsettings['loyalty-message-dr'][0]['meta_value'] != ""){
                        $scope.lylt2Data.comments = $scope.lsettings['loyalty-message-dr'][0]['meta_value'];
                    }else{
                        $scope.lylt2Data.comments = 'Hi! [[Pts]] loyalty points have been added to your account on [[Date]]. Available points are [[Bal]] - [[layalty.strName]]';
                    }
                }catch(e){}            
                for (var i = 1; i <= d.pager.totalPg; i++) {
                    $scope.pager.totalPages.push({vl: i});
                }
                $scope.isLoading = false;
            }).error(function(e){
                SweetAlert.error("Some Network Issue!", "Please try once again...", "error");
                $scope.isLoading = false;
            });
        };
        $scope.search = function(){
            $scope.getData();
        };
        $scope.searchKeyup = function(event){
            if(event.keyCode != 13){
                return false;
            }
            $scope.getData();
        };
        $scope.openSettings = function(){
            $scope.settingsBkp = {
                ratioPointsCr: angular.copy($scope.ratioPointsCr),
                ratioPointsDr: angular.copy($scope.ratioPointsDr),
                rMinLimit: angular.copy($scope.rMinLimit),
                aMaxLimit: angular.copy($scope.aMaxLimit)
            };
            $scope.asideInstance = $aside.open({
                templateUrl: 'loyaltySettings.html',
                controller: 'LoyaltySettingsCtrl',
                placement: 'right',
                size: 'md',
                resolve: {
                    $prVm: $scope
                }
            });
        };
        $scope.updateMsg = function(e){
          var txt = $(e.currentTarget).val();
          
          $http.post("/loyalty/loyalty-msg/"+$(e.currentTarget).data('tp')+".json",{msg: txt}).success(function (d) { 
              $(e.currentTarget).next().fadeIn('slow').effect('pulsate').fadeOut('slow');
          }).error(function(e){
                SweetAlert.error("Some Network Issue!", "Please try once again...", "error");
                $scope.isLoading = false;
            });
        };
        $scope.changePg = function (pg) {
            $scope.currentPgNo = pg;
            $scope.getData();
        };
        $scope.getData();
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
                    $scope.lyltData.conversionRatio = $scope.conversionRatioCr;
                    $scope.lyltData.points = $scope.aPoints;
                    $scope.lyltData.purchase_total = $scope.aAmount;
                    $http.post("/loyalty/add-points.json", $scope.lyltData).success(function (d) {
                        if (d.result.error == 0) {
                            SweetAlert.success("Success!", d.result.msg, "success");
                            $scope.getData();
                            //$scope.nwSttng = angular.copy(master);
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
            },
            submit2: function (form) {
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
                    $scope.lylt2Data.conversionRatio = $scope.conversionRatioDr;
                    $scope.lylt2Data.points = $scope.rPoints;
                    $scope.lylt2Data.purchase_total = $scope.rAmount;
                    $http.post("/loyalty/redeem-points.json", $scope.lylt2Data).success(function (d) {
                        if (d.result.error == 0) {
                            SweetAlert.success("Success!", d.result.msg, "success");
                            $scope.getData();
                            //$scope.nwSttng = angular.copy(master);
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
        
        console.log($scope);
}]);