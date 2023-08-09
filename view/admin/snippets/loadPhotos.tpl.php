<?php
    /**
     * Gallery Photos
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: lodPhotos.tpl.php, v1.00 2022-03-02 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
?>
<?php if ($this->data): ?>
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
<?php endif; ?>