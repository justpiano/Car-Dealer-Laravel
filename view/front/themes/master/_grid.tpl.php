<?php
    /**
     * Grid View
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: _grid.tpl.php, v1.00 2022-04-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
?>
<?php if ($this->data): ?>
    <?php $keys = array_keys($this->compareData); ?>
  <div class="row grid gutters screen-2 tablet-2 mobile-1 phone-1">
      <?php foreach ($this->data as $row): ?>
          <?php $gallery = Utility::jSonToArray($row->gallery) ? count(Utility::jSonToArray($row->gallery)) : null; ?>
        <div class="columns">
          <div class="wojo attached basic fitted listing segment hasItems">
            <div class="compareList<?php echo ($this->compareData) ? null : " hide-all"; ?>">
              <div class="wojo small checkbox fitted inline">
                <input name="compare" type="checkbox" value="<?php echo $row->id; ?>"
                  id="compare_<?php echo $row->id; ?>"<?php echo Validator::getChecked(in_array($row->id, $keys), $row->id); ?>>
                <label for="compare_<?php echo $row->id; ?>"
                  class="wojo primary text"><?php echo Lang::$word->COMPARE; ?></label>
              </div>
            </div>
            <a href="<?php echo Url::url("/listing/" . $row->idx, $row->slug); ?>" class="wojo rounded zoom image">
                <?php if ($row->sold): ?>
                  <div class="wojo bookmark"><?php echo Lang::$word->SOLD; ?></div>
                <?php endif; ?>
              <img src="<?php echo UPLOADURL . '/listings/thumbs/' . $row->thumb; ?>"
                alt="<?php echo $row->nice_title; ?>">
                <?php if ($gallery): ?>
                  <span class="wojo icon text galleryCount"><i class="icon images"></i><?php echo $gallery; ?></span>
                <?php endif; ?>
            </a>
            <div class="content">
              <div class="row slign middle small gutters">
                <div class="columns"><span
                    class="wojo primary small basic label"><?php echo $row->category_name; ?></span></div>
                <div class="columns auto"><span
                    class="wojo small primary inverted label"><?php echo $row->year; ?></span></div>
              </div>
              <h5 class="basic truncate">
                <a href="<?php echo Url::url("/listing/" . $row->idx, $row->slug); ?>"
                  class="secondary"><?php echo $row->nice_title; ?></a>
              </h5>
                <?php if ($row->price_sale > 0): ?>
                  <span
                    class="wojo basic large demi primary text"><?php echo Utility::formatMoney($row->price_sale); ?></span>
                <?php endif; ?>
              <span
                class="wojo<?php echo ($row->price_sale > 0) ? " strike" : " large demi"; ?> demi text"><?php echo Utility::formatMoney($row->price); ?></span>
              <div class="wojo small divider"></div>
              <div class="row horizontal small gutters align middle">
                <div class="columns center aligned">
                  <img src="<?php echo UPLOADURL; ?>/images/pump-icon.svg" class="wojo mini inline image"
                    alt="<?php echo $row->nice_title; ?>">
                  <p class="wojo small demi text"><?php echo $row->fuel_name; ?></p>
                </div>
                <div class="columns center aligned">
                  <img src="<?php echo UPLOADURL; ?>/images/dash-icon.svg" alt="<?php echo $row->nice_title; ?>"
                    class="wojo mini inline image">
                  <p
                    class="wojo small demi text"><?php echo number_format($row->mileage, 0, '.', ($this->core->odometer == "km" ? "." : ",")); ?><?php echo $this->core->odometer; ?></p>
                </div>
                <div class="columns center aligned">
                  <img src="<?php echo UPLOADURL; ?>/images/gear-icon.svg" alt="<?php echo $row->nice_title; ?>"
                    class="wojo mini inline image">
                  <p class="wojo small demi text"><?php echo $row->trans_name; ?></p>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
  </div>
<?php endif; ?>
