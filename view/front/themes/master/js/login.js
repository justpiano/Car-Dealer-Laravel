(function ($) {
   "use strict";
   $.Login = function (settings) {
      const config = {
         url: "frontview",
         surl: "siteurl",
         lang: {
            now: "Now",

         }
      };
      if (settings) {
         $.extend(config, settings);
      }


      $("#backto").on('click', function () {
         $("#loginform").slideDown();
         $("#passform").slideUp();
      });
      $("#passreset").on('click', function () {
         $("#loginform").slideUp();
         $("#passform").slideDown();
      });

      $("#doLogin").on('click', function () {
         const $btn = $(this);
         $btn.addClass('loading').prop("disabled", true);
         const username = $("input[name=username]").val();
         const password = $("input[name=password]").val();
         $.ajax({
            type: 'post',
            url: config.url + "/controller.php",
            data: {
               'action': 'userLogin',
               'username': username,
               'password': password
            },
            dataType: "json",
            success: function (json) {
               if (json.type === "error") {
                  $.wNotice(decodeURIComponent(json.message), {
                     autoclose: 6000,
                     type: json.type,
                     title: json.title
                  });
               } else {
                  window.location.href = config.surl + "/dashboard/";
               }
               $btn.removeClass('loading').prop("disabled", false);
            }
         });
      });

      $("#doPass").on('click', function () {
         const $btn = $(this);
         $btn.addClass('loading');
         const email = $("input[name=pEmail]").val();
         $.ajax({
            type: 'post',
            url: config.url + "/controller.php",
            data: {
               'action': 'uResetPass',
               'email': email,
            },
            dataType: "json",
            success: function (json) {
               $.wNotice(decodeURIComponent(json.message), {
                  autoclose: 6000,
                  type: json.type,
                  title: json.title
               });
               if (json.type === "success") {
                  $btn.prop("disabled", true);
                  $("input[name=pEmail]").val('');
               }
               $btn.removeClass('loading');
            }
         });
      });

   };
})(jQuery);