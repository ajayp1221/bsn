
/** 
 * controller for Questions
 */

app.controller('questionsCtrl.Index', ['$scope', 'toaster', '$http', '$aside', '$timeout', 'SweetAlert','FileUploader', function ($scope, toaster, $http, $aside, $timeout, SweetAlert,FileUploader) {

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
        /* End: Define Variable  */
        $scope.master = {
            question_type: 1,
            options: [{option:''}]
        };
        $scope.queData = {
            question_type: 1,
            options: [{option:''}]
        };
        $scope.addMoreOpt = function(){
          $scope.queData.options.push({option:''});  
        };
        $scope.removeOpt = function(index){
          $scope.queData.options.splice(index,1);
        };
        $scope.cleanOpts = function(t){
            if(t == 1){
                $scope.queData.options = [{option:''}];
            }
            if(t == 2){
                $scope.queData.options = [{option:'2'}];
            }
            if(t == 3){
                $scope.queData.options = [];
            }
        };
        $scope.editQue = function(que){
            $('body,html').animate({scrollTop:0})
            $scope.showAddNewForm = true;
            $scope.queData = angular.copy(que);
        };
        
        $scope.showAddNewForm = false;
        
        $scope.addNew = function(){
            $scope.queData = angular.copy($scope.master);
            $scope.showAddNewForm = true;
        };
        
        
        $scope.getData = function () {
            $scope.isSendingData = true;
            $http.get('/questions.json').success(function (d) {
                $scope.isSendingData = false;
                $scope.questions = d.questions;
            });
        };
        $scope.deactivateQue = function(qid){
            SweetAlert.swal({   
                title: "Are you sure?",   
                text: "You will not be able to see this question on Tab",   
                type: "warning",   
                showCancelButton: true,   
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Yes, Deactivate it!",   
                closeOnConfirm: false 
            },function(x){   
                if(!x){
                    return false;
                }
                $http.delete('/questions/deactivate/'+qid+'.json').success(function(d){
                    if(d.result.error == 0){
                        SweetAlert.success("Success!", d.result.msg, "success");
                        $scope.getData();
                        $scope.queData = angular.copy($scope.master);
                        form.$setPristine(true);
                    }else{
                        SweetAlert.error("Error!", d.result.msg, "error");
                    }
                }).error(function(){
                    SweetAlert.error("Some Network Issue!", "Please try once again...", "error");
                });
            });
        };
        $scope.activateQue = function(qid){
            $http.delete('/questions/activate/'+qid+'.json').success(function(d){
                if(d.result.error == 0){
                    SweetAlert.success("Success!", d.result.msg, "success");
                    $scope.getData();
                    $scope.queData = angular.copy($scope.master);
                    form.$setPristine(true);
                }else{
                    SweetAlert.error("Error!", d.result.msg, "error");
                }
            }).error(function(){
                SweetAlert.error("Some Network Issue!", "Please try once again...", "error");
            });
        };
        
        $scope.form = {
            reset: function (form) {
                $scope.queData = angular.copy($scope.master);
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
                    console.log($scope.queData);
                    $scope.isSendingData = true;
                    $http.post("/questions.json",$scope.queData).success(function(d){
                        console.log($scope.cstData);
                        if(d.result.error == 0){
                            SweetAlert.success("Success!", d.result.msg, "success");
                            $scope.getData();
                            $scope.queData = angular.copy($scope.master);
                            form.$setPristine(true);
                        }else{
                            SweetAlert.error("Error!", d.result.msg, "error");
                        }
                        $scope.isSendingData = false;
                    }).error(function(e){
                        $scope.isSendingData = false;
                        SweetAlert.error("Some Network Issue!", "Please try once again...", "error");
                    });
                    //your code for submit
                }

            }
        };
        
        $scope.getData();
    }]);