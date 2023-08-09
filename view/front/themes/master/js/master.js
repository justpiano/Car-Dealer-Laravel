(function ($) {
   "use strict";
   $.Master = function (settings) {
      const config = {
         weekstart: 0,
         ampm: 0,
         url: '',
         surl: '',
         theme: '',
         lang: {
            monthsFull: '',
            monthsShort: '',
            weeksFull: '',
            weeksShort: '',
            weeksMed: '',
            today: "Today",
            now: "Now",
            selPic: "Select Picture",
            delBtn: "Delete Record",
            trsBtn: "Move to Trash",
            arcBtn: "Move to Archive",
            uarcBtn: "Restore From Archive",
            restBtn: "Restore Item",
            canBtn: "Cancel",
            clear: "Clear",
            ok: "OK",
            delMsg1: "Are you sure you want to delete this record?",
            delMsg2: "This action cannot be undone!!!",
            working: "working...",
            err1: "file type error",
            err: "Error"
         }
      };

      const hh = $("header").height();

      if (settings) {
         $.extend(config, settings);
      }

      /* == Navigation == */
      $('.menu > ul > li > ul:not(:has(ul))').addClass('normal-sub');
      $(".menu > ul > li").hover(
        function (e) {
           if ($(window).width() > 769) {
              $(this).children("ul").slideDown(250);
              e.preventDefault();
           }
        },
        function (e) {
           if ($(window).width() > 769) {
              $(this).children("ul").slideUp(150);
              e.preventDefault();
           }
        }
      );

      $(".menu > ul > li").on("click", function () {
         const thisMenu = $(this).children("ul");
         const prevState = thisMenu.css('display');
         const icon = $(this).children("a").find(".icon.chevron");
         $(".menu > ul > li > ul").fadeOut();
         if ($(window).width() <= 768) {
            if (prevState !== 'block') {
               thisMenu.fadeIn(150);
               icon.addClass("vertically flipped");
            } else {
               icon.removeClass("vertically flipped");
            }
         }
      });

      $(".menu-mobile").on("click", function (e) {
         $(".menu > ul").toggleClass('show-on-mobile');
         e.preventDefault();
      });
      /*
              if ($("header").length) {
                  $(window).on('scroll', function() {
                      if ($(window).scrollTop() >= hh) {
                          $('header').addClass('fixed');
                      } else {
                          $('header').removeClass('fixed');
                      }
                  });

                  var scrollTop = $(window).scrollTop();
                  if (scrollTop >= hh) {
                      $('header').addClass('fixed');
                  } else {
                      $('header').removeClass('fixed');
                  }
              }
      */
      //Carousel
      $('.wSlider').slick();

      /* == Input focus == */
      $(document).on("focusout", '.wojo.input input, .wojo.input textarea', function () {
         $('.wojo.input').removeClass('focus');
      });
      $(document).on("focusin", '.wojo.input input, .wojo.input textarea', function () {
         $(this).closest('.input').addClass('focus');
      });

      /* == Tabs == */
      $(".wojo.tabs").wTabs();

      /* == Progress Bars == */
      $('.wojo.progress').wProgress();

      /* == Number Spinner == */
      $(".wojo.input.number").wNumber();

      /* == Custom Select == */
      $(".wojo.select").customSelect();

      /* == Range Slider == */
      $('input[type="range"]').wRange();

      /* == Accordion == */
      $(".wojo.accordion").wAccordion();

      //Lightbox
      $('.lightbox').wlightbox();

      //Numbers Only
      $(document).on("keyup", ".wNumbers", function () {
         this.value = this.value.replace(/[^0-9\.]/g, '');
      });

      //Alpha Only
      $(document).on("keyup", ".wLetters", function () {
         this.value = this.value.replace(/[^a-zA-Z\.]/g, '');
      });

      //Reveal phone
      $(document).on("click", ".pnumber", function (e) {
         $(this).find('span').text($(this).data('number'));
         e.preventDefault;
      });


      /* == Transitions == */
      $(document).on('click', '[data-slide="true"]', function () {
         const trigger = $(this).data('trigger');
         $(trigger).slideToggle(100);
         console.log(trigger);
      });

      /* == Scrool to element == */
      $(document).on('click', '[data-scroll="true"]', function (event) {
         event.preventDefault();
         event.stopPropagation();
         const target = $(this).attr('href');
         $("html,body").animate({
            scrollTop: $(target).offset().top - 30
         }, 1200);
         return false;
      });

      /* == Buy membership == */
      $(".mCart").on("click", function () {
         $("#mBlock .segment").removeClass('shadow');
         $(this).closest('.segment').addClass('shadow');
         const id = $(this).data('id');
         const button = $(this);
         button.addClass('loading').prop("disabled", true);
         $.post(config.url + "/controller.php", {
            action: "membership",
            id: id
         }, function (json) {
            $("#mResult").html(json.message);
            $("html,body").animate({
               scrollTop: $("#mResult").offset().top - 40
            }, 500);
            button.removeClass('loading').prop("disabled", false);
         }, "json");
      });

      /* == Membership gateways == */
      $("#mResult").on("click", ".sGateway", function () {
         $("#mResult .sGateway").removeClass('primary');
         const $this = $(this);
         $(this).addClass('primary loading');
         const id = $(this).data('id');
         $.post(config.url + "/controller.php", {
            action: "mGateway",
            id: id
         }, function (json) {
            $("#mResult #gdata").html(json.message);
            $this.removeClass('loading');
            $("html,body").animate({
               scrollTop: $("#mResult #gdata").offset().top - 40
            }, 500);
         }, "json");
      });

      /* == Apply coupon == */
      $("#mResult").on('click', "button[name=discount]", function () {
         const $button = $(this);
         const id = $(this).data('id');
         const $parent = $(this).parent();
         const $input = $("input[name='coupon']");

         if (!$input.val()) {
            $parent.transition('shake');
         } else {
            $button.addClass('loading');
            $.post(config.url + "/controller.php", {
               action: "coupon",
               id: id,
               code: $input.val()
            }, function (json) {
               if (json.type === "success") {
                  $button.children().toggleClass('search check');
                  $button.prop('disabled', true);
                  $(".totaltax").html(json.tax);
                  $(".totalamt").html(json.gtotal);
                  $(".disc").html(json.disc);
                  $(".disc").parent().addClass('highlite');
                  if (json.is_full === 100) {
                     $("#activateCoupon").show();
                     $("#gateList").hide();
                  } else {
                     $("#activateCoupon").hide();
                     $("#gateList").show();
                  }
               } else {
                  $parent.transition('shake');
               }
               $button.removeClass('loading');
            }, "json");
         }
      });

      /* == Gateway Select == */
      $("#mResult").on("click", ".sGateway", function () {
         const button = $(this);
         $("#mResult .sGateway").removeClass('primary');
         button.addClass('primary loading');
         const id = $(this).data('id');
         $.post(config.url + "/controller.php", {
            action: "selectGateway",
            id: id
         }, function (json) {
            $("#mResult #gdata").html(json.message);
            $("html,body").animate({
               scrollTop: $("#gdata").offset().top - 40
            }, 500);
            button.removeClass('loading');
         }, "json");
      });

      /* == Coupon Select == */
      $(document).on("click", ".activateCoupon", function () {
         const $this = $(this);
         $this.addClass('loading');
         $.post(config.url + "/controller.php", {
            action: "activateCoupon",
         }, function (json) {
            if (json.type === "success") {
               window.location.href = window.location.href;
            }
            $this.removeClass('loading');
         }, "json");
      });

      /* == Make/Model selector == */
      $(document).on('change', '#make_id', function () {
         const id = $(this).val();
         const $select = $('select#model_id');
         $.ajax({
            type: "POST",
            url: config.url + '/controller.php',
            data: {
               iaction: "makeList",
               id: id
            },
            dataType: "json",
            success: function (json) {
               $select.html(json.message).customSelect('reset');
            }
         });
      });

      /* == Home Page Carousel Chnager == */
      $(document).on('click', '#catnav a', function () {
         const id = $(this).data('id');
         ($("#catnav a").not($(this))).removeClass("active");
         $(this).addClass("active");

         $("#fcarousel").addClass('wbox-preloader');
         $.ajax({
            type: "POST",
            url: config.url + '/controller.php',
            data: {
               iaction: "popCategory",
               id: id
            },
            dataType: "json",
            success: function (json) {
               if (json.type === "success") {
                  $("#fcarousel").slick('unslick');
                  $("#fcarousel").html(json.html).slick('reinit');
                  $("#fcarousel").removeClass('wbox-preloader');
               }
            }
         });
      });

      /* == Compare == */
      $(document).on('click', '.doCompare', function () {
         $(this).find("span").toggle(120);
         $(".hasItems").find(".compareList").toggle(120);
      });

      $(document).on('click', '.compareButton', function () {
         $(this).parent().toggleClass("active");
         $("#compare").find(".inner").slideToggle(120);
      });

      $(document).on('change', '.hasItems input[name=compare]', function () {
         const $items = $(".hasItems input[name=compare]");
         const id = $(this).val();
         const count = $(".hasItems input[name=compare]:checked").length;

         $(".doCompare span").text("(" + count + ")");
         $(".compareButton span").text("(" + count + ")");

         if ($('#compare').not(':visible')) {
            $('#compare').css("visibility", "visible");
         }
         if (count > 1) {
            $('#compare').find(".cMessage").hide();
            $('#compare').find(".cButton").show();
         }

         if (count === 4) {
            $items.not(":checked").prop('disabled', true);
         } else {
            $items.not(":checked").prop('disabled', false);
         }

         const action = ($(this).prop("checked") === true) ? "compare" : "removeCompare";

         $.ajax({
            type: "GET",
            url: config.url + '/controller.php',
            data: {
               action: action,
               id: id
            },
            dataType: "json",
            success: function (json) {
               if (json.type === "success") {
                  if (json.message === "added") {
                     $('#compare').find(".columns.blank:first").replaceWith(json.html);
                  } else {
                     $('#compare').find("#column_" + id).replaceWith(json.html);
                     if (count == 0) {
                        $('#compare').css("visibility", "hidden");
                     }
                  }
               }
            }
         });
      });

      $('#compare').on('click', 'figure .button', function () {
         const id = $(this).data("id");
         $("#compare_" + id).prop("checked", false).trigger("change");
      });

      /* == Clear Session Debug Queries == */
      $("#debug-panel").on('click', 'a.clear_session', function () {
         $.post(config.url + '/controller.php', {
            iaction: "session"
         });
         $(this).css('color', '#222');
      });

      /* == Master Form == */
      $(document).on('click', 'button[name=dosubmit]', function () {
         const $button = $(this);
         const action = $(this).data('action');
         const $form = $(this).closest("form");
         const asseturl = $(this).data('url');
         const hide = $(this).data('hide');
         const reset = $(this).data('reset');

         function showResponse(json) {
            setTimeout(function () {
               $($button).removeClass("loading").prop("disabled", false);
            }, 500);
            $.wNotice(json.message, {
               autoclose: 12000,
               type: json.type,
               title: json.title
            });
            if (json.type === "success" && json.redirect) {
               setTimeout(function () {
                  $('main').transition("scaleOut", {
                     duration: 4000,
                     complete: function () {
                        window.location.href = json.redirect;
                     }
                  });
               }, 1500);
            }

            if (json.type === "success" && hide) {
               $form.transition('fadeOut', {
                  duration: 5000,
                  complete: function () {
                     $form.hide();
                  }
               });
            }

            if (json.type === "success" && reset) {
               $form[0].reset();
            }
         }

         function showLoader() {
            $($button).addClass("loading").prop("disabled", true);
         }

         const options = {
            target: null,
            beforeSubmit: showLoader,
            success: showResponse,
            type: "post",
            url: asseturl ? config.url + "/" + asseturl + "/controller.php" : config.url + "/controller.php",
            data: {
               action: action
            },
            dataType: 'json'
         };

         $($form).ajaxForm(options).submit();
      });

      /* == Avatar Upload == */
      $('[data-type="image"]').wavatar({
         text: config.lang.selPic,
         validators: {
            maxWidth: 3200,
            maxHeight: 1800
         },
         reject: function (file, errors) {
            if (errors.mimeType) {
               $.wNotice(decodeURIComponent(file.name + ' must be an image.'), {
                  autoclose: 4000,
                  type: "error",
                  title: 'Error'
               });
            }
            if (errors.maxWidth || errors.maxHeight) {
               $.wNotice(decodeURIComponent(file.name + ' must be width:3200px, and height:1800px  max.'), {
                  autoclose: 4000,
                  type: "error",
                  title: 'Error'
               });
            }
         }
      });

      // convert logo svg to editable
      $('.logo img').each(function () {
         const $img = $(this);
         const imgID = $img.attr('id');
         const imgClass = $img.attr('class');
         const imgURL = $img.attr('src');

         $.get(imgURL, function (data) {
            let $svg = $(data).find('svg');
            if (typeof imgID !== 'undefined') {
               $svg = $svg.attr('id', imgID);
            }
            if (typeof imgClass !== 'undefined') {
               $svg = $svg.attr('class', imgClass + ' replaced-svg');
            }
            $svg = $svg.removeAttr('xmlns:a');
            $img.replaceWith($svg);
         }, 'xml');

      });

      /* == Add/Edit Modal Actions == */
      $(document).on('click', 'a.action, button.action', function () {
         const dataset = $(this).data("set");
         const $parent = dataset.parent;
         const $this = $(this);
         let actions = '';
         const url = config.url + dataset.url;

         $.get(url, dataset.option[0], function (data) {
            if (dataset.buttons !== false) {
               actions += '' +
                 '<div class="footer">' +
                 '<button type="button" class="wojo small simple button" data="modal:close">' + config.lang.canBtn + '</button>' +
                 '<button type="button" class="wojo small positive button" data="modal:ok">' + dataset.label + '</button>' +
                 '</div>';
            }

            const $wmodal = $('<div class="wojo ' + dataset.modalclass + ' modal"><div class="dialog" role="document"><div class="content">' +
              '' + data + '' +
              '' + actions + '' +
              '</div></div></div>').on($.modal.BEFORE_OPEN, function () {

            }).modal().on('click', '[data="modal:ok"]', function () {
               $(this).addClass('loading').prop("disabled", true);

               function showResponse(json) {
                  setTimeout(function () {
                     $('[data="modal:ok"]', $wmodal).removeClass('loading').prop("disabled", false);
                     if (json.message) {
                        $.wNotice(decodeURIComponent(json.message), {
                           autoclose: 12000,
                           type: json.type,
                           title: json.title
                        });
                     }
                     if (json.type === "success") {
                        if (dataset.redirect) {
                           setTimeout(function () {
                              $("main").transition('scaleOut');
                              window.location.href = json.redirect;
                           }, 800);
                        } else {
                           switch (dataset.complete) {
                              case "replace":
                                 $($parent).html(json.html).transition('fadeIn', {
                                    duration: 600
                                 });
                                 break;
                              case "replaceWith":
                                 $($this).replaceWith(json.html).transition('fadeIn', {
                                    duration: 600
                                 });
                                 break;
                              case "append":
                                 $($parent).append(json.html).transition('scaleIn', {
                                    duration: 300
                                 });
                                 break;
                              case "prepend":
                                 $($parent).prepend(json.html).transition('scaleIn', {
                                    duration: 300
                                 });
                                 break;
                              case "update":
                                 $($parent).replaceWith(json.html).transition('fadeIn', {
                                    duration: 600
                                 });
                                 break;
                              case "insert":
                                 if (dataset.mode === "append") {
                                    $($parent).append(json.html);
                                 }
                                 if (dataset.mode === "prepend") {
                                    $($parent).prepend(json.html);
                                 }
                                 break;
                              case "highlite":
                                 $($parent).addClass('highlite');
                                 break;
                              default:
                                 break;
                           }
                           $.modal.close();
                        }
                     }

                  }, 500);
               }

               const options = {
                  target: null,
                  success: showResponse,
                  type: "post",
                  url: url,
                  data: dataset.option[0],
                  dataType: 'json'
               };
               $('#modal_form').ajaxForm(options).submit();
            });
         });
      });

      /* == Simple Actions == */
      $(document).on('click', '.iaction', function () {
         const dataset = $(this).data("set");
         const $parent = $(dataset.parent);
         $.ajax({
            type: 'POST',
            url: config.url + dataset.url,
            dataType: 'json',
            data: dataset.option[0]
         }).done(function (json) {
            if (json.type === "success") {
               switch (dataset.complete) {
                  case "remove":
                     $parent.transition("scaleOut", {
                        duration: 300,
                        complete: function () {
                           $parent.remove();
                           if (dataset.callback === "mason") {
                              $(".wojo.mason").wMason("refresh");
                           }
                        }
                     });

                     break;

                  case "replace":
                     $parent.html(json.html).transition('fadeIn', {
                        duration: 600
                     });
                     break;

                  case "replaceWith":
                     $parent.replaceWith(json.html).transition('fadeIn', {
                        duration: 600
                     });
                     break;

                  case "prepend":
                     $parent.prepend(json.html).transition('fadeIn', {
                        duration: 600
                     });
                     break;
               }

               if (dataset.redirect) {
                  setTimeout(function () {
                     $("main").transition('scaleOut');
                     window.location.href = dataset.redirect;
                  }, 800);
               }
            }

            if (json.message) {
               $.wNotice(decodeURIComponent(json.message), {
                  autoclose: 12000,
                  type: json.type,
                  title: json.title
               });
            }

         });
      });

      /* == Modal Delete/Archive/Trash Actions == */
      $(document).on('click', 'a.data', function () {
         const dataset = $(this).data("set");
         const $parent = $(this).closest(dataset.parent);
         const asseturl = dataset.url;
         const url = asseturl ? config.url + "/" + asseturl : config.url + "/controller.php";
         let subtext = dataset.subtext;
         const children = dataset.children ? dataset.children[0] : null;
         const complete = dataset.complete;
         let header;
         let content;
         let icon;
         let btnLabel;

         switch (dataset.action) {
            case "trash":
               icon = "trash";
               btnLabel = config.lang.trsBtn;
               subtext = '<span class="wojo bold text">' + config.lang.delMsg8 + '</span>';
               header = config.lang.delMsg3 + " <span class=\"wojo secondary text\">" + dataset.option[0].title + "?</span>";
               content = "<img src=\"" + config.theme + "/images/trash.svg\" class=\"wojo basic center notification image\" alt='\"\"'>";
               break;

            case "archive":
               icon = "briefcase";
               btnLabel = config.lang.arcBtn;
               header = config.lang.delMsg5.replace('[NAME]', '<span class=\"wojo secondary text\">' + dataset.option[0].title + '</span>');
               content = "<img src=\"" + config.theme + "/images/archive.svg\" class=\"wojo basic center notification image\" alt='\"\"'>";
               break;

            case "unarchive":
               icon = "briefcase alt";
               btnLabel = config.lang.uarcBtn;
               header = config.lang.delMsg6.replace('[NAME]', '<span class=\"wojo secondary text\">' + dataset.option[0].title + '</span>');
               content = "<img src=\"" + config.theme + "/images/unarchive.svg\" class=\"wojo basic center notification image\" alt='\"\"'>";
               break;

            case "restore":
               icon = "undo";
               btnLabel = config.lang.restBtn;
               subtext = '<span class="wojo bold text">' + config.lang.delMsg9 + '</span>';
               header = config.lang.delMsg7.replace('[NAME]', '<span class=\"wojo secondary text\">' + dataset.option[0].title + '</span>');
               content = "<img src=\"" + config.theme + "/images/restore.svg\" class=\"wojo basic center notification image\" alt='\"\"'>";
               break;

            case "delete":
               icon = "delete";
               btnLabel = config.lang.delBtn;
               subtext = '<span class="wojo bold text">' + config.lang.delMsg2 + '</span>';
               header = config.lang.delMsg1;
               content = "<img src=\"" + config.theme + "/images/delete.svg\" class=\"wojo basic center notification image\">";
               break;
         }

         $('<div class="wojo modal"><div class="dialog" role="document"><div class="content">' +
           '<div class="header"><h5>' + header + '</h5></div>' +
           '<div class="body center aligned">' + content + '<p class="margin top">' + subtext + '</p></div>' +
           '<div class="footer">' +
           '<button type="button" class="wojo small simple button" data="modal:close">' + config.lang.canBtn + '</button>' +
           '<button type="button" class="wojo small positive button" data="modal:ok">' + btnLabel + '</button>' +
           '</div></div></div></div>').modal().on('click', '[data="modal:ok"]', function () {
            $(this).addClass('loading').prop("disabled", true);

            $.ajax({
               type: 'POST',
               url: url,
               dataType: 'json',
               data: dataset.option[0]
            }).done(function (json) {
               if (json.type === "success") {
                  if (dataset.redirect) {
                     $.modal.close();
                     $.wNotice(decodeURIComponent(json.message), {
                        autoclose: 4000,
                        type: json.type,
                        title: json.title
                     });
                     $("main").transition("scaleOut", {
                        duration: 4000,
                        complete: function () {
                           window.location.href = dataset.redirect;
                        }
                     });
                  } else {
                     $($parent).transition("scaleOut", {
                        duration: 300,
                        complete: function () {
                           $($parent).remove();
                           if (complete === "refresh") {
                              $(".wojo.mason").wMason("refresh");
                           }
                        }
                     });
                     if (children) {
                        $.each(children, function (i, values) {
                           $.each(values, function (k, v) {
                              if (v === "html") {
                                 $(i).html(json[k]);
                              } else {
                                 $(i).val(json[k]);
                              }
                           });
                        });
                     }
                     $(".wojo.modal").find(".notification.image").attr("src", config.theme + "/images/checkmark.svg").transition('rollInTop', {
                        duration: 500,
                        complete: function () {
                           $.modal.close();
                           $.wNotice(decodeURIComponent(json.message), {
                              autoclose: 6000,
                              type: json.type,
                              title: json.title
                           });
                        }
                     });
                  }
               }
            });
         });
      });
   };
})(jQuery);