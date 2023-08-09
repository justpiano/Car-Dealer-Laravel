<?php
    /**
     * Edit Role
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: newInventory.tpl.php, v1.00 2022-03-02 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
?>
<div class="body">
  <div class="wojo small form">
    <form method="post" id="modal_form" name="modal_form">
      <div class="wojo block fields">
        <div class="basic field">
          <label><?php echo Lang::$word->NAME; ?><i class="icon asterisk"></i></label>
          <input type="text" name="name">
        </div>
      </div>
    </form>
  </div>
</div>