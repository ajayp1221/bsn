
/** 
 * controller for Customer Feedback Analytics
 */

app.controller('Analytics.NpsCtrl', ['$scope', '$http', 'toaster', 'SweetAlert', function ($scope, $http, toaster, SweetAlert) {
        Chart.defaults.global.responsive = true;
        Chart.defaults.global.scaleFontSize = 8;

        $scope.data = {};
        $scope.range = null;
        $scope.npsSummary = {
            totalCount: 0,
            actualNPS: 0,
            npsColor: '#555555'
        };
        $scope.isLoading = false;
        $scope.topChart = {
            type: 'Line',
            data: [
                [],
                [],
                []
            ],
            series: ["Promoters", "Passive", "Demoters"],
            labels: [],
            options: []
        };
        $scope.prChart = {
            type: 'Line',
            data: [
                []
            ],
            colors: [{
                    fillColor: 'rgba(0,0,0,0)',
                    strokeColor: 'rgba(0,0,0,0.2)'
                }],
            series: ['dataset'],
            labels: [],
            options: {
                maintainAspectRatio: false,
                showScale: false,
                scaleLineWidth: 0,
                responsive: true,
                scaleFontFamily: "'Helvetica', 'Arial', sans-serif",
                scaleFontSize: 11,
                scaleFontColor: "#aaa",
                scaleShowGridLines: true,
                tooltipFontSize: 11,
                tooltipFontFamily: "'Helvetica', 'Arial', sans-serif",
                tooltipTitleFontFamily: "'Helvetica', 'Arial', sans-serif",
                tooltipTitleFontSize: 12,
                scaleGridLineColor: 'rgba(0,0,0,.05)',
                scaleGridLineWidth: 1,
                bezierCurve: false,
                bezierCurveTension: 0.2,
                scaleLineColor: 'transparent',
                scaleShowVerticalLines: false,
                pointDot: false,
                pointDotRadius: 4,
                pointDotStrokeWidth: 1,
                pointHitDetectionRadius: 20,
                datasetStroke: true,
                datasetStrokeWidth: 2,
                datasetFill: true,
                animationEasing: "easeInOutExpo"
            }
        };
        $scope.psChart = {
            type: 'Line',
            data: [
                []
            ],
            colors: [{
                    fillColor: 'rgba(0,0,0,0)',
                    strokeColor: 'rgba(0,0,0,0.2)'
                }],
            series: ['dataset'],
            labels: []
        };
        $scope.deChart = {
            type: 'Line',
            data: [
                []
            ],
            colors: [{
                    fillColor: 'rgba(0,0,0,0)',
                    strokeColor: 'rgba(0,0,0,0.2)'
                }],
            series: ['dataset'],
            labels: []
        };

        $scope.genderKnobs = {
            knob1: 2,
            knob2: 2,
            knob3: 2,
            knText: '',
            options: {
                unit: "%",
                readOnly: true,
                size: 70,
                fontSize: '11px',
                textColor: '#fff',
                trackWidth: 5,
                barWidth: 10,
                trackColor: 'rgba(255,255,255,0.4)',
                barColor: '#fff',
                max: 100
            }
        };
        $scope.ageKnobs = {
            knob1: 2,
            knob2: 2,
            knob3: 2,
            knob4: 2,
            knText: '',
            options: {
                unit: "%",
                readOnly: true,
                size: 70,
                fontSize: '11px',
                textColor: '#fff',
                trackWidth: 5,
                barWidth: 10,
                trackColor: 'rgba(255,255,255,0.4)',
                barColor: '#FFF',
                max: 100
            }
        };


        $scope.categoriesHeads = null;
        $scope.getNpsData = function () {
            var qr = '';
            if ($scope.range) {
                qr = '?range=' + $scope.range;
            }
            $scope.isLoading = true;
            $http.post("/feedback/nps.json" + qr).success(function (d) {
                var rt1 = $.map(d.data.promoters.data, function (v, k) {
                    return k
                });
                var rt2 = $.map(d.data.demoters.data, function (v, k) {
                    return k
                });
                var rt3 = $.map(d.data.passive.data, function (v, k) {
                    return k
                });
                var $r = $.merge(rt1, rt2);
                $r = $.merge($r, rt3);
                $r = $.unique($r.sort());
                $scope.topChart.labels = $.map(d.data.demoters.data, function (v, k) {
                    return k;
                });
                if (d.data.promoters.data.length > d.data.demoters.data.length && d.data.promoters.data.length > d.data.passive.data.length) {
                    $scope.topChart.labels = $.map(d.data.promoters.data, function (v, k) {
                        return k
                    });
                }
                if (d.data.demoters.data.length > d.data.promoters.data.length && d.data.demoters.data.length > d.data.passive.data.length) {
                    $scope.topChart.labels = $.map(d.data.demoters.data, function (v, k) {
                        return k
                    });
                }
                if (d.data.passive.data.length > d.data.promoters.data.length && d.data.passive.data.length > d.data.demoters.data.length) {
                    $scope.topChart.labels = $.map(d.data.passive.data, function (v, k) {
                        return k
                    });
                }
                $scope.topChart.labels = $r;

                $scope.topChart.data[0] = $.map($r, function (v, k) {
                    var c = d.data.promoters.data[v], i;
                    if (!c) {
                        return 0;
                    }
                    var l = 0;
                    for (i in c) {
                        l++;
                    }
                    return l;
                });
                $scope.topChart.data[1] = $.map($r, function (v, k) {
                    var c = d.data.passive.data[v], i;
                    if (!c) {
                        return 0;
                    }
                    var l = 0;
                    for (i in c) {
                        l++;
                    }
                    return l;
                });
                $scope.topChart.data[2] = $.map($r, function (v, k) {
                    var c = d.data.demoters.data[v], i;
                    if (!c) {
                        return 0;
                    }
                    var l = 0;
                    for (i in c) {
                        l++;
                    }
                    return l;
                });

                $scope.psChart.data[0] = angular.copy($scope.topChart.data[0]).slice(0, 17);
                $scope.psChart.labels = angular.copy($r).slice(0, 17);
                $scope.prChart.data[0] = angular.copy($scope.topChart.data[1]).slice(0, 17);
                $scope.prChart.labels = angular.copy($r).slice(0, 17);
                $scope.deChart.data[0] = angular.copy($scope.topChart.data[2]).slice(0, 17);
                $scope.deChart.labels = angular.copy($r).slice(0, 17);


                $scope.npsSummary = {
                    totalCount: d.data.totalcounts,
                    promoters: Math.round(d.data.promoters.count / (d.data.totalcounts == 0 ? 1 : d.data.totalcounts) * 100, 0),
                    passive: Math.round(d.data.passive.count / (d.data.totalcounts == 0 ? 1 : d.data.totalcounts) * 100, 0),
                    demoters: Math.round(d.data.demoters.count / (d.data.totalcounts == 0 ? 1 : d.data.totalcounts) * 100, 0)
                };

                $scope.npsSummary.actualNPS = $scope.npsSummary.promoters - $scope.npsSummary.demoters;

                if ($scope.npsSummary.actualNPS <= -50) {
                    $scope.npsSummary.npsColor = '#D2070D';
                } else if ($scope.npsSummary.actualNPS <= -50) {
                    $scope.npsSummary.npsColor = '#f46523';
                }
                if ($scope.npsSummary.actualNPS <= -50) {
                    $scope.npsSummary.npsColor = '#555555';
                }
                if ($scope.npsSummary.actualNPS <= -50) {
                    $scope.npsSummary.npsColor = '#0FA11A';
                } else {
                    $scope.npsSummary.npsColor = '#01582B';
                }


                $scope.data = d.data;

                var genderData = d.data.gender;
                var ageData = d.data.age;
                setTimeout(function () {
                    $scope.genderKnobs.knob1 = (genderData.female.promoters * 100 / genderData.female.total) | 0;
                    $scope.genderKnobs.knob2 = (genderData.male.promoters * 100 / genderData.male.total) | 0;
                    $scope.genderKnobs.knob3 = (genderData.unknown.promoters * 100 / genderData.unknown.total) | 0;
                    if ($scope.genderKnobs.knob1 > $scope.genderKnobs.knob2 && $scope.genderKnobs.knob1 > $scope.genderKnobs.knob3) {
                        $scope.genderKnobs.knText = "of your Female customers are promoters";
                        $scope.genderKnobs.knVal = $scope.genderKnobs.knob1;
                    } else if ($scope.genderKnobs.knob2 > $scope.genderKnobs.knob1 && $scope.genderKnobs.knob2 > $scope.genderKnobs.knob3) {
                        $scope.genderKnobs.knText = "of your Male customers are promoters";
                        $scope.genderKnobs.knVal = $scope.genderKnobs.knob2;
                    } else if ($scope.genderKnobs.knob3 > $scope.genderKnobs.knob1 && $scope.genderKnobs.knob3 > $scope.genderKnobs.knob2) {
                        $scope.genderKnobs.knText = "of your Unspecified customers are promoters";
                        $scope.genderKnobs.knVal = $scope.genderKnobs.knob3;
                    } else {
                        $scope.genderKnobs.knText = '';
                    }


                    $scope.ageKnobs.knob1 = (ageData['18t24'].promoters * 100 / ageData['18t24'].total) | 0;
                    $scope.ageKnobs.knob2 = (ageData['25t32'].promoters * 100 / ageData['25t32'].total) | 0;
                    $scope.ageKnobs.knob3 = (ageData['33t40'].promoters * 100 / ageData['33t40'].total) | 0;
                    $scope.ageKnobs.knob4 = (ageData['40p'].promoters * 100 / ageData['40p'].total) | 0;
                    if ($scope.ageKnobs.knob1 > $scope.ageKnobs.knob2 && $scope.ageKnobs.knob1 > $scope.ageKnobs.knob3 && $scope.ageKnobs.knob1 > $scope.ageKnobs.knob4) {
                        $scope.ageKnobs.knText = "of your promoters are of age 18-24";
                        $scope.ageKnobs.knVal = $scope.ageKnobs.knob1;
                    } else if ($scope.ageKnobs.knob2 > $scope.ageKnobs.knob1 && $scope.ageKnobs.knob2 > $scope.ageKnobs.knob3 && $scope.ageKnobs.knob2 > $scope.ageKnobs.knob4) {
                        $scope.ageKnobs.knText = "of your promoters are of age 25-32";
                        $scope.ageKnobs.knVal = $scope.ageKnobs.knob2;
                    } else if ($scope.ageKnobs.knob3 > $scope.ageKnobs.knob1 && $scope.ageKnobs.knob3 > $scope.ageKnobs.knob2 && $scope.ageKnobs.knob3 > $scope.ageKnobs.knob4) {
                        $scope.ageKnobs.knText = "of your promoters are of age 33-40";
                        $scope.ageKnobs.knVal = $scope.ageKnobs.knob3;
                    } else if ($scope.ageKnobs.knob4 > $scope.ageKnobs.knob1 && $scope.ageKnobs.knob4 > $scope.ageKnobs.knob2 && $scope.ageKnobs.knob4 > $scope.ageKnobs.knob3) {
                        $scope.ageKnobs.knText = "of your promoters are of age 40+";
                        $scope.ageKnobs.knVal = $scope.ageKnobs.knob4;
                    } else {
                        $scope.ageKnobs.knText = '';
                    }

                }, 500);


                $scope.isLoading = false;
                console.log($scope.categoriesHeads);
            }).error(function () {
                SweetAlert.error("Some Network Issue!", "Please try once again...", "error");
                $scope.isLoading = false;
            });
        };
        $scope.getNpsData();
        window.scp = $scope;


        $("#datarangeSel").daterangepicker({
            datepickerOptions: {
                numberOfMonths: 2,
                initialText: 'Select DateRange...'
            },
            onChange: function (val) {
                $scope.range = $('#datarangeSel').val();
                $scope.getNpsData();
//                window.location = window.location.pathname + "?range="+$('#datarangeSel').val();
            }
        });


    }]);


app.controller('Analytics.RepeatCustomersCtrl', ['$scope', '$http', 'toaster', 'SweetAlert', function ($scope, $http, toaster, SweetAlert) {
        $scope.isLoading = false;
        $scope.range = null;
        $scope.customerVisits = {};
        $scope.finalCount = {};


        $scope.knobs = {
            val1: 0,
            val2: 0,
            options: {
                unit: "%",
                readOnly: true,
                size: 70,
                fontSize: '11px',
                textColor: '#fff',
                trackWidth: 5,
                barWidth: 10,
                trackColor: 'rgba(255,255,255,0.4)',
                barColor: '#fff',
                max: 100
            }
        };
        $scope.ch1 = {
            labels: [],
            series: ['No. Repeat Customers'],
            data: [],
            options: {
//                tooltipTemplate: "<%if (label){%><%=label%>: <%}%><%= value %>%",
                legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"
            },
            type: 'Line',
            toggle: function () {
                $scope.ch1.type = $scope.ch1.type === 'Bar' ? 'Line' : 'Bar';
            }
        };
        Chart.defaults.global.tooltipTemplate = "<%= value %> Repeat Customers";
        Chart.defaults.global.multiTooltipTemplate = "<%= datasetLabel %>: <%= value %>%";
        $scope.getData = function () {
            var qr = '';
            if ($scope.range) {
                qr = '?range=' + $scope.range;
            }
            $scope.isLoading = true;
            $http.get('/customer-visits.json' + qr).success(function (d) {
                $scope.customerVisits = d.customerVisits;
                $scope.finalCount = d.finalCount;

                $scope.ch1.labels = Object.keys($scope.customerVisits);
                $scope.ch1.data = [$.map($scope.customerVisits, function (i, v) {
                        return i.countRepeat;
                    })];


                setTimeout(function () {
                    $scope.knobs.val1 = d.finalCount.totalRepeat;
                    $scope.knobs.val2 = d.finalCount.totalFirst;
                }, 500);

                $scope.isLoading = false;
            }).error(function () {
                SweetAlert.error("Some Network Issue!", "Please try once again...", "error");
                $scope.isLoading = false;
            });
        };
        $scope.topCustomers = [];
        $scope.isLoadingCstTbl = false;
        $scope.getTopCustomers = function(){
            $scope.isLoadingCstTbl = true;
            $http.get('/customer-visits/topcustomers.json').success(function (d) {
                $scope.topCustomers = d.result;
                $scope.isLoadingCstTbl = false;
            }).error(function () {
                SweetAlert.error("Some Network Issue!", "Please try once again...", "error");
                $scope.isLoadingCstTbl = false;
            });  
        };

        $scope._init = function () {
            $("#datarangeSel").daterangepicker({
                datepickerOptions: {
                    numberOfMonths: 2,
                    initialText: 'Select DateRange...'
                },
                onChange: function (val) {
                    $scope.range = $('#datarangeSel').val();
                    $scope.getData();
                    //                window.location = window.location.pathname + "?range="+$('#datarangeSel').val();
                }
            });
            $scope.getTopCustomers();
            $scope.getData();
        };
        $scope._init();
        window.scp2 = $scope;
    }]);



app.controller('Analytics.FeedbackQuestionsCtrl', ['$stateParams', '$scope', '$http', 'toaster', 'SweetAlert','$state', function ($stateParams, $scope, $http, toaster, SweetAlert,$state) {
        $scope.isLoading = false;
        $scope.range = '';
        $scope.data = {};
        $scope.dataUrl = '/feedback/m/' + $stateParams.question_code + '.json';
        $scope.allQuestions = [];
        $scope.currentQue = '';
        $scope.selectedQue = null;

        $scope.multipleChoiceCH = {
            labels: [],
            series: [''],
            data: [],
            options: {
//                tooltipTemplate: "<%if (label){%><%=label%>: <%}%><%= value %>%",
                legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"
            },
            type: 'Doughnut',
            toggle: function () {
                $scope.multipleChoiceCH.type = $scope.multipleChoiceCH.type === 'Pie' ? 'Doughnut' : 'Pie';
            }
        };
        
        
        $scope.ratingCH = {
            type: 'Line',
            data: [
                [],
                [],
                []
            ],
            series: ["dataset"],
            labels: [],
//            options: {
////                tooltipTemplate: "<%if (label){%><%=label%>: <%}%><%= value %>%",
//                legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"
//            },
            toggle: function () {
                $scope.ratingCH.type = $scope.ratingCH.type === 'Bar' ? 'Line' : 'Bar';
            }
        };



        $scope.getData = function () {
            var qr = '';
            if ($scope.range) {
                qr = '?range=' + $scope.range;
            }
            $scope.isLoading = true;
            $http.get($scope.dataUrl + qr).success(function (d) {
//           console.log(d);
                $scope.allQuestions = d.questions;
                if($stateParams.question_code == ""){
                    var val = btoa(btoa($scope.allQuestions[0].id));
                    $state.go('cstfback.analytics.feedbackquestions',{question_code:val});
                }
                $scope.queType = d.selectedQue.question_type;
                $scope.selectedQue = d.selectedQue;
                $scope.data = d.data;
                $scope.currentQue = d.selectedQue.id;
                if($scope.queType == 1){
                    $scope.multipleChoiceCH.data = $.map(d.data,function(v,k){return v; });
                    $scope.multipleChoiceCH.labels = $.map(d.data,function(v,k){return k; });
                }
                if($scope.queType == 2){
                    
                    $scope.ratingCH.data = [$.map(d.data,function(v,k){
                        var count = v.length;
                        var sum = 0;
                        for(i in v){
                            sum = sum + parseInt(v[i].content[0]);
                        }
                        return (sum / count).toFixed(2); 
                    })];
                    $scope.ratingCH.labels = $.map(d.data,function(v,k){return k; });
                }
                
                
                $scope.isLoading = false;
            }).error(function () {
                SweetAlert.error("Some Network Issue!", "Please try once again...", "error");
                $scope.isLoading = false;
            });
        };
        $scope.changeQue = function(id){
            var val = btoa(btoa($scope.currentQue));
            $state.go('cstfback.analytics.feedbackquestions',{question_code:val});
        };
        $scope._init = function () {
            $("#datarangeSel").daterangepicker({
                datepickerOptions: {
                    numberOfMonths: 2,
                    initialText: 'Select DateRange...'
                },
                onChange: function (val) {
                    $scope.range = $('#datarangeSel').val();
                    $scope.getData();
//                window.location = window.location.pathname + "?range="+$('#datarangeSel').val();
                }
            });
            $scope.getData();
            if($stateParams.question_code == ""){
                alert($scope.allQuestions[0].id);
            }
        };
        $scope._init();
        console.log($scope);
    }]);