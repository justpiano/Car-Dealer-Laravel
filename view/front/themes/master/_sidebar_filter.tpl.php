<?php
    /**
     * Sidebar Filter
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: _sidebar_filter.tpl.php, v1.00 2022-04-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
?>
<form id="wojo_form" name="wojo_form" method="get" class="phone-block-hide mobile-block-hide">
  <div class="wojo form top attached basic segment">
      <?php if (!empty($this->qs['make'])): ?>
        <a href="<?php echo Url::buildUrl("make", null, false); ?>"
          class="wojo small dark inverted right icon label"><?php echo Url::doSeo($this->qs['make']); ?>
          <i class="icon x alt small"></i></a>
      <?php endif; ?>
      <?php if (!empty($this->qs['color'])): ?>
        <a href="<?php echo Url::buildUrl("color", null, false); ?>"
          class="wojo small dark inverted right icon label"><?php echo Url::doSeo($this->qs['color']); ?>
          <i class="icon x alt small"></i></a>
      <?php endif; ?>
      <?php if (!empty($this->qs['condition'])): ?>
        <a href="<?php echo Url::buildUrl("condition", null, false); ?>"
          class="wojo small dark inverted right icon label"><?php echo Url::doSeo($this->qs['condition']); ?>
          <i class="icon x alt small"></i></a>
      <?php endif; ?>
      <?php if (!empty($this->qs['body'])): ?>
        <a href="<?php echo Url::buildUrl("body", null, false); ?>"
          class="wojo small dark inverted right icon label"><?php echo Url::doSeo($this->qs['body']); ?>
          <i class="icon x alt small"></i></a>
      <?php endif; ?>
      <?php if (!empty($this->qs['sale'])): ?>
        <a href="<?php echo Url::buildUrl("sale", null, false); ?>"
          class="wojo small dark inverted right icon label"><?php echo Url::doSeo($this->qs['sale']); ?>
          <i class="icon x alt small"></i></a>
      <?php endif; ?>
    <h5><?php echo Lang::$word->LST_COND; ?></h5>
    <div class="wojo small divided items">
      <div class="item align middle">
        <div class="columns">
          <a rel="nofollow" href="<?php echo Url::buildUrl("sale", "sale", false); ?>"
            class="item"><strong><?php echo Lang::$word->HOME_SPCL; ?></strong></a>
        </div>
      </div>
        <?php if ($this->core->cond_list): ?>
            <?php foreach (Utility::jSonToArray($this->core->cond_list) as $cond): ?>
            <div class="item align middle">
              <div class="columns">
                <a rel="nofollow"
                  href="<?php echo Url::buildUrl("condition", strtolower($cond->condition_name), false); ?>"
                  class="item">
                  <strong><?php echo $cond->condition_name; ?></strong></a>
              </div>
              <div class="columns auto">
                <span class="wojo demi text">(<?php echo $cond->total; ?>)</span></div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <div class="wojo auto very wide divider"></div>
      <?php if ($this->core->makes): ?>
        <h5><?php echo Lang::$word->LST_MAKE; ?></h5>
        <div class="wojo small divided items">
            <?php foreach (Utility::jSonToArray($this->core->makes) as $makes): ?>
              <div class="item align middle">
                <div class="columns">
                  <a rel="nofollow" href="<?php echo Url::buildUrl("make", Url::doSeo($makes->make_name), false); ?>"
                    class="item">
                    <strong><?php echo $makes->make_name; ?></strong>
                  </a>
                </div>
                <div class="columns auto">
                  <span class="wojo demi text">(<?php echo $makes->total; ?>)</span></div>
              </div>
            <?php endforeach; ?>
        </div>
      <?php endif; ?>
    <div class="wojo auto very wide divider"></div>
      <?php if ($this->core->color): ?>
        <h5><?php echo Lang::$word->LST_EXTC; ?></h5>
        <div class="wojo small divided items">
            <?php foreach (Utility::jSonToArray($this->core->color) as $color): ?>
              <div class="item align middle">
                <div class="columns">
                  <a rel="nofollow" href="<?php echo Url::buildUrl("color", strtolower($color->color_e), false); ?>"
                    class="item">
                    <strong><?php echo $color->color_e; ?></strong>
                  </a>
                </div>
                <div class="columns auto">
                  <span class="wojo demi text">(<?php echo $color->total; ?>)</span></div>
              </div>
            <?php endforeach; ?>
        </div>
        <div class="wojo auto very wide divider"></div>
      <?php endif; ?>
      <?php if ($this->core->category_list): ?>
        <h5><?php echo Lang::$word->LST_CAT; ?></h5>
        <div class="wojo small divided items">
            <?php foreach (Utility::jSonToArray($this->core->category_list) as $category): ?>
              <div class="item align middle">
                <div class="columns">
                  <a rel="nofollow"
                    href="<?php echo Url::buildUrl("body", Url::doSeo($category->category_name), false); ?>"
                    class="item"><strong><?php echo $category->category_name; ?></strong></a>
                </div>
                <div class="columns auto">
                  <span class="wojo demi text">(<?php echo $category->total; ?>)</span></div>
              </div>
            <?php endforeach; ?>
        </div>
        <div class="wojo auto very wide divider"></div>
      <?php endif; ?>
    <div class="wojo block fields">
        <?php if ($this->core->minprice): ?>
          <div class="field">
            <label><?php echo Lang::$word->PRICE; ?></label>
            <div class="wojo labeled input margin bottom">
              <input class="wNumbers" name="price_min" type="text" placeholder="<?php echo Lang::$word->MIN; ?>"
                value="<?php echo Validator::get("price_min"); ?>">
              <span class="wojo simple label"><?php echo $this->core->minprice; ?></span>
            </div>
            <div class="wojo labeled input">
              <input class="wNumbers" name="price_max" type="text" placeholder="<?php echo Lang::$word->MAX; ?>"
                value="<?php echo Validator::get("price_max"); ?>">
              <span class="wojo simple label"><?php echo $this->core->maxprice; ?></span>
            </div>
          </div>
        <?php endif; ?>
        <?php if ($this->core->minyear): ?>
          <div class="field">
            <label><?php echo Lang::$word->LST_YEAR_MM; ?></label>
            <div class="wojo labeled input">
              <input class="wNumbers" name="year_min" type="text" placeholder="<?php echo Lang::$word->MIN; ?>"
                value="<?php echo Validator::get("year_min"); ?>">
              <span class="wojo simple label"><?php echo $this->core->minyear; ?></span>
              <input class="wNumbers" name="year_max" type="text" placeholder="<?php echo Lang::$word->MAX; ?>"
                value="<?php echo Validator::get("year_max"); ?>">
              <span class="wojo simple label"><?php echo $this->core->maxyear; ?></span>
            </div>
          </div>
        <?php endif; ?>
        <?php if ($this->core->maxkm): ?>
          <div class="field">
            <label><?php echo $this->core->odometer == "km" ? Lang::$word->KM : Lang::$word->MI; ?></label>
            <div class="wojo labeled input margin bottom">
              <input class="wNumbers" name="miles_min" type="text" placeholder="<?php echo Lang::$word->MIN; ?>"
                value="<?php echo Validator::get("miles_min"); ?>">
              <span class="wojo simple label"><?php echo $this->core->minkm; ?></span>
            </div>
            <div class="wojo labeled input">
              <input class="wNumbers" name="miles_max" type="text" placeholder="<?php echo Lang::$word->MAX; ?>"
                value="<?php echo Validator::get("miles_max"); ?>">
              <span class="wojo simple label"><?php echo $this->core->maxkm; ?></span>
            </div>
          </div>
        <?php endif; ?>
      <button class="wojo primary button" type="submit"><?php echo Lang::$word->FIND; ?></button>
    </div>
  </div>
  <input type="hidden" name="condition" value="<?php echo Validator::get("condition"); ?>">
  <input type="hidden" name="make" value="<?php echo Validator::get("make"); ?>">
  <input type="hidden" name="color" value="<?php echo Validator::get("color"); ?>">
  <input type="hidden" name="body" value="<?php echo Validator::get("body"); ?>">
</form>