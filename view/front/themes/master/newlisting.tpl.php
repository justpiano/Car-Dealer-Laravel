<?php
    /**
     * New Listing
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: newlisting.tpl.php, v1.00 2022-04-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
?>
<div class="wojo big vertical padding relative">
  <div class="wojo-grid relative zi2">
      <?php include_once(THEMEBASE . '/snippets/dashNav.tpl.php'); ?>
    <h3><?php echo Lang::$word->LST_ADD; ?></h3>
    <p><?php echo Lang::$word->HOME_SUB17P; ?>
        <?php echo str_replace(array("[A]", "[B]", "[C]"), array($this->row->total, $this->row->listings, ($this->row->total - $this->row->listings)), Lang::$word->HOME_SUB18P); ?>
    </p>
      <?php if (!$this->row->membership_id or $this->row->listings >= $this->row->total): ?>
          <?php echo Message::msgSingleError(Lang::$word->HOME_MEMEXP); ?>
      <?php else: ?>
        <div class="row align center">
          <div class="columns screen-70 tablet-100 mobile-100 phone-100">
              <?php if (!$this->core->autoapprove): ?>
                <div class="wojo small primary inverted simple icon message">
                  <i class="icon info square align self middle"></i>
                  <div class="content"><?php echo Lang::$word->HOME_APPNOTE; ?></div>
                </div>
              <?php endif; ?>
            <form method="post" id="wojo_form" name="wojo_form">
              <div class="wojo basic segment form">
                <div class="wojo fields">
                  <div class="field">
                    <label><?php echo Lang::$word->LST_NAME; ?><span
                        data-tooltip="<?php echo Lang::$word->LST_NAME_T; ?>" data-position="top left"><i
                          class="icon question circle"></i></span></label>
                    <input type="text" placeholder="<?php echo Lang::$word->LST_NAME; ?>" name="title">
                  </div>
                  <div class="field">
                    <label><?php echo Lang::$word->LST_VIN; ?></label>
                    <div class="wojo action input">
                      <input type="text" placeholdder="<?php echo Lang::$word->LST_VIN; ?>" name="vin">
                        <?php if ($this->core->vinapi): ?>
                          <button type="button" class="wojo primary inverted icon button" id="dovin"><i
                              class="icon search"></i></button>
                        <?php endif; ?>
                    </div>
                  </div>
                </div>
                <div class="wojo fields">
                  <div class="field">
                    <label><?php echo Lang::$word->LST_YEAR; ?><i class="icon asterisk"></i></label>
                    <input type="text" placeholdder="<?php echo Lang::$word->PAG_SLUG; ?>" name="year">
                  </div>
                  <div class="field">
                    <label><?php echo Lang::$word->LST_CAT; ?></label>
                    <select name="category">
                      <option value="">-- <?php echo Lang::$word->LST_CAT_S; ?> --</option>
                        <?php echo Utility::loopOptions($this->categories, "id", "name"); ?>
                    </select>
                  </div>
                  <div class="field">
                    <label><?php echo Lang::$word->LST_COND; ?></label>
                    <select name="vcondition">
                      <option value="">-- <?php echo Lang::$word->LST_COND_S; ?> --</option>
                        <?php echo Utility::loopOptions($this->conditions, "id", "name"); ?>
                    </select>
                  </div>
                </div>
                <div class="wojo fields">
                  <div class="field">
                    <label><?php echo Lang::$word->LST_ROOM; ?></label>
                    <select name="location" id="userLocation">
                      <option value="">-- <?php echo Lang::$word->LST_ROOM_S; ?> --</option>
                        <?php echo Utility::loopOptions($this->locations, "id", "name"); ?>
                    </select>
                  </div>
                  <div class="field">
                    <label><?php echo Lang::$word->LST_MAKE; ?></label>
                    <select name="make_id" id="makes">
                      <option value="">-- <?php echo Lang::$word->LST_MAKE_S; ?> --</option>
                        <?php echo Utility::loopOptions($this->makes, "id", "name"); ?>
                    </select>
                  </div>
                  <div class="field">
                    <label><?php echo Lang::$word->LST_MODEL; ?></label>
                    <select name="model_id" id="models">
                      <option value="">-- <?php echo Lang::$word->LST_MODEL_S; ?> --</option>
                    </select>
                  </div>
                </div>
                <div class="wojo fields">
                  <div class="field">
                    <label><?php echo Lang::$word->LST_TRANS; ?></label>
                    <select name="transmission">
                      <option value="">-- <?php echo Lang::$word->LST_TRANS_S; ?> --</option>
                        <?php echo Utility::loopOptions($this->transmissions, "id", "name"); ?>
                    </select>
                  </div>
                  <div class="field">
                    <label><?php echo Lang::$word->LST_INTC; ?></label>
                    <select name="color_i">
                      <option value="">-- <?php echo Lang::$word->LST_SELCLR; ?> --</option>
                        <?php echo Utility::loopOptionsSimple($this->colors); ?>
                    </select>
                  </div>
                  <div class="field">
                    <label><?php echo Lang::$word->LST_EXTC; ?></label>
                    <select name="color_e">
                      <option value="">-- <?php echo Lang::$word->LST_SELCLR; ?> --</option>
                        <?php echo Utility::loopOptionsSimple($this->colors); ?>
                    </select>
                  </div>
                </div>
                <div class="wojo fields">
                  <div class="field">
                    <label><?php echo Lang::$word->LST_ENGINE; ?></label>
                    <input type="text" placeholdder="<?php echo Lang::$word->LST_ENGINE; ?>" name="engine">
                  </div>
                  <div class="field">
                    <label><?php echo Lang::$word->LST_TRAIN; ?></label>
                    <input type="text" placeholdder="<?php echo Lang::$word->LST_TRAIN; ?>" name="drive_train">
                  </div>
                  <div class="field">
                    <label><?php echo Lang::$word->LST_FUEL; ?></label>
                    <select name="fuel">
                      <option value="">-- <?php echo Lang::$word->LST_FUEL_S; ?> --</option>
                        <?php echo Utility::loopOptions($this->fuel, "id", "name"); ?>
                    </select>
                  </div>
                </div>
                <div class="wojo fields">
                  <div class="field">
                    <label><?php echo Lang::$word->LST_ODM; ?><i class="icon asterisk"></i></label>
                    <div class="wojo labeled input">
                      <input type="text" placeholdder="<?php echo Lang::$word->LST_ODM; ?>" name="mileage">
                      <span class="wojo simple label"><?php echo $this->core->odometer; ?></span>
                    </div>
                  </div>
                  <div class="field">
                    <label><?php echo Lang::$word->LST_SPEED; ?></label>
                    <div class="wojo labeled input">
                      <input type="text" placeholdder="<?php echo Lang::$word->LST_SPEED; ?>" name="top_speed">
                      <span
                        class="wojo simple label"><?php echo ($this->core->odometer == "km") ? 'km/h' : 'mph'; ?></span>
                    </div>
                  </div>
                  <div class="field">
                    <label><?php echo Lang::$word->LST_TORQUE; ?></label>
                    <input type="text" placeholdder="<?php echo Lang::$word->LST_TORQUE; ?>" name="torque">
                  </div>
                </div>
                <div class="wojo fields">
                  <div class="field">
                    <label><?php echo Lang::$word->LST_DOORS; ?></label>
                    <input class="wojo range slider" type="range" min="1" max="6" step="1" name="doors" value="2">
                  </div>
                  <div class="field">
                    <label><?php echo Lang::$word->LST_POWER; ?></label>
                    <input type="text" placeholdder="<?php echo Lang::$word->LST_POWER; ?>" name="horse_power">
                  </div>
                  <div class="field">
                    <label><?php echo Lang::$word->LST_TOWING; ?></label>
                    <input type="text" placeholdder="<?php echo Lang::$word->LST_TOWING; ?>" name="towing_capacity">
                  </div>
                </div>
                <div class="wojo fields">
                  <div class="field">
                    <label><?php echo Lang::$word->LST_IMAGE; ?></label>
                    <input type="file" name="thumb" data-type="image" accept="image/png, image/jpeg" data-class="left">
                  </div>
                  <div class="field">
                    <label><?php echo Lang::$word->LST_PRICE; ?><i class="icon asterisk"></i></label>
                    <div class="wojo labeled input">
                      <div class="wojo simple label"><?php echo Utility::currencySymbol(); ?></div>
                      <input type="text" placeholdder="<?php echo Lang::$word->LST_PRICE; ?>" name="price">
                    </div>
                    <div class="wojo big top margin">
                      <label><?php echo Lang::$word->DASH_SUB11; ?></label>
                      <p><?php echo Date::doDate("calendar", App::Auth()->mem_expire); ?></p>
                    </div>
                  </div>
                </div>
                <div class="wojo fields">
                  <div class="field basic">
                    <label><?php echo Lang::$word->LST_DESC; ?></label>
                    <textarea class="bodypost" name="body"></textarea>
                  </div>
                </div>
              </div>
              <div class="wojo basic segment form">
                <div class="wojo fields">
                  <div class="field basic">
                    <label>10 <?php echo Lang::$word->LST_IMAGEA; ?></label>
                    <input type="file" name="images" id="images" data-input="false" data-btnClass="primary"
                      data-text="<?php echo Lang::$word->MULTIPLE; ?>"
                      data-fields='{"action":"processImages","id":<?php echo App::Session()->get(App::Auth()->username . "_imgtoken"); ?>}'
                      class="filestyle" multiple>
                    <div class="scrollbox margin top h200">
                      <div class="row grid phone-1 mobile-2 tablet-3 screen-5 gutters wedit" id="sortable"></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="wojo basic segment form">
                <div class="wojo fields">
                  <div class="field">
                    <div class="wojo input">
                      <input placeholder="<?php echo Lang::$word->LOC_SUB3; ?>" type="text" name="adrs" id="address">
                    </div>
                    <p class="wojo icon text"><i class="icon info square"></i><?php echo Lang::$word->LOC_INFO3; ?></p>
                  </div>
                  <div class="field auto">
                    <a
                      data-set='{"option":[{"action":"addLocation"}], "label":"<?php echo Lang::$word->LOC_ADD; ?>", "url":"/controller.php", "parent":"#userLocation", "complete":"append", "modalclass":"normal", "lat":0, "lng":0, "zoom":0}'
                      class="wojo primary fluid button action" id="newLocation"><?php echo Lang::$word->LOC_ADD; ?></a>
                  </div>
                </div>
                <div class="wojo fields">
                  <div class="field basic">
                    <div id="map" style="width:100%;height:400px;z-index:300"></div>
                  </div>
                </div>
              </div>
              <div class="wojo basic segment form">
                <div class="wojo fields">
                  <div class="field basic">
                    <label><?php echo Lang::$word->LST_FEATURES; ?></label>
                    <div class="wojo checkbox toggle inline">
                      <input type="checkbox" name="masterCheckbox" data-parent="#features" id="masterCheckbox">
                      <label for="masterCheckbox"><?php echo Lang::$word->LST_SEL_ALL; ?></label>
                    </div>
                    <div id="features">
                        <?php if ($this->features): ?>
                            <?php foreach ($this->features as $frow): ?>
                            <div class="wojo checkbox inline simple">
                              <input name="features[]" type="checkbox" value="<?php echo $frow->id; ?>"
                                id="feat_<?php echo $frow->id; ?>">
                              <label for="feat_<?php echo $frow->id; ?>"><?php echo $frow->name; ?></label>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                  </div>
                </div>
              </div>
              <div class="wojo fields">
                <div class="field">
                  <div class="right aligned">
                    <button type="button" data-action="processItem" name="dosubmit"
                      class="wojo primary button"><?php echo Lang::$word->LST_ADD; ?></button>
                  </div>
                </div>
              </div>
              <input type="hidden" data-geo="lat" name="lat" id="lat" value="0">
              <input type="hidden" data-geo="lng" name="lng" id="lng" value="0">
              <input type="hidden" data-geo="name" name="address">
              <input type="hidden" data-geo="locality" name="city">
              <input type="hidden" data-geo="administrative_area_level_1" name="state">
              <input type="hidden" data-geo="postal_code" name="zip">
              <input type="hidden" data-geo="country" name="country">
            </form>
          </div>
        </div>
      <?php endif; ?>
  </div>
  <figure class="absolute zi1 hp100 w100 pt pl">
    <svg viewBox="0 0 3000 1000" xmlns="http://www.w3.org/2000/svg" class="ha">
      <path fill="#fef8f2" d="M-.5-.5v611.1L2999.5-.5z"/>
    </svg>
  </figure>
</div>
<script defer
  src="https://maps.googleapis.com/maps/api/js?key=<?php echo $this->core->mapapi; ?>&libraries=places&callback=Function.prototype"></script>
<script src="<?php echo SITEURL; ?>/assets/geocomplete.js"></script>
<script src="<?php echo THEMEURL; ?>/js/listing.js"></script>
<script src="<?php echo SITEURL; ?>/assets/sortable.js"></script>
<script type="text/javascript">
   // <![CDATA[
   $(document).ready(function () {
      $.Listing({
         url: '<?php echo FRONTVIEW;?>',
         zoom: 14,
         location: "<?php echo $this->core->city;?>",
         pin: "<?php echo SITEURL;?>/assets/pin.png",
         vinapi: "<?php echo $this->core->vinapi;?>",
         lang: {
            add: "<?php echo Lang::$word->LOC_ADD;?>",
            err: "<?php echo Lang::$word->ERROR;?>",
            err1: "<?php echo Lang::$word->FU_ERROR7;?>",
         }
      });
   });
   // ]]>
</script>