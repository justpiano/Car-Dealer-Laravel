<?php
    /**
     * Menus
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: menus.tpl.php, v1.00 2022-05-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
?>
<?php switch (Url::segment($this->segments)): case "edit": ?>
    <?php if (!Auth::hasPrivileges('edit_menus')): print Message::msgError(Lang::$word->NOACCESS);
        return; endif; ?>
  <!-- Start edit -->
  <h2><?php echo Lang::$word->MENU_SUB1; ?></h2>
  <p class="wojo small text"><?php echo Lang::$word->MENU_INFO1; ?></p>
  <form method="post" id="wojo_form" name="wojo_form">
    <div class="row gutters">
      <div class="columns screen-70 tablet-50 mobile-100 phone-100">
        <div class="wojo segment form">
          <div class="wojo block fields">
            <div class="field">
              <label><?php echo Lang::$word->MENU_NAME; ?>
                <i class="icon asterisk"></i></label>
              <input type="text" placeholder="<?php echo Lang::$word->MENU_NAME; ?>"
                value="<?php echo $this->row->name; ?>" name="name">
            </div>
            <div class="field">
              <label><?php echo Lang::$word->MENU_TYPE; ?>
                <i class="icon asterisk"></i></label>
              <select name="content_type" id="contenttype">
                <option value=""><?php echo Lang::$word->MENU_TYPE_SEL; ?></option>
                  <?php echo Utility::loopOptionsSimpleAlt($this->contenttype, $this->row->content_type); ?>
              </select>
            </div>
            <div class="field" id="contentid"
              style="display:<?php echo ($this->row->content_type != "web") ? 'block' : 'none'; ?>">
              <label><?php echo Lang::$word->MENU_LINK; ?></label>
              <select name="page_id" id="page_id">
                  <?php if ($this->row->content_type == "page"): ?>
                      <?php echo Utility::loopOptions($this->pagelist, "id", "title", $this->row->page_id); ?>
                  <?php endif; ?>
              </select>
            </div>
            <div id="webid" style="display:<?php echo ($this->row->content_type == "web") ? 'block' : 'none'; ?>">
              <div class="field">
                <label><?php echo Lang::$word->MENU_LINK; ?></label>
                <input type="text" name="web" placeholder="<?php echo Lang::$word->MENU_LINK; ?>"
                  value="<?php echo $this->row->link; ?>">
              </div>
              <div class="field">
                <label><?php echo Lang::$word->MENU_TARGETL; ?></label>
                <select name="target" class="wojo fluid selection dropdown">
                  <option value=""><?php echo Lang::$word->MENU_TARGETL; ?></option>
                  <option
                    value="_blank" <?php echo Validator::getSelected($this->row->target, "_blank"); ?>><?php echo Lang::$word->MENU_TARGET_B; ?></option>
                  <option
                    value="_self" <?php echo Validator::getSelected($this->row->target, "_self"); ?>><?php echo Lang::$word->MENU_TARGET_S; ?></option>
                </select>
              </div>
            </div>
          </div>
          <div class="wojo fields">
            <div class="field">
              <label><?php echo Lang::$word->PUBLISHED; ?></label>
              <div class="wojo checkbox radio fitted inline">
                <input name="active" type="radio" value="1"
                  id="active_1" <?php echo Validator::getChecked($this->row->active, 1); ?>>
                <label for="active_1"><?php echo Lang::$word->YES; ?></label>
              </div>
              <div class="wojo checkbox radio fitted inline">
                <input name="active" type="radio" value="0"
                  id="active_0" <?php echo Validator::getChecked($this->row->active, 0); ?>>
                <label for="active_0"><?php echo Lang::$word->NO; ?></label>
              </div>
            </div>
          </div>
        </div>
        <div class="center aligned"><a href="<?php echo Url::url("/admin/menus"); ?>"
            class="wojo small simple button"><?php echo Lang::$word->CANCEL; ?></a>
          <button type="button" data-action="processMenu" name="dosubmit"
            class="wojo primary button"><?php echo Lang::$word->MENU_UPDATE; ?></button>
        </div>
      </div>
      <div class="columns screen-30 tablet-50 mobile-100 phone-100">
        <h3><?php echo Lang::$word->MENU_LIST; ?></h3>
        <div id="sortlist" class="dd">
            <?php if ($this->sortlist) : echo $this->sortlist; endif; ?>
        </div>
      </div>
    </div>
    <input type="hidden" name="id" value="<?php echo $this->row->id; ?>">
  </form>
  <script src="<?php echo SITEURL; ?>/assets/nestable.js"></script>
  <script type="text/javascript" src="<?php echo ADMINVIEW; ?>/js/menus.js"></script>
  <script type="text/javascript">
     // <![CDATA[
     $(document).ready(function () {
        $.Menus({
           url: "<?php echo ADMINVIEW;?>",
        });
     });
     // ]]>
  </script>
    <?php break; ?>
<?php default: ?>
  <h2><?php echo Lang::$word->MENU_TITLE; ?></h2>
  <p class="wojo small text"><?php echo Lang::$word->MENU_INFO; ?></p>
  <form method="post" id="wojo_form" name="wojo_form">
    <div class="row gutters">
      <div class="columns screen-70 tablet-50 mobile-100 phone-100">
        <div class="wojo segment form">
          <div class="wojo block fields">
            <div class="field">
              <label><?php echo Lang::$word->MENU_NAME; ?>
                <i class="icon asterisk"></i></label>
              <input type="text" placeholder="<?php echo Lang::$word->MENU_NAME; ?>" name="name">
            </div>
            <div class="field">
              <label><?php echo Lang::$word->MENU_TYPE; ?>
                <span data-tooltip="<?php echo Lang::$word->MENU_TYPE_T; ?>"><i class="icon question circle"></i></span></label>
              <select name="content_type" id="contenttype">
                <option value=""><?php echo Lang::$word->MENU_TYPE_SEL; ?></option>
                  <?php echo Utility::loopOptionsSimpleAlt($this->contenttype); ?>
              </select>
            </div>
            <div class="field" id="contentid">
              <label><?php echo Lang::$word->MENU_LINK; ?></label>
              <select name="content_id" id="page_id">
                <option value="0"><?php echo Lang::$word->NONE; ?></option>
              </select>
            </div>
            <div id="webid" class="hide-all">
              <div class="field">
                <label><?php echo Lang::$word->MENU_LINK; ?>
                  <span data-tooltip="<?php echo Lang::$word->MENU_LINK_T; ?>"><i
                      class="icon question circle"></i></span></label>
                <input type="text" name="web" placeholder="<?php echo Lang::$word->MENU_LINK; ?>">
              </div>
              <div class="field">
                <label><?php echo Lang::$word->MENU_TARGETL; ?></label>
                <select name="target">
                  <option value=""><?php echo Lang::$word->MENU_TARGET; ?></option>
                  <option value="_blank"><?php echo Lang::$word->MENU_TARGET_B; ?></option>
                  <option value="_self"><?php echo Lang::$word->MENU_TARGET_S; ?></option>
                </select>
              </div>
            </div>
          </div>
          <div class="wojo fields">
            <div class="field">
              <label><?php echo Lang::$word->PUBLISHED; ?></label>
              <div class="wojo checkbox radio fitted inline">
                <input name="active" type="radio" value="1" id="active_1" checked="checked">
                <label for="active_1"><?php echo Lang::$word->YES; ?></label>
              </div>
              <div class="wojo checkbox radio fitted inline">
                <input name="active" type="radio" value="0" id="active_0">
                <label for="active_0"><?php echo Lang::$word->NO; ?></label>
              </div>
            </div>
          </div>
        </div>
          <?php if (Auth::hasPrivileges('add_menus')): ?>
            <div class="center aligned">
              <button type="button" data-action="processMenu" name="dosubmit"
                class="wojo primary button"><?php echo Lang::$word->MENU_ADD; ?></button>
            </div>
          <?php endif; ?>
      </div>
      <div class="columns screen-30 tablet-50 mobile-100 phone-100">
        <h3><?php echo Lang::$word->MENU_LIST; ?></h3>
        <div id="sortlist" class="dd">
            <?php if ($this->sortlist) : echo $this->sortlist; endif; ?>
        </div>
      </div>
    </div>
  </form>
  <script src="<?php echo SITEURL; ?>/assets/nestable.js"></script>
  <script type="text/javascript" src="<?php echo ADMINVIEW; ?>/js/menus.js"></script>
  <script type="text/javascript">
     // <![CDATA[
     $(document).ready(function () {
        $.Menus({
           url: "<?php echo ADMINVIEW;?>",
        });
     });
     // ]]>
  </script>
    <?php break; ?>
<?php endswitch; ?>