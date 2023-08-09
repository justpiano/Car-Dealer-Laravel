(function ($) {
   "use strict";
   $.Menus = function (settings) {
      const config = {
         url: "",
      };
      const $pageid = $('#page_id');
      const $contentid = $("#contentid");
      const $webid = $("#webid");
      if (settings) {
         $.extend(config, settings);
      }

      $('#contenttype').on('change', function () {
         const $icon = $(this).parent();
         $icon.addClass('loading');
         const option = $(this).val();
         $.get(config.url + "/helper.php", {
            action: "contenttype",
            type: option,
         }, function (json) {
            switch (json.type) {
               case "page":
                  $contentid.show();
                  $webid.hide();
                  $pageid.html(json.message);
                  $pageid.prop('name', 'page_id');
                  break;

               default:
                  $contentid.hide();
                  $webid.show();
                  $pageid.prop('name', 'web_id');
                  break;
            }

            $icon.removeClass('loading');
         }, "json");
      });

      // sort menu
      $('#sortlist').nestable({
         maxDepth: 1
      }).on('change', function () {
         const json_text = $('#sortlist').nestable('serialize');
         $.ajax({
            cache: false,
            type: "post",
            url: config.url + "/helper.php",
            dataType: "json",
            data: {
               iaction: "sortMenus",
               sorting: JSON.stringify(json_text)
            }
         });
      }).nestable('collapseAll');
   };
})(jQuery);