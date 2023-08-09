<?php
    /**
     * Edit
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: _items_new.tpl.php, v1.00 2022-03-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
?>
<?php if (!Auth::hasPrivileges('edit_items')): print Message::msgError(Lang::$word->NOACCESS);
    return; endif; ?>
<h2><?php echo Lang::$word->LST_TITLE2; ?></h2>
<p class="wojo small text"><?php echo Lang::$word->LST_INFO2; ?></p>
<div class="wojo tabs">
  <ul class="nav">
    <li class="active">
      <a data-tab="general"><i class="icon card text"></i>
          <?php echo Lang::$word->LST_MGEN; ?></a>
    </li>
    <li>
      <a data-tab="features"><i class="icon card list"></i>
          <?php echo Lang::$word->LST_MFET; ?></a>
    </li>
    <li>
      <a data-tab="description"><i class="icon card heading"></i>
          <?php echo Lang::$word->LST_MDSC; ?></a>
    </li>
  </ul>
  <form method="post" id="wojo_form" name="wojo_form">
    <div class="wojo basic tab">
      <div data-tab="general" class="item">
        <div class="wojo segment form">
          <div class="wojo fields">
            <div class="field">
              <label><?php echo Lang::$word->LST_IMAGE; ?></label>
              <input type="file" name="thumb" data-type="image" accept="image/png, image/jpeg">
            </div>
            <div class="field">
              <label><?php echo Lang::$word->LST_YEAR; ?><i class="icon asterisk"></i></label>
              <input type="text" placeholdder="<?php echo Lang::$word->PAG_SLUG; ?>" name="year">
              <div class="wojo big top margin"></div>
              <label><?php echo Lang::$word->LST_ROOM; ?></label>
              <select name="location">
                <option value="">-- <?php echo Lang::$word->LST_ROOM_S; ?> --</option>
                  <?php echo Utility::loopOptions($this->locations, "id", "name"); ?>
              </select>
            </div>
          </div>
          <div class="wojo fields">
            <div class="field">
              <label><?php echo Lang::$word->LST_MAKE; ?></label>
              <select name="make_id" id="make_id">
                <option value="">-- <?php echo Lang::$word->LST_MAKE_S; ?> --</option>
                  <?php echo Utility::loopOptions($this->makes, "id", "name"); ?>
              </select>
            </div>
            <div class="field">
              <label><?php echo Lang::$word->LST_MODEL; ?></label>
              <select name="model_id" id="model_id">
                <option value="">-- <?php echo Lang::$word->LST_MODEL_S; ?> --</option>
              </select>
            </div>
          </div>
          <div class="wojo fields">
            <div class="field">
              <label><?php echo Lang::$word->LST_STOCK; ?></label>
              <input type="text" placeholdder="<?php echo Lang::$word->LST_STOCK; ?>" name="stock_id">
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
              <label><?php echo Lang::$word->FILE; ?></label>
              <input type="file" data-input="false" data-badge="true"
                data-buttonText="<?php echo Lang::$word->BROWSE; ?>" name="file" id="file" class="filestyle">
            </div>
          </div>
          <div class="wojo fields">
            <div class="field">
              <label><?php echo Lang::$word->LST_ODM; ?></label>
              <div class="wojo labeled input">
                <input type="text" placeholdder="<?php echo Lang::$word->LST_ODM; ?>" name="mileage">
                <span class="wojo simple label"><?php echo $this->core->odometer; ?></span>
              </div>
            </div>
            <div class="field">
              <label><?php echo Lang::$word->LST_TORQUE; ?></label>
              <input type="text" placeholdder="<?php echo Lang::$word->LST_TORQUE; ?>" name="torque">
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
              <label><?php echo Lang::$word->LST_DOORS; ?></label>
              <input class="wojo range slider" type="range" min="1" max="6" step="1" name="doors" value="2">
            </div>
            <div class="field">
              <label><?php echo Lang::$word->LST_TRANS; ?></label>
              <select name="transmission">
                <option value="">-- <?php echo Lang::$word->LST_TRANS_S; ?> --</option>
                  <?php echo Utility::loopOptions($this->transmissions, "id", "name"); ?>
              </select>
            </div>
          </div>
          <div class="wojo fields">
            <div class="field">
              <label><?php echo Lang::$word->LST_SPEED; ?></label>
              <div class="wojo labeled input">
                <input type="text" placeholdder="<?php echo Lang::$word->LST_SPEED; ?>" name="top_speed">
                <span class="wojo simple label"><?php echo ($this->core->odometer == "km") ? 'km/h' : 'mph'; ?></span>
              </div>
            </div>
            <div class="field">
              <label><?php echo Lang::$word->LST_FUEL; ?></label>
              <select name="fuel">
                <option value="">-- <?php echo Lang::$word->LST_FUEL_S; ?> --</option>
                  <?php echo Utility::loopOptions($this->fuel, "id", "name"); ?>
              </select>
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
              <label><?php echo Lang::$word->LST_PRICE; ?><i class="icon asterisk"></i></label>
              <div class="wojo labeled input">
                <div class="wojo simple label"><?php echo Utility::currencySymbol(); ?></div>
                <input type="text" placeholdder="<?php echo Lang::$word->LST_PRICE; ?>" name="price">
              </div>
            </div>
            <div class="field">
              <label><?php echo Lang::$word->LST_DPRICE_S; ?></label>
              <div class="wojo labeled input">
                <div class="wojo simple label"><?php echo Utility::currencySymbol(); ?></div>
                <input type="text" placeholdder="<?php echo Lang::$word->LST_DPRICE_S; ?>" name="price_sale">
              </div>
            </div>
            <div class="field disabled">
              <label><?php echo Lang::$word->CREATED; ?></label>
              <input name="last_active" placeholdder="<?php echo Lang::$word->CREATED; ?>" type="text" disabled
                value="<?php echo Date::dodate("long_date", Date::today()); ?>" readonly>
            </div>
            <div class="field">
              <label><?php echo Lang::$word->EXPIRE; ?><span data-tooltip="<?php echo Lang::$word->LST_EXPIRE_T; ?>"
                  data-position="top left"><i class="icon question circle"></i></span></label>
              <div class="wojo icon input" data-datepicker="true">
                <input placeholder="<?php echo Lang::$word->EXPIRE; ?>" name="expire" type="text"
                  value="<?php echo Date::doDate("calendar", Date::NumberOfDays('+ 30 day')); ?>" readonly
                  class="datepick">
                <i class="icon date calendar"></i>
              </div>
            </div>
          </div>
        </div>
        <div class="wojo segment form">
          <div class="wojo fields">
            <div class="field">
              <label><?php echo Lang::$word->LST_IMAGEA; ?></label>
              <input type="file" name="images" id="images" data-input="false" data-btnClass="primary"
                data-text="<?php echo Lang::$word->MULTIPLE; ?>"
                data-fields='{"action":"processImages","id":<?php echo App::Session()->get("imgtoken"); ?>}'
                class="filestyle" multiple>
              <div class="scrollbox margin top h300">
                <div class="row grid phone-1 mobile-2 tablet-3 screen-5 gutters wedit" id="sortable"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div data-tab="features" class="item">
        <div class="wojo segment form">
          <div class="wojo fields">
            <div class="field basic">
              <label><?php echo Lang::$word->LST_FEATURES; ?></label>
              <div class="wojo checkbox toggle inline">
                <input type="checkbox" name="masterCheckbox" data-parent="#features" id="masterCheckbox">
                <label for="masterCheckbox"><?php echo Lang::$word->LST_SEL_ALL; ?></label>
              </div>
              <div class="row" id="features">
                  <?php if ($this->features): ?>
                      <?php foreach ($this->features as $frow): ?>
                      <div class="columns screen-33 tablet-33 mobile-50 phone-100">
                        <div class="wojo checkbox inline">
                          <input name="features[]" type="checkbox" value="<?php echo $frow->id; ?>"
                            id="feat_<?php echo $frow->id; ?>">
                          <label for="feat_<?php echo $frow->id; ?>"><?php echo $frow->name; ?></label>
                        </div>
                      </div>
                      <?php endforeach; ?>
                  <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div data-tab="description" class="item">
        <div class="wojo segment form">
          <div class="wojo fields">
            <div class="field">
              <label><?php echo Lang::$word->LST_NAME; ?><span data-tooltip="<?php echo Lang::$word->LST_NAME_T; ?>"
                  data-position="top left"><i class="icon question circle"></i></span></label>
              <input type="text" placeholdder="<?php echo Lang::$word->LST_NAME; ?>" name="title">
            </div>
            <div class="field">
              <label><?php echo Lang::$word->LST_SLUG; ?><span data-tooltip="<?php echo Lang::$word->LST_SLUG_T; ?>"
                  data-position="top left"><i class="icon question circle"></i></span></label>
              <input type="text" placeholdder="<?php echo Lang::$word->LST_SLUG; ?>" name="slug">
            </div>
          </div>
          <div class="wojo fields">
            <div class="field">
              <label><?php echo Lang::$word->LST_DESC; ?></label>
              <textarea class="bodypost" name="body"></textarea>
            </div>
          </div>
          <div class="wojo fields">
            <div class="field">
              <label><?php echo Lang::$word->LST_METAKEY; ?></label>
              <textarea class="small" placeholder="<?php echo Lang::$word->LST_METAKEY; ?>" name="metakey"></textarea>
            </div>
            <div class="field">
              <label><?php echo Lang::$word->LST_METADESC; ?></label>
              <textarea class="small" placeholder="<?php echo Lang::$word->LST_METADESC; ?>" name="metadesc"></textarea>
            </div>
          </div>
          <div class="wojo fields">
            <div class="field">
              <label><?php echo Lang::$word->LST_NOTES; ?></label>
              <textarea class="small" placeholder="<?php echo Lang::$word->LST_NOTES; ?>" name="notes"></textarea>
            </div>
            <div class="field">
              <label><?php echo Lang::$word->ACTIONS; ?></label>
              <div class="wojo toggle checkbox">
                <input name="featured" type="checkbox" value="1" id="featured_1">
                <label for="featured_1"><?php echo Lang::$word->FEATURED; ?></label>
              </div>
              <div class="wojo toggle checkbox">
                <input name="status" type="checkbox" value="1" id="status_1" checked="checked">
                <label for="status_1"><?php echo Lang::$word->ACTIVE; ?></label>
              </div>
              <div class="wojo toggle checkbox">
                <input name="sold" type="checkbox" value="1" id="sold_1">
                <label for="sold_1"><?php echo Lang::$word->SOLD; ?></label>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="center aligned">
      <a href="<?php echo Url::url("/admin/items"); ?>"
        class="wojo small simple button"><?php echo Lang::$word->CANCEL; ?></a>
      <button type="button" data-action="processItem" name="dosubmit"
        class="wojo secondary button"><?php echo Lang::$word->LST_ADD; ?></button>
    </div>
    <input name="is_owner" type="hidden" value="1">
  </form>
</div>
<script src="<?php echo SITEURL; ?>/assets/sortable.js"></script>
<script src="<?php echo ADMINVIEW; ?>/js/listing.js"></script>
<script type="text/javascript">
   // <![CDATA[
   $(document).ready(function () {
      $.Listing({
         url: "<?php echo ADMINVIEW . '/helper.php';?>",
         vinapi: "<?php echo $this->core->vinapi;?>",
         lang: {
            err: "<?php echo Lang::$word->ERROR;?>",
            err1: "<?php echo Lang::$word->FU_ERROR7;?>",
         }
      });
   });
   // ]]>
</script>