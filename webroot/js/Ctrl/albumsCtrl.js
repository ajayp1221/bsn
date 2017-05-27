
/** 
 * controller for Albums
 */

app.controller('Albums.AddAlbumCtrl', ['$scope', '$http', 'toaster','SweetAlert', function ($scope, $http, toaster, SweetAlert) {
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
                    if ($('#form').find('input[type="file"]')[0].files.length == 0) {
                        toaster.pop('error', 'Please select few images...');
                    } else {
                        var formData = new FormData($('#form')[0]);
                        $.ajax({
                            url: '/albums/add.json', //Server script to process data
                            type: 'POST',
                            xhr: function () {  // Custom XMLHttpRequest
                                var myXhr = $.ajaxSettings.xhr();
                                return myXhr;
                            },
                            success: function (a) {
                                $scope.isLoading = false;
                                SweetAlert.success("Success!", "Album Created Successfully", "success");
                            },
                            error: function (e) {
                                $scope.isLoading = false;
                                SweetAlert.error("Some Network Issue!", "Please try once again...", "error");
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
            }};
    }]);


app.controller('UpdatePicAsideCtrl', ['$scope', '$http', '$uibModalInstance', '$prVm', function ($scope, $http, $uibModalInstance, $prVm) {
        $scope.updateAlbmDoc = $prVm.updateAlbmDoc;
        $scope.updateImg = $scope.updateAlbmDoc.albumimages[$prVm.updateImgIndx];
        $scope.ok = function (e) {
            $uibModalInstance.close();
            var res = $http.post("/albumimages/edit/" + $scope.updateImg.id + ".json", {data: $scope.updateImg});
            res.success(function (data) {
                if (data.response.error == 0) {
                    toaster.pop('success', 'Update...', data.response.msg);
                }
                if (data.response.error == 1) {
                    toaster.pop('error', 'Error...', data.response.msg);
                }
            });
        };
        $scope.cancel = function (e) {
            $uibModalInstance.dismiss();
        };
    }]);

app.controller('Albums.IndexCtrl', ['$scope', 'toaster', '$http', '$aside', '$timeout', '$window','SweetAlert', function ($scope, toaster, $http, $aside, $timeout, $window,SweetAlert) {
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
        $scope.albums = [];
        $scope.pager = null;
        $scope.isLoading = false;
        $scope.getCmp = function () {
            $scope.isLoading = true;
            $http.get("/albums.json").success(function (data) {
                $scope.albums = data.albums;
                $scope.isLoading = false;
            });
        };
        $scope.getCmp();
        $scope.updateImgIndx = null;
        $scope.updateAlbmDoc = null;
        $scope.albumimagesUpdate = function (updateImgIndx, updateAlbmDoc) {
            $scope.updateImgIndx = updateImgIndx;
            $scope.updateAlbmDoc = updateAlbmDoc;
            var asideInstance = $aside.open({
                templateUrl: 'UpdatePicAsideQue.html',
                controller: 'UpdatePicAsideCtrl',
                placement: 'right',
                size: 'sm',
                resolve: {
                    $prVm: $scope
                }
            });
        };
        $scope.albumimagesDelete = function (id) {
            var deleteUser = $window.confirm('Are you absolutely sure you want to delete?');
            if (deleteUser) {
                $scope.isLoading = true;
                var res = $http.post("/albumimages/delete/" + id + ".json", {id: id});
                res.success(function (data) {
                    if (data.response.error == 0) {
                        $scope.isLoading = false;
                        toaster.pop('success', 'Deleted...', data.response.msg);
                    }
                    if (data.response.error == 1) {
                        $scope.isLoading = false;
                        toaster.pop('error', 'Some issue occured...', data.response.msg);
                    }
                });
            }
        };
        $scope.albumAdd = null;
        $scope.addImage = function (album) {
            $scope.albumAdd = album;
            var asideInstance = $aside.open({
                templateUrl: 'uploadImgAside.html',
                controller: 'AddPicCtrl',
                placement: 'top',
                size: 'sm',
                resolve: {
                    $prVm: $scope
                }
            });
        };
        $scope.enableEditor1 = function(offering){
            offering.editorEnabled1 = true;
        };
        $scope.disableEditor1= function(offering) {
            offering.editorEnabled1 = false;
        };
        $scope.save1 = function(album) {
            $scope.isLoading = true;
            var data = JSON.stringify(album);
            $http.post('/albums/edit/'+album.id+'.json',{'dt':data}).success(function(d){
                console.log(d);
                $scope.isLoading = false;
                if(d.result.error==0){
                    SweetAlert.success("Success!", d.result, "success");
                }else{
                    SweetAlert.error("Sorry!", d.result, "error");
                }
            }).error(function(){
                SweetAlert.error("Some Network Issue!", "Please try once again...", "error");
                $scope.isLoading = false;
            });
            $scope.disableEditor1(album);
        };
    }]);
app.controller('AddPicCtrl', ['$scope', '$http', '$uibModalInstance', '$prVm', 'toaster', function ($scope, $http, $uibModalInstance, $prVm, toaster) {
        $scope.albumAdd = $prVm.albumAdd;
        $scope.showPrg = false;
        $scope.progress = 0;
        $scope.prgText = 'Uploading ';
        $scope.ok = function (e) {
            $scope.showPrg = true;
            if ($('#fileUploadFrm').find('input[type="file"]')[0].files.length == 0) {
                toaster.pop('error', 'Please select few images...');
            } else {
                var formData = new FormData($('#fileUploadFrm')[0]);
                $.ajax({
                    url: '/albums/addphotos/' + $prVm.albumAdd.id + '.json', //Server script to process data
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
                    //Ajax events
//                    beforeSend: beforeSendHandler,
                    success: function (a) {
                        $scope.showPrg = false;
                        $scope.progress = 0;
                        toaster.pop('success', 'Images uploaded successfully...');
                        $uibModalInstance.close();
                        $prVm.getCmp();
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

        };
        $scope.cancel = function (e) {
            $uibModalInstance.dismiss();
        };
    }]);
app.controller('Albums.Sendphotos', ['$scope', 'toaster', '$http', '$timeout', 'SweetAlert', function ($scope, toaster, $http, $timeout, SweetAlert) {
        $scope.selectImgIds = [];
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
        $scope.nwAlbm = {
            who_send: 1,
            shareVia: "email"
        };
        $scope.nwAlbm1 = {
            whos_send: 1,
            shareVia: "sms"
        };
        $scope.imgSelected = {};
        $scope.albums = [];
        $scope.pager = null;
        $scope.isLoading = false;
        $scope.getAlbm = function () {
            $scope.isLoading = true;
            $http.get("/albums.json").success(function (data) {
                $scope.albums = data.albums;
                $scope.isLoading = false;
            });
        };
        $scope.smsCounterText = "";
        $scope.smsCounter = function (message) {
            if (!message) {
                return false;
            }
            var v = message;
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
        $scope.$watch('nwAlbm1.sms_content', function () {
            $scope.smsCounter($scope.nwAlbm1.sms_content);
        });
        $scope.filterSelectedImgs = function () {
            var imgs = $.map($scope.albums, function (a) {
                return $.map(a.albumimages, function (b) {
                    return b.selected ? b.id : false;
                })
            });
            $scope.selectImgIds = $(imgs).filter(function (a, b) {
                return b;
            }).toArray();
            $scope.nwAlbm.selectImgIds = $scope.selectImgIds;
            $scope.nwAlbm1.selectImgIds = $scope.selectImgIds;
        };
        $scope.selectAll = function (album, val) {
            var i;
            for (i in album.albumimages) {
                album.albumimages[i].selected = val;
            }
            $scope.filterSelectedImgs();
        };
        $scope.$watch('albums', function (n, o, s) {
            $scope.filterSelectedImgs();
        }, true);
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
                    $http.post("/albums/share.json", $scope.nwAlbm).success(function (d) {
                        if (d.result.error == 0) {
                            SweetAlert.success("Success!", d.result.msg, "success");
                            $scope.getAlbm();
                            var master = {
                                repeat_type: 'email'
                            };
                            $scope.nwAlbm = angular.copy(master);
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

        $scope.form1 = {
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
                    $http.post("/albums/share.json", $scope.nwAlbm1).success(function (d) {
                        if (d.result.error == 0) {
                            SweetAlert.success("Success!", d.result.msg, "success");
                            $scope.getAlbm();
                            var master = {
                                repeat_type: 'email'
                            };
                            $scope.nwAlbm = angular.copy(master);
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
        $scope.getAlbm();
        $timeout(function () {
            var dateToday  = new Date();
            var dtp = jQuery('.dtime').datetimepicker({
                datepicker:true,
                timepicker:false,
                format:'d/m/Y',
                minDate: dateToday
            });
        }, 3000);
        
    }]);