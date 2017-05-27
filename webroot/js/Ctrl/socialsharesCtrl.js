
/** 
 * controller for Socialshares
 */

app.controller('Socialshares.IndexCtrl', ['$scope', 'toaster', '$http', '$aside', '$timeout', 'SweetAlert', function ($scope, toaster, $http, $aside, $timeout, SweetAlert,FileUploader) {
        $scope.schedulepost = function () {
            $scope.isLoading = true;
            $scope.emailData = [];
            $http.post('/socialshares/schedule.json').success(function (data) {
                $scope.scheduleData = data.socialshares;
                $scope.isLoading = false;
            });
        };
        $scope.allpost = function () {
            $scope.isLoading = true;
            $scope.smsData = [];
            $http.post('/socialshares/all.json').success(function (data) {
                $scope.allData = data.socialshares;
                $scope.isLoading = false;
            });
        };
}]);

app.controller('Socialshares.CreateCtrl', ['$scope', 'toaster', '$http', '$aside', '$timeout', 'SweetAlert', function ($scope, toaster, $http, $aside, $timeout, SweetAlert,FileUploader) {
       
        $scope.frmData = {};
        $scope.fbConnected = false;
        $scope.twConnected = false;
        $scope.fbPages = [];
        $scope.getData = function(){
            $http.get('/socialshares.json').success(function(d){
                var r = d.result;
                $scope.fbConnected = r.facebook.connected;
                $scope.twConnected = r.twitter.connected;
                if($scope.fbConnected == 1){
                    r.facebook.pages.data.push({
                       id: r.facebook.self.id,
                       name: "Your Own Profile"
                    });
                    $scope.fbPages = r.facebook.pages.data;
                }
            });
        };
        $scope.disconnect = function($ac){
          console.log($ac);
          $http.delete('/social-connections/delete/'+$ac+'.json').success(function(d){
              SweetAlert.success("Success!", d.result.msg, "success");
              $scope.getData();
          });
        };
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
        $scope.form = {
            reset: function (form) {
                $scope.frmData = angular.copy($scope.master);
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
                    var formData = new FormData($('#form1')[0]);
                    $.ajax({
                        url: '/socialshares/add.json', //Server script to process data
                        type: 'POST',
                        xhr: function () {  // Custom XMLHttpRequest
                            var myXhr = $.ajaxSettings.xhr();
                            return myXhr;
                        },
                        //Ajax events
    //                    beforeSend: beforeSendHandler,
                        success: function (a) {
                            toaster.pop('success', 'Images uploaded successfully...');
                            $uibModalInstance.close();
                            $scope.getData();
                        },
                        error: function (e) {
                            toaster.pop('error', 'Some issue occured. Please contact for support...');
                        },
                        // Form data
                        data: formData,
                        //Options to tell jQuery not to process data or worry about content-type.
                        cache: false,
                        contentType: false,
                        processData: false
                    });
                }
            }
        };
        //init below
        $scope.getData();
    }]);