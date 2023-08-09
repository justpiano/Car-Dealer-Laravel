<?php
    /**
     * Slider
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: slider.tpl.php, v1.00 2022-05-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
    
    if (!Auth::hasPrivileges('manage_slider')): print Message::msgError(Lang::$word->NOACCESS);
        return;
    endif;
?>
<?php switch (Url::segment($this->segments)): case "edit": ?>
  <!-- Start edit -->
  <h2><?php echo Lang::$word->SLD_SUB1; ?></h2>
  <p class="wojo small text"><?php echo Lang::$word->SLD_INFO1; ?></p>
  <form method="post" id="wojo_form" name="wojo_form">
    <div class="wojo segment form">
      <div class="wojo fields">
        <div class="field">
          <label><?php echo Lang::$word->SLD_NAME; ?><i class="icon asterisk"></i></label>
          <input type="text" placeholdder="<?php echo Lang::$word->SLD_NAME; ?>"
            value="<?php echo $this->row->caption; ?>" name="caption">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->SLD_URL; ?><i class="icon asterisk"></i></label>
          <input type="text" placeholdder="<?php echo Lang::$word->SLD_URL; ?>" value="<?php echo $this->row->url; ?>"
            name="url">
        </div>
      </div>
      <div class="wojo block fields">
        <div class="field">
          <label><?php echo Lang::$word->SLD_IMAGE; ?><i class="icon asterisk"></i></label>
          <input type="file" name="thumb" data-type="image"
            data-exist="<?php echo ($this->row->thumb) ? UPLOADURL . '/slider/' . $this->row->thumb : UPLOADURL . '/blank.png'; ?>"
            accept="image/png, image/jpeg">
        </div>
      </div>
      <div class="wojo block fields">
        <div class="field">
          <label><?php echo Lang::$word->SLD_BODY; ?><i class="icon asterisk"></i></label>
          <textarea class="altpost" name="body"><?php echo Url::out_url($this->row->body); ?></textarea>
        </div>
      </div>
      <div class="center aligned">
        <a href="<?php echo Url::url("/admin/slider"); ?>"
          class="wojo small simple button"><?php echo Lang::$word->CANCEL; ?></a>
        <button type="button" data-action="processSlide" name="dosubmit"
          class="wojo secondary button"><?php echo Lang::$word->SLD_UPDATE; ?></button>
      </div>
    </div>
    <input type="hidden" name="id" value="<?php echo $this->row->id; ?>">
  </form>
    <?php break; ?>
  <!-- Start new -->
<?php case "new": ?>
  <h2><?php echo Lang::$word->SLD_SUB2; ?></h2>
  <p class="wojo small text"><?php echo Lang::$word->SLD_INFO2; ?></p>
  <form method="post" id="wojo_form" name="wojo_form">
    <div class="wojo segment form">
      <div class="wojo fields">
        <div class="field">
          <label><?php echo Lang::$word->SLD_NAME; ?><i class="icon asterisk"></i></label>
          <input type="text" placeholdder="<?php echo Lang::$word->SLD_NAME; ?>" name="caption">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->SLD_URL; ?><i class="icon asterisk"></i></label>
          <input type="text" placeholdder="<?php echo Lang::$word->SLD_URL; ?>" name="url">
        </div>
      </div>
      <div class="wojo block fields">
        <div class="field">
          <label><?php echo Lang::$word->SLD_IMAGE; ?><i class="icon asterisk"></i></label>
          <input type="file" name="thumb" data-type="image" accept="image/png, image/jpeg">
        </div>
      </div>
      <div class="wojo block fields">
        <div class="field">
          <label><?php echo Lang::$word->SLD_BODY; ?><i class="icon asterisk"></i></label>
          <textarea class="altpost" name="body"></textarea>
        </div>
      </div>
      <div class="center aligned">
        <a href="<?php echo Url::url("/admin/slider"); ?>"
          class="wojo small simple button"><?php echo Lang::$word->CANCEL; ?></a>
        <button type="button" data-action="processSlide" name="dosubmit"
          class="wojo secondary button"><?php echo Lang::$word->SLD_ADD; ?></button>
      </div>
    </div>
  </form>
    <?php break; ?>
<?php default: ?>
  <div class="row gutters align middle">
    <div class="columns phone-100">
      <h2><?php echo Lang::$word->SLD_SUB; ?></h2>
      <p class="wojo small text"><?php echo Lang::$word->SLD_INFO; ?></p>
    </div>
    <div class="columns auto phone-100">
      <a href="<?php echo Url::url("/admin/slider", "new"); ?>" class="wojo small secondary stacked button"><i
          class="icon plus alt"></i><?php echo Lang::$word->SLD_ADD; ?></a>
    </div>
  </div>
    <?php if (!$this->data): ?>
    <div class="center aligned"><img src="<?php echo ADMINVIEW; ?>/images/nop.svg" alt="">
      <p class="wojo small demi caps text"><?php echo Lang::$word->SLD_NOSLIDE; ?></p>
    </div>
    <?php else: ?>
    <div class="wojo full cards screen-3 tablet-3 mobile-2 phone-1" id="sortable">
        <?php foreach ($this->data as $row): ?>
          <div class="card" id="item_<?php echo $row->id; ?>" data-id="<?php echo $row->id; ?>">
            <img src="<?php echo UPLOADURL; ?>/slider/<?php echo $row->thumb; ?>" alt="" class="wojo rounded image">
            <div class="footer divided center aligned">
              <a href="<?php echo Url::url("/admin/slider/edit", $row->id); ?>"
                class="wojo icon inverted circular primary button"><i class="icon pencil"></i></a>
              <a
                data-set='{"option":[{"trash":"trashSlide","title": "<?php echo Validator::sanitize($row->caption, "chars"); ?>","id": "<?php echo $row->id; ?>"}],"action":"trash", "parent":"#item_<?php echo $row->id; ?>"}'
                class="wojo icon inverted circular negative button data">
                <i class="icon trash"></i>
              </a>
            </div>
          </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
  <script type="text/javascript" src="<?php echo SITEURL; ?>/assets/sortable.js"></script>
  <script type="text/javascript">
     // <![CDATA[
     $(document).ready(function () {
        $("#sortable").sortable({
           ghostClass: "ghost",
           animation: 600,
           onUpdate: function () {
              let order = this.toArray();
              $.ajax({
                 type: 'post',
                 url: "<?php echo ADMINVIEW . '/helper.php';?>",
                 dataType: 'json',
                 data: {
                    iaction: "sortSlide",
                    sorting: order
                 }
              });
           }
        });
     });
     // ]]>
  </script>
    <?php break; ?>
<?php endswitch; ?>