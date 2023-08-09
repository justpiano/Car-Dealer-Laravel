<?php
    /**
     * Reviews
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: reviews.tpl.php, v1.00 2022-04-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
?>
<div class="wojo big vertical padding">
  <div class="wojo-grid">
      <?php include_once(THEMEBASE . '/snippets/dashNav.tpl.php'); ?>
    <h3><?php echo Lang::$word->SRW_SUBMIT; ?></h3>
    <p class="wojo big bottom margin"><?php echo Lang::$word->HOME_SUB20P; ?></p>
    <div class="wojo form">
      <form method="post" id="wojo_form" name="wojo_form">
        <div class="wojo fields align middle">
          <div class="field four wide labeled">
            <label><?php echo Lang::$word->SRW_DESC; ?>
              <i class="icon asterisk"></i></label>
          </div>
          <div class="field">
            <textarea name="content" placeholder="<?php echo Lang::$word->SRW_DESC; ?>"></textarea>
          </div>
        </div>
        <div class="wojo fields align middle">
          <div class="field four wide labeled">
            <label>Twitter ID</label>
          </div>
          <div class="field">
            <input type="text" placeholder="Twitter ID" name="twitter">
          </div>
        </div>
        <div class="wojo fields align middle">
          <div class="field four wide labeled"></div>
          <div class="field">
            <button class="bottom wojo primary button" data-action="addReview" name="dosubmit"
              type="button"><?php echo Lang::$word->SRW_SUBMIT; ?></button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>