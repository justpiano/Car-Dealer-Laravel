<?php
    /**
     * Advert
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: advert.tpl.php, v1.00 2022-05-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
    
    if (!Auth::hasPrivileges('manage_advert')): print Message::msgError(Lang::$word->NOACCESS);
        return; endif;
?>
<?php switch (Url::segment($this->segments)): case "edit": ?>
  <!-- Start edit -->
  <h2><?php echo Lang::$word->NWA_SUB1; ?></h2>
  <p class="wojo small text"><?php echo Lang::$word->NWA_INFO1; ?></p>
  <form method="post" id="wojo_form" name="wojo_form">
    <div class="wojo segment form">
      <div class="wojo fields">
        <div class="field">
          <label><?php echo Lang::$word->NWA_NAME; ?><i class="icon asterisk"></i></label>
          <input type="text" placeholdder="<?php echo Lang::$word->NWA_NAME; ?>"
            value="<?php echo $this->row->title; ?>" name="title">
        </div>
      </div>
      <div class="wojo fields">
        <div class="field">
          <label><?php echo Lang::$word->NWA_CONTENT; ?></label>
          <textarea class="bodypost" name="body"><?php echo Url::out_url($this->row->body); ?></textarea>
        </div>
      </div>
      <div class="wojo fields">
        <div class="field">
          <label><?php echo Lang::$word->PUBLISHED; ?></label>
          <div class="wojo checkbox radio inline">
            <input name="active" type="radio" value="1"
              id="active_1" <?php echo Validator::getChecked($this->row->active, 1); ?>>
            <label for="active_1"><?php echo Lang::$word->YES; ?></label>
          </div>
          <div class="wojo checkbox radio inline">
            <input name="active" type="radio" value="0"
              id="active_0" <?php echo Validator::getChecked($this->row->active, 0); ?>>
            <label for="active_0"><?php echo Lang::$word->NO; ?></label>
          </div>
        </div>
      </div>
      <div class="center aligned">
        <a href="<?php echo Url::url("/admin/advert"); ?>"
          class="wojo small simple button"><?php echo Lang::$word->CANCEL; ?></a>
        <button type="button" data-action="processAdvert" name="dosubmit"
          class="wojo secondary button"><?php echo Lang::$word->NWA_UPDATE; ?></button>
      </div>
    </div>
    <input type="hidden" name="id" value="<?php echo $this->row->id; ?>">
  </form>
    <?php break; ?>
  <!-- Start new -->
<?php case "new": ?>
  <h2><?php echo Lang::$word->NWA_SUB2; ?></h2>
  <p class="wojo small text"><?php echo Lang::$word->NWA_INFO2; ?></p>
  <form method="post" id="wojo_form" name="wojo_form">
    <div class="wojo segment form">
      <div class="wojo fields">
        <div class="field">
          <label><?php echo Lang::$word->NWA_NAME; ?><i class="icon asterisk"></i></label>
          <input type="text" placeholdder="<?php echo Lang::$word->NWA_NAME; ?>" name="title">
        </div>
      </div>
      <div class="wojo fields">
        <div class="field">
          <label><?php echo Lang::$word->NWA_CONTENT; ?></label>
          <textarea class="bodypost" name="body"></textarea>
        </div>
      </div>
      <div class="wojo fields">
        <div class="field">
          <label><?php echo Lang::$word->PUBLISHED; ?></label>
          <div class="wojo checkbox radio inline">
            <input name="active" type="radio" value="1" id="active_1" checked="checked">
            <label for="active_1"><?php echo Lang::$word->YES; ?></label>
          </div>
          <div class="wojo checkbox radio inline">
            <input name="active" type="radio" value="0" id="active_0">
            <label for="active_0"><?php echo Lang::$word->NO; ?></label>
          </div>
        </div>
      </div>
      <div class="center aligned">
        <a href="<?php echo Url::url("/admin/advert"); ?>"
          class="wojo small simple button"><?php echo Lang::$word->CANCEL; ?></a>
        <button type="button" data-action="processAdvert" name="dosubmit"
          class="wojo secondary button"><?php echo Lang::$word->NWA_ADD; ?></button>
      </div>
    </div>
  </form>
    <?php break; ?>
<?php default: ?>
  <div class="row gutters align middle">
    <div class="columns phone-100">
      <h2><?php echo Lang::$word->NWA_TITLE; ?></h2>
      <p class="wojo small text"><?php echo Lang::$word->NWA_INFO; ?></p>
    </div>
    <div class="columns auto phone-100">
      <a href="<?php echo Url::url("/admin/advert", "new"); ?>" class="wojo small secondary stacked button"><i
          class="icon plus alt"></i><?php echo Lang::$word->NWA_ADD; ?></a>
    </div>
  </div>
    <?php if (!$this->data): ?>
    <div class="center aligned"><img src="<?php echo ADMINVIEW; ?>/images/nop.svg" alt="">
      <p class="wojo small demi caps text"><?php echo Lang::$word->NWA_NONEWS; ?></p>
    </div>
    <?php else: ?>
    <div class="wojo full cards screen-3 tablet-3 mobile-2 phone-1">
        <?php foreach ($this->data as $row): ?>
          <div class="card" id="item_<?php echo $row->id; ?>">
            <div class="content"><?php echo $row->title; ?></div>
            <div class="footer divided center aligned">
              <a href="<?php echo Url::url("/admin/advert/edit", $row->id); ?>"
                class="wojo icon circular inverted primary button"><i class="icon pencil"></i></a>
              <a
                data-set='{"option":[{"trash":"trashAdvert","title": "<?php echo Validator::sanitize($row->title, "chars"); ?>","id": "<?php echo $row->id; ?>"}],"action":"trash", "parent":"#item_<?php echo $row->id; ?>"}'
                class="wojo icon circular inverted negative button data">
                <i class="icon trash"></i>
              </a>
            </div>
          </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
    <?php break; ?>
<?php endswitch; ?>