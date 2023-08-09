<?php
    /**
     * Home Search
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: _home_search.tpl.php, v1.00 2022-04-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
?>
<div class="screen-hide tablet-hide mobile-100 phone-100">
  <a class="wojo fluid white button" data-slide="true" data-trigger="#mainSearch"><i
      class="icon sliders horizontal"></i><?php echo Lang::$word->FILTERS; ?></a>
</div>
<div id="mainSearch">
  <div class="wojo-grid">
    <div class="wojo form">
      <form method="get" id="sform" action="<?php echo Url::url("/search"); ?>" name="sform">
        <div class="wojo fields">
          <div class="field">
            <select name="make_id" class="wojo select" data-class="large" id="make_id">
              <option value="">-- <?php echo Lang::$word->LST_MAKE; ?> --</option>
                <?php echo Utility::loopOptions(Utility::jSonToArray($this->core->make_list), "id", "name"); ?>
            </select>
          </div>
          <div class="field">
            <select name="model_id" class="wojo select" data-class="large" id="model_id">
              <option value="">-- <?php echo Lang::$word->LST_MODEL; ?> --</option>
            </select>
          </div>
          <div class="field">
            <select name="transmission" class="wojo select" data-class="large">
              <option value="">-- <?php echo Lang::$word->LST_TRANS; ?> --</option>
                <?php echo Utility::loopOptions(Utility::jSonToArray($this->core->trans_list), "id", "name"); ?>
            </select>
          </div>
        </div>
        <div class="wojo fields">
          <div class="field">
            <select name="price" class="wojo select" data-class="large">
              <option value="">-- <?php echo Lang::$word->LST_PRICE; ?> --</option>
              <option value="10">&lt; 5000</option>
              <option value="20">5 000 - 10 000</option>
              <option value="30">10 000 - 20 000</option>
              <option value="40">20 000 - 30 000</option>
              <option value="50">30 000 - 50 000</option>
              <option value="60">50 000 - 75 000</option>
              <option value="70">75 000 - 100 000</option>
              <option value="80">100 000 +</option>
            </select>
          </div>
          <div class="field">
            <select name="miles" class="wojo select" data-class="large">
              <option value="">-- <?php echo $this->core->odometer == "km" ? Lang::$word->KM : Lang::$word->MI; ?> --
              </option>
              <option value="10">&lt; 10000</option>
              <option value="20">10 000 - 30 000</option>
              <option value="30">30 000 - 60 000</option>
              <option value="40">60 000 - 100 000</option>
              <option value="50">100 000 - 150 000</option>
              <option value="60">150 000 - 200 000</option>
              <option value="70">200 000 +</option>
            </select>
          </div>
          <div class="field">
            <div class="wojo fields">
              <div class="field">
                <select name="fuel" class="wojo select" data-class="large">
                  <option value="">-- <?php echo Lang::$word->LST_FUEL; ?> --</option>
                    <?php echo Utility::loopOptions(Utility::jSonToArray($this->core->fuel_list), "id", "name"); ?>
                </select>
              </div>
              <div class="field">
                <select name="year" class="wojo select" data-search="true" data-class="large">
                  <option value="">-- <?php echo Lang::$word->LST_YEAR; ?> --</option>
                    <?php echo Utility::doRange($this->core->minyear, $this->core->maxyear, 1); ?>
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="center aligned">
          <button type="submit" name="search" class="wojo primary button"><i
              class="icon search"></i><?php echo Lang::$word->HOME_BTN; ?></button>
        </div>
      </form>
    </div>
  </div>
</div>
