
/** 
 * controller for Campaigns
 */
app.controller('EditModalCtrl',["$scope","$http","SweetAlert", "$uibModalInstance","c_id","message_txt", function ($scope,$http, SweetAlert , $uibModalInstance,c_id,message_txt) {
    $scope.id = c_id;
    $scope.message = message_txt;
    $scope.updateMessage = function($event){
        $scope.message = $event.target.value;
    };
    $scope.ok = function () {
        $http.post('/campaigns/update-message.json',{id:$scope.id,message:$scope.message}).success(function(d){
            SweetAlert.success("Success!", d.result.msg, "success");
        }).error(function(){
            SweetAlert.error("Some Network Issue!", "Please try once again...", "error");
        });
        
        $uibModalInstance.close($scope);
    };
    $scope.cancel = function () {
        $uibModalInstance.dismiss('cancel');
    };
}]);
app.controller('QuesAsideCtrl', ['$scope', '$http', '$uibModalInstance', 'newCmp', function ($scope, $http, $uibModalInstance, newCmp) {
        $scope.selectedQID = null;
        $scope.selectedOpt = {};
        $scope.ques = [];
        $scope.optionsFive = [
            {val: "1/5"},
            {val: "2/5"},
            {val: "3/5"},
            {val: "4/5"},
            {val: "5/5"}
        ];
        $scope.optionsTen = [
            {val: "1/10"},
            {val: "2/10"},
            {val: "3/10"},
            {val: "4/10"},
            {val: "5/10"},
            {val: "6/10"},
            {val: "7/10"},
            {val: "8/10"},
            {val: "9/10"},
            {val: "10/10"}
        ];
        $scope.isQuestionData = true;
        $http.post('/campaigns/question.json').success(function (d) {
            $scope.ques = d.questions;
            $scope.isQuestionData = false;
        });
        $scope.openQue = function ($qid) {
            $scope.selectedQID = $qid;
            $scope.selectedOpt = {};
//            $scope.$parent.selectedQuesID = $qid;
        };
        $scope.selectOpt = function (v) {};
        $scope.ok = function (e) {
            newCmp.selectedQID = $scope.selectedQID;
            newCmp.selectedOpt = $scope.selectedOpt;
            if (newCmp.questionType == "sms") {
                $http.post('/campaigns/required-credit-sms.json', newCmp).success(function (d) {
                    newCmp.answeredCst = d.result.answeredCst
                });
            } else {
                $http.post('/campaigns/required-credit-email.json', newCmp).success(function (d) {
                    newCmp.answeredCst = d.result.answeredCst
                });
            }

            $uibModalInstance.close();
        };
        $scope.cancel = function (e) {
            $uibModalInstance.dismiss();
        };
    }]);

app.controller('Campaigns.SmsCtrl', ['$scope', 'toaster', '$http', '$aside', '$timeout', 'SweetAlert','$uibModal', function ($scope, toaster, $http, $aside, $timeout, SweetAlert,$uibModal) {
        $scope.toaster = {
            type: 'info',
            title: '',
            text: $flasMsg
        };
        $scope.flash = function () {
            if ($flasMsg != null) {
                toaster.pop($scope.toaster.type, $scope.toaster.title, $scope.toaster.text);
                $flasMsg = null;
            }
        };
        $scope.currentPgNo = 1;
        $scope.campaigns = [];
        $scope.pager = null;
        $scope.isLoading = false;
        $scope.isSendingData = false;
        $scope.getCmp = function () {
            $scope.isLoading = true;
            $http.get("/campaigns/birthday-ann-sms.json?page=" + $scope.currentPgNo).success(function (data) {
                $scope.campaigns = data.campaigns;
                $scope.pager = data.pager;
                $scope.pager.totalPages = [];
                for (var i = 1; i <= data.pager.totalPg; i++) {
                    $scope.pager.totalPages.push({vl: i});
                }
                $scope.isLoading = false;
            });
        };
        $scope.getCmp();

        $scope.changePg = function (pg) {
            $scope.currentPgNo = pg;
            $scope.getCmp();
        };

        $scope.init = function () {
            $scope.status = true;
        };
        $scope.options = [
            {id: 1, name: 'Running'},
            {id: 0, name: 'Completed'},
            {id: 2, name: 'Paused'}
        ];
        $scope.changeStatus = function (v, id) {
            var res = $http.post("/campaigns/changeStatus.json", {id: id, status: v});
            res.success(function (data) {
                if (data.response.error == 0) {
                    toaster.pop('success', 'Updated...', data.response.msg);
                }
                if (data.response.error == 1) {
                    toaster.pop('error', 'Some issue occured...', data.response.msg);
                }
            });
        };

        $scope.smsCounterText = "";
        $scope.newCmp = {
            repeat_type: 'birthday',
            campaign_type: 'sms',
            send_before_day: 1,
            send_at: "09:00",
            cost_multiplier: 1
        };
        $scope.smsCounter = function (message, embedcode) {
            if (!message) {
                return false;
            }
            var v = message;
            var embedcode = embedcode;
            var cnSrtCd = v.match(/\{\{\w+}}/ig) || "";
            var ttlSrtLen = v.match(/\{\{\w+}}/ig) || "";
            ttlSrtLen = ttlSrtLen.toString().replace(/,/ig, "") || "";
            var unicodeCount = v.match(/([\u0900-\u097F])/igm);
            if (unicodeCount) {
                unicodeCount = v.match(/([\u0900-\u097F])|(\s)/igm).length;
            } else {
                unicodeCount = 0;
            }
            var c = v.length;
            if (ttlSrtLen != "") {
                c += cnSrtCd.length * 15;
                c -= ttlSrtLen.length;
            }
            if (embedcode) {
                c += 15;
            }
            if (unicodeCount > 0) {
                var smsCount = 1 + Math.ceil((c - (70 - 32)) / 66);
                var msg = "(SPECIAL CHARACTER SMS) Characters used: " + c + ",  Left: " + (625 - c) + "<br>SMS count: <span class='text-red text-bold'>" + smsCount + '</span>';
            } else {
                var smsCount = 1 + Math.ceil((c - (160 - 32)) / 153);
                var msg = "(PLAIN SMS) Characters used: " + c + ",  Left: " + (2500 - c) + "<br>SMS count: <span class='text-red text-bold'>" + smsCount + '</span>';
            }
            $scope.smsCounterText = msg;
            $scope.newCmp.cost_multiplier = smsCount;
        };

        $scope.$watch('newCmp.message', function () {
            $scope.smsCounter($scope.newCmp.message, $scope.newCmp.embedcode);
        });
        $scope.$watch('newCmp.embedcode', function () {
            $scope.smsCounter($scope.newCmp.message, $scope.newCmp.embedcode);
        });
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
                    return;

                } else {
                    $scope.isSendingData = true;
                    $http.post("/campaigns/birthday-ann-save.json", $scope.newCmp).success(function (d) {
                        if (d.result.error == 0) {
                            SweetAlert.success("Success!", d.result.msg, "success");
                            $scope.getCmp();
                            var master = {
                                repeat_type: 'birthday',
                                campaign_type: 'sms',
                                send_before_day: 1,
                                send_at: "09:00",
                                cost_multiplier: 1
                            };
                            $scope.newCmp = angular.copy(master);
                            form.$setPristine(true);
                        } else {
                            SweetAlert.error("Error!", d.result.msg, "error");
                        }
                        $scope.isSendingData = false;
                    }).error(function (e) {
                        SweetAlert.error("Some Network Issue!", "Please try once again...", "error");
                        $scope.isSendingData = false;
                    });
                    //your code for submit
                }
            }
        };
        $scope.openEditMdl = function (id,messageTxt) {
            var modalInstance = $uibModal.open({
                templateUrl: 'editMsgMdl.html',
                controller: 'EditModalCtrl',
                size: 'md',
                resolve: {
                    c_id: function(){
                        return id;
                    },
                    message_txt: function(){
                        return messageTxt;
                    }
                }
            });
        };
    }]);
app.controller('Campaigns.EmailCtrl', ['$scope', 'toaster', '$http', '$aside', '$timeout', 'SweetAlert','$uibModal', function ($scope, toaster, $http, $aside, $timeout, SweetAlert, $uibModal) {
        $scope.toaster = {
            type: 'info',
            title: '',
            text: $flasMsg
        };
        $scope.flash = function () {
            if ($flasMsg != null) {
                toaster.pop($scope.toaster.type, $scope.toaster.title, $scope.toaster.text);
                $flasMsg = null;
            }
        };
        $scope.currentPgNo = 1;
        $scope.campaigns = [];
        $scope.pager = null;
        $scope.isLoading = false;
        $scope.isSendingData = false;
        $scope.getCmp = function () {
            $scope.isLoading = true;
            $http.get("/campaigns/birthday-ann-email.json?page=" + $scope.currentPgNo).success(function (data) {
                $scope.campaigns = data.campaigns;
                $scope.pager = data.pager;
                $scope.pager.totalPages = [];
                for (var i = 1; i <= data.pager.totalPg; i++) {
                    $scope.pager.totalPages.push({vl: i});
                }
                $scope.isLoading = false;
            });
        };
        $scope.getCmp();

        $scope.changePg = function (pg) {
            $scope.currentPgNo = pg;
            $scope.getCmp();
        };

        $scope.init = function () {
            $scope.status = true;
        };
        $scope.options = [
            {id: 1, name: 'Running'},
            {id: 0, name: 'Completed'},
            {id: 2, name: 'Paused'}
        ];
        $scope.changeStatus = function (v, id) {
            var res = $http.post("/campaigns/changeStatus.json", {id: id, status: v});
            res.success(function (data) {
                if (data.response.error == 0) {
                    toaster.pop('success', 'Updated...', data.response.msg);
                }
                if (data.response.error == 1) {
                    toaster.pop('error', 'Some issue occured...', data.response.msg);
                }
            });
        };
        $scope.newCmp = {
            repeat_type: 'birthday',
            campaign_type: 'email',
            send_before_day: 1,
            send_at: "09:00"
        };
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
                    return;

                } else {
                    $scope.isSendingData = true;
                    $http.post("/campaigns/birthday-ann-save.json", $scope.newCmp).success(function (d) {
                        if (d.result.error == 0) {
                            SweetAlert.success("Success!", d.result.msg, "success");
                            $scope.getCmp();
                            var master = {
                                repeat_type: 'birthday',
                                campaign_type: 'email',
                                send_before_day: 1,
                                send_at: "09:00"
                            };
                            $scope.newCmp = angular.copy(master);
                            form.$setPristine(true);
                        } else {
                            SweetAlert.error("Error!", d.result.msg, "error");
                        }
                        $scope.isSendingData = false;
                    }).error(function (e) {
                        SweetAlert.error("Some Network Issue!", "Please try once again...", "error");
                        $scope.isSendingData = false;
                    });
                    //your code for submit
                }

            }
        };
        $scope.openEditMdl = function (id,messageTxt) {
            var modalInstance = $uibModal.open({
                templateUrl: 'editMsgMdl.html',
                controller: 'EditModalCtrl',
                size: 'md',
                resolve: {
                    c_id: function(){
                        return id;
                    },
                    message_txt: function(){
                        return messageTxt;
                    }
                }
            });
        };
        
    }]);

app.controller('Campaigns.EmailRegularCtrl', ['$scope', 'toaster', '$http', '$aside', '$timeout', 'SweetAlert','$uibModal', function ($scope, toaster, $http, $aside, $timeout, SweetAlert,$uibModal) {
        $scope.toaster = {
            type: 'info',
            title: '',
            text: $flasMsg
        };
        $scope.flash = function () {
            if ($flasMsg != null) {
                toaster.pop($scope.toaster.type, $scope.toaster.title, $scope.toaster.text);
                $flasMsg = null;
            }
        };
        $scope.currentPgNo = 1;
        $scope.campaigns = [];
        $scope.pager = null;
        $scope.isLoading = false;
        $scope.isSendingData = false;
        $scope.getCmp = function () {
            $scope.isLoading = true;
            $http.get('/campaigns/required-credit-email.json').success(function (d) {
                $scope.newCmp.allCst = d.result.allCst;
                $scope.newCmp.redeemedCst = d.result.redeemedCst;
            });
            $http.get("/campaigns/regularemail.json?page=" + $scope.currentPgNo).success(function (data) {
                $scope.campaigns = data.campaigns;
                $scope.pager = data.pager;
                $scope.pager.totalPages = [];
                for (var i = 1; i <= data.pager.totalPg; i++) {
                    $scope.pager.totalPages.push({vl: i});
                }
                $scope.isLoading = false;
            });
        };
        $scope.getCmp();

        $scope.changePg = function (pg) {
            $scope.currentPgNo = pg;
            $scope.getCmp();
        };

        $scope.init = function () {
            $scope.status = true;
        };
        $scope.options = [
            {id: 1, name: 'Running'},
            {id: 0, name: 'Completed'},
            {id: 2, name: 'Paused'}
        ];
        $scope.changeStatus = function (v, id) {
            var res = $http.post("/campaigns/changeStatus.json", {id: id, status: v});
            res.success(function (data) {
                if (data.response.error == 0) {
                    toaster.pop('success', 'Updated...', data.response.msg);
                }
                if (data.response.error == 1) {
                    toaster.pop('error', 'Some issue occured...', data.response.msg);
                }
            });
        };
        $scope.newCmp = {
            whos_send: 1,
            send_date: moment().add(2, 'hour').format('YYYY/MM/DD HH:mm'),
            exceptDefaulter: true,
            questionType: 'email',
            answeredCst: 0
        };
        $scope.$watch('newCmp.whos_send', function () {
            if ($scope.newCmp.whos_send == 3) {
                var asideInstance = $aside.open({
                    templateUrl: 'AsideQue.html',
                    controller: 'QuesAsideCtrl',
                    placement: 'right',
                    size: 'sm',
                    resolve: {
                        newCmp: $scope.newCmp
                    }
                });
            }
        });
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
                var master = {
                    whos_send: 1,
                    send_date: moment().add(2, 'hour').format('YYYY/MM/DD HH:mm'),
                    exceptDefaulter: true
                };
                $scope.newCmp = angular.copy(master);
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
                    $http.post("/campaigns/add-email/email.json", $scope.newCmp).success(function (d) {
                        if (d.result.error == 0) {
                            SweetAlert.success("Success!", d.result.msg, "success");
                            $scope.getCmp();
                            var master = {
                                whos_send: 1,
                                send_date: moment().add(2, 'hour').format('YYYY/MM/DD HH:mm'),
                                exceptDefaulter: true
                            };
                            $scope.newCmp = angular.copy(master);
                            form.$setPristine(true);
                        } else {
                            SweetAlert.error("Error!", d.result.msg, "error");
                        }
                        $scope.isSendingData = false;
                    }).error(function (e) {
                        SweetAlert.error("Some Network Issue!", "Please try once again...", "error");
                        $scope.isSendingData = false;
                    });
                    //your code for submit
                }
            }
        };
        $scope.openEditMdl = function (id,messageTxt) {
            var modalInstance = $uibModal.open({
                templateUrl: 'editMsgMdl.html',
                controller: 'EditModalCtrl',
                size: 'md',
                resolve: {
                    c_id: function(){
                        return id;
                    },
                    message_txt: function(){
                        return messageTxt;
                    }
                }
            });
        };
        
    }]);

app.controller('Campaigns.SmsRegularCtrl', ['$scope', 'toaster', '$http', '$aside', '$timeout', 'SweetAlert', '$uibModal', function ($scope, toaster, $http, $aside, $timeout, SweetAlert, $uibModal) {
        $scope.toaster = {
            type: 'info',
            title: '',
            text: $flasMsg
        };
        $scope.flash = function () {
            if ($flasMsg != null) {
                toaster.pop($scope.toaster.type, $scope.toaster.title, $scope.toaster.text);
                $flasMsg = null;
            }
        };

        $scope.currentPgNo = 1;
        $scope.campaigns = [];
        $scope.pager = null;
        $scope.isLoading = false;

        $scope.getCmp = function () {
            $scope.isLoading = true;
            $http.get('/campaigns/required-credit-sms.json').success(function (d) {
                $scope.newCmp.allCst = d.result.allCst;
                $scope.newCmp.redeemedCst = d.result.redeemedCst;
            });
            $http.get("/campaigns/regularsms.json?page=" + $scope.currentPgNo).success(function (data) {
                $scope.campaigns = data.campaigns;
                $scope.pager = data.pager;
                $scope.pager.totalPages = [];
                for (var i = 1; i <= data.pager.totalPg; i++) {
                    $scope.pager.totalPages.push({vl: i});
                }
                $scope.isLoading = false;
            });
        };
        $scope.getCmp();

        $scope.changePg = function (pg) {
            $scope.currentPgNo = pg;
            $scope.getCmp();
        };

        $scope.init = function () {
            $scope.status = true;
        };
        $scope.options = [
            {id: 1, name: 'Running'},
            {id: 0, name: 'Completed'},
            {id: 2, name: 'Paused'}
        ];
        $scope.tagLists = function(){
            var res = $http.post("/campaigns/tags.json");
            res.success(function (data) {
                $scope.tags = data;
            }).error(function (e) {
                
            });
        };
        $scope.tagLists();
        
        
        $scope.tagSources = function(){
            var res = $http.post("/campaigns/sources.json");
            res.success(function (data) {
                $scope.sources = data;
            }).error(function (e) {
                
            });
        };
        $scope.tagSources();
        
        $scope.changeStatus = function (v, id) {
            var res = $http.post("/campaigns/changeStatus.json", {id: id, status: v});
            res.success(function (data) {
                if (data.response.error == 0) {
                    toaster.pop('success', 'Updated...', data.response.msg);
                }
                if (data.response.error == 1) {
                    toaster.pop('error', 'Some issue occured...', data.response.msg);
                }
            });
        };
        $scope.newCmp = {
            whos_send: 0,
            send_date: moment().add(2, 'hour').format('YYYY/MM/DD HH:mm'),
            exceptDefaulter: true,
            answeredCst: 0,
            questionType: 'sms'
        };
        $scope.$watch('newCmp.whos_send', function () {
            if ($scope.newCmp.whos_send == 3) {
                var asideInstance = $aside.open({
                    templateUrl: 'AsideQue.html',
                    controller: 'QuesAsideCtrl',
                    placement: 'right',
                    size: 'sm',
                    resolve: {
                        newCmp: $scope.newCmp
                    }
                });
            }
        });
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
        $scope.smsCounterText = "";
        $scope.smsCounter = function (message, embedcode) {
            if (!message) {
                return false;
            }
            var v = message;
            var embedcode = embedcode;
            var cnSrtCd = v.match(/\{\{\w+}}/ig) || "";
            var ttlSrtLen = v.match(/\{\{\w+}}/ig) || "";
            ttlSrtLen = ttlSrtLen.toString().replace(/,/ig, "") || "";
            var unicodeCount = v.match(/([\u0900-\u097F])/igm);
            if (unicodeCount) {
                unicodeCount = v.match(/([\u0900-\u097F])|(\s)/igm).length;
            } else {
                unicodeCount = 0;
            }
            var c = v.length;
            if (ttlSrtLen != "") {
                c += cnSrtCd.length * 15;
                c -= ttlSrtLen.length;
            }
            if (embedcode) {
                c += 15;
            }
            if (unicodeCount > 0) {
                var smsCount = 1 + Math.ceil((c - (70 - 32)) / 66);
                var msg = "(SPECIAL CHARACTER SMS) Characters used: " + c + ",  Left: " + (625 - c) + "<br>SMS count: <span class='text-red text-bold'>" + smsCount + '</span>';
            } else {
                var smsCount = 1 + Math.ceil((c - (160 - 32)) / 153);
                var msg = "(PLAIN SMS) Characters used: " + c + ",  Left: " + (2500 - c) + "<br>SMS count: <span class='text-red text-bold'>" + smsCount + '</span>';
            }
            $scope.smsCounterText = msg;
        }
        $scope.$watch('newCmp.message', function () {
            $scope.smsCounter($scope.newCmp.message, $scope.newCmp.embedcode);
        });
        $scope.$watch('newCmp.embedcode', function () {
            $scope.smsCounter($scope.newCmp.message, $scope.newCmp.embedcode);
        });
        $scope.form = {
            reset: function (form) {
                var master = {
                    whos_send: 1,
                    send_date: moment().add(2, 'hour').format('YYYY/MM/DD HH:mm'),
                    exceptDefaulter: true
                };
                $scope.newCmp = angular.copy(master);
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
                    $http.post("/campaigns/add-sms/sms.json", $scope.newCmp).success(function (d) {
                        if (d.result.error == 0) {
                            SweetAlert.success("Success!", d.result.msg, "success");
                            $scope.getCmp();
                            var master = {
                                whos_send: 0,
                                send_date: moment().add(2, 'hour').format('YYYY/MM/DD HH:mm'),
                                exceptDefaulter: true
                            };
                            $scope.newCmp = angular.copy(master);
                            form.$setPristine(true);
                        } else {
                            SweetAlert.error("Error!", d.result.msg, "error");
                        }
                        $scope.isSendingData = false;
                    }).error(function (e) {
                        SweetAlert.error("Some Network Issue!", "Please try once again...", "error");
                        $scope.isSendingData = false;
                    });
                    //your code for submit
                }
            }
        };

        $scope.openEditMdl = function (id,messageTxt) {
            var modalInstance = $uibModal.open({
                templateUrl: 'editMsgMdl.html',
                controller: 'EditModalCtrl',
                size: 'md',
                resolve: {
                    c_id: function(){
                        return id;
                    },
                    message_txt: function(){
                        return messageTxt;
                    }
                }
            });
        };


    }]);


app.controller('Campaigns.RecommendCtrl', ['$scope', 'toaster', '$http', '$aside', '$timeout', 'SweetAlert', function ($scope, toaster, $http, $aside, $timeout, SweetAlert) {
        $scope.toaster = {
            type: 'info',
            title: '',
            text: $flasMsg
        };
        $scope.flash = function () {
            if ($flasMsg != null) {
                toaster.pop($scope.toaster.type, $scope.toaster.title, $scope.toaster.text);
                $flasMsg = null;
            }
        };
        $scope.currentPgNo = 1;
        $scope.campaigns = [];
        $scope.pager = null;
        $scope.isLoading = false;
        $scope.isSendingData = false;
        $scope.getCmp = function () {
            $scope.isLoading = true;
            $http.get("/campaigns/regularsms.json?page=" + $scope.currentPgNo).success(function (data) {
                $scope.campaigns = data.campaigns;
                $scope.pager = data.pager;
                $scope.pager.totalPages = [];
                for (var i = 1; i <= data.pager.totalPg; i++) {
                    $scope.pager.totalPages.push({vl: i});
                }
                $scope.isLoading = false;
            });
        };
        $scope.getCmp();

        $scope.changePg = function (pg) {
            $scope.currentPgNo = pg;
            $scope.getCmp();
        };

        $scope.init = function () {
            $scope.status = true;
        };
        $scope.options = [
            {id: 1, name: 'Running'},
            {id: 0, name: 'Completed'},
            {id: 2, name: 'Paused'}
        ];
        $scope.changeStatus = function (v, id) {
            var res = $http.post("/campaigns/changeStatus.json", {id: id, status: v});
            res.success(function (data) {
                if (data.response.error == 0) {
                    toaster.pop('success', 'Updated...', data.response.msg);
                }
                if (data.response.error == 1) {
                    toaster.pop('error', 'Some issue occured...', data.response.msg);
                }
            });
        };
        $scope.newCmp = {
            whos_send: 1,
            send_date: moment().add(2, 'hour').format('YYYY/MM/DD HH:mm'),
            exceptDefaulter: true
        };
        $scope.$watch('newCmp.whos_send', function () {
            if ($scope.newCmp.whos_send == 3) {
                var asideInstance = $aside.open({
                    templateUrl: 'AsideQue.html',
                    controller: 'QuesAsideCtrl',
                    placement: 'right',
                    size: 'sm',
                    resolve: {
                        newCmp: $scope.newCmp
                    }
                });
            }
        });
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
        $scope.smsCounterText = "";
        $scope.smsCounter = function (message, embedcode) {
            if (!message) {
                return false;
            }
            var v = message;
            var embedcode = embedcode;
            var cnSrtCd = v.match(/\{\{\w+}}/ig) || "";
            var ttlSrtLen = v.match(/\{\{\w+}}/ig) || "";
            ttlSrtLen = ttlSrtLen.toString().replace(/,/ig, "") || "";
            var unicodeCount = v.match(/([\u0900-\u097F])/igm);
            if (unicodeCount) {
                unicodeCount = v.match(/([\u0900-\u097F])|(\s)/igm).length;
            } else {
                unicodeCount = 0;
            }
            var c = v.length;
            if (ttlSrtLen != "") {
                c += cnSrtCd.length * 15;
                c -= ttlSrtLen.length;
            }
            if (embedcode) {
                c += 15;
            }
            if (unicodeCount > 0) {
                var smsCount = 1 + Math.ceil((c - (70 - 32)) / 66);
                var msg = "(SPECIAL CHARACTER SMS) Characters used: " + c + ",  Left: " + (625 - c) + "<br>SMS count: <span class='text-red text-bold'>" + smsCount + '</span>';
            } else {
                var smsCount = 1 + Math.ceil((c - (160 - 32)) / 153);
                var msg = "(PLAIN SMS) Characters used: " + c + ",  Left: " + (2500 - c) + "<br>SMS count: <span class='text-red text-bold'>" + smsCount + '</span>';
            }
            $scope.smsCounterText = msg;
        }
        $scope.$watch('newCmp.message', function () {
            $scope.smsCounter($scope.newCmp.message, $scope.newCmp.embedcode);
        });
        $scope.$watch('newCmp.embedcode', function () {
            $scope.smsCounter($scope.newCmp.message, $scope.newCmp.embedcode);
        });
        $scope.form = {
            reset: function (form) {
                var master = {
                    whos_send: 1,
                    send_date: moment().add(2, 'hour').format('YYYY/MM/DD HH:mm'),
                    exceptDefaulter: true
                };
                $scope.newCmp = angular.copy(master);
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
                    $http.post("/campaigns/add-sms/sms.json", $scope.newCmp).success(function (d) {
                        if (d.result.error == 0) {
                            SweetAlert.success("Success!", d.result.msg, "success");
                            $scope.getCmp();
                            var master = {
                                whos_send: 1,
                                send_date: moment().add(2, 'hour').format('YYYY/MM/DD HH:mm'),
                                exceptDefaulter: true
                            };
                            $scope.newCmp = angular.copy(master);
                            form.$setPristine(true);
                        } else {
                            SweetAlert.error("Error!", d.result.msg, "error");
                        }
                        $scope.isSendingData = false;
                    }).error(function (e) {
                        SweetAlert.error("Some Network Issue!", "Please try once again...", "error");
                        $scope.isSendingData = false;
                    });
                    //your code for submit
                }
            }
        };
    }]);


app.controller('AnalyticsCtrlSelectCMP', ['$scope', '$http', '$uibModalInstance', '$parentScope', function ($scope, $http, $uibModalInstance, $parentScope) {
        $scope.list = $parentScope.cmpList;
        $scope.orderList = 'compaign_name';
        $scope.selectedOpt = $parentScope.selectedOpt;
        $scope.ok = function (e) {
//        newCmp.selectedQID = $scope.selectedQID;
//        newCmp.selectedOpt = $scope.selectedOpt;
            $uibModalInstance.close();
        };
        $scope.cancel = function (e) {
            $uibModalInstance.dismiss();
        };
    }]);
app.controller('Campaigns.AnalyticsCtrl', ['$scope', 'toaster', '$http', '$aside', '$timeout', 'SweetAlert', function ($scope, toaster, $http, $aside, $timeout, SweetAlert) {


        $scope.cmpList = [];
        $scope.selectedOpt = {};
        $scope.ids = [];
        $scope.range = '';
        $scope.chartData;
        $scope.ch1 = {
            labels: ['Conversion Rate'],
            series: [],
            data: [],
            options: {
//                legendTemplate : "dsfsd"
            },
            type: 'Bar',
            toggle: function () {
                $scope.ch1.type = $scope.ch1.type === 'Bar' ? 'Line' : 'Bar';
            }
        };

        $scope.ch2 = {
            labels: ['Overall', '18-24 yrs.', '25-30 yrs.', '30-40 yrs.', '40+ yrs.'],
            series: [],
            data: [],
            options: {
//                tooltipTemplate: "<%if (label){%><%=label%>: <%}%><%= value %>%",
                legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"
            },
            type: 'Bar',
            toggle: function () {
                $scope.ch2.type = $scope.ch2.type === 'Bar' ? 'Line' : 'Bar';
            }
        };

        $scope.ch3 = {
            labels: ['Overall', 'Male', 'Female'],
            series: [],
            data: [],
            options: {
//                tooltipTemplate: "<%if (label){%><%=label%>: <%}%><%= value %>%",
                legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"
            },
            type: 'Bar',
            toggle: function () {
                $scope.ch3.type = $scope.ch3.type === 'Bar' ? 'Line' : 'Bar';
            }
        };

        $scope.ch4 = {
            labels: ['Avg. conversion days'],
            series: [],
            data: [],
            options: {
//                tooltipTemplate: "<%if (label){%><%=label%>: <%}%><%= value %>%",
                legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"
            },
            type: 'Bar',
            toggle: function () {
                $scope.ch4.type = $scope.ch4.type === 'Bar' ? 'Line' : 'Bar';
            }
        };


        $scope.selectedCmpns = function () {
            var i;
            var result = [];
            for (i in $scope.selectedOpt) {
                if ($scope.selectedOpt[i] == true) {
                    result.push(i);
                }
            }
            $result = $.grep($scope.cmpList,function(a){return $.inArray(a.id.toString(),result) > -1;});
            return $result;
        };

        $scope.getCmpList = function () {
            $http.get('/campaigns/analytics.json').success(function (d) {
                $scope.cmpList = d.campaigns;
                for (var j = 0; j < d.campaigns.length; j++) {
                    if (j == 4)
                        break;
                    $scope.selectedOpt[d.campaigns[j].id] = true;
                }
            });
        };
        Chart.defaults.global.tooltipTemplate = "Conversion Rate: <%= value %>%";
        Chart.defaults.global.multiTooltipTemplate = "<%= datasetLabel %>: <%= value %>%";
        $scope.getData = function () {
            var ids = [], i, cmps = $scope.selectedCmpns();
            for (i in cmps) {
                ids.push(cmps[i].id);
            }
            try{
                clearTimeout($scope.ajaxTimer);
            }catch(e){}
            $scope.ajaxTimer = setTimeout(function(){
                $http.post('/campaigns/analytics.json', {ids: ids, range: $scope.range}).success(function (d) {
                    $scope.chartData = d;
                    $scope.ch1.series = $.map(d.dataChart1, function (a) {
                        return a.name
                    });
                    $scope.ch1.data = $.map(d.dataChart1, function (a) {
                        return [a.data]
                    });

                    $scope.ch2.series = $.map(d.ageWiseCmpData, function (a) {
                        return a.name
                    });
                    $scope.ch2.data = $.map(d.ageWiseCmpData, function (a) {
                        return [a.data]
                    });

                    $scope.ch3.series = $.map(d.genderWiseCmpData, function (a) {
                        return a.name
                    });
                    $scope.ch3.data = $.map(d.genderWiseCmpData, function (a) {
                        return [a.data]
                    });


    //                $scope.ch4.labels = $.map(d.timeWiseCmpData,function(a){return a.label});
                    $scope.ch4.series = $.map(d.timeWiseCmpData, function (a) {
                        return a.name
                    });
                    var x = [];
                    $.map(d.timeWiseCmpData, function (a) {
                        x.push([a.data[0]])
                    });
                    $scope.ch4.data = x;
                });
            },2000);
            
        };
        $scope.selectCmpns = function () {
            var asideInstance = $aside.open({
                templateUrl: 'AnalyticsCtrlSelectCMP.html',
                controller: 'AnalyticsCtrlSelectCMP',
                placement: 'right',
                size: 'sm',
                resolve: {
                    $parentScope: $scope
                }
            });
        };
        $scope.$watch('selectedOpt', function (n, o, s) {
            $scope.getData();
        }, true);
        $scope.getData();
        $scope.getCmpList();

        $("#datarangeSel").daterangepicker({
            datepickerOptions: {
                numberOfMonths: 2,
                initialText: 'Select DateRange...'
            },
            onChange: function (val) {
                var range = JSON.parse($('#datarangeSel').val());
                $scope.range = JSON.stringify(range);
                $scope.getData();
            }
        });
    }]);
app.controller('Campaigns.Refered', ['$scope', 'toaster', '$http', '$aside', '$timeout', 'SweetAlert', function ($scope, toaster, $http, $aside, $timeout, SweetAlert) {
        $scope.toaster = {
            type: 'info',
            title: '',
            text: $flasMsg
        };
        $scope.flash = function () {
            if ($flasMsg != null) {
                toaster.pop($scope.toaster.type, $scope.toaster.title, $scope.toaster.text);
                $flasMsg = null;
            }
        };
        $scope.currentPgNo = 1;
        $scope.messages = [];
        $scope.isLoading = false;
        $scope.isSendingData = false;
        $scope.getReferedCmp = function () {
            $scope.isLoading = true;
            $http.get("/campaigns/refered-list.json?page=" + $scope.currentPgNo).success(function (data) {
                $scope.referdCmp.message = data.messages[0]._matchingData.Campaigns.message;
                $scope.messages = data.messages;
                $scope.isLoading = false;
            });
        };
        $scope.getReferedCmp();
        $scope.init = function () {
            $scope.status = true;
        };
        $scope.referdCmp = {};
        $scope.smsCounterText = "";
        $scope.smsCounter = function (message, embedcode) {
            if (!message) {
                return false;
            }
            var v = message;
            var embedcode = embedcode;
            var cnSrtCd = v.match(/\{\{\w+}}/ig) || "";
            var ttlSrtLen = v.match(/\{\{\w+}}/ig) || "";
            ttlSrtLen = ttlSrtLen.toString().replace(/,/ig, "") || "";
            var unicodeCount = v.match(/([\u0900-\u097F])/igm);
            if (unicodeCount) {
                unicodeCount = v.match(/([\u0900-\u097F])|(\s)/igm).length;
            } else {
                unicodeCount = 0;
            }
            var c = v.length;
            if (ttlSrtLen != "") {
                c += cnSrtCd.length * 15;
                c -= ttlSrtLen.length;
            }
            if (unicodeCount > 0) {
                var smsCount = 1 + Math.ceil((c - (70 - 32)) / 66);
                var msg = "(SPECIAL CHARACTER SMS) Characters used: " + c + ",  Left: " + (625 - c) + "<br>SMS count: <span class='text-red text-bold'>" + smsCount + '</span>';
            } else {
                var smsCount = 1 + Math.ceil((c - (160 - 32)) / 153);
                var msg = "(PLAIN SMS) Characters used: " + c + ",  Left: " + (2500 - c) + "<br>SMS count: <span class='text-red text-bold'>" + smsCount + '</span>';
            }
            $scope.smsCounterText = msg;
        }
        $scope.$watch('referdCmp.message', function () {
            $scope.smsCounter($scope.referdCmp.message, $scope.referdCmp.embedcode);
        });
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
                    return;

                } else {
                    $scope.isSendingData = true;
                    $http.post("/campaigns/refered.json", $scope.referdCmp).success(function (d) {
                        if (d.result.error == 0) {
                            SweetAlert.success("Success!", d.result.msg, "success");
                            $scope.getReferedCmp();
                            $scope.referdCmp = angular.copy(master);
                            form.$setPristine(true);
                        } else {
                            SweetAlert.error("Error!", d.result.msg, "error");
                        }
                        $scope.isSendingData = false;
                    }).error(function (e) {
                        SweetAlert.error("Some Network Issue!", "Please try once again...", "error");
                        $scope.isSendingData = false;
                    });
                    //your code for submit
                }
            }
        };
    }]);

app.controller('Campaigns.LastvisitCtrl', ['$scope', 'toaster', '$http', '$aside', '$timeout', 'SweetAlert', function ($scope, toaster, $http, $aside, $timeout, SweetAlert) {
        $scope.toaster = {
            type: 'info',
            title: '',
            text: $flasMsg
        };
        $scope.flash = function () {
            if ($flasMsg != null) {
                toaster.pop($scope.toaster.type, $scope.toaster.title, $scope.toaster.text);
                $flasMsg = null;
            }
        };
        $scope.currentPgNo = 1;
        $scope.campaigns = [];
        $scope.pager = null;
        $scope.isLoading = false;
        $scope.getCmp = function () {
            $scope.isLoading = true;
            $http.get("/campaigns/lastvisit.json?page=" + $scope.currentPgNo).success(function (data) {
                $scope.campaigns = data.campaigns;
                $scope.isLoading = false;
            });
        };
        $scope.getCmp();

        $scope.changePg = function (pg) {
            $scope.currentPgNo = pg;
            $scope.getCmp();
        };

        $scope.init = function () {
            $scope.status = true;
        };
        $scope.options = [
            {id: 1, name: 'Running'},
            {id: 0, name: 'Completed'},
            {id: 2, name: 'Paused'}
        ];
        $scope.changeStatus = function (v, id) {
            var res = $http.post("/campaigns/changeStatus.json", {id: id, status: v});
            res.success(function (data) {
                if (data.response.error == 0) {
                    toaster.pop('success', 'Updated...', data.response.msg);
                }
                if (data.response.error == 1) {
                    toaster.pop('error', 'Some issue occured...', data.response.msg);
                }
            });
        };

        $scope.smsCounterText = "";
        $scope.newCmp = {
            repeat_type: 'birthday',
            campaign_type: 'sms',
            send_before_day: 30,
            send_at: "09:00",
            cost_multiplier: 1
        };
        $scope.smsCounter = function (message, embedcode) {
            if (!message) {
                return false;
            }
            var v = message;
            var embedcode = embedcode;
            var cnSrtCd = v.match(/\{\{\w+}}/ig) || "";
            var ttlSrtLen = v.match(/\{\{\w+}}/ig) || "";
            ttlSrtLen = ttlSrtLen.toString().replace(/,/ig, "") || "";
            var unicodeCount = v.match(/([\u0900-\u097F])/igm);
            if (unicodeCount) {
                unicodeCount = v.match(/([\u0900-\u097F])|(\s)/igm).length;
            } else {
                unicodeCount = 0;
            }
            var c = v.length;
            if (ttlSrtLen != "") {
                c += cnSrtCd.length * 15;
                c -= ttlSrtLen.length;
            }
            if (embedcode) {
                c += 15;
            }
            if (unicodeCount > 0) {
                var smsCount = 1 + Math.ceil((c - (70 - 32)) / 66);
                var msg = "(SPECIAL CHARACTER SMS) Characters used: " + c + ",  Left: " + (625 - c) + "<br>SMS count: <span class='text-red text-bold'>" + smsCount + '</span>';
            } else {
                var smsCount = 1 + Math.ceil((c - (160 - 32)) / 153);
                var msg = "(PLAIN SMS) Characters used: " + c + ",  Left: " + (2500 - c) + "<br>SMS count: <span class='text-red text-bold'>" + smsCount + '</span>';
            }
            $scope.smsCounterText = msg;
            $scope.newCmp.cost_multiplier = smsCount;
        };

        $scope.$watch('newCmp.message', function () {
            $scope.smsCounter($scope.newCmp.message, $scope.newCmp.embedcode);
        });
        $scope.$watch('newCmp.embedcode', function () {
            $scope.smsCounter($scope.newCmp.message, $scope.newCmp.embedcode);
        });
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
                    return;

                } else {
                    $scope.isLoading = true;
                    $http.post("/campaigns/lastvisit.json", $scope.newCmp).success(function (d) {
                        if (d.result.error == 0) {
                            SweetAlert.success("Success!", d.result.msg, "success");
                            $scope.getCmp();
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
