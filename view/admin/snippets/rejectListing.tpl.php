<?php
    /**
     * Reject Listing
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: rejectListing.tpl.php, v1.00 2022-03-02 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
?>
<div class="body">
  <div class="wojo small form">
    <form method="post" id="modal_form" name="modal_form">
      <div class="wojo block fields">
        <div class="field">
          <p
            class="wojo small both attached alert message"><?php echo str_replace("[NAME]", $this->row->nice_title, Lang::$word->LST_REJECT_I); ?></p>
        </div>
        <div class="basic field">
          <label><?php echo Lang::$word->LST_REASON; ?></label>
          <textarea name="reason"></textarea>
        </div>
      </div>
    </form>
  </div>
</div>