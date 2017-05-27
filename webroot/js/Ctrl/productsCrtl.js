
/** 
 * controller for Pushmessages
 */

app.controller('productsCrtl.Index', ['$scope', 'toaster', '$http', '$aside', '$timeout', 'SweetAlert','FileUploader', function ($scope, toaster, $http, $aside, $timeout, SweetAlert,FileUploader) {

        /* Start: Define Variable  */
        $scope.sw = SweetAlert; /* For Action response */
        $scope.master = {}; /* For After submit Data on from*/
        $scope.isSendingData = false; /* Loder */
        $scope.pager = {
            "totalRec": 78,
            "step": 10,
            "currentPg": 1,
            "totalPg": 8
        }; /**/
        $scope.newPrd= {
            discountOn:1
        };
        $scope.newPrdct = {};
        $scope.addNewMenBx = false;
        $scope.addNewWomenBx = false;
        $scope.addNewKidsBx = false;
        
        $scope.showAddNew = function(v){
            if(v == 'women'){
                $scope.addNewMenBx = false;
                $scope.addNewWomenBx = true;
                $scope.addNewKidsBx = false;   
            }
            if(v == 'men'){
                $scope.addNewMenBx = true;
                $scope.addNewWomenBx = false;
                $scope.addNewKidsBx = false;
            }
            if(v == 'kids'){
                $scope.addNewMenBx = false;
                $scope.addNewWomenBx = false;
                $scope.addNewKidsBx = true;
            }
            $scope.newPrdct = {category:v};
            
        };
        
        
        
        /* Set from value from view */
        /* End: Define Variable  */
        
        $scope.getData = function () {
            $scope.isSendingData = true;
            $http.get('/products.json').success(function (d) {
                $scope.isSendingData = false;
                $scope.products = d.products;
            });
        };
        
        $scope.editPrd = function(val,id){
            console.log(val,id);
            $scope.isSendingData = true;
            $http.post("/products/edit.json",{product_name:val,id:id}).success(function(d){
                if(d.result.error == 0){
                    SweetAlert.success("Success!", d.result.msg, "success");
                    $scope.getData();
                }else{
                    SweetAlert.error("Error!", d.result.msg, "error");
                    return false;
                }
                $scope.isSendingData = false;
            }).error(function(e){
                SweetAlert.error("Some Network Issue!", "Please try once again...", "error");
                $scope.isSendingData = false;
                return false;
            });
        };
        
        $scope.deletePrd = function(id){
            
            $scope.sw.swal({   
                title: "Are you sure?",   
                text: "You will not be able to recover this product's data!",   
                type: "warning",   
                showCancelButton: true,   
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Yes, delete it!",   
                closeOnConfirm: false 
            },function(x){   
                if(!x){
                    return false;
                }
                $http.delete("/products/delete/"+id+".json").success(function(d){
                    if(d.result.error == 0){
                        SweetAlert.success("Success!", d.result.msg, "success");
                        $scope.getData();
                        $scope.newPush = angular.copy($scope.master);
                        $scope.getData();
                    }else{
                        SweetAlert.error("Error!", d.result.msg, "error");
                        return false;
                    }
                    $scope.isSendingData = false; 
                }).error(function(e){
                    SweetAlert.error("Some Network Issue!", "Please try once again...", "error");
                    $scope.isSendingData = false;
                    return false;
                });
                    
            });
            
        };
        
        
        $scope.form = {
            reset: function (form) {
                $scope.cstData = angular.copy($scope.master);
                form.$setPristine(true);
            },
            submit: function (form) {
                console.log($scope.newPrdct);
                $scope.isSendingData = true;
                $http.post("/products/add.json",$scope.newPrdct).success(function(d){
                    console.log($scope.newPush);
                    if(d.result.error == 0){
                        SweetAlert.success("Success!", d.result.msg, "success");
                        $scope.getData();
                        form.$setPristine(true);
                        $scope.addNewMenBx = false;
                        $scope.addNewWomenBx = false;
                        $scope.addNewKidsBx = false;
                    }else{
                        SweetAlert.error("Error!", d.result.msg, "error");
                    }
//                    $scope.isSendingData = false;
                    $scope.getData();
                }).error(function(e){
                    SweetAlert.error("Some Network Issue!", "Please try once again...", "error");
                    $scope.isSendingData = false;
                });
            }
        };
        $scope.getData();
    }]);