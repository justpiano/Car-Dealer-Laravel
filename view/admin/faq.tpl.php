<?php
    /**
     * Faq
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: faq.tpl.php, v1.00 2022-05-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
?>
<?php switch (Url::segment($this->segments)): case "edit": ?>
    <?php if (!Auth::hasPrivileges('edit_faq')): print Message::msgError(Lang::$word->NOACCESS);
        return; endif; ?>
  <!-- Start edit -->
  <h2><?php echo Lang::$word->FAQ_SUB1; ?></h2>
  <p class="wojo small text"><?php echo Lang::$word->FAQ_INFO1; ?></p>
  <form method="post" id="wojo_form" name="wojo_form">
    <div class="wojo segment form">
      <div class="wojo block fields">
        <div class="field">
          <label><?php echo Lang::$word->FAQ_NAME; ?><i class="icon asterisk"></i></label>
          <input type="text" placeholdder="<?php echo Lang::$word->FAQ_NAME; ?>"
            value="<?php echo $this->row->question; ?>" name="question">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->FAQ_BODY; ?><i class="icon asterisk"></i></label>
          <textarea class="bodypost" name="answer"><?php echo Url::out_url($this->row->answer); ?></textarea>
        </div>
      </div>
      <div class="center aligned">
        <a href="<?php echo Url::url("/admin/faq"); ?>"
          class="wojo small simple button"><?php echo Lang::$word->CANCEL; ?></a>
        <button type="button" data-action="processFaq" name="dosubmit"
          class="wojo secondary button"><?php echo Lang::$word->FAQ_UPDATE; ?></button>
      </div>
    </div>
    <input type="hidden" name="id" value="<?php echo $this->row->id; ?>">
  </form>
    <?php break; ?>
  <!-- Start new -->
<?php case "new": ?>
    <?php if (!Auth::hasPrivileges('add_faq')): print Message::msgError(Lang::$word->NOACCESS);
        return; endif; ?>
  <h2><?php echo Lang::$word->FAQ_SUB2; ?></h2>
  <p class="wojo small text"><?php echo Lang::$word->FAQ_INFO2; ?></p>
  <form method="post" id="wojo_form" name="wojo_form">
    <div class="wojo segment form">
      <div class="wojo block fields">
        <div class="field">
          <label><?php echo Lang::$word->FAQ_NAME; ?><i class="icon asterisk"></i></label>
          <input type="text" placeholdder="<?php echo Lang::$word->FAQ_NAME; ?>" name="question">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->FAQ_BODY; ?><i class="icon asterisk"></i></label>
          <textarea class="bodypost" name="answer"></textarea>
        </div>
      </div>
      <div class="center aligned">
        <a href="<?php echo Url::url("/admin/faq"); ?>"
          class="wojo small simple button"><?php echo Lang::$word->CANCEL; ?></a>
        <button type="button" data-action="processFaq" name="dosubmit"
          class="wojo secondary button"><?php echo Lang::$word->FAQ_ADD; ?></button>
      </div>
    </div>
  </form>
    <?php break; ?>
<?php default: ?>
  <div class="row gutters align middle">
    <div class="columns phone-100">
      <h2><?php echo Lang::$word->FAQ_TITLE; ?></h2>
      <p class="wojo small text"><?php echo Lang::$word->FAQ_INFO; ?></p>
    </div>
      <?php if (Auth::hasPrivileges('add_faq')): ?>
        <div class="columns auto phone-100">
          <a href="<?php echo Url::url("/admin/faq", "new"); ?>" class="wojo small secondary stacked button"><i
              class="icon plus alt"></i><?php echo Lang::$word->FAQ_ADD; ?></a>
        </div>
      <?php endif; ?>
  </div>
    <?php if (!$this->data): ?>
    <div class="center aligned"><img src="<?php echo ADMINVIEW; ?>/images/nop.svg" alt="">
      <p class="wojo small demi caps text"><?php echo Lang::$word->FAQ_NOFAQ; ?></p>
    </div>
    <?php else: ?>
    <div class="wojo full cards screen-3 tablet-3 mobile-2 phone-1" id="sortable">
        <?php foreach ($this->data as $row): ?>
          <div class="card" id="item_<?php echo $row->id; ?>" data-id="<?php echo $row->id; ?>">
            <div class="content"><?php echo $row->question; ?></div>
            <div class="footer divided center aligned">
              <a href="<?php echo Url::url("/admin/faq/edit", $row->id); ?>"
                class="wojo icon circular inverted primary button"><i class="icon pencil"></i></a>
                <?php if (Auth::hasPrivileges('delete_faq')): ?>
                  <a
                    data-set='{"option":[{"trash":"trashFaq","title": "<?php echo Validator::sanitize($row->question, "chars"); ?>","id": "<?php echo $row->id; ?>"}],"action":"trash", "parent":"#item_<?php echo $row->id; ?>"}'
                    class="wojo icon circular inverted negative button data">
                    <i class="icon trash"></i>
                  </a>
                <?php endif; ?>
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
                    iaction: "sortFaq",
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