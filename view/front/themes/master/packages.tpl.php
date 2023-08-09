<?php
    /**
     * Packages
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: packages.tpl.php, v1.00 2022-04-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
?>
<div class="wojo big vertical padding relative">
  <div class="wojo-grid relative zi2">
    <h3><?php echo Lang::$word->MSM_PACK; ?></h3>
      <?php if (!App::Auth()->is_User()): ?>
        <div class="wojo primary inverted simple message align middle">
          <i class="icon info square"></i>
          <div class="content left margin">
              <?php echo str_replace(array("[LOGIN]", "[REGISTER]"), array(' <a href="' . Url::url('/login') . '" class="secondary">' . strtolower(Lang::$word->LOGIN) . '</a>', '<a href="' . Url::url('/register') . '" class="secondary">' . strtolower(Lang::$word->SIGNUP) . '</a>'), Lang::$word->MSM_ERROR); ?></div>
        </div>
      <?php endif; ?>
      <?php if ($this->data): ?>
        <div class="row grid screen-4 tablet-2 mobile-1 phone-1 gutters">
            <?php foreach ($this->data as $row): ?>
              <div class="columns">
                <div id="item_<?php echo $row->id; ?>" class="wojo attached basic segment">
                    <?php if ($row->thumb): ?>
                      <img src="<?php echo UPLOADURL; ?>/memberships/<?php echo $row->thumb; ?>"
                        alt="<?php echo $row->title; ?>">
                    <?php else: ?>
                      <img src="<?php echo UPLOADURL; ?>/memberships/default.svg" alt="<?php echo $row->title; ?>">
                    <?php endif; ?>
                  <h4 class="center aligned"><?php echo $row->title; ?></h4>
                  <div class="wojo relaxed divided list margin bottom">
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
                </div>
              </div>
            <?php endforeach; ?>
        </div>
      <?php endif; ?>
  </div>
  <figure class="absolute zi1 hp100 w100 pt pl">
    <svg viewBox="0 0 3000 1000" xmlns="http://www.w3.org/2000/svg" class="ha">
      <path fill="#e5f8f3" d="M-.5-.5v611.1L2999.5-.5z"/>
    </svg>
  </figure>
</div>