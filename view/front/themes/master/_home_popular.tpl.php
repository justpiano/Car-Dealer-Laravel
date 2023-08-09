<?php
    /**
     * Home Popular
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: _home_popular.tpl.php, v1.00 2022-04-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
?>
<?php if ($this->catnav): ?>
  <div id="mainPopular">
    <div class="wojo-grid">
      <h4 class="wojo white text"><?php echo Lang::$word->HOME_SUB2; ?></h4>
      <div class="right aligned mb3" id="catnav">
          <?php foreach ($this->catnav as $crow): ?>
            <a data-id="<?php echo $crow->id; ?>" class="wojo small transparent notify stacked button">
              <span><?php echo $crow->listings; ?></span><?php echo $crow->name; ?>
            </a>
          <?php endforeach; ?>
      </div>
        <?php if ($this->popular): ?>
          <div id="fcarousel" class="wSlider spaced arrowOutside"
            data-slick='{"dots": false, "arrows":true,"mobileFirst":true,"lazyLoad": "ondemand","responsive":[{"breakpoint":1024,"settings":{"slidesToShow": 4,"slidesToScroll": 1}},{ "breakpoint": 769, "settings":{"slidesToShow": 3,"slidesToScroll": 1}},{"breakpoint": 480,"settings":{ "slidesToShow": 1,"slidesToScroll": 1}}]}'>
              <?php foreach ($this->popular as $frow): ?>
                <div class="wojo photo simple attached card">
                    <?php if ($frow->sold): ?>
                      <div class="wojo bookmark"><?php echo Lang::$word->SOLD; ?></div>
                    <?php endif; ?>
                  <a href="<?php echo Url::url("/listing/" . $frow->idx, $frow->slug); ?>"
                    class="wojo top rounded full zoom image">
                    <img src="<?php echo UPLOADURL . '/listings/thumbs/' . $frow->thumb; ?>"
                      data-lazy="<?php echo UPLOADURL . '/listings/thumbs/' . $frow->thumb; ?>" alt=""></a>
                  <div class="footer wojo secondary bg">
                    <h6 class="wojo white text truncate">
                      <a href="<?php echo Url::url("/listing/" . $frow->idx, $frow->slug); ?>"
                        class="white"><?php echo $frow->nice_title; ?></a>
                    </h6>
                    <div class="row small horizontal gutters align middle">
                      <div class="columns"><span class="wojo small primary label"><?php echo $frow->year; ?></span>
                      </div>
                      <div class="columns auto"><span
                          class="wojo primary bold text"><?php echo Utility::formatMoney($frow->price); ?></span></div>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
              <?php unset($frow); ?>
          </div>
        <?php endif; ?>
      <div class="mt1 right aligned">
        <a href="<?php echo Url::url("/listings"); ?>"
          class="wojo primary transparent right button"><?php echo Lang::$word->HOME_BTN1; ?><i
            class="icon chevron right"></i></a>
      </div>
    </div>
  </div>
<?php endif; ?>