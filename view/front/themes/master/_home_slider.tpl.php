<?php
    /**
     * Slider
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: _home_slider.tpl.php, v1.00 2022-04-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
?>
<?php if ($this->sliderdata): ?>
  <div id="mainSlider">
    <div class="wojo-grid">
      <div class="wSlider"
        data-slick='{"slidesToShow": 1, "dots": true, "autoplay": false, "autoplaySpeed" : 5000, "arrows": false, "slidesToScroll": 1}'>
          <?php foreach ($this->sliderdata as $row): ?>
            <div class="holder"><a href="<?php echo $row->url; ?>"><img
                  src="<?php echo UPLOADURL . '/slider/' . $row->thumb; ?>" alt="<?php echo basename($row->thumb); ?>"></a>
              <div class="inner">
                <h3><?php echo $row->caption; ?></h3>
                  <?php echo Url::out_url($row->body); ?></div>
            </div>
          <?php endforeach; ?>
      </div>
    </div>
  </div>
<?php endif; ?>