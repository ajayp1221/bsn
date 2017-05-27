
/** 
 * controller for Banners
 */
    app.controller('Banners.IndexCtrl', ['$scope', 'toaster', '$http', '$aside', '$timeout', '$window', function ($scope, toaster, $http, $aside, $timeout, $window) {
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
            $http.get("/banners/bannerlist").success(function (data) {
                $scope.storeCoverImages = data;
//            $http.get("/store-images.json").success(function (data) {
//                $scope.storeCoverImages = data.result.v;
                $scope.isLoading = false;
            });
        };
        $scope.getCmp();
        $scope.bannerImageDelete = function (id) {
            var deleteUser = $window.confirm('Are you absolutely sure you want to delete?');
            if (deleteUser) {
                $scope.isLoading = true;
                var res = $http.post("/banners/bannerdelete/" + id + ".json", {id: id});
//                var res = $http.post("/store-images/delete/" + id + ".json", {id: id});
                res.success(function (data) {
                    if (data.error == 0) {
                        $scope.getCmp();
                        $scope.isLoading = false;
                        toaster.pop('success', '', data.msg);
                    }
                    if (data.error == 1) {
                        $scope.isLoading = false;
                        toaster.pop('error', '', data.msg);
                    }
                }).error(function(){
                    $scope.isLoading = false;
                    toaster.pop('error', '', 'Server error occurred');
                });
            }
        };
        $scope.albumAdd = null;
        $scope.addImage = function(){
            var asideInstance = $aside.open({
                templateUrl: 'uploadImgBanner.html',
                controller: 'AddBannerPicCtrl',
                placement: 'top',
                size: 'sm',
                resolve: {
                    $prVm: $scope
                }
            });
        };
    }]);
    
    app.controller('AddBannerPicCtrl', ['$scope', '$http', '$uibModalInstance','$prVm', 'toaster', function ($scope, $http, $uibModalInstance,$prVm,toaster) {
        $scope.storeSlug = $storeSlug;
        $scope.albumAdd = $prVm.albumAdd;
        $scope.ok = function (e) {
            $scope.showPrg = true;
            if ($('#fileUploadFrm').find('input[type="file"]')[0].files.length == 0) {
                toaster.pop('error', 'Please select few images...');
            } else {
                var formData = new FormData($('#fileUploadFrm')[0]);
                $.ajax({
                    url: 'http://www.zakoopi.com/api/stores/StoreImagesAdd.json', //Server script to process data
//                    url: '/store-images/add.json',
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


    app.controller('Banners.ImageCtrl', ['$scope', 'toaster', '$http', '$aside', '$timeout', '$window', function ($scope, toaster, $http, $aside, $timeout, $window) {
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
            $http.get("/banners/coverlist").success(function (data) {
                $scope.storeCoverImages = data.storeCoverImages;
//            $http.get("/store-cover-images.json").success(function (data) {
//                $scope.storeCoverImages = data.v;
                $scope.isLoading = false;
            });
        };
        $scope.getCmp();
        $scope.bannerImageDelete = function (id) {
            var deleteUser = $window.confirm('Are you absolutely sure you want to delete?');
            if (deleteUser) {
                $scope.isLoading = true;
                var res = $http.post("/banners/coverdelete/" + id + ".json", {id: id});
//                var res = $http.post("/store-cover-images/delete/" + id + ".json", {id: id});
                res.success(function (data) {
                    if (data.error == 0) {
                        $scope.getCmp();
                        $scope.isLoading = false;
                        toaster.pop('success', '', data.msg);
                    }
                    if (data.error == 1) {
                        $scope.isLoading = false;
                        toaster.pop('error', '', data.msg);
                    }
                }).error(function(){
                    $scope.isLoading = false;
                    toaster.pop('error', '', 'Server error occurred');
                });
            }
        };
        $scope.albumAdd = null;
        $scope.addImage = function(){
            var asideInstance = $aside.open({
                templateUrl: 'uploadImg.html',
                controller: 'AddImageCtrl',
                placement: 'top',
                size: 'sm',
                resolve: {
                    $prVm: $scope
                }
            });
        };
    }]);

    app.controller('AddImageCtrl', ['$scope', '$http', '$uibModalInstance','$prVm', 'toaster', function ($scope, $http, $uibModalInstance,$prVm,toaster) {
        $scope.storeSlug = $storeSlug;
        $scope.albumAdd = $prVm.albumAdd;
        $scope.ok = function (e) {
            $scope.showPrg = true;
            if ($('#fileUploadFrm').find('input[type="file"]')[0].files.length == 0) {
                toaster.pop('error', 'Please select few images...');
            } else {
                var formData = new FormData($('#fileUploadFrm')[0]);
                $.ajax({
                    url: 'http://www.zakoopi.com/api/StoreCoverImages/add.json', //Server script to process data
//                    url : '/store-cover-images/add.json',
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
