<?php
    /**
     * Home Newsletter
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: _home_newsletter.tpl.php", v1.00 2022-04-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
?>
<form method="post" id="wojo_form" name="wojo_form">
  <div id="nlform" class="wojo small form">
    <div class="wojo small block fields">
      <div class="field">
        <div class="wojo icon transparent small input">
          <i class="icon person"></i>
          <input type="text" placeholder="<?php echo Lang::$word->EMN_NLN; ?>" name="name">
        </div>
      </div>
      <div class="field">
        <div class="wojo icon transparent small input">
          <i class="icon envelope"></i>
          <input type="text" placeholder="<?php echo Lang::$word->EMN_NLE; ?>" name="email">
        </div>
      </div>
    </div>
    <p class="wojo small white text"><?php echo Lang::$word->HOME_SUB4P; ?></p>
    <button type="button" data-reset="true" data-action="processNewsletter" name="dosubmit"
      class="wojo small transparent fluid right button"><?php echo Lang::$word->EMN_BTS; ?><i
        class="icon long right arrow"></i></button>
  </div>
</form>