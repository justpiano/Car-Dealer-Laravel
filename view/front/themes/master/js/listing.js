(function ($) {
   "use strict";
   $.Listing = function (settings) {
      const config = {
         url: '',
         zoom: 14,
         pin: "",
         location: "Toronto",
         lang: {
            add: 'Add Location',
            err: 'Error Uploading',
            err1: 'Error image type',
         }
      };

      if (settings) {
         $.extend(config, settings);
      }

      /* == Vin Search == */
      $(document).on('click', '#dovin', function () {
         const $button = $(this);
         $.extend($.expr[":"], {
            "containsIN": function (elem, i, match) {
               return (elem.textContent || elem.innerText || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
            }
         });
         const vin = $("input[name=vin]").val();
         if ($.trim(vin).length) {
            $button.addClass('loading');
            $.getJSON("https://auto.dev/api/vin/" + vin + "?apikey=" + config.vinapi)
              .done(function (json) {
                 if (json.status === "BAD_REQUEST" || json.status === "NOT_FOUND") {
                    $.wNotice(json.message, {
                       autoclose: 12000,
                       type: "error",
                       title: json.errorType
                    });
                 } else {
                    const make_id = $('select[name=make_id] option:containsIN("' + json.make.name + '")').prop("selected", "selected");
                    updateMakeModel(json.model.name, make_id.val(), true);
                    $('input[name=year]').val(json.years[0].year);
                    $('select[name=category] option:containsIN("' + json.categories.vehicleStyle + '")').prop("selected", "selected");
                    $('input[name=torque]').val(json.engine.rpm.torque);
                    $('input[name=horse_power]').val(json.engine.horsepower);
                    $('input[name=drive_train]').val(json.drivenWheels);
                    $('input[name=engine]').val(json.engine.size + 'L ' + json.engine.cylinder);
                    $('select[name=transmission] option:containsIN("' + json.transmission.transmissionType + '")').prop("selected", "selected");
                    $('select[name=fuel] option:containsIN("' + json.engine.type + '")').prop("selected", "selected");
                    $('input[type="range"]').val(parseInt(json.numOfDoors)).change();
                 }
              }).fail(function (jqxhr) {
               $.wNotice(jqxhr.responseJSON.message, {
                  autoclose: 12000,
                  type: "error",
                  title: "Request Failed"
               });
            });
            $button.removeClass('loading');
         }
      });

      function updateMakeModel(model, id, isvin) {
         $.post(config.url + '/controller.php', {
            iaction: "getModels",
            id: id,
         }, function (json) {
            if (json.type === "success") {
               $("select[name=model_id]").html('');
               $.each(json.data, function (i, option) {
                  $("select[name=model_id]").append($('<option></option>').val(option.id).html(option.name));
               });
               if (isvin) {
                  $('select[name=model_id] option').filter(function () {
                     return $(this).html() == model
                  }).prop("selected", "selected");
               }
            }
         }, "json");
      }

      /* == Get Models == */
      $(document).on('change', '#makes', function () {
         updateMakeModel(null, $(this).val(), false);
      });

      // add images
      $('#images').simpleUpload({
         url: config.url + "/controller.php",
         types: ['jpg', 'png', 'JPG', 'PNG'],
         dataType: 'json',
         error: function (error) {
            if (error.type === 'fileType') {
               $.wNotice(config.lang.err1, {
                  autoclose: 12000,
                  type: "error",
                  title: config.lang.err
               });
            }
         },
         beforeSend: function () {
            $('#sortable').closest('.segment').addClass('loading');
         },
         success: function (json) {
            if (json.type === "success") {
               $('#sortable').prepend(json.html).sortable();
            } else {
               $.wNotice(json.message, {
                  autoclose: 12000,
                  type: "error",
                  title: json.title
               });
            }
            $('#sortable').closest('.segment').removeClass('loading');
         }
      });

      // sort photos
      $("#sortable").sortable({
         ghostClass: "ghost",
         handle: ".columns",
         animation: 600,
         onUpdate: function () {
            const order = this.toArray();
            $.ajax({
               type: 'post',
               url: config.url + "/controller.php",
               dataType: 'json',
               data: {
                  iaction: "sortGallery",
                  sorting: order
               }
            });
         }
      });

      /* == Check All == */
      $('#masterCheckbox').click(function () {
         const parent = $(this).data('parent');
         const $checkbox = $(parent).find(':checkbox');
         $checkbox.prop('checked', !$checkbox.prop('checked'));
      });

      $('.bodypost').redactor({
         minHeight: "200px",
         plugins: ['alignment', 'fullscreen'],
         buttons: ['format', 'bold', 'italic', 'deleted', 'alignment', 'lists', 'link', 'fullscreen'],
      });

      $("#address").geocomplete({
         details: "form",
         map: "#map",
         location: config.location,
         markerOptions: {
            draggable: true
         },
         detailsAttribute: "data-geo",
         types: ["geocode", "establishment"],
         mapOptions: {
            zoom: 14,
            mapTypeControl: false,
            streetViewControl: false,
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
         }
      }).on("geocode:result", function () {
         updateData();
      }).on("geocode:dragged", function (event, latLng) {
         $("input[name=lat]").val(latLng.lat());
         $("input[name=lng]").val(latLng.lng());
         updateData();
      });

      function updateData() {
         const lat = $("input[name=lat]").val();
         const lng = $("input[name=lng]").val();
         const address = $("input[name=address]").val();
         const city = $("input[name=city]").val();
         const state = $("input[name=state]").val();
         const zip = $("input[name=zip]").val();
         const country = $("input[name=country]").val();
         const array = {
            "option": [{
               "action": "addLocation",
               "lat": lat,
               "lng": lng,
               "zoom": config.zoom,
               "address": address,
               "city": city,
               "state": state,
               "zip": zip,
               "country": country
            }],
            "label": config.lang.add,
            "url": "/controller.php",
            "parent": "#userLocation",
            "complete": "append",
            "modalclass": "normal"
         };
         $("#newLocation").attr("data-set", JSON.stringify(array));
      }
   };
})(jQuery);