(function ($) {
   "use strict";
   $.Gallery = function (settings) {
      let config = {
         url: "",
         grid: ".grid",
         id: 1,
         sortable: '#sortable',
         lang: {
            done: 'Done',
         }
      };

      const $done = $("#done");

      if (settings) {
         $.extend(config, settings);
      }

      // sort photos
      $("#sortable").sortable({
         ghostClass: "ghost",
         handle: ".label",
         animation: 600,
         onUpdate: function () {
            let order = this.toArray();
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

      //File Upload
      $('#drag-and-drop-zone').on('click', function () {
         $(this).wojoUpload({
            url: config.url,
            dataType: 'json',
            extraData: {
               action: "uploadGallery",
               id: config.id
            },
            allowedTypes: '*',
            onBeforeUpload: function (id) {
               update_file_status(id, 'primary', 'Uploading...');
            },
            onNewFile: function (id, file) {
               add_file(id, file);
            },
            onUploadProgress: function (id, percent) {
               update_file_progress(id, percent);
            },
            onUploadSuccess: function (id, data) {
               if (data.type === "error") {
                  update_file_status(id, '<i class="icon small negative circular minus"></i>', data.message);
                  update_file_progress(id, 0);
               } else {
                  let icon = '<i class="icon small positive circular check"></i>';
                  let btn = '<img src="' + data.filename + '" class="wojo small rounded image" alt="">';

                  update_file_status(id, icon, btn);
                  update_file_progress(id, 100);
               }
            },
            onUploadError: function (id, message) {
               update_file_status(id, '<i class="icon small negative circular minus"></i>', message);
            },
            onFallbackMode: function (message) {
               alert('Browser not supported: ' + message);
            },

            onComplete: function () {
               if ($done.length === 0) {
                  $("#fileList").after('<div id="done" class="vertical margin"><a class="wojo small primary button"><i class="icon check"></i>' + config.lang.done + '</a></div>');
               }

               $done.on('click', 'a', function () {
                  buildList(config.id);
                  $('#fileList').html('');
                  $("#done").remove();
               });
            }
         });
      });

      function add_file(id, file) {
         let template = '' +
           '<div class="item progress" id="uploadFile_' + id + '">' +
           '<div class="columns auto" id="bStstus_' + id + '">' +
           '<div class="wojo icon button"><i class="icon white file"></i></div>' +
           '</div>' +
           '<div class="columns" id="contentFile_' + id + '">' +
           '<h6 class="basic">' + file.name + '</h6>' +
           '</div>' +
           '<div class="columns auto" id="iStatus_' + id + '"><i class="icon small info circular upload"></i></div>' +
           '<div class="wojo attached bottom tiny progress">' +
           '<div class="bar" data-percent="100"></div>' +
           '</div>' +
           '</div>';

         $('#fileList').prepend(template);
      }

      function update_file_status(id, status, message) {
         $('#bStstus_' + id).html(message);
         $('#iStatus_' + id).html(status);
      }

      function update_file_progress(id, percent) {
         const $uf = $('#uploadFile_' + id);
         $uf.find('.progress').wProgress();
         $uf.find('.progress .bar').attr("data-percent", percent);
      }

      function buildList(id) {
         $(config.grid).addClass('loading');
         $.get(config.url, {
            action: "loadPhotos",
            id: id,
         }, function (json) {
            if (json.type === "success") {
               $(config.grid).html(json.html);
               $(config.grid).editableTableWidget();
            }
            $(config.grid).removeClass('loading');
         }, "json");
      }
   };
})(jQuery);