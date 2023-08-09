(function ($) {
   "use strict";
   $.Index = function (settings) {
      let config = {
         is_dark: false
      };

      if (settings) {
         $.extend(config, settings);
      }

      $("#payment_chart").addClass('loading');
      $("#payment_chart2").addClass('loading');

      //counters
      $('.counter').each(function () {
         $(this).prop('Counter', 0).animate({
            Counter: $(this).text()
         }, {
            duration: 3500,
            step: function (now) {
               $(this).text(Math.ceil(now));
            }
         });
      });

      //chart
      $.ajax({
         type: 'GET',
         url: config.url + "/helper.php?action=getIndexStats",
         dataType: 'json'
      }).done(function (json) {
         let legend = '';
         json.legend.map(function (val) {
            legend += val;
         });
         $("#legend").html(legend);
         Morris.Line({
            element: 'payment_chart',
            data: json.data,
            xkey: 'm',
            ykeys: json.label,
            labels: json.label,
            parseTime: false,
            gridTextFamily: "Wojo Sans",
            gridTextWeight: 500,
            lineWidth: 4,
            pointSize: 6,
            lineColors: json.color,
            gridTextColor: config.is_dark ? "rgba(255,255,255,0.6)" : "rgba(0,0,0,0.6)",
            gridTextSize: 14,
            fillOpacity: '.75',
            hideHover: 'auto',
            preUnits: json.preUnits,
            behaveLikeLine: true,
            hoverCallback: function (index, json, content) {
               let text = $(content)[1].textContent;
               return content.replace(text, text.replace(json.preUnits, ""));
            },
            smooth: true,
            resize: true,
            xLabelAngle: 0,

         });
         $("#payment_chart").removeClass('loading');
      });

      //chart
      $.ajax({
         type: 'GET',
         url: config.url + "/helper.php?action=getMainStats",
         dataType: 'json'
      }).done(function (json) {
         const data = json.data;
         json.legend.map(function (v) {
            return $("#legend2").append(v);
         });
         Morris.Area({
            element: 'payment_chart2',
            data: data,
            xkey: 'm',
            ykeys: json.label,
            labels: json.label,
            parseTime: false,
            gridTextFamily: "Wojo Sans",
            gridTextWeight: 500,
            lineWidth: 4,
            pointSize: 6,
            lineColors: json.color,
            gridTextColor: config.is_dark ? "rgba(255,255,255,0.6)" : "rgba(0,0,0,0.6)",
            gridTextSize: 14,
            fillOpacity: '.75',
            hideHover: 'auto',
            xLabelAngle: 0,
            preUnits: json.preUnits,
            smooth: true,
            resize: true,
         });

         $("#payment_chart2").removeClass('loading');
      });

      //counters
      let sparkline = function () {
         $('.sparkline').sparkline('html', {
            enableTagOptions: true,
            tagOptionsPrefix: "data"
         });
      };
      let sparkResize;
      $(window).resize(function () {
         clearTimeout(sparkResize);
         sparkResize = setTimeout(sparkline, 500);
         chart1();
         chart2();
      });
      sparkline();

      let barEl1 = $("#chart1");
      let barValues1 = barEl1.attr('data-values').split(',');
      let barValueCount1 = barValues1.length;
      const barSpacing1 = 4;
      let chart1 = function () {
         barEl1.sparkline(barValues1, {
            type: 'bar',
            height: 55,
            barWidth: Math.round((barEl1.parent().width() - (barValueCount1 - 1) * barSpacing1) / barValueCount1),
            barSpacing: barSpacing1,
            zeroAxis: false,
            barColor: '#0A48B3'
         });
      };

      const barEl2 = $("#chart2");
      const barValues2 = barEl2.attr('data-values').split(',');
      const barValueCount2 = barValues2.length;
      const barSpacing2 = 5;
      let chart2 = function () {
         barEl2.sparkline(barValues2, {
            type: 'bar',
            height: 55,
            barWidth: Math.round((barEl2.parent().width() - (barValueCount2 - 1) * barSpacing2) / barValueCount2),
            barSpacing: barSpacing2,
            zeroAxis: false,
            barColor: '#11cdef'
         });
      };
      chart1();
      chart2();
   };
})(jQuery);