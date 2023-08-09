(function ($) {
   "use strict";
   $.Master = function (settings) {
      const config = {
         weekstart: 0,
         ampm: 0,
         url: '',
         aurl: '',
         editorCss: '',
         lang: {
            monthsFull: '',
            monthsShort: '',
            weeksFull: '',
            weeksShort: '',
            weeksMed: '',
            dateFormat: 'mm/dd/yyyy',
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
            sellected: "Selected",
            allBtn: "Select All",
            allSel: "all selected",
            sellOne: "Select option",
            doSearch: "Search ...",
            noMatch: "No matches for",
            ok: "OK",
            delMsg1: "Are you sure you want to delete this record?",
            delMsg2: "This action cannot be undone!!!",
            delMsg3: "Trash",
            delMsg5: "Move [NAME] to the archive?",
            delMsg6: "Remove [NAME] from the archive?",
            delMsg7: "Restore [NAME]?",
            delMsg8: "The item will remain in Trash for 30 days. To remove it permanently, go to Trash and empty it.",
            delMsg9: "This action will restore item to it's original state",
            working: "working..."
         }
      };

      if (settings) {
         $.extend(config, settings);
      }

      /* == Navigation == */
      $('.menu > ul > li > ul:not(:has(ul))').addClass('normal-sub');
      $(".menu > ul > li").hover(
        function (e) {
           if ($(window).width() > 768) {
              $(this).children("ul").slideDown(250);
              e.preventDefault();
           }
        },
        function (e) {
           if ($(window).width() > 768) {
              $(this).children("ul").slideUp(150);
              e.preventDefault();
           }
        }
      );

      $(".menu > ul > li").click(function () {
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

      $(".menu-mobile").click(function (e) {
         $(".menu > ul").toggleClass('show-on-mobile');
         e.preventDefault();
      });

      /* == Dark Light Theme == */
      $(document).on('click', '.atheme-switch', function () {
         const mode = $(this).attr("data-mode");
         $("body").attr("data-theme", (mode === "light") ? "dark" : "light");

         $(this).attr("data-mode", (mode === "light") ? "dark" : "light");
         $("span", this).text((mode === "light") ? "Dark" : "Light");

         Cookies.set("CDPA_THEME", (mode === "light") ? "dark" : "light", {
            expires: 360,
            path: '/',
            sameSite: 'strict'
         });
      });

      /* == Transitions == */
      $(document).on('click', '[data-slide="true"]', function () {
         const trigger = $(this).data('trigger');
         $(trigger).slideToggle(100);
      });

      /* == Input focus == */
      $(document).on("focusout", '.wojo.input input, .wojo.input textarea', function () {
         $('.wojo.input').removeClass('focus');
      });
      $(document).on("focusin", '.wojo.input input, .wojo.input textarea', function () {
         $(this).closest('.input').addClass('focus');
      });

      /* == Range Slider == */
      $('input[type="range"]').wRange();

      /* == Tabs == */
      $(".wojo.tabs").wTabs();

      /* == Input Tags == */
      $(".wojo.tags").wTags();

      /* == Progress Bars == */
      $('.wojo.progress').wProgress();

      /* == Accordion == */
      $(".wojo.accordion").wAccordion();

      /* == Number Spinner == */
      $(".wojo.input.number").wNumber();

      /* == Dimmable content == */
      $(document).on('change', '.is_dimmable', function () {
         const dataset = $(this).data('set');
         const status = $('input[type=checkbox]', this).is(':checked') ? 1 : 0;
         const result = $.extend(true, dataset.option[0], {
            "active": status
         });
         $.post(config.url + "/helper.php", result);
         $(dataset.parent).toggleClass("active");
      });

      /* == Datepicker == */
      $('.datepick').wDate({
         months: config.lang.monthsFull,
         short_months: config.lang.monthsShort,
         days_of_week: config.lang.weeksFull,
         short_days: config.lang.weeksShort,
         days_min: config.lang.weeksSmall,
         selected_format: 'DD, mmmm d',
         month_head_format: 'mmmm yyyy',
         format: config.lang.dateFormat,
         clearBtn: true,
         todayBtn: true,
         cancelBtn: true,
         clearBtnLabel: config.lang.clear,
         cancelBtnLabel: config.lang.canBtn,
         okBtnLabel: config.lang.ok,
         todayBtnLabel: config.lang.today,
      }).on('datechanged', function (event) {
         if ($(this).attr("data-element")) {
            const element = $(this).data('element');
            const parent = $(this).data('parent');

            const date = new Date(event.date);
            const day = date.getDate();
            const month = config.lang.monthsFull[date.getMonth()];
            const year = date.getFullYear();
            const formatted = month + ' ' + day + ', ' + year;

            $(parent).html(formatted);
            if ($(element).is(":input")) {
               $(element).val(event.date).trigger('change');
            } else {
               $(element).html(formatted);
            }
         }
      });

      $('.timepick').wTime({
         timeFormat: 'hh:mm:ss.000', // format of the time value (data-time attribute)
         format: 'hh:mm t', // format of the input value
         is24: true, // format 24 hour header
         readOnly: true, // determines if input is readonly
         hourPadding: true, // determines if display value has zero padding for hour value less than 10 (i.e. 05:30 PM); 24-hour format has padding by default
         btnNow: config.lang.now,
         btnOk: config.lang.ok,
         btnCancel: config.lang.canBtn,
      });

      /* == From/To date range == */
      $('#fromdate').wDate({
         rangeTo: $('#enddate'),
         clearBtn: true,
         todayBtn: true,
         cancelBtn: true,
         format: config.lang.dateFormat,
         days_min: config.lang.weeksSmall,
         clearBtnLabel: config.lang.clear,
         cancelBtnLabel: config.lang.canBtn,
         okBtnLabel: config.lang.ok,
         todayBtnLabel: config.lang.today,
      });
      $('#enddate').wDate({
         rangeFrom: $('#fromdate'),
         clearBtn: true,
         todayBtn: true,
         cancelBtn: true,
         format: config.lang.dateFormat,
         days_min: config.lang.weeksSmall,
         clearBtnLabel: config.lang.clear,
         cancelBtnLabel: config.lang.canBtn,
         okBtnLabel: config.lang.ok,
         todayBtnLabel: config.lang.today,
      });

      /* == Inline Edit == */
      $('#editable, .wedit').on('validate', '[data-editable]', function (e, val) {
         if (val === "") {
            return false;
         }
      }).on('change', '[data-editable]', function (e, val) {
         const dataset = $(this).data('set');
         const $this = $(this);

         const result = $.extend(true, dataset, {
            title: val,
         });

         $.ajax({
            type: "POST",
            url: (dataset.url) ? dataset.url : config.url + "/helper.php",
            dataType: "json",
            data: result,
            beforeSend: function () {
               $this.animate({
                  opacity: 0.2
               }, 800);
            },
            success: function (json) {
               $this.animate({
                  opacity: 1
               }, 800);
               setTimeout(function () {
                  $this.html(json.title).fadeIn("slow");
               }, 1000);
            }
         });
      }).editableTableWidget();

      /* == Avatar Upload == */
      $('[data-type="image"]').wavatar({
         text: config.lang.selPic,
         validators: {
            maxWidth: 3200,
            maxHeight: 3200
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

      /* == Basic color picker == */
      $('[data-wcolor="simple"]').each(function () {
         const set = $(this).data('color');
         $(this).wojocolors({
            color: set.color,
            opacity: set.opacity,
            format: set.format,
            mode: "swatches",
            defaultValue: set.color,
            theme: "wojo input color",
            swatches: ["#1abc9c", "#16a085", "#2ecc71", "#27ae60", "#3498db", "#2980b9", "#9b59b6", "#8e44ad", "#34495e", "#2c3e50", "#f1c40f", "#f39c12", "#e67e22", "#d35400", "#e74c3c", "#c0392b", "#ecf0f1", "#bdc3c7", "#95a5a6", "#7f8c8d"
            ]
         });
      });

      /* == Full color picker == */
      $('[data-wcolor="full"]').each(function () {
         const set = $(this).data('color');
         $(this).wojocolors({
            mode: "full",
            opacity: set.opacity,
            color: set.color,
            format: set.format,
            defaultValue: set.color,
            theme: "wojo input color",
            change: function (color) {
               return color;
            },
         });
      });

      /* == Editor == */
      $('.bodypost').redactor({
         replaceTags: {
            'b': 'strong',
            'strike': 'del'
         },
         plugins: ['alignment', 'fontsize', 'imagemanager', 'fullscreen'],
         buttons: ['format', 'fontsize', 'bold', 'italic', 'deleted', 'alignment', 'lists', 'image', 'link', 'fullscreen', 'html'],
         imageUpload: config.url + "/helper.php",
         imageManagerJson: config.url + "/helper.php?action=getImages",
         imageData: {
            action: "eupload",
         },
         callbacks: {
            image: {
               uploadError: function (json) {
                  $.wNotice(json.message, {
                     autoclose: 12000,
                     type: json.type,
                     title: json.title
                  });
               }
            }
         }
      });

      $('.altpost').redactor({
         minHeight: "200px",
         plugins: ['alignment', 'fontsize', 'fullscreen'],
         buttons: ['format', 'fontsize', 'bold', 'italic', 'deleted', 'alignment', 'lists', 'fullscreen', 'html'],
      });

      /* == Clear Session Debug Queries == */
      $("#debug-panel").on('click', 'a.clear_session', function () {
         $.post(config.url + '/helper.php', {
            iaction: "session"
         });
         $(this).css('color', '#222');
      });

      /* == Check All == */
      $('#masterCheckbox').click(function () {
         const parent = $(this).data('parent');
         const $checkbox = $(parent).find(':checkbox');
         $checkbox.prop('checked', !$checkbox.prop('checked'));
      });

      /* == Master Form == */
      $(document).on('click', 'button[name=dosubmit]', function () {

         const $button = $(this);
         const action = $(this).data('action');
         const $form = $(this).closest("form");
         const asseturl = $(this).data('url');
         const hide = $(this).data('hide');

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
               $('main').transition("scaleOut", {
                  duration: 1000,
                  complete: function () {
                     window.location.href = json.redirect;
                  }
               });
            }
            if (json.type === "success" && hide) {
               $form.transition('fadeOut', {
                  duration: 5000,
                  complete: function () {
                     $form.hide();
                  }
               });
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

      /* == Add/Edit Modal Actions == */
      $(document).on('click', 'a.action, button.action', function () {
         const dataset = $(this).data("set");
         const $parent = dataset.parent;
         const $this = $(this);
         let actions = '';
         const asseturl = dataset.url;
         //var closeb = dataset.buttons === false ? '<div class="header"><h5>Modal Header</h5> </div>' : '';
         const url = asseturl ? config.url + "/" + asseturl : config.url + "/controller.php";

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
               $('.datepick', this).wDate({
                  months: config.lang.monthsFull,
                  short_months: config.lang.monthsShort,
                  days_of_week: config.lang.weeksFull,
                  short_days: config.lang.weeksShort,
                  days_min: config.lang.weeksSmall,
                  selected_format: 'DD, mmmm d',
                  month_head_format: 'mmmm yyyy',
                  format: 'mm/dd/yyyy',
                  clearBtn: true,
                  todayBtn: true,
                  cancelBtn: true,
                  clearBtnLabel: config.lang.clear,
                  cancelBtnLabel: config.lang.canBtn,
                  okBtnLabel: config.lang.ok,
                  todayBtnLabel: config.lang.today,
               }).on('datechanged', function (event) {
                  if ($(this).attr("data-element")) {
                     const element = $(this).data('element');
                     const parent = $(this).data('parent');

                     const date = new Date(event.date);
                     const day = date.getDate();
                     const month = config.lang.monthsFull[date.getMonth()];
                     const year = date.getFullYear();
                     const formatted = month + ' ' + day + ', ' + year;

                     $(parent).html(formatted);
                     if ($(element).is(":input")) {
                        $(element).val(event.date).trigger('change');
                     } else {
                        $(element).html(formatted);
                     }
                  }
               });
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
                              case "remove":
                                 $($parent).transition("scaleOut", {
                                    duration: 600,
                                    complete: function () {
                                       $($parent).remove();
                                    }
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
                              case "refresh":
                                 $(".wojo.mason").wMason("refresh");
                                 break;
                              default:
                                 break;
                           }
                           if (dataset.callback) {
                              const callback = dataset.callback[0];
                              switch (callback.type) {
                                 case "select":
                                    break;
                                 case "mason":
                                    if (callback.method === "refresh") {
                                       $(callback.element).wMason("refresh");
                                    }
                                    break;

                              }
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
               content = "<img src=\"" + config.url + "/images/trash.svg\" class=\"wojo basic center notification image\" alt=\"\">";
               break;

            case "archive":
               icon = "briefcase";
               btnLabel = config.lang.arcBtn;
               header = config.lang.delMsg5.replace('[NAME]', '<span class=\"wojo secondary text\">' + dataset.option[0].title + '</span>');
               content = "<img src=\"" + config.url + "/images/archive.svg\" class=\"wojo basic center notification image\" alt=\"\">";
               break;

            case "unarchive":
               icon = "briefcase alt";
               btnLabel = config.lang.uarcBtn;
               header = config.lang.delMsg6.replace('[NAME]', '<span class=\"wojo secondary text\">' + dataset.option[0].title + '</span>');
               content = "<img src=\"" + config.url + "/images/unarchive.svg\" class=\"wojo basic center notification image\" alt=\"\">";
               break;

            case "restore":
               icon = "undo";
               btnLabel = config.lang.restBtn;
               subtext = '<span class="wojo bold text">' + config.lang.delMsg9 + '</span>';
               header = config.lang.delMsg7.replace('[NAME]', '<span class=\"wojo secondary text\">' + dataset.option[0].title + '</span>');
               content = "<img src=\"" + config.url + "/images/restore.svg\" class=\"wojo basic center notification image\" alt=\"\">";
               break;

            case "delete":
               icon = "delete";
               btnLabel = config.lang.delBtn;
               subtext = '<span class="wojo bold text">' + config.lang.delMsg2 + '</span>';
               header = config.lang.delMsg1;
               content = "<img src=\"" + config.url + "/images/delete.svg\" class=\"wojo basic center notification image\" alt=\"\">";
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
                     $(".wojo.modal").find(".notification.image").attr("src", config.url + "/images/checkmark.svg").transition('rollInTop', {
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