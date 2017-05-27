
app.controller('CstMsr', ['$scope', 'toaster', '$http', '$aside', '$timeout', 'SweetAlert','FileUploader', function ($scope, toaster, $http, $aside, $timeout, SweetAlert,FileUploader) {
        $scope.name = "dtsst";
        $scope.select = [];
        $scope.measurementType = function(){
            $http.get('/customers/measurement.json').success(function(d){
                $scope.select = d.results;
            });
        };
        $scope.measurementType();
        $scope.showParams = false;
        $scope.mes = {
            type : "",
            select : ""
        };
        $scope.measureSize = "Measurement Size1";
        $scope.measureType = "Measurement Type1";
        $scope.toggleParams = function(mes){
            if(mes.type == "" && mes.select==""){
                 SweetAlert.error("Error!", "Please select or type product category", "error");
            }else{
                $scope.isLoading = true;
                $http.post('/customers/customer-measurement.json',{data:mes,cid:$scope.editId}).success(function(d){
                    if(d.error==0){
                        $scope.paramList = d.results;
                        $scope.isLoading = false;
                    }else{
                        $scope.paramList = [
                            {measurement_name:" ", value: " ",customer_id:$scope.editId,measurement_type:d.measurement_type},
                            {measurement_name:" ", value: " ",customer_id:$scope.editId,measurement_type:d.measurement_type},
                            {measurement_name:" ", value: " ",customer_id:$scope.editId,measurement_type:d.measurement_type},
                            {measurement_name:" ", value: " ",customer_id:$scope.editId,measurement_type:d.measurement_type}
                        ];
                        $scope.isLoading = false;
                    }
                });
                $scope.showParams = !$scope.showParams;
            }
        };
        
        $scope.addMoreParam = function(measurement_type){
            console.log(measurement_type);
            $scope.paramList.push({measurement_name:" ", value: " ",customer_id:$scope.editId,measurement_type:measurement_type});  
        };
        $scope.removeParam = function(index){
            $scope.paramList.splice(index,1);
        };
        $scope.$on('editId', function(response) {
            $scope.name = response.targetScope.editId;
            $scope.$apply();
        });
        
        $scope.save = function(data){
            $scope.isLoading = true;
            $http.post('/customers/add-new-measurement.json',{data:data}).success(function(d){
                $scope.getDataCall();
                $scope.isLoading = false;
            });
        };
}]);

/** 
 * controller for Campaigns
 */
app.controller('Customers.IndexCtrl', ['$scope', 'toaster', '$http', '$aside', '$timeout', 'SweetAlert','FileUploader', function ($scope, toaster, $http, $aside, $timeout, SweetAlert,FileUploader) {

        $scope.uploader = new FileUploader({
            url: '/customers/uploadcsv.json',
            alias : 'csv'
        });
        $scope.cstVisit1 = -1;
        $scope.cstVisit = function(cstId){
            $scope.isLoading = true;
            $scope.cstVisit1 = cstId;
            $http.post('/customers/store-vistis.json',{customer_id:cstId}).success(function(d){
                $scope.customerVisit = d.result.count;
                $scope.isLoading = false;
            }).error(function(d){
                
            });
        };
        $scope.sw = SweetAlert;
        $scope.cstData = {};
        $scope.master = {};
        $scope.isSendingData = false;
        
        $scope.editId = -1;
        $scope.setEditId = function (pid) {
            setTimeout(function(){
                $scope.$broadcast('editId', pid);
            },500);
            $scope.editId = pid;
        };
        $scope.addFormField = function() {
            $scope.cst.measurement_name.push('');
            $scope.cst.value.push('');
        };
        
        $scope.isLoading = true;
        $scope.sort = {
            col:'name',
            odr: 'asc'
        };
        $scope.pager = {
            "totalRec": 78,
            "step": 10,
            "currentPg": 1,
            "totalPg": 8
        };
        $scope.customersData = [];
        $scope.srchkey = '';
        $scope.searchNow = function(){
          console.log($scope.srchkey);
          $scope.isLoading = true;
            $http.post('/customers/srchkey.json?page=' + $scope.pager.currentPg + '&limit=' + $scope.pager.step + '&sort='+$scope.sort.col+'&direction='+$scope.sort.odr,{keyword:$scope.srchkey}).success(function (d) {
                $scope.customersData = d.customers;
                $scope.pager = d.pager;
                $scope.pager.totalPages = [];
                $scope.isLoading = false;
                for (var i = 1; i <= d.pager.totalPg; i++) {
                    $scope.pager.totalPages.push({vl: i});
                }
            });
        };
        $scope.getDataCall = function () {
            $scope.isLoading = true;
            $http.get('/customers.json?page=' + $scope.pager.currentPg + '&limit=' + $scope.pager.step + '&sort='+$scope.sort.col+'&direction='+$scope.sort.odr).success(function (d) {
                $scope.customersData = d.customers;
                $scope.pager = d.pager;
                $scope.pager.totalPages = [];
                $scope.isLoading = false;
                for (var i = 1; i <= d.pager.totalPg; i++) {
                    $scope.pager.totalPages.push({vl: i});
                }
            });
        };
        $scope.getData = function () {
            if($scope.srchkey == ''){
                $scope.getDataCall();
            }else{
                $scope.searchNow();
            }
        };
        $scope.searchNowBtn = function(){
            // Reset Pager and start search
            $scope.sort = {
                col:'name',
                odr: 'asc'
            };
            $scope.pager = {
                "totalRec": 78,
                "step": 10,
                "currentPg": 1,
                "totalPg": 8
            };
            $scope.searchNow();
        };
        $scope.sortBy = function(col){
            console.log($scope.sort);
            console.log(col);
            if(col == $scope.sort.col && $scope.sort.odr == 'asc'){
                $scope.sort.odr = 'desc';
            }else{
                $scope.sort.col = col;
                $scope.sort.odr = 'asc';
            }
            $scope.getData();
        };
        $scope.editCst = function(data){
            $scope.cstData = data;
        };
        $scope.removeCst = function(data){
            var id = data.id;
            $scope.sw.swal({   
                title: "Are you sure?",   
                text: "You will not be able to recover this customers data!",   
                type: "warning",   
                showCancelButton: true,   
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Yes, delete it!",   
                closeOnConfirm: false 
            },function(x){   
                if(!x){
                    return false;
                }
                $http.post("/customers/delete/"+id+".json").success(function(r){
                    $scope.getData();
                    $scope.sw.swal("Deleted!", "Your customer has been deleted.", "success"); 
                });
                    
            });
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
                    $http.post("/customers/add-or-update.json",$scope.cstData).success(function(d){
                        console.log($scope.cstData);
                        if(d.result.error == 0){
                            SweetAlert.success("Success!", d.result.msg, "success");
                            $scope.getData();
                            $scope.cstData = angular.copy($scope.master);
                            form.$setPristine(true);
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
        //init below
        $scope.getData();
        jQuery.datetimepicker.setLocale('en');
        var a = new Date();
        var dtp = jQuery('.dtime').datepicker({
            maxDate: 0,
            format: 'MM/dd/YYYY'
        });
    }]);

app.controller('Customers.MesaurementCtrl', ['$scope', '$http', 'SweetAlert', function ($scope, $http, SweetAlert) {
        
}]);