
/** 
 * controller for Pushmessages
 */

app.controller('Pushmessages.Index', ['$scope', 'toaster', '$http', '$aside', '$timeout', 'SweetAlert','FileUploader', function ($scope, toaster, $http, $aside, $timeout, SweetAlert,FileUploader) {

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
        $scope.newPush= {
            send_before_day: 1
        }; /* Set from value from view */
        $scope.pushmessages = []; /* Set get value from controller */
        /* End: Define Variable  */
        
        /* Start: Status Display and update  */
        $scope.options = [
            {id: 1, name: 'Running'},
            {id: 0, name: 'Completed'},
            {id: 2, name: 'Paused'}
        ];
        $scope.changeStatus = function (v, id) {
            var res = $http.post("/pushmessages/changeStatus.json", {id: id, status: v});
            res.success(function (data) {
                if (data.response.error == 0) {
                    toaster.pop('success', 'Updated...', data.response.msg);
                }
                if (data.response.error == 1) {
                    toaster.pop('error', 'Some issue occured...', data.response.msg);
                }
            });
        };
        /* End: Status Display and update  */
        
        /*  Start: Function for get record of pushmessages */
        
        $scope.getData = function () {
            $scope.isLoading = true;
            $http.get('/pushmessages.json?page=' + $scope.pager.currentPg + '&limit=' + $scope.pager.step + '&sort='+$scope.sort.col+'&direction='+$scope.sort.odr).success(function (d) {
                $scope.pushmessages = d.pushmessages;
                $scope.pager = d.pager;
                $scope.pager.totalPages = [];
                $scope.isLoading = false;
                for (var i = 1; i <= d.pager.totalPg; i++) {
                    $scope.pager.totalPages.push({vl: i});
                }
            });
        };
        /*  End: Function for get record of pushmessages */
        /* Start: For oder and data per page*/
        $scope.sort = {
            col:'id',
            odr: 'desc'
        };
        $scope.sort = function(col){
            if(col == $scope.sort.col && $scope.sort.odr == 'asc'){
                $scope.sort.odr = 'desc';
            }else{
                $scope.sort.col = col;
                $scope.sort.odr = 'asc';
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
        /* End: For oder and data per page */
        
        /* Start: Calender */
        jQuery.datetimepicker.setLocale('en');
        var a = new Date();
        var dtp = jQuery('.dtime').datetimepicker({
            minDate: 0,
            minTime: a.getHours() + ":" + a.getMinutes(),
            onSelectDate: function (ct, ab) {
                var today = new Date();
                today.setHours(0, 0, 0, 0);
                ct.setHours(0, 0, 0, 0, 0);
                if (ct - today > 100) {
                    this.setOptions({minTime: "00:00"});
                } else {
                    this.setOptions({minTime: 0});
                    var tmp = $('.datepicker').val();
                    $('.datepicker').val(tmp[0] + " " + (new Date()).getHours() + ":00");
                }
            }
        });
        /* END: Calender */
        
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
                    var formData = new FormData($('#form1')[0]);
                    $.ajax({
                        url: '/pushmessages/add.json', //Server script to process data
                        type: 'POST',
                        xhr: function () {  // Custom XMLHttpRequest
                            var myXhr = $.ajaxSettings.xhr();
                            if (myXhr.upload) { // Check if upload property exists
                                myXhr.upload.addEventListener('progress', function (a, b) {
                                    var value = (a.loaded / a.total * 100).toFixed(2);
                                    if (parseInt(value, 10) == 100) {
                                        $scope.prgText = "Processing...";
                                    }
                                    $scope.progress = value;
                                    $scope.prgText = 'Uploading ' + value + '%'
                                }, false); // For handling the progress of the upload
                            }
                            return myXhr;
                        },
//                        Ajax events
//                        beforeSend: beforeSendHandler,
                        success: function (a) {
                            $scope.showPrg = false;
                            $scope.progress = 0;
                            toaster.pop('success', 'Images uploaded successfully...');
                            $scope.getData();
                        },
                        error: function (e) {
                            toaster.pop('error', 'Some issue occured. Please contact for support...');
                        },
//                         Form data
                        data: formData,
//                        Options to tell jQuery not to process data or worry about content-type.
                        cache: false,
                        contentType: false,
                        processData: false
                    });
            
                    
//                    $http.post("/pushmessages/add.json",$scope.newPush).success(function(d){
//                        console.log($scope.newPush);
//                        if(d.result.error == 0){
//                            SweetAlert.success("Success!", d.result.msg, "success");
//                            $scope.getData();
//                            $scope.newPush = angular.copy($scope.master);
//                            form.$setPristine(true);
//                        }else{
//                            SweetAlert.error("Error!", d.result.msg, "error");
//                        }
//                        $scope.isSendingData = false;
//                    }).error(function(e){
//                        SweetAlert.error("Some Network Issue!", "Please try once again...", "error");
//                        $scope.isSendingData = false;
//                    });
                }
            }
        };
        /* End: Form Submit Save Record */
        $scope.getData();
    }]);