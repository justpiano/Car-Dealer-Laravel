<?php
    /**
     * Profile
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: profile.tpl.php, v1.00 2022-04-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
?>
<div class="wojo big vertical padding">
  <div class="wojo-grid">
      <?php include_once(THEMEBASE . '/snippets/dashNav.tpl.php'); ?>
    <div class="row gutters">
      <div class="columns phone-100">
        <h3><?php echo Lang::$word->HOME_SUB40; ?></h3>
        <p><?php echo Lang::$word->HOME_SUB12P; ?></p>
      </div>
      <div class="columns auto phone-100">
        <a href="<?php echo Url::url("/dashboard", "new"); ?>" class="wojo primary fluid button"><i
            class="icon plus"></i><?php echo Lang::$word->LST_ADD; ?></a>
      </div>
    </div>
      <?php if (!$this->data): ?>
          <?php echo Message::msgSingleInfo(Lang::$word->HOME_SUB15P); ?>
      <?php else: ?>
        <div class="wojo mason three">
            <?php foreach ($this->data as $row): ?>
              <div class="wojo attached basic card" id="item_<?php echo $row->id; ?>">
                <a href="<?php echo Url::url("/listing/" . $row->idx, $row->slug); ?>"
                  class="wojo rounded zoom inline image">
                    <?php if ($row->sold): ?>
                      <div class="wojo bookmark"><?php echo Lang::$word->SOLD; ?></div>
                    <?php endif; ?>
                  <img src="<?php echo UPLOADURL . '/listings/thumbs/' . $row->thumb; ?>"
                    alt="<?php echo $row->nice_title; ?>">
                </a>
                <div class="content">
                  <div class="center aligned">
                    <h5 class="truncate">
                      <a href="<?php echo Url::url("/listing/" . $row->idx, $row->slug); ?>"
                        class="secondary"><?php echo $row->nice_title; ?></a>
                    </h5>
                    <div class="wojo horizontal divided list">
                      <div
                        class="item"><?php echo ($row->price_sale > 0) ? Utility::formatMoney($row->price_sale) : Utility::formatMoney($row->price); ?></div>
                      <div
                        class="item"><?php echo $row->status ? '<i class="icon small positive check"></i>' : '<i class="icon negative small circle slash"></i>'; ?><?php echo Lang::$word->ACTIVE; ?></div>
                      <div class="item"><?php echo $row->year; ?></div>
                    </div>
                  </div>
                </div>
                <div class="footer divided">
                  <div class="row">
                    <div class="columns">
                        <?php if ($row->sold): ?>
                          <a class="wojo small simple passive button"><i
                              class="icon check"></i><?php echo Lang::$word->SOLD; ?></a>
                        <?php else: ?>
                          <a id="icon_sold_<?php echo $row->id; ?>"
                            data-set='{"option":[{"iaction":"sold", "id":<?php echo $row->id; ?>}], "url":"/controller.php", "complete":"replaceWith", "parent":"#icon_sold_<?php echo $row->id; ?>"}'
                            class="wojo small primary inverted button iaction"><i
                              class="icon circle slash"></i><?php echo Lang::$word->SOLD_M; ?></a>
                        <?php endif; ?>
                    </div>
                    <div class="columns auto">
                      <a
                        data-set='{"option":[{"delete": "deleteListing","title": "<?php echo Validator::sanitize($row->title, "chars"); ?>","id": <?php echo $row->id; ?>}],"action":"delete","parent":"#item_<?php echo $row->id; ?>"}'
                        class="wojo small negative inverted button data"><i
                          class="icon trash"></i><?php echo Lang::$word->DELETE; ?></a>
                    </div>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
        </div>
      <?php endif; ?>
  </div>
</div>