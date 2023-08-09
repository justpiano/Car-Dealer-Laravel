<?php
    /**
     * List View
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: _list.tpl.php, v1.00 2022-04-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
?>
<?php if ($this->data): ?>
    <?php $keys = array_keys($this->compareData); ?>
    <?php foreach ($this->data as $row): ?>
        <?php $gallery = Utility::jSonToArray($row->gallery) ? count(Utility::jSonToArray($row->gallery)) : null; ?>
    <div class="wojo basic spaced listing segment hasItems">
      <div class="compareList<?php echo ($this->compareData) ? null : " hide-all"; ?>">
        <div class="wojo small checkbox fitted inline">
          <input name="compare" type="checkbox" value="<?php echo $row->id; ?>"
            id="compare_<?php echo $row->id; ?>"<?php echo Validator::getChecked(in_array($row->id, $keys), $row->id); ?>>
          <label for="compare_<?php echo $row->id; ?>"
            class="wojo primary text"><?php echo Lang::$word->COMPARE; ?></label>
        </div>
      </div>
      <div class="row gutters">
        <div class="columns screen-70 tablet-70 mobile-100 phone-100">
          <div class="row gutters align middle">
            <div class="columns screen-50 tablet-50 mobile-100 phone-100 center aligned">
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
            </div>
            <div class="columns screen-50 tablet-50 mobile-100 phone-100">
              <span class="wojo primary basic label"><?php echo $row->category_name; ?></span>
              <h4 class="small top margin">
                <a href="<?php echo Url::url("/listing/" . $row->idx, $row->slug); ?>"
                  class="secondary"><?php echo $row->nice_title; ?></a>
              </h4>
              <span class="wojo small primary inverted label"><?php echo $row->year; ?></span>
            </div>
          </div>
          <div class="row grid vertical divided screen-4 tablet-4 mobile-hide phone-hide">
            <div class="columns">
              <div class="item">
                <div class="columns">
                  <div class="center aligned">
                    <img src="<?php echo UPLOADURL; ?>/images/pump-icon.svg" class="wojo default inline image"
                      alt="<?php echo $row->nice_title; ?>">
                    <p class="wojo small demi text"><?php echo $row->fuel_name; ?></p>
                  </div>
                </div>
              </div>
            </div>
            <div class="columns">
              <div class="item">
                <div class="columns">
                  <div class="center aligned">
                    <img src="<?php echo UPLOADURL; ?>/images/dash-icon.svg" alt="<?php echo $row->nice_title; ?>"
                      class="wojo default inline image">
                    <p
                      class="wojo small demi text"><?php echo number_format($row->mileage, 0, '.', ($this->core->odometer == "km" ? "." : ",")); ?><?php echo $this->core->odometer; ?></p>
                  </div>
                </div>
              </div>
            </div>
            <div class="columns">
              <div class="item">
                <div class="columns">
                  <div class="center aligned">
                    <img src="<?php echo UPLOADURL; ?>/images/gear-icon.svg" alt="<?php echo $row->nice_title; ?>"
                      class="wojo default inline image">
                    <p class="wojo small demi text"><?php echo $row->trans_name; ?></p>
                  </div>
                </div>
              </div>
            </div>
            <div class="columns">
              <div class="item">
                <div class="columns">
                  <div class="center aligned">
                    <img src="<?php echo UPLOADURL; ?>/images/doors-icon.svg" class="wojo default inline image"
                      alt="<?php echo $row->nice_title; ?>">
                    <p class="wojo small demi text"><?php echo $row->doors; ?>
                        <?php echo Lang::$word->LST_DOORS; ?></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="columns screen-30 tablet-30 mobile-100 phone-100 align self bottom">
          <div class="wojo list mb2">
            <div class="item">
              <span
                class="wojo small basic label"><?php echo Lang::$word->LST_SPEED; ?>: <?php echo $row->top_speed . $this->core->odometer; ?></span>
            </div>
              <?php if ($row->engine): ?>
                <div class="item"><span
                    class="wojo small basic label"><?php echo Lang::$word->LST_ENGINE; ?>: <?php echo $row->engine; ?></span>
                </div>
              <?php endif; ?>
            <div class="item"><span
                class="wojo small basic label"><?php echo Lang::$word->COLOR; ?>: <?php echo $row->color_name; ?></span>
            </div>
            <div class="item screen-hide tablet-hide"><i class="icon primary check"></i><?php echo $row->fuel_name; ?>
            </div>
            <div class="item screen-hide tablet-hide"><i
                class="icon primary check"></i><?php echo number_format($row->mileage, 0, '.', ($this->core->odometer == "km" ? "." : ",")); ?><?php echo $this->core->odometer; ?>
            </div>
            <div class="item screen-hide tablet-hide"><i class="icon primary check"></i><?php echo $row->trans_name; ?>
            </div>
            <div class="item screen-hide tablet-hide"><i class="icon primary check"></i><?php echo $row->doors; ?>
                <?php echo Lang::$word->LST_DOORS; ?></div>
          </div>
          <div class="wojo primary full padding rounded inverted bg center aligned">
            <h6
              class="wojo primary basic text"><?php echo ($row->price_sale > 0) ? Lang::$word->LST_DPRICE_S : Lang::$word->LST_PRICE; ?></h6>
              <?php if ($row->price_sale > 0): ?>
                <p class="wojo basic large demi primary text"><?php echo Utility::formatMoney($row->price_sale); ?></p>
              <?php endif; ?>
            <p
              class="wojo<?php echo ($row->price_sale > 0) ? " strike" : " large demi"; ?> demi text"><?php echo Utility::formatMoney($row->price); ?></p>
            <a href="<?php echo Url::url("/listing/" . $row->idx, $row->slug); ?>"
              class="wojo fluid primary button shadow"><?php echo Lang::$word->LST_VIEW_MORE; ?></a>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
<?php endif; ?>
