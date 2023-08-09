<?php
    /**
     * Edit Review
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: editReview.tpl.php, v1.00 2022-03-02 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
?>
<div class="body">
  <div class="wojo small form">
    <form method="post" id="modal_form" name="modal_form">
      <div class="wojo block fields">
        <div class="field">
          <label><?php echo Lang::$word->DESCRIPTION; ?></label>
          <textarea name="content"><?php echo $this->data->content; ?></textarea>
        </div>
        <div class="field">
          <label>#Twitter</label>
          <input type="text" value="<?php echo $this->data->twitter; ?>" name="twitter">
        </div>
        <div class="basic field">
          <label><?php echo Lang::$word->PUBLISHED; ?></label>
          <div class="wojo checkbox radio inline">
            <input name="status" type="radio" value="1"
              id="status_1" <?php echo Validator::getChecked($this->data->status, 1); ?>>
            <label for="status_1"><?php echo Lang::$word->YES; ?></label>
          </div>
          <div class="wojo checkbox radio inline">
            <input name="status" type="radio" value="0"
              id="status_0" <?php echo Validator::getChecked($this->data->status, 0); ?>>
            <label for="status_0"><?php echo Lang::$word->NO; ?></label>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>