(function ($) {
   "use strict";
   $.Listing = function (settings) {
      const config = {
         url: "",
         vinapi: "",
         lang: {
            err: "",
            err1: ""
         }
      };
      const $sortable = $('#sortable');
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
         $.get(config.url, {
            action: "getModels",
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
      $(document).on('change', '#make_id', function () {
         updateMakeModel(null, $(this).val(), false);
      });

      // add images
      $('#images').simpleUpload({
         url: config.url,
         types: ['jpg', 'png', 'JPG', 'PNG'],
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
         success: function (data) {
            $sortable.prepend(data).sortable();
            $sortable.closest('.segment').removeClass('loading');
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
               url: config.url,
               dataType: 'json',
               data: {
                  iaction: "sortGallery",
                  sorting: order
               }
            });
         }
      });
   };
})(jQuery);