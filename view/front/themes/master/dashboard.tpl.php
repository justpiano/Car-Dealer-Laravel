<?php
    /**
     * Dashboard
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: dashboard.tpl.php", v1.00 2022-04-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
?>
<div class="wojo big vertical padding">
  <div class="wojo-grid">
      <?php include_once(THEMEBASE . '/snippets/dashNav.tpl.php'); ?>
    <h3><?php echo Lang::$word->HOME_SUB12; ?></h3>
    <p class="wojo big bottom margin"><?php echo Lang::$word->HOME_SUB12P; ?></p>
    <div class="row gutters align center">
      <div class="columns screen-30">
        <div class="wojo primary inverted simple message"><i class="icon large box"></i>
          <div class="content margin left">
            <div class="header"><?php echo Lang::$word->M_CPACKAGE; ?></div>
            <p
              class="wojo secondary text"><?php echo $this->mrow->membership_id ? $this->mrow->title . ' <small>(' . $this->mrow->total . ' ' . Lang::$word->LISTINGS . ')</small>' : "--/--"; ?></p>
          </div>
        </div>
      </div>
      <div class="columns screen-30">
        <div class="wojo primary inverted simple message"><i class="icon large calendar range"></i>
          <div class="content margin left">
            <div class="header"><?php echo Lang::$word->MSM_VALIDTO; ?></div>
            <p
              class="wojo secondary text"><?php echo $this->mrow->membership_id ? Date::doDate("long_date", $this->mrow->membership_expire) : "--/--"; ?></p>
          </div>
        </div>
      </div>
    </div>
      <?php if ($this->data): ?>
        <div id="mBlock" class="row grid screen-4 tablet-3 mobile-1 phone-1 gutters">
            <?php foreach ($this->data as $row): ?>
              <div class="columns">
                <div id="item_<?php echo $row->id; ?>"
                  class="wojo attached basic segment<?php echo App::Auth()->membership_id == $row->id ? ' shadow' : null; ?>">
                    <?php if ($row->thumb): ?>
                      <img src="<?php echo UPLOADURL; ?>/memberships/<?php echo $row->thumb; ?>"
                        alt="<?php echo $row->title; ?>">
                    <?php else: ?>
                      <img src="<?php echo UPLOADURL; ?>/memberships/default.svg" alt="<?php echo $row->title; ?>">
                    <?php endif; ?>
                  <h4 class="center aligned"><?php echo $row->title; ?></h4>
                  <div class="wojo relaxed list margin bottom">
                    <div class="item"><i
                        class="icon calendar month"></i><?php echo $row->days . ' ' . Date::getPeriodReadable($row->period, $row->days); ?>
                    </div>
                    <div
                      class="item"><?php echo ($row->featured) ? '<i class="icon positive check"></i>' : '<i class="icon negative circle slash"></i>'; ?><?php echo Lang::$word->FEATURED; ?>
                    </div>
                    <div class="item"><i class="icon car"></i><span
                        class="wojo demi primary text"><?php echo $row->listings; ?></span>
                      <span class="small margin left"><?php echo Lang::$word->LISTINGS; ?></span></div>
                    <div class="item"><i class="icon credit card"></i><?php echo Utility::formatMoney($row->price); ?>
                    </div>
                  </div>
                  <p class="wojo small text"><?php echo $row->description; ?></p>
                    <?php if (App::Auth()->membership_id != $row->id): ?>
                      <button type="button" class="wojo primary button mCart"
                        data-id="<?php echo $row->id; ?>"><?php echo ($row->price <> 0) ? '<i class="icon basket fill"></i>' : '<i class="icon check circle"></i>'; ?>
                          <?php echo ($row->price <> 0) ? Lang::$word->M_BUY : Lang::$word->M_ACTIVATE; ?></button>
                    <?php else: ?>
                      <span class="wojo simple disabled button"><?php echo Lang::$word->M_ACTIVATE; ?></span>
                    <?php endif; ?>
                </div>
              </div>
            <?php endforeach; ?>
        </div>
      <?php endif; ?>
    <div id="mResult"></div>
  </div>
</div>