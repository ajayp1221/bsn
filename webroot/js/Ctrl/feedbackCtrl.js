
/** 
 * controller for Campaigns
 */

app.controller('feedbackCtrl.Index', ['$scope', '$http', '$aside', '$timeout','SweetAlert', function ($scope, $http, $aside, $timeout,SweetAlert) {
    $scope.feedbacks = [];
    $scope.isLoading = false;
    $scope.getFeedback = function () {
        $scope.isLoading = true;
        $http.get('/feedback/publish.json').success(function (d) {
            $scope.feedbacks = d.data;
            $scope.isLoading = false;
        });
    };
    $scope.getFeedback();
    $scope.publish = function (Indx) {
        $scope.ids = [];
        
//        angular.forEach(Indx, function(value, key){
//           $scope.ids.push(value.id);
//        });

        $scope.ids.push(Indx.id);
        $http.post("/feedback/posttozkp.json",$scope.ids).success(function(d){
            if(d.response.error == 0){
                SweetAlert.success("Success!", d.response.msg, "success");
                $scope.getFeedback();
            }else{
                SweetAlert.error("Error!", d.response.msg, "error");
            }
        });
    };
}]);
