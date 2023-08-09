<?php
/**
 * Load Summary
 *
 * @package Wojo Framework
 * @author wojoscripts.com
 * @copyright 2022
 * @version $Id: loadSummary.tpl.php, v1.00 2022-03-02 10:12:05 gewa Exp $
 */
if (!defined("_WOJO"))
  die('Direct access to this location is not allowed.');
?>
<div class="wojo form">
  <table class="wojo basic unstackable table">
    <thead>
      <tr>
        <th colspan="2"><?php echo Lang::$word->M_P_SUMMARY;?></th>
      </tr>
    </thead>
    <tr>
      <td><strong><?php echo Lang::$word->MSM_NAME;?></strong></td>
      <td><?php echo $this->row->title;?></td>
    </tr>
    <tr>
      <td><strong><?php echo Lang::$word->MSM_PRICE;?></strong></td>
      <td><?php echo Utility::formatMoney($this->cart->total);?></td>
    </tr>
    <tr>
      <td><strong><?php echo Lang::$word->DISC;?>
        </strong></td>
      <td class="disc">0.00</td>
    </tr>
    <?php if (App::Core()->enable_tax):?>
    <tr>
      <td><strong><?php echo Lang::$word->TRX_TAX;?></strong></td>
      <td class="totaltax"><?php echo Utility::formatMoney($this->cart->total * $this->cart->tax);?></td>
    </tr>
    <?php endif;?>
    <tr>
      <td><strong><?php echo Lang::$word->TRX_TOTAMT;?></strong></td>
      <td class="totalamt"><?php echo Utility::formatMoney($this->cart->tax * $this->cart->total + $this->cart->total);?></td>
    </tr>
    <tr>
      <td><strong><?php echo Lang::$word->MSM_PERIOD;?></strong></td>
      <td><?php echo $this->row->days;?>
        <?php echo Date::getPeriodReadable($this->row->period, $this->row->days);?></td>
    </tr>
    <tr>
      <td><strong><?php echo Lang::$word->MSM_VALIDTO;?></strong></td>
      <td><?php echo Date::doDate("short_date", Date::calculateDays($this->row->id));?></td>
    </tr>
    <tr>
      <td><strong><?php echo Lang::$word->DESCRIPTION;?></strong></td>
      <td><?php echo $this->row->description;?></td>
    </tr>
    <tr>
      <td><strong><?php echo Lang::$word->DC_CODE;?></strong></td>
      <td><div class="wojo small action input">
          <input type="text" placeholder="<?php echo Lang::$word->DC_CODE_I;?>" name="coupon">
          <button name="discount" class="wojo primary icon button" data-id="<?php echo $this->row->id;?>"><i class="icon search"></i></button>
        </div></td>
    </tr>
    <tr id="gatedata">
      <td><strong><?php echo Lang::$word->M_P_PAYWITH;?></strong></td>
      <td><div id="activateCoupon" class="hide-all">
          <a class="wojo basic primary button activateCoupon"><?php echo Lang::$word->ACTIVATE;?></a>
        </div>
        <div id="gateList">
          <div class="row grid phone-2 mobile-3 tablet-4 screen-5 small gutters align center">
            <?php foreach ($this->gateways as $grows):?>
            <div class="columns">
              <a class="wojo basic light icon fluid button sGateway" data-id="<?php echo $grows->id;?>"><img src="<?php echo SITEURL;?>/gateways/<?php echo $grows->dir;?>/<?php echo $grows->name;?>_logo.svg"><?php //echo $grows->displayname;?></a>
            </div>
            <?php endforeach;?>
          </div>
        </div></td>
    </tr>
    <tr>
      <td colspan="2" id="gdata"></td>
    </tr>
  </table>
</div>