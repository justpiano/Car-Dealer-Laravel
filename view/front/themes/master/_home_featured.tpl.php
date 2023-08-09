<?php
    /**
     * Home Feature
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: _home_search.tpl.php, v1.00 2022-04-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
?>
<?php if ($this->special): ?>
  <div id="mainFeatured">
    <div class="wojo-grid">
      <h4 class="mb3"><?php echo Lang::$word->HOME_SUB1; ?></h4>
      <div class="row gutters">
        <div class="columns tablet-100 mobile-100 phone-100">
            <?php if ($this->featured): ?>
              <div class="wojo attached photo card full">
                <a class="wojo full zoom top rounded image"
                  href="<?php echo Url::url("/listing/" . $this->featured[0]->idx, $this->featured[0]->slug . "/"); ?>">
                  <img src="<?php echo UPLOADURL . '/listings/' . $this->featured[0]->thumb; ?>"
                    alt="<?php echo $this->featured[0]->nice_title; ?>"></a>
                <div class="footer wojo secondary bg">
                  <h5 class="basic wojo light text truncate">
                    <a class="white"
                      href="<?php echo Url::url("/listing/" . $this->featured[0]->idx, $this->featured[0]->slug . "/"); ?>"><?php echo $this->featured[0]->nice_title; ?></a>
                  </h5>
                  <div class="wojo relaxed divider inverted"></div>
                  <div class="row small horizontal gutters align middle">
                    <div class="columns auto"><span
                        class="wojo primary label"><?php echo $this->featured[0]->year; ?></span></div>
                    <div class="columns auto"><span
                        class="wojo demi text"><?php echo $this->featured[0]->transmission; ?></span></div>
                    <div class="columns auto"><span
                        class="wojo demi text"><?php echo $this->featured[0]->fuel; ?></span></div>
                    <div class="columns auto"><span
                        class="wojo demi text"><?php echo $this->featured[0]->drive_train; ?></span></div>
                    <div class="columns wojo right aligned"><span
                        class="wojo demi medium white text"><?php echo Utility::formatMoney($this->featured[0]->price); ?></span>
                    </div>
                  </div>
                </div>
              </div>
            <?php endif; ?>
        </div>
        <div class="columns flex tablet-100 mobile-100 phone-100">
          <div class="row grid gutters screen-2 tablet-2 mobile-1 phone-1">
              <?php foreach ($this->special as $i => $srow): ?>
                <div class="columns<?php echo(($i == 2 or $i == 3) ? " end" : null); ?>">
                  <div class="wojo attached photo full card">
                    <a class="wojo full zoom top rounded image"
                      href="<?php echo Url::url("/listing/" . $srow->idx, $srow->slug . "/"); ?>"><img
                        src="<?php echo UPLOADURL . '/listings/' . $srow->thumb; ?>"
                        alt="<?php echo $srow->nice_title; ?>"></a>
                    <div class="footer wojo secondary bg">
                      <h6 class="basic wojo light text truncate">
                        <a class="white"
                          href="<?php echo Url::url("/listing/" . $srow->idx, $srow->slug . "/"); ?>"><?php echo $srow->nice_title; ?></a>
                      </h6>
                      <span class="wojo demi white text"><?php echo Utility::formatMoney($srow->price); ?></span>
                      <div class="wojo divider inverted"></div>
                      <div class="row small horizontal gutters align middle">
                        <div class="columns auto"><span
                            class="wojo small primary label"><?php echo $srow->year; ?></span></div>
                        <div class="columns auto"><span
                            class="wojo small text"><?php echo $srow->transmission; ?></span></div>
                        <div class="columns auto"><span class="wojo small text"><?php echo $srow->fuel; ?></span></div>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>