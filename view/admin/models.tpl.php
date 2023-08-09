<?php
    /**
     * Models
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: models.tpl.php, v1.00 2022-05-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
    
    if (!Auth::hasPrivileges('manage_models')): print Message::msgError(Lang::$word->NOACCESS);
        return; endif;
?>
<div class="row gutters align middle">
  <div class="columns phone-100">
    <h2><?php echo Lang::$word->MODL_SUB; ?></h2>
    <p class="wojo small text"><?php echo Lang::$word->MODL_INFO; ?></p>
  </div>
  <div class="columns auto phone-100">
    <a
      data-set='{"option":[{"action":"newModel", "url":"<?php echo Url::uri(); ?>"}], "label":"<?php echo Lang::$word->SUBMIT; ?>", "redirect":true, "url":"helper.php", "parent":"#editable", "complete":"prepend", "modalclass":"normal"}'
      class="wojo small secondary stacked button action"><i
        class="icon plus alt"></i><?php echo Lang::$word->MODL_ADD; ?></a>
  </div>
</div>
<div class="wojo form">
  <div class="row gutters align center">
    <div class="columns screen-40 tablet-50 mobile-100 phone-100">
      <form method="get" id="wojo_form" name="wojo_form">
        <div class="wojo action input">
          <input name="find" placeholder="<?php echo Lang::$word->SEARCH; ?>" type="text">
          <button class="wojo primary inverted icon button">
            <i class="icon search"></i></button>
        </div>
      </form>
    </div>
    <div class="columns screen-40 tablet-50 mobile-100 phone-100">
      <select name="id">
        <option value="0">--- <?php echo Lang::$word->MODL_RESET; ?> ---</option>
          <?php echo Utility::loopOptions($this->makes, "id", "name", Filter::$id); ?>
      </select>
    </div>
  </div>
</div>
<div class="center aligned margin bottom"><?php echo Validator::alphaBits(Url::url(Router::$path)); ?></div>
<?php if (!$this->data): ?>
  <div class="center aligned"><img src="<?php echo ADMINVIEW; ?>/images/nop.svg" alt="">
    <p class="wojo small demi caps text"><?php echo Lang::$word->MODL_NOMODEL; ?></p>
  </div>
<?php else: ?>
  <div class="row grid screen-3 tablet-3 mobile-2 phone-1" id="editable">
      <?php foreach ($this->data as $row): ?>
        <div class="columns" id="item_<?php echo $row->mdid; ?>">
          <div class="wojo compact attached segment">
            <p class="wojo tiny grey basic text"><?php echo $row->mkname; ?></p>
            <span data-editable="true"
              data-set='{"action": "editModel", "id": <?php echo $row->mdid; ?>, "name":"<?php echo $row->mdname; ?>"}'
              class="truncate"><?php echo $row->mdname; ?></span>
            <a
              data-set='{"option":[{"delete": "deleteModel","title": "<?php echo Validator::sanitize($row->mdname, "chars"); ?>","id": <?php echo $row->mdid; ?>}],"action":"delete","parent":"#item_<?php echo $row->mdid; ?>"}'
              class="wojo small simple negative attached top right icon button data"><i class="icon trash alt fill"></i></a>
          </div>
        </div>
      <?php endforeach; ?>
  </div>
<?php endif; ?>
<div class="wojo space divider"></div>
<div class="row gutters align middle">
  <div class="columns auto mobile-100 phone-100">
    <div
      class="wojo small thick text"><?php echo Lang::$word->TOTAL . ': ' . $this->pager->items_total; ?> / <?php echo Lang::$word->CURPAGE . ': ' . $this->pager->current_page . ' ' . Lang::$word->OF . ' ' . $this->pager->num_pages; ?></div>
  </div>
  <div class="columns right aligned mobile-100 phone-100"><?php echo $this->pager->display_pages(); ?></div>
</div>
<script type="text/javascript">
   // <![CDATA[
   $(document).ready(function () {
      $("select[name=id]").on('change', function () {
         let value = $(this).val();
         if (parseInt(value) === 0) {
            window.location.href = "<?php echo Url::url("/admin/models");?>";
         } else {
            window.location.href = "<?php echo Url::url("/admin/models", "?id=");?>" + value;
         }
      });
   });
   // ]]>
</script>