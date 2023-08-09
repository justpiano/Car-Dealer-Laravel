<?php
    /**
     * List View
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: _list_search.tpl.php, v1.00 2022-04-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
?>
<?php if ($this->data): ?>
    <?php $keys = array_keys($this->compareData); ?>
    <?php foreach ($this->data as $row): ?>
        <?php $gallery = Utility::jSonToArray($row->gallery) ? count(Utility::jSonToArray($row->gallery)) : null; ?>
    <div class="wojo card spaced listing hasItems">
      <div class="compareList<?php echo ($this->compareData) ? null : " hide-all"; ?>">
        <div class="wojo small checkbox fitted inline">
          <input name="compare" type="checkbox" value="<?php echo $row->id; ?>"
            id="compare_<?php echo $row->id; ?>"<?php echo Validator::getChecked(in_array($row->id, $keys), $row->id); ?>>
          <label for="compare_<?php echo $row->id; ?>"
            class="wojo primary text"><?php echo Lang::$word->COMPARE; ?></label>
        </div>
      </div>
      <div class="big padding left right top">
        <div class="row gutters">
          <div class="columns auto center aligned mobile-100 phone-100">
            <a href="<?php echo Url::url("/listing/" . $row->idx, $row->slug); ?>"
              class="wojo rounded zoom large inline image">
                <?php if ($row->sold): ?>
                  <div class="wojo bookmark"><?php echo Lang::$word->SOLD; ?></div>
                <?php endif; ?>
              <img src="<?php echo UPLOADURL . '/listings/thumbs/' . $row->thumb; ?>"
                alt="<?php echo $row->nice_title; ?>">
                <?php if ($gallery): ?>
                  <span class="wojo icon text galleryCount"><i class="icon images"></i><?php echo $gallery; ?></span>
                <?php endif; ?>
            </a>
          </div>
          <div class="columns mobile-100 phone-100">
            <span class="wojo primary small basic label"><?php echo $row->category_name; ?></span>
            <h4 class="small margin top basic">
              <a href="<?php echo Url::url("/listing/" . $row->idx, $row->slug); ?>"
                class="secondary"><?php echo $row->nice_title; ?></a>
            </h4>
              <?php if ($features = Utility::jSonToArray($row->feature_name)): ?>
                <p
                  class="wojo small text"><?php echo Utility::implodeFields(array_column(array_slice($features, 0, 5), "name"), " &bull; "); ?></p>
              <?php endif; ?>
            <div class="wojo horizontal divided list margin bottom">
              <div class="item"><span class="wojo small primary inverted label"><?php echo $row->year; ?></span></div>
              <div class="item"><span class="wojo small demi text"><?php echo $row->fuel_name; ?></span></div>
              <div class="item"><span class="wojo small demi text"><?php echo $row->trans_name; ?></span></div>
              <div class="item"><span class="wojo small demi text"><?php echo $row->color_name; ?></span></div>
                <?php if ($row->engine): ?>
                  <div class="item">
                    <span
                      class="wojo small demi text"><?php echo Lang::$word->LST_ENGINE; ?>: <?php echo $row->engine; ?></span>
                  </div>
                <?php endif; ?>
              <div class="item"><span
                  class="wojo small demi text"><?php echo number_format($row->mileage, 0, '.', ($this->core->odometer == "km" ? "." : ",")); ?><?php echo $this->core->odometer; ?></span>
              </div>
            </div>
              <?php if ($location = Utility::jSonToArray($row->location_name)): ?>
                <p
                  class="wojo small secondary demi text"><?php echo Lang::$word->LOC_TITLE4; ?>: <?php echo $location->city; ?>, <?php echo $location->state; ?>, <?php echo $location->country; ?></p>
              <?php endif; ?>
          </div>
          <div class="columns auto align self bottom">
            <h6
              class="wojo primary basic text"><?php echo ($row->price_sale > 0) ? Lang::$word->LST_DPRICE_S : Lang::$word->LST_PRICE; ?></h6>
              <?php if ($row->price_sale > 0): ?>
                <p class="wojo basic large demi primary text"><?php echo Utility::formatMoney($row->price_sale); ?></p>
              <?php endif; ?>
            <p
              class="wojo<?php echo ($row->price_sale > 0) ? " strike" : " large demi"; ?> demi text"><?php echo Utility::formatMoney($row->price); ?></p>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
<?php endif; ?>
