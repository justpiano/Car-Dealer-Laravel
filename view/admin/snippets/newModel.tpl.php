<?php
    /**
     * New Model
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: newModel.tpl.php, v1.00 2022-03-02 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
?>
<div class="body">
  <div class="wojo small form">
    <form method="post" id="modal_form" name="modal_form">
      <div class="wojo block fields">
        <div class="field">
          <label><?php echo Lang::$word->MAKE_SUB2; ?></label>
          <select name="make_id">
              <?php echo Utility::loopOptions($this->makes, "id", "name"); ?>
          </select>
        </div>
        <div class="field">
          <label>1. <?php echo Lang::$word->NAME; ?><i class="icon asterisk"></i></label>
          <input type="text" name="name[]">
        </div>
        <div class="field">
          <label>2. <?php echo Lang::$word->NAME; ?></label>
          <input type="text" name="name[]">
        </div>
        <div class="field">
          <label>3. <?php echo Lang::$word->NAME; ?></label>
          <input type="text" name="name[]">
        </div>
        <div class="field">
          <label>4. <?php echo Lang::$word->NAME; ?></label>
          <input type="text" name="name[]">
        </div>
        <div class="basic field">
          <label>5. <?php echo Lang::$word->NAME; ?></label>
          <input type="text" name="name[]">
        </div>
      </div>
    </form>
  </div>
</div>