<?php
    /**
     * Coupons
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: coupons.tpl.php, v1.00 2022-05-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
    
    if (!Auth::hasPrivileges('manage_coupons')): print Message::msgError(Lang::$word->NOACCESS);
        return; endif;
?>
<?php switch (Url::segment($this->segments)): case "edit": ?>
  <!-- Start edit -->
  <h2><?php echo Lang::$word->DC_SUB1; ?></h2>
  <p class="wojo small text"><?php echo Lang::$word->DC_INFO1; ?></p>
  <form method="post" id="wojo_form" name="wojo_form">
    <div class="wojo segment form">
      <div class="wojo fields">
        <div class="field">
          <label><?php echo Lang::$word->DC_NAME; ?><i class="icon asterisk"></i></label>
          <input type="text" placeholdder="<?php echo Lang::$word->DC_NAME; ?>" value="<?php echo $this->row->title; ?>"
            name="title">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->DC_CODE; ?><i class="icon asterisk"></i></label>
          <input type="text" placeholdder="<?php echo Lang::$word->DC_CODE; ?>" value="<?php echo $this->row->code; ?>"
            name="code">
        </div>
      </div>
      <div class="wojo fields">
        <div class="field">
          <label><?php echo Lang::$word->DC_DISC; ?>
            <i class="icon asterisk"></i></label>
          <input type="text" placeholdder="<?php echo Lang::$word->DC_DISC; ?>"
            value="<?php echo $this->row->discount; ?>" name="discount">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->DC_TYPE; ?><i class="icon asterisk"></i></label>
          <select name="type">
            <option
              value="p"<?php if ($this->row->type == "p") echo ' selected="selected"'; ?>><?php echo Lang::$word->DC_TYPE_P; ?></option>
            <option
              value="a"<?php if ($this->row->type == "a") echo ' selected="selected"'; ?>><?php echo Lang::$word->DC_TYPE_A; ?></option>
          </select>
        </div>
      </div>
      <div class="wojo fields">
        <div class="field">
          <label><?php echo Lang::$word->MEMBERSHIP; ?>
            <span data-tooltip="<?php echo Lang::$word->DC_MEMBERSHIP_T; ?>" data-position="top left"><i
                class="icon question circle"></i></span></label>
          <a data-wdropdown="#membership_id" class="wojo light right button"><?php echo Lang::$word->MEMBERSHIP; ?>
            <i class="icon chevron down"></i></a>
          <div class="wojo static dropdown small pointing top-left" id="membership_id">
            <div style="max-width:400px">
              <div class="row grid phone-1 mobile-1 tablet-2 screen-2">
                  <?php echo Utility::loopOptionsMultiple($this->mlist, "id", "title", $this->row->membership_id, "membership_id"); ?>
              </div>
            </div>
          </div>
        </div>
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
        <a href="<?php echo Url::url("/admin/coupons"); ?>"
          class="wojo small simple button"><?php echo Lang::$word->CANCEL; ?></a>
        <button type="button" data-action="processCoupon" name="dosubmit"
          class="wojo secondary button"><?php echo Lang::$word->DC_UPDATE; ?></button>
      </div>
    </div>
    <input type="hidden" name="id" value="<?php echo $this->row->id; ?>">
  </form>
    <?php break; ?>
  <!-- Start new -->
<?php case "new": ?>
  <h2><?php echo Lang::$word->DC_SUB2; ?></h2>
  <p class="wojo small text"><?php echo Lang::$word->DC_INFO2; ?></p>
  <form method="post" id="wojo_form" name="wojo_form">
    <div class="wojo segment form">
      <div class="wojo fields">
        <div class="field">
          <label><?php echo Lang::$word->DC_NAME; ?><i class="icon asterisk"></i></label>
          <input type="text" placeholdder="<?php echo Lang::$word->DC_NAME; ?>" name="title">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->DC_CODE; ?><i class="icon asterisk"></i></label>
          <input type="text" placeholdder="<?php echo Lang::$word->DC_CODE; ?>" name="code">
        </div>
      </div>
      <div class="wojo fields">
        <div class="field">
          <label><?php echo Lang::$word->DC_DISC; ?>
            <i class="icon asterisk"></i></label>
          <input type="text" placeholdder="<?php echo Lang::$word->DC_DISC; ?>" name="discount">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->DC_TYPE; ?><i class="icon asterisk"></i></label>
          <select name="type">
            <option value="p"><?php echo Lang::$word->DC_TYPE_P; ?></option>
            <option value="a"><?php echo Lang::$word->DC_TYPE_A; ?></option>
          </select>
        </div>
      </div>
      <div class="wojo fields">
        <div class="field">
          <label><?php echo Lang::$word->MEMBERSHIP; ?>
            <span data-tooltip="<?php echo Lang::$word->DC_MEMBERSHIP_T; ?>" data-position="top left"><i
                class="icon question circle"></i></span></label>
          <a data-wdropdown="#membership_id" class="wojo light right button"><?php echo Lang::$word->MEMBERSHIP; ?>
            <i class="icon chevron down"></i></a>
          <div class="wojo static dropdown small pointing top-left" id="membership_id">
            <div style="max-width:400px">
              <div class="row grid phone-1 mobile-1 tablet-2 screen-2">
                  <?php echo Utility::loopOptionsMultiple($this->mlist, "id", "title", false, "membership_id"); ?>
              </div>
            </div>
          </div>
        </div>
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
        <a href="<?php echo Url::url("/admin/coupons"); ?>"
          class="wojo small simple button"><?php echo Lang::$word->CANCEL; ?></a>
        <button type="button" data-action="processCoupon" name="dosubmit"
          class="wojo secondary button"><?php echo Lang::$word->DC_ADD; ?></button>
      </div>
    </div>
  </form>
    <?php break; ?>
<?php default: ?>
  <div class="row gutters align middle">
    <div class="columns phone-100">
      <h2><?php echo Lang::$word->DC_TITLE; ?></h2>
      <p class="wojo small text"><?php echo Lang::$word->DC_INFO; ?></p>
    </div>
    <div class="columns auto phone-100">
      <a href="<?php echo Url::url("/admin/coupons", "new"); ?>" class="wojo small secondary stacked button"><i
          class="icon plus alt"></i><?php echo Lang::$word->DC_ADD; ?></a>
    </div>
  </div>
    <?php if (!$this->data): ?>
    <div class="center aligned"><img src="<?php echo ADMINVIEW; ?>/images/nop.svg" alt="">
      <p class="wojo small demi caps text"><?php echo Lang::$word->DC_NONDISC; ?></p>
    </div>
    <?php else: ?>
    <table class="wojo fitted segment responsive table">
      <thead>
      <tr>
        <th class="center aligned auto"></th>
        <th><?php echo Lang::$word->DC_NAME; ?></th>
        <th><?php echo Lang::$word->DC_CODE; ?></th>
        <th><?php echo Lang::$word->DC_TYPE; ?></th>
        <th><?php echo Lang::$word->CREATED; ?></th>
        <th class="center aligned"><?php echo Lang::$word->ACTIONS; ?></th>
      </tr>
      </thead>
        <?php foreach ($this->data as $row): ?>
          <tr id="item_<?php echo $row->id; ?>">
            <td><span class="wojo small simple label"><?php echo $row->id; ?></span></td>
            <td><?php echo $row->title; ?></td>
            <td><?php echo $row->code; ?></td>
            <td><?php echo $row->type; ?></td>
            <td><?php echo Date::doDate("short_date", $row->created); ?></td>
            <td class="auto"><a href="<?php echo Url::url("/admin/coupons/edit", $row->id); ?>"
                class="wojo icon primary inverted circular button"><i class="icon pencil"></i></a>
              <a
                data-set='{"option":[{"trash":"trashCoupon","title": "<?php echo Validator::sanitize($row->title, "chars"); ?>","id": "<?php echo $row->id; ?>"}],"action":"trash","subtext":"<?php echo Lang::$word->DELCONFIRM3; ?>","parent":"#item_<?php echo $row->id; ?>"}'
                class="wojo icon circular inverted negative button data">
                <i class="icon trash"></i>
              </a></td>
          </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>
    <?php break; ?>
<?php endswitch; ?>