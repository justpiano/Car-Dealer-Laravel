<?php
    /**
     * Listing Contact
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: _listing_contact.tpl.php, v1.00 2022-04-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
?>
<div class="wojo form">
  <h5 class="mb2"><?php echo Lang::$word->CL_SENDMSG; ?></h5>
  <form method="post" id="wojo_form" name="wojo_form">
    <div class="wojo fields">
      <div class="field">
        <label><?php echo Lang::$word->EMN_NLN; ?><i class="icon asterisk"></i></label>
        <input name="name" type="text" placeholder="<?php echo Lang::$word->EMN_NLN; ?>"
          value="<?php echo App::Auth()->name; ?>">
      </div>
      <div class="field">
        <label><?php echo Lang::$word->EMN_NLE; ?><i class="icon asterisk"></i></label>
        <input name="email" type="text" placeholder="<?php echo Lang::$word->EMN_NLE; ?>"
          value="<?php echo App::Auth()->email; ?>">
      </div>
    </div>
    <div class="wojo fields">
      <div class="field">
        <textarea name="message" placeholder="<?php echo Lang::$word->MESSAGE; ?>" class="small"></textarea>
      </div>
    </div>
    <div class="wojo fields">
      <div class="field">
        <div class="wojo inline fitted checkbox">
          <input name="agree" type="checkbox" value="1" id="agree_1">
          <label for="agree_1">
            <a href="<?php echo Url::url("/page", "privacy-policy"); ?>"
              target="_blank"><?php echo Lang::$word->AGREE; ?></a>
          </label>
        </div>
      </div>
      <div class="field right aligned">
        <button type="button" data-action="contactSeller" data-reset="true" name="dosubmit"
          class="wojo primary button"><?php echo Lang::$word->SUBMIT; ?></button>
      </div>
    </div>
    <input name="item_id" type="hidden" value="<?php echo $this->row->idx . '/' . $this->row->slug; ?>">
    <input name="location" type="hidden" value="<?php echo $this->location->id; ?>">
    <input name="stock_id" type="hidden" value="<?php echo $this->row->stock_id; ?>">
  </form>
</div>