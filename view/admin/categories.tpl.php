<?php
    /**
     * Categories
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: categories.tpl.php, v1.00 2022-05-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
    
    if (!Auth::hasPrivileges('manage_cats')): print Message::msgError(Lang::$word->NOACCESS);
        return; endif;
?>
<?php switch (Url::segment($this->segments)): case "edit": ?>
  <!-- Start edit -->
  <h2><?php echo Lang::$word->CAT_SUB1; ?></h2>
  <p class="wojo small text"><?php echo Lang::$word->CAT_INFO1; ?></p>
  <form method="post" id="wojo_form" name="wojo_form">
    <div class="wojo segment form">
      <div class="wojo fields">
        <div class="field">
          <label><?php echo Lang::$word->CAT_NAME; ?></label>
          <input type="text" placeholdder="<?php echo Lang::$word->CAT_NAME; ?>" value="<?php echo $this->row->name; ?>"
            name="name">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->CAT_SLUG; ?>
            <span data-tooltip="<?php echo Lang::$word->CAT_SLUG_T; ?>"><i
                class="icon question circle"></i></span></label>
          <input type="text" placeholdder="<?php echo Lang::$word->CAT_SLUG; ?>" value="<?php echo $this->row->slug; ?>"
            name="slug">
        </div>
      </div>
      <div class="wojo fields">
        <div class="field">
          <label><?php echo Lang::$word->CAT_IMG; ?></label>
          <input type="file" name="image" data-type="image"
            data-exist="<?php echo ($this->row->image) ? UPLOADURL . '/catico/' . $this->row->image : UPLOADURL . 'blank.png'; ?>"
            accept="image/png, image/jpeg">
        </div>
      </div>
      <div class="center aligned">
        <a href="<?php echo Url::url("/admin/categories"); ?>"
          class="wojo small simple button"><?php echo Lang::$word->CANCEL; ?></a>
        <button type="button" data-action="processCategory" name="dosubmit"
          class="wojo secondary button"><?php echo Lang::$word->CAT_UPDATE; ?></button>
      </div>
    </div>
    <input type="hidden" name="id" value="<?php echo $this->row->id; ?>">
  </form>
    <?php break; ?>
  <!-- Start new -->
<?php case "new": ?>
  <h2><?php echo Lang::$word->CAT_SUB2; ?></h2>
  <p class="wojo small text"><?php echo Lang::$word->CAT_INFO2; ?></p>
  <form method="post" id="wojo_form" name="wojo_form">
    <div class="wojo segment form">
      <div class="wojo fields">
        <div class="field">
          <label><?php echo Lang::$word->CAT_NAME; ?></label>
          <input type="text" placeholdder="<?php echo Lang::$word->CAT_NAME; ?>" name="name">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->CAT_SLUG; ?>
            <span data-tooltip="<?php echo Lang::$word->CAT_SLUG_T; ?>"><i
                class="icon question circle"></i></span></label>
          <input type="text" placeholdder="<?php echo Lang::$word->CAT_SLUG; ?>" name="slug">
        </div>
      </div>
      <div class="wojo fields">
        <div class="field">
          <label><?php echo Lang::$word->CAT_IMG; ?></label>
          <input type="file" name="image" data-type="image" accept="image/png, image/jpeg">
        </div>
      </div>
      <div class="center aligned">
        <a href="<?php echo Url::url("/admin/categories"); ?>"
          class="wojo small simple button"><?php echo Lang::$word->CANCEL; ?></a>
        <button type="button" data-action="processCategory" name="dosubmit"
          class="wojo secondary button"><?php echo Lang::$word->CAT_ADD; ?></button>
      </div>
    </div>
  </form>
    <?php break; ?>
<?php default: ?>
  <div class="row gutters align middle">
    <div class="columns phone-100">
      <h2><?php echo Lang::$word->CAT_TITLE; ?></h2>
      <p class="wojo small text"><?php echo Lang::$word->CAT_INFO; ?></p>
    </div>
    <div class="columns auto phone-100">
      <a href="<?php echo Url::url("/admin/categories", "new"); ?>" class="wojo small secondary stacked button"><i
          class="icon plus alt"></i><?php echo Lang::$word->CAT_ADD; ?></a>
    </div>
  </div>
    <?php if (!$this->data): ?>
    <div class="center aligned"><img src="<?php echo ADMINVIEW; ?>/images/nop.svg" alt="">
      <p class="wojo small demi caps text"><?php echo Lang::$word->CAT_NOCAT; ?></p>
    </div>
    <?php else: ?>
    <div class="row grid gutters screen-3 tablet-3 mobile-2 phone-1">
        <?php foreach ($this->data as $row): ?>
          <div class="columns" id="item_<?php echo $row->id; ?>">
            <div class="wojo attached segment">
              <div class="center aligned"><a href="<?php echo Url::url(Router::$path, "edit/" . $row->id); ?>"><img
                    src="<?php echo $row->image ? UPLOADURL . '/catico/' . $row->image : UPLOADURL . '/blank.png'; ?>"
                    alt="" class="wojo inline image"></a>
              </div>
              <p class="center aligned"><a
                  href="<?php echo Url::url(Router::$path, "edit/" . $row->id); ?>"><?php echo $row->name; ?></a>
              </p>
              <div class="center aligned">
                <a href="<?php echo Url::url(Router::$path, "edit/" . $row->id); ?>"
                  class="wojo icon circular inverted primary button"><i class="icon pencil fill"></i></a>
                <a
                  data-set='{"option":[{"delete": "deleteCategory","title": "<?php echo Validator::sanitize($row->name, "chars"); ?>","id": <?php echo $row->id; ?>}],"action":"delete","parent":"#item_<?php echo $row->id; ?>"}'
                  class="wojo circular inverted negative icon button data"><i class="icon trash alt fill"></i></a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
    <?php break; ?>
<?php endswitch; ?>