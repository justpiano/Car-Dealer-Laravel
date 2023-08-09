<?php
    /**
     * Maintenance
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: header.tpl.php, v1.00 2022-08-10 10:12:05 gewa Exp $
     */
    const _WOJO = true;
    include('init.php');
    
    if (!App::Core()->offline)
        Url::redirect(SITEURL);
    
    $d = explode("-", App::Core()->offline_d);
    $t = explode(":", App::Core()->offline_t);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title><?php echo App::Core()->company; ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <link rel="shortcut icon" href="<?php echo SITEURL; ?>/assets/favicon.ico" type="image/x-icon">
  <link
    href="<?php echo THEMEURL . '/cache/' . Cache::cssCache(array('color.css', 'base.css', 'transition.css', 'dropdown.css', 'image.css', 'label.css', 'message.css', 'list.css', 'table.css', 'tooltip.css', 'editor.css', 'form.css', 'input.css', 'icon.css', 'button.css', 'card.css', 'modal.css', 'progress.css', 'utility.css', 'slider.css', 'style.css'), THEMEBASE); ?>"
    rel="stylesheet" type="text/css">
  <script src="<?php echo SITEURL; ?>/assets/jquery.js"></script>
</head>
<body>
<header id="mheader">
  <a href="<?php echo SITEURL; ?>/" class="logo">
      <?php echo (App::Core()->logo) ? '<img src="' . SITEURL . '/uploads/' . App::Core()->logo . '" alt="' . App::Core()->company . '">' : App::Core()->company; ?></a>
</header>
<main id="mmain">
  <div class="wojo-grid">
    <div class="row gutters align middle center">
      <div class="columns screen-40 tablet-40 mobile-100 phone-100">
        <figure class="margin bottom phone-hide">
          <img src="<?php echo THEMEURL; ?>/images/maintenance.svg" alt="Maintenance">
        </figure>
      </div>
      <div class="columns screen-40 tablet-60 mobile-100 phone-100">
        <h4>
            <?php echo Lang::$word->HOME_MTNC; ?>
        </h4>
          <?php echo Url::out_url(App::Core()->offline_msg); ?>
        <div id="mdashboard" class="row gutters">
          <div class="columns auto phone-50">
            <div class="dash weeks_dash">
              <div class="digit first">
                <div style="display:none" class="top">1</div>
                <div style="display:block" class="bottom">0</div>
              </div>
              <div class="digit last">
                <div style="display:none" class="top">3</div>
                <div style="display:block" class="bottom">0</div>
              </div>
              <span class="dash_title"><?php echo Lang::$word->_WEEKS; ?></span>
            </div>
          </div>
          <div class="columns auto phone-50">
            <div class="dash days_dash">
              <div class="digit first">
                <div style="display:none" class="top">0</div>
                <div style="display:block" class="bottom">0</div>
              </div>
              <div class="digit last">
                <div style="display:none" class="top">0</div>
                <div style="display:block" class="bottom">0</div>
              </div>
              <span class="dash_title"><?php echo Lang::$word->_DAYS; ?></span>
            </div>
          </div>
          <div class="columns auto phone-50">
            <div class="dash hours_dash">
              <div class="digit first">
                <div style="display:none" class="top">2</div>
                <div style="display:block" class="bottom">0</div>
              </div>
              <div class="digit last">
                <div style="display:none" class="top">3</div>
                <div style="display:block" class="bottom">0</div>
              </div>
              <span class="dash_title"><?php echo Lang::$word->_HOURS; ?></span>
            </div>
          </div>
          <div class="columns auto phone-50">
            <div class="dash minutes_dash">
              <div class="digit first">
                <div style="display:none" class="top">2</div>
                <div style="display:block" class="bottom">0</div>
              </div>
              <div class="digit last">
                <div style="display:none" class="top">9</div>
                <div style="display:block" class="bottom">0</div>
              </div>
              <span class="dash_title"><?php echo Lang::$word->_MINUTES; ?></span>
            </div>
          </div>
        </div>
        <form method="post" id="wojo_form" name="wojo_form">
          <div class="wojo small form">
            <div class="wojo small block fields">
              <div class="field">
                <div class="wojo icon small input">
                  <i class="icon person"></i>
                  <input type="text" placeholder="<?php echo Lang::$word->EMN_NLN; ?>" name="name">
                </div>
              </div>
              <div class="field basic">
                <div class="wojo icon small input">
                  <i class="icon envelope"></i>
                  <input type="text" placeholder="<?php echo Lang::$word->EMN_NLE; ?>" name="email">
                </div>
              </div>
            </div>
            <button type="button" name="dosubmit"
              class="wojo small fluid primary right button"><?php echo Lang::$word->EMN_BTS; ?><i
                class="icon long right arrow"></i></button>
          </div>
        </form>
        <div id="message"></div>
      </div>
    </div>
  </div>
</main>
<div
  id="mfooter">Copyright &copy;<?php echo date('Y') . ' ' . App::Core()->company; ?> Powered by CMS pro v.<?php echo App::Core()->wojov; ?></div>
<script src="<?php echo SITEURL; ?>/assets/countdown.js"></script>
<script>
   $(document).ready(function () {
      $('#mdashboard').countDown({
         targetDate: {
            'day': <?php echo $d[2];?> ,
            'month': <?php echo $d[1];?> ,
            'year': <?php echo $d[0];?> ,
            'hour': <?php echo $t[0];?> ,
            'min': <?php echo $t[1];?> ,
            'sec': 0
         }
      });

      $("button[name=dosubmit]").on("click", function () {
         const $button = $(this);
         const $form = $(this).closest("form");
         const data = {
            name: $("input[name=name]").val(),
            email: $("input[name=email]").val(),
            action: "processNewsletter"
         };

         $.ajax({
            type: "POST",
            url: "<?php echo FRONTVIEW;?>/controller.php",
            data: data,
            dataType: "json",
            encode: true,
            beforeSend: function () {
               $button.addClass("loading").prop("disabled", true);
            },
         }).done(function (json) {
            setTimeout(function () {
               $($button).removeClass("loading").prop("disabled", false);
            }, 500);

            $("#message").html('<div class="wojo small message">' + json.message + '</div>');
            if (json.type === "success") {
               $form[0].reset();
            }
         });

         event.preventDefault();
      });

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
   });
</script>
</body>
</html>