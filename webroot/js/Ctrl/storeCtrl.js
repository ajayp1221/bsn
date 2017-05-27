//
/** 
 * controller for Stores
 */

app.controller('Store.IndexCtrl', ['$state','$scope', 'toaster', '$http', '$aside', '$timeout', 'SweetAlert', function ($state,$scope, toaster, $http, $aside, $timeout, SweetAlert, FileUploader) {
        $scope.setMap = function () {
            marker = [];
            var map = new google.maps.Map(document.getElementById('map'), {
                center: new google.maps.LatLng($scope.store.latitude, $scope.store.longitude),
                zoom: 13,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            });

            // Create the search box and link it to the UI element.
            var input = document.getElementById('pac-input');
            var searchBox = new google.maps.places.SearchBox(input);
            map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

            // Bias the SearchBox results towards current map's viewport.
            map.addListener('bounds_changed', function () {
                searchBox.setBounds(map.getBounds());
            });

            if ($scope.store.latitude != 0) {
                marker = new google.maps.Marker({
                    map: map,
                    title: $scope.store.store_address,
                    draggable: true,
                    position: new google.maps.LatLng($scope.store.latitude, $scope.store.longitude)
                });
                google.maps.event.addListener(marker, 'drag', function ()
                {
                    $scope.store.latitude = marker.getPosition().lat().toFixed(6);
                    $scope.store.longitude = marker.getPosition().lng().toFixed(6);
                    $scope.$apply();
                });
            }
            // [START region_getplaces]
            // Listen for the event fired when the user selects a prediction and retrieve
            // more details for that place.
            searchBox.addListener('places_changed', function () {
                var places = searchBox.getPlaces();

                if (places.length == 0) {
                    return;
                }

                var bounds = new google.maps.LatLngBounds();
                places.forEach(function (place) {
                    marker.setPosition(place.geometry.location);
                    $scope.store.latitude = marker.getPosition().lat().toFixed(6);
                    $scope.store.longitude = marker.getPosition().lng().toFixed(6);
                    $scope.$apply();
                    if (place.geometry.viewport) {
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                });
                map.fitBounds(bounds);

            });
        };
        $scope.getStore = function () {
            $scope.isLoading = true;
            $http.get("http://www.zakoopi.com/api/stores/my_view_store/" + $storeSlug + ".json").success(function (data) {
                $scope.store = data.store;
                $scope.isLoading = false;
                $scope.setMap();
            });
//            SweetAlert.error("Under Maintenance!", "This page is under process please wait for some time", "error");
        };
        $scope.getStore();
        $scope.options = true;
        $scope.enableEditor = function () {
            $scope.editorEnabled = true;
        };
        $scope.disableEditor = function () {
            $scope.editorEnabled = false;
        };

        $scope.toggleEdit = function (ele, enable) {
            if (enable) {
                $(ele.currentTarget).parent().next().removeClass('hide');
                $(ele.currentTarget).parent().addClass('hide');
                $(ele.currentTarget).parent().parent().addClass('ed-enable');
            } else {
                $(ele.currentTarget).parent().prev().removeClass('hide');
                $(ele.currentTarget).parent().addClass('hide');
                $(ele.currentTarget).parent().parent().removeClass('ed-enable');
            }
        };

        $scope.save = function () {
            var data = JSON.stringify($scope.store);
            $.post('http://www.zakoopi.com/api/stores/storePostData/'+$scope.store.slug+'.json',{'dt':data},function(d){

                SweetAlert.success("Updated!", "Store details now updated", "success");
                $state.go($state.current, {}, {reload: true});
            }).error(function(){
                SweetAlert.error("Some Network Issue!", "Please try once again...", "error");
            });
//            $scope.disableEditor();
        };

        $scope.enableEditor1 = function (offering) {
            offering.editorEnabled1 = true;
        };
        $scope.disableEditor1 = function (offering) {
            offering.editorEnabled1 = false;
        };
        $scope.save1 = function (offering, store) {
            var data = JSON.stringify($scope.store);
            $.post('http://www.zakoopi.com/api/stores/storePostData/'+$scope.store.slug+'.json',{'dt':data},function(d){

                SweetAlert.success("Updated!", "Store details now updated", "success");
                $state.go($state.current, {}, {reload: true});
            }).error(function(){
                SweetAlert.error("Some Network Issue!", "Please try once again...", "error");
            });
            $scope.disableEditor1(offering);
        };
        

    }]);