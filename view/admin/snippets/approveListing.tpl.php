<?php
    /**
     * Approve Listing
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: approveListing.tpl.php, v1.00 2022-03-02 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
?>
<div class="body">
  <div class="wojo small form">
    <form method="post" id="modal_form" name="modal_form">
        <?php echo Message::msgSingleInfo(str_replace("[NAME]", $this->row->nice_title, Lang::$word->LST_APPROVE_I)); ?>
    </form>
  </div>
</div>