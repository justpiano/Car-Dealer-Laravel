(function ($) {
   "use strict";
   $.Locations = function (settings) {
      const config = {
         zoom: 10,
         latitude: "",
         longitude: "",
         pin: ""
      };

      if (settings) {
         $.extend(config, settings);
      }
      let map = null;
      let geocoder;

      geocoder = new google.maps.Geocoder();
      const latitude = parseFloat(config.latitude);
      const longitude = parseFloat(config.longitude);
      loadMap(latitude, longitude);
      setupMarker(latitude, longitude);

      $('#lookup').on("click", function () {
         const address = document.getElementById('address').value;
         geocoder.geocode({
            'address': address
         }, function (results, status) {
            if (status === google.maps.GeocoderStatus.OK) {
               map.setCenter(results[0].geometry.location);
               const image = new google.maps.MarkerImage(config.pin);
               const marker = new google.maps.Marker({
                  map: map,
                  draggable: true,
                  raiseOnDrag: false,
                  icon: image,
                  position: results[0].geometry.location
               });

               $("#lat").val(results[0].geometry.location.lat());
               $("#lng").val(results[0].geometry.location.lng());

               google.maps.event.addListener(marker, 'dragend', function () {
                  $("#lat").val(this.getPosition().lat());
                  $("#lng").val(this.getPosition().lng());
               });
            } else {
               $.wNotice(decodeURIComponent('Geocode was not successful for the following reason: ' + status), {
                  autoclose: 4000,
                  type: "error",
                  title: 'Error'
               });
            }

         });
      });

      google.maps.event.addListener(map, 'zoom_changed', function () {
         document.getElementById('zoomlevel').value = map.getZoom();
      });

      // Loads the maps
      function loadMap(latitude, longitude) {
         const latlng = new google.maps.LatLng(latitude, longitude);
         const myOptions = {
            zoom: config.zoom,
            center: latlng,
            mapTypeControl: false,
            streetViewControl: false,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            styles: [{
               "featureType": "landscape",
               "stylers": [{
                  "hue": "#F1FF00"
               }, {
                  "saturation": -27.4
               }, {
                  "lightness": 9.4
               }, {
                  "gamma": 1
               }]
            }, {
               "featureType": "road.highway",
               "stylers": [{
                  "hue": "#0099FF"
               }, {
                  "saturation": -20
               }, {
                  "lightness": 36.4
               }, {
                  "gamma": 1
               }]
            }, {
               "featureType": "road.arterial",
               "stylers": [{
                  "hue": "#00FF4F"
               }, {
                  "saturation": 0
               }, {
                  "lightness": 0
               }, {
                  "gamma": 1
               }]
            }, {
               "featureType": "road.local",
               "stylers": [{
                  "hue": "#FFB300"
               }, {
                  "saturation": -38
               }, {
                  "lightness": 11.2
               }, {
                  "gamma": 1
               }]

            }, {
               "featureType": "water",
               "stylers": [{
                  "hue": "#00B6FF"
               }, {
                  "saturation": 4.2
               }, {
                  "lightness": -63.4
               }, {
                  "gamma": 1
               }]
            }, {
               "featureType": "poi",
               "stylers": [{
                  "hue": "#9FFF00"
               }, {
                  "saturation": 0
               }, {
                  "lightness": 0
               }, {
                  "gamma": 1
               }]
            }]
         };
         map = new google.maps.Map(document.getElementById("map"), myOptions);
      }

      function setupMarker(latitude, longitude) {
         const pos = new google.maps.LatLng(latitude, longitude);
         const image = new google.maps.MarkerImage(config.pin);
         const marker = new google.maps.Marker({
            position: pos,
            map: map,
            draggable: true,
            raiseOnDrag: false,
            icon: image,
            title: name
         });
         google.maps.event.addListener(marker, 'dragend', function () {
            $("#lat").val(this.getPosition().lat());
            $("#lng").val(this.getPosition().lng());
         });
      }
   };
})(jQuery);