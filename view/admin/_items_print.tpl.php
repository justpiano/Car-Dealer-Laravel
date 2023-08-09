<?php
    /**
     * Print
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: _items_print.tpl.php, v1.00 2022-03-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
?>
<div class="row gutters align middle">
  <div class="columns phone-100">
    <h2><?php echo Lang::$word->LPR_SUB; ?>
      <small class="wojo medium text">// <?php echo $this->row->nice_title; ?></small></h2>
    <p class="wojo small text"><?php echo Lang::$word->LPR_INFO; ?></p>
  </div>
  <div class="columns auto phone-100">
    <a href="<?php echo ADMINVIEW; ?>/helper.php?action=print&id=<?php echo $this->row->id; ?>"
      class="wojo small secondary button"><i class="icon printer"></i><?php echo Lang::$word->PRINT; ?></a>
  </div>
</div>
<div class="row gutters">
  <div class="columns phone-100">
    <div class="center aligned margin bottom"><img src="<?php echo UPLOADURL . '/listings/' . $this->row->thumb; ?>"
        alt=""></div>
      <?php if ($this->gallery): ?>
        <div class="wojo mason three">
            <?php foreach ($this->gallery as $grow): ?>
              <div class="item"><img class="wojo image"
                  src="<?php echo UPLOADURL . '/listings/pics' . $this->row->id . '/thumbs/' . $grow->photo; ?>" alt="">
              </div>
            <?php endforeach; ?>
        </div>
      <?php endif; ?>
  </div>
  <div class="columns phone-100">
    <table class="wojo table">
      <thead>
      <tr>
        <th colspan="2"><?php echo $this->data->year . ' ' . $this->data->name; ?></th>
      </tr>
      </thead>
      <tr>
        <td><?php echo Lang::$word->LST_STOCK; ?></td>
        <td><?php echo Validator::has($this->data->stock_id); ?></td>
      </tr>
      <tr>
        <td><?php echo Lang::$word->LST_VIN; ?></td>
        <td><?php echo Validator::has($this->data->vin); ?></td>
      </tr>
      <tr>
        <td><?php echo Lang::$word->LST_COND; ?></td>
        <td><?php echo $this->data->condition_name; ?></td>
      </tr>
      <tr>
        <td><?php echo Lang::$word->LST_ODM; ?></td>
        <td><?php echo Validator::has($this->data->mileage); ?>
            <?php echo $this->core->odometer; ?></td>
      </tr>
      <tr>
        <td><?php echo Lang::$word->LST_PRICE; ?></td>
        <td><?php echo ($this->data->price_sale) ? '<span class="wojo strike text">' . Utility::formatMoney($this->data->price) . '</span>' : Utility::formatMoney($this->data->price); ?></td>
      </tr>
        <?php if ($this->data->price_sale): ?>
          <tr>
            <td><?php echo Lang::$word->LST_DPRICE_S; ?></td>
            <td><?php echo Validator::has(Utility::formatMoney($this->data->price_sale)); ?></td>
          </tr>
        <?php endif; ?>
      <tr>
        <td><?php echo Lang::$word->LST_ROOM; ?></td>
        <td><?php echo $this->location->name; ?></td>
      </tr>
      <tr>
        <td><?php echo Lang::$word->EMAIL; ?></td>
        <td><?php echo $this->location->email; ?></td>
      </tr>
      <tr>
        <td><?php echo Lang::$word->CF_PHONE; ?></td>
        <td><?php echo $this->location->phone; ?></td>
      </tr>
      <tr>
        <td><?php echo Lang::$word->LST_INTC; ?></td>
        <td><?php echo Validator::has($this->data->color_i); ?></td>
      </tr>
      <tr>
        <td><?php echo Lang::$word->LST_EXTC; ?></td>
        <td><?php echo Validator::has($this->data->color_e); ?></td>
      </tr>
      <tr>
        <td><?php echo Lang::$word->LST_DOORS; ?></td>
        <td><?php echo Validator::has($this->data->doors); ?></td>
      </tr>
      <tr>
        <td><?php echo Lang::$word->LST_ENGINE; ?></td>
        <td><?php echo Validator::has($this->data->engine); ?></td>
      </tr>
      <tr>
        <td><?php echo Lang::$word->LST_TRANS; ?></td>
        <td><?php echo $this->data->trans_name; ?></td>
      </tr>
      <tr>
        <td><?php echo Lang::$word->LST_FUEL; ?>:</td>
        <td><?php echo $this->data->fuel_name; ?></td>
      </tr>
      <tr>
        <td><?php echo Lang::$word->LST_TRAIN; ?>:</td>
        <td><?php echo Validator::has($this->data->drive_train); ?></td>
      </tr>
      <tr>
        <td><?php echo Lang::$word->LST_SPEED; ?>:</td>
        <td><?php echo Validator::has($this->data->top_speed); ?>
            <?php echo ($this->core->odometer == "km") ? 'km/h' : 'mph'; ?></td>
      </tr>
      <tr>
        <td><?php echo Lang::$word->LST_POWER; ?></td>
        <td><?php echo Validator::has($this->data->horse_power); ?></td>
      </tr>
      <tr>
        <td><?php echo Lang::$word->LST_TORQUE; ?></td>
        <td><?php echo Validator::has($this->data->torque); ?></td>
      </tr>
      <tr>
        <td><?php echo Lang::$word->LST_TOWING; ?></td>
        <td><?php echo Validator::has($this->data->towing_capacity); ?></td>
      <tr>
        <td colspan="2"><?php echo str_replace("[SITEURL]", SITEURL, $this->data->body); ?></td>
      </tr>
      <tr>
        <td colspan="2"><?php if ($this->features): ?>
            <div class="row grid small gutters phone-1 mobile-2 tablet-2 screen-2">
                <?php foreach ($this->features as $frow): ?>
                  <div class="columns">
                      <?php echo $frow->name; ?>
                  </div>
                <?php endforeach; ?>
                <?php unset($frow); ?>
            </div>
            <?php endif; ?></td>
      </tr>
    </table>
  </div>
</div>