/** 
 * controller for Clients
 */
app.controller('Pages.DashboardCtrl', ['$scope', 'toaster', '$http', function ($scope, toaster, $http) {
        $scope.blockOneLoading = false;
        $scope.blockTwoLoading = false;
        $scope.blockThreeLoading = false;
        $scope.blockFourLoading = false;

        $scope.blockOneChart = {};
        $scope.blockOneChart.labels = ['SMS', 'Email'];
        $scope.blockOneChart.series = ['Left', 'Sent'];
        $scope.blockOneChart.data = [[8500, 594], [282, 484]];
        $scope.blockOneChart.options = {
            scales: {
                xAxes: [{
                        stacked: true
                    }],
                yAxes: [{
                        stacked: true
                    }]
            },
            responsive: true,
            maintainAspectRatio: false,
            scaleFontFamily: "'Helvetica', 'Arial', sans-serif",
            scaleFontSize: 11
        };
        
        
        $scope.blockTwoChart = {};
        $scope.blockTwoChart.labels = ['Campaigns'];
        $scope.blockTwoChart.series = [''];
        $scope.blockTwoChart.data = [[85],[96],[82],[70]];
        $scope.blockTwoChart.options = {
            scales: {
                xAxes: [{
                        stacked: true
                    }],
                yAxes: [{
                        stacked: true
                    }]
            },
            tooltipTemplate: "<%if (label){%><%=label%>: <%}%><%= value %> messages sent",
            responsive: true,
            maintainAspectRatio: false,
            scaleFontFamily: "'Helvetica', 'Arial', sans-serif",
            scaleFontSize: 11
        };
        
        $scope.blockThreeChart = {};
        $scope.blockThreeChart.opted = 0;
        $scope.blockThreeChart.value = 0;
        
        
        $scope.blockFourChart = {};
        $scope.blockFourChart.labels = ['Visits'];
        $scope.blockFourChart.series = ['Customers Visited'];
        $scope.blockFourChart.data = [[85],[96],[82],[70]];
        $scope.blockFourChart.options = {
            scales: {
                xAxes: [{
                        stacked: true
                    }],
                yAxes: [{
                        stacked: true
                    }]
            },
            tooltipTemplate: "<%if (label){%><%=label%>: <%}%><%= value %> customer",
            responsive: true,
            maintainAspectRatio: false,
            scaleFontFamily: "'Helvetica', 'Arial', sans-serif",
            scaleFontSize: 11
        };
        $scope.loadBlockOne = function(){
            $scope.blockOneLoading = true;
            $http.get('/dashboard/block-one.json').success(function(d){
                $scope.blockOneLoading = true;
                $scope.blockOneChart.data = [[d.data.sms.total, d.data.email.total], [d.data.sms.sent, d.data.email.sent]];
            }).error(function(){});
        };
        
        $scope.loadBlockTwo = function(){
            $scope.blockTwoLoading = true;
            $http.get('/dashboard/block-two.json').success(function(d){
                $scope.blockTwoLoading = false;
                $scope.blockTwoChart.labels = $.map(d.data,function(item,index){return item.compaign_name});
                $scope.blockTwoChart.data = [$.map(d.data,function(item,index){return item.total_msg; })];
            }).error(function(){});
        };
        $scope.loadBlockThree = function(){
            $scope.blockThreeLoading = true;
            $http.get('/dashboard/block-three.json').success(function(d){
                $scope.blockThreeLoading = false;
                $scope.blockThreeChart.value = d.data.total_customers;
                $scope.blockThreeChart.opted = d.data.opted_customers;
            }).error(function(){});
        };
        $scope.loadBlockFour = function(){
            $scope.blockFourLoading = true;
            $http.get('/dashboard/block-four.json').success(function(d){
                $scope.blockFourLoading = false;
                $scope.blockFourChart.labels = $.map(d.data,function(item,index){return index});
                $scope.blockFourChart.data = [$.map(d.data,function(item,index){return item; })];
            }).error(function(){});
        };
        
        $scope._init = function(){
            $scope.loadBlockOne();
            $scope.loadBlockTwo();
            $scope.loadBlockFour();
            $scope.loadBlockThree();
        };
        $scope._init();
        console.log($scope);

    }]);
