<?php
    /**
     * Home Reviews
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: _home_reviews.tpl.php", v1.00 2022-04-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
?>
<?php if ($this->reviews): ?>
  <div id="mainReviews">
    <div class="wojo-grid">
      <div class="center aligned mb3">
        <h4 class="wojo primary text"><?php echo Lang::$word->HOME_SUB3; ?></h4>
      </div>
      <div class="row align center">
        <div class="columns screen-80 tablet-80 mobile-100 phone-100">
          <div id="rcarousel" class="wSlider"
            data-slick='{"dots": false, "asNavFor": "#pcarousel","arrows":false,"centerMode": true,"mobileFirst":true,"responsive":[{"breakpoint":1024,"settings":{"slidesToShow": 3,"slidesToScroll": 1}},{ "breakpoint": 769, "settings":{"slidesToShow": 2,"slidesToScroll": 1}},{"breakpoint": 480,"settings":{ "slidesToShow": 1,"slidesToScroll": 1}}]}'>
              <?php foreach ($this->reviews as $wrow): ?>
                <div class="center aligned">
                  <img src="<?php echo UPLOADURL; ?>/avatars/<?php echo ($wrow->avatar) ?: "blank.svg"; ?>" alt=""
                    class="wojo normal image circular center aligned">
                  <h4 class="mt2"><?php echo $wrow->name; ?></h4>
                    <?php if ($wrow->twitter): ?>
                      <p class="mt3">
                        <a target="_blank" href="https://twitter.com/<?php echo $wrow->twitter; ?>"
                          class="wojo icon text"><i class="icon twitter"></i><?php echo $wrow->twitter; ?></a>
                      </p>
                    <?php endif; ?>
                </div>
              <?php endforeach; ?>
              <?php unset($wrow); ?>
          </div>
          <div id="pcarousel" class="wSlider"
            data-slick='{"dots": true,"arrows":false,"fade":true,"asNavFor": "#rcarousel", "slidesToShow": 1,"slidesToScroll": 1}'>
              <?php foreach ($this->reviews as $wrow): ?>
                <div class="center aligned">
                  <p class="wojo thin dark large italic text"><?php echo $wrow->content; ?></p>
                </div>
              <?php endforeach; ?>
              <?php unset($wrow); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>