(function ($) {
   "use strict";
   $.Stats = function (settings) {
      let config = {
         url: "",
         id: 0
      };

      if (settings) {
         $.extend(config, settings);
      }
      loadStats();

      function loadStats() {
         $("#lData").addClass('loading');
         $.ajax({
            type: 'GET',
            url: config.url + "/helper.php",
            data: {
               action: "listingStats",
               id: $.url().segment(-1)
            },
            dataType: 'json',
            cache: false
         }).done(function (json) {
            let legend = '';
            json.legend.map(function (val) {
               legend += val;
            });
            $("#legend").html(legend);
            Morris.Line({
               element: 'stats_chart',
               data: json.data,
               xkey: 'm',
               ykeys: json.label,
               labels: json.label,
               parseTime: false,
               lineWidth: 4,
               pointSize: 6,
               lineColors: json.color,
               gridTextFamily: "Wojo Sans",
               gridTextWeight: 500,
               gridTextColor: "rgba(0,0,0,0.6)",
               gridTextSize: 14,
               fillOpacity: '.1',
               hideHover: 'auto',
               preUnits: json.preUnits,
               hoverCallback: function (index, json, content) {
                  const text = $(content)[1].textContent;
                  return content.replace(text, text.replace(json.preUnits, ""));
               },
               smooth: true,
               resize: true,
            });
            $("#lData").removeClass('loading');
         });
      }

      $("#resetStats").on("click", function () {
         const button = $(this);
         button.addClass('loading');
         $.ajax({
            type: 'GET',
            url: config.url + "/helper.php",
            data: {
               action: "resetListingStats",
               id: $.url().segment(-1)
            },
            dataType: 'json'
         }).done(function () {
            $("#stats_chart").html(' ');
            loadStats();
            button.removeClass('loading');
         });
      });
   };
})(jQuery);