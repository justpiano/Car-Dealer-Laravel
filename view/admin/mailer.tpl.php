<?php
    /**
     * Email Templates
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: mailer.tpl.php, v1.00 2022-05-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
    
    if (!Auth::checkAcl("owner", "staff")): print Message::msgError(Lang::$word->NOACCESS);
        return; endif;
?>
<h2><?php echo Lang::$word->EMN_SUB; ?></h2>
<p class="wojo small text"><?php echo Lang::$word->EMN_INFO; ?></p>
<form method="post" id="wojo_form" name="wojo_form">
  <div class="wojo segment form">
    <div class="wojo fields">
      <div class="field">
        <label><?php echo Lang::$word->EMN_SUJECT; ?><i class="icon asterisk"></i></label>
        <input type="text" placeholder="<?php echo Lang::$word->EMN_SUJECT; ?>" name="subject">
      </div>
      <div class="field">
        <label><?php echo Lang::$word->EMN_REC; ?><i class="icon asterisk"></i></label>
          <?php if (Validator::get('mailid')): ?>
            <input type="text" placeholder="<?php echo Lang::$word->EMN_REC; ?>" name="recipient"
              value="<?php echo Validator::get('mailid'); ?>" readonly>
              <?php if (Validator::get('clients')): ?>
              <input name="clients" type="hidden" value="1">
              <?php endif; ?>
          <?php else: ?>
            <select name="recipient">
              <option value="">--- <?php echo Lang::$word->EMN_REC_SEL; ?> ---</option>
              <option value="members"><?php echo Lang::$word->EMN_REC_C; ?></option>
              <option value="staff"><?php echo Lang::$word->EMN_REC_S; ?></option>
              <option value="sellers"><?php echo Lang::$word->EMN_REC_M; ?></option>
              <option value="newsletter"><?php echo Lang::$word->EMN_REC_N; ?></option>
            </select>
          <?php endif; ?>
      </div>
    </div>
    <div class="wojo fields">
      <div class="field">
        <textarea class="bodypost"
          name="body"><?php echo Url::out_url(file_get_contents(BASEPATH . "/mailer/" . $this->core->lang . "/Newsletter_Admin.tpl.php")); ?></textarea>
        <p class="wojo small icon negative text">
          <i class="icon negative info sign"></i>
            <?php echo Lang::$word->NOTEVAR; ?></p>
      </div>
    </div>
    <div class="center aligned">
      <button type="button" data-action="processEmail" name="dosubmit"
        class="wojo secondary button"><?php echo Lang::$word->EMN_SEND; ?></button>
    </div>
  </div>
</form>