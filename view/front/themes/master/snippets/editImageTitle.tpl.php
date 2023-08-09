<?php
  /**
   * Edit Image
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2022
   * @version $Id: editRole.tpl.php, v1.00 2022-03-02 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
?>
<div class="body">
  <div class="wojo small form">
    <form method="post" id="modal_form" name="modal_form">
      <div class="wojo block fields">
        <div class="field">
          <label><?php echo Lang::$word->NAME;?></label>
          <input type="text" value="<?php echo $this->data->title;?>" name="title">
        </div>
      </div>
    </form>
  </div>
</div>