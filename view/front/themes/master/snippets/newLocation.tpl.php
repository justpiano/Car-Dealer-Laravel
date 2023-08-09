<?php
/**
 * New Location
 *
 * @package Wojo Framework
 * @author wojoscripts.com
 * @copyright 2022
 * @version $Id: newLocation.tpl.php, v1.00 2022-03-02 10:12:05 gewa Exp $
 */
if (!defined("_WOJO"))
  die('Direct access to this location is not allowed.');
?>
<div class="body">
  <div class="wojo small form">
    <form method="post" id="modal_form" name="modal_form">
      <div class="wojo block fields">
        <div class="field">
          <label><?php echo Lang::$word->NAME;?><i class="icon asterisk"></i></label>
          <input type="text" placeholder="<?php echo Lang::$word->NAME;?>" name="name">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->EMAIL;?><i class="icon asterisk"></i></label>
          <input type="text" placeholder="<?php echo Lang::$word->EMAIL;?>" value="<?php echo App::Auth()->email;?>" name="email">
        </div>
        <div class="field">
          <label><?php echo Lang::$word->CF_WEBURL;?></label>
          <input type="text" placeholder="<?php echo Lang::$word->CF_WEBURL;?>" name="url">
        </div>
      </div>
      <div class="wojo fields">
        <div class="field basic">
          <label><?php echo Lang::$word->CF_PHONE;?></label>
          <input type="text" placeholder="<?php echo Lang::$word->CF_PHONE;?>" name="phone">
        </div>
        <div class="field basic">
          <label><?php echo Lang::$word->CF_FAX;?></label>
          <input type="text" placeholder="<?php echo Lang::$word->CF_FAX;?>" name="fax">
        </div>
      </div>
    </form>
  </div>
</div>