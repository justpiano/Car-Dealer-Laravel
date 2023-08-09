<?php
    /**
     * Email Templates
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: etemplates.tpl.php, v1.00 2022-05-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
    
    if (!Auth::checkAcl("owner", "staff", "manager")): print Message::msgError(Lang::$word->NOACCESS);
        return; endif;
?>
<h2><?php echo Lang::$word->ET_TITLE; ?></h2>
<p class="wojo small text"><?php echo Lang::$word->ET_SUB; ?></p>
<form id="wojo_form" name="wojo_form" method="post">
  <div class="wojo form segment">
    <div class="wojo fields">
      <div class="field">
        <label><?php echo Lang::$word->ET_NAME; ?></label>
        <select name="filename" id="mtemplatelist">
          <option value="">--- ---</option>
            <?php if ($this->data): ?>
                <?php foreach ($this->data as $row): ?>
                    <?php $name = basename($row); ?>
                <option value="<?php echo $name; ?>"><?php echo substr(str_replace("_", " ", $name), 0, -8); ?></option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->ET_BACKUP; ?></label>
        <div class="wojo checkbox toggle inline">
          <input name="backup" type="checkbox" value="1" id="backup_0">
          <label for="backup_0"><?php echo Lang::$word->YES; ?></label>
        </div>
      </div>
    </div>
    <div class="wojo fields">
      <div class="field">
        <textarea class="bodypost" name="body"></textarea>
        <p class="wojo small icon negative text">
          <i class="icon negative info sign"></i>
            <?php echo Lang::$word->NOTEVAR; ?></p>
      </div>
    </div>
    <div class="center aligned">
      <button type="button" data-action="processEtemplate" name="dosubmit"
        class="wojo secondary button"><?php echo Lang::$word->ET_UPDATE; ?></button>
    </div>
  </div>
</form>
<script type="text/javascript">
   $(document).ready(function () {
      $('#mtemplatelist').change(function () {
         const $container = $(".wojo.form");
         $container.addClass('loading');
         const option = $(this).val();
         $.ajax({
            cache: false,
            type: "get",
            url: "<?php echo ADMINVIEW;?>/helper.php",
            dataType: "json",
            data: {
               action: "getEtemplate",
               filename: option
            },
            success: function (json) {
               if (json.status === "success") {
                  $R('.bodypost', 'source.setCode', json.html)
               }
               $container.removeClass('loading');
            }
         });
      });
   });
</script>