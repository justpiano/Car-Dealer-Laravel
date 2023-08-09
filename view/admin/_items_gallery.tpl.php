<?php
    /**
     * Gallery
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: _items_gallery.tpl.php, v1.00 2022-03-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
?>
<div class="row gutters align middle">
  <div class="columns phone-100">
    <h2><?php echo Lang::$word->GAL_TITLE; ?> <small
        class="wojo medium text">// <?php echo $this->row->nice_title; ?></small></h2>
    <p
      class="wojo small text"><?php echo str_replace("[ICON]", "<i class=\"icon reorder\"></i>", Lang::$word->GAL_INFO); ?></p>
  </div>
  <div class="columns auto phone-100">
    <div class="wojo small stacked secondary button uploader" id="drag-and-drop-zone">
      <i class="icon plus alt"></i>
      <label><?php echo Lang::$word->GAL_UPLOAD; ?>
        <input type="file" multiple name="files[]">
      </label>
    </div>
  </div>
</div>
<div class="wojo small fluid relaxed celled items" id="fileList"></div>
<?php if (!$this->data): ?>
  <div class="center aligned"><img src="<?php echo ADMINVIEW; ?>/images/nop.svg" alt="">
    <p class="wojo small demi caps text"><?php echo Lang::$word->GAL_NOGAL; ?></p>
  </div>
<?php else: ?>
  <div class="wojo mason three wedit" id="sortable">
      <?php foreach ($this->data as $row): ?>
        <div class="item" id="item_<?php echo $row->id; ?>" data-id="<?php echo $row->id; ?>">
          <div class="wojo attached card">
            <div class="wojo simple label draggable"><i class="icon reorder"></i></div>
            <img src="<?php echo UPLOADURL . '/listings/pics' . $row->listing_id . '/thumbs/' . $row->photo; ?>"
              class="wojo basic rounded image center aligned" alt="">
            <div class="content">
              <div class="center aligned margin bottom">
                <p><span data-editable="true"
                    data-set='{"action": "editGallery", "id": <?php echo $row->id; ?>, "name":"<?php echo $row->title; ?>"}'><?php echo $row->title; ?></span>
                </p>
                <a
                  data-set='{"option":[{"delete": "deleteGallery","title": "<?php echo Validator::sanitize($row->title, "chars"); ?>","id": <?php echo $row->id; ?>}],"action":"delete","parent":"#item_<?php echo $row->id; ?>"}'
                  class="wojo small negative icon button data"><i class="icon trash"></i></a>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
  </div>
<?php endif; ?>
<script src="<?php echo SITEURL; ?>/assets/sortable.js"></script>
<script src="<?php echo ADMINVIEW; ?>/js/gallery.js"></script>
<script type="text/javascript">
   // <![CDATA[
   $(document).ready(function () {
      $.Gallery({
         url: "<?php echo ADMINVIEW . '/helper.php';?>",
         grid: ".wojo.mason",
         id: <?php echo $this->row->id;?>,
         lang: {
            done: "<?php echo Lang::$word->DONE;?>"
         }
      });
   });
   // ]]>
</script>