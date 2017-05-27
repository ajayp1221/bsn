
/** 
 * controller for Templatemessages
 */

app.controller('templatemessagesCtrl.IndexCtrl', ['$scope', 'toaster', '$http', '$aside', '$timeout', 'SweetAlert', function ($scope, toaster, $http, $aside, $timeout, SweetAlert,FileUploader) {
        $scope.newTmpltmsgs = {
            'welcome-message':'',
            'welcome-message-repeat': '',
            'welcome-message-screen-text': '',
            'discount-on-next-visit':''
        };
        $scope.getData = function () {
            $scope.isSendingData = true;
            $http.get('/templatemessages.json').success(function (d) {
                if(d["welcome-message"]){
                    $scope.newTmpltmsgs["welcome-message"] = d["welcome-message"][0].message;
                }
                if(d["welcome-message-repeat"]){
                    $scope.newTmpltmsgs["welcome-message-repeat"] = d["welcome-message-repeat"][0].message;
                }
                if(d["welcome-message-screen-text"]){
                    $scope.newTmpltmsgs["welcome-message-screen-text"] = d["welcome-message-screen-text"][0].message;
                }
                if(d["discount-on-next-visit"]){
                    $scope.newTmpltmsgs["discount-on-next-visit"] = d["discount-on-next-visit"][0].message;
                }
                $scope.isSendingData = false;
            });
        };
        $scope.getData();
        $scope.smsCounterText1 = "";
        $scope.smsCounterText2 = "";
        $scope.smsCounter = function (message,smsCounterText) {
            if (!message) {
                return false;
            }
            var v = message;
            var cnSrtCd = v.match(/\{\{\w+}}/ig) || "";
            var ttlSrtLen = v.match(/\{\{\w+}}/ig) || "";
            ttlSrtLen = ttlSrtLen.toString().replace(/,/ig, "") || "";
            console.log(cnSrtCd, ttlSrtLen);
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
            if(smsCounterText == "smsCounterText1"){
                $scope.smsCounterText1 = msg;
            }
            if(smsCounterText == "smsCounterText2"){
                $scope.smsCounterText2 = msg;
            }
            if(smsCounterText == "smsCounterText3"){
                $scope.smsCounterText3 = msg;
            }
            
            $scope.newTmpltmsgs.cost_multiplier = smsCount;
        };
        
        
        $scope.$watch("newTmpltmsgs['welcome-message']", function () {
            $scope.smsCounter($scope.newTmpltmsgs['welcome-message'],'smsCounterText1');
        });
        $scope.$watch("newTmpltmsgs['welcome-message-repeat']", function () {
            $scope.smsCounter($scope.newTmpltmsgs['welcome-message-repeat'],'smsCounterText2');
        });
        $scope.$watch("newTmpltmsgs['discount-on-next-visit']", function () {
            $scope.smsCounter($scope.newTmpltmsgs['discount-on-next-visit'],'smsCounterText3');
        });

        $scope.form = {
            reset: function (form) {
                form.$setPristine(true);
            },
            submit: function (form) {
                var firstError = null;
                    $scope.isSendingData = true;
                    $http.post("/templatemessages/add.json", $scope.newTmpltmsgs).success(function (d) {
                        console.log(d);
                        if (d.result.error == 0) {
                            SweetAlert.success("Success!", d.result.msg, "success");
                            $scope.getData();
                            form.$setPristine(true);
                        } else {
                            SweetAlert.error("Error!", d.result.msg, "error");
                        }
                        $scope.isSendingData = false;
                    }).error(function (e) {
                        SweetAlert.error("Some Network Issue!", "Please try once again...", "error");
                        $scope.isSendingData = false;
                    });
            }
        };
        console.log($scope);
}]);
