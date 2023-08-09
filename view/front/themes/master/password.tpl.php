<?php
    /**
     * Password
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: password.tpl.php, v1.00 2022-04-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
?>
<div class="relative massive vertical padding">
  <div class="wojo-grid">
    <div class="row align center">
      <div class="columns screen-50 tablet-70 mobile-100 phone-100">
        <div class="wojo basic card zi2">
          <div class="content">
            <h4><?php echo Lang::$word->HI; ?>
                <?php echo $this->row->name; ?></h4>
            <form method="post" id="wojo_form" name="wojo_form">
              <div class="wojo form">
                <div class="wojo block fields">
                  <div class="field">
                    <label><?php echo Lang::$word->NEWPASS; ?>
                      <i class="icon asterisk"></i></label>
                    <input placeholder="<?php echo Lang::$word->NEWPASS; ?>" name="password" type="password">
                  </div>
                </div>
                <div class="wojo fields">
                  <div class="field center aligned">
                    <button class="wojo primary  button" data-action="password" name="dosubmit"
                      type="button"><?php echo Lang::$word->SUBMIT; ?></button>
                  </div>
                </div>
              </div>
              <input type="hidden" name="token" value="<?php echo $this->segments[1]; ?>">
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <figure class="absolute zi1 hp100 w100 pt pl">
    <svg viewBox="0 0 3000 1000" xmlns="http://www.w3.org/2000/svg" class="ha">
      <path fill="#e5f8f3" d="M-.5-.5v611.1L2999.5-.5z"/>
    </svg>
  </figure>
</div>