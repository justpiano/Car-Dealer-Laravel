<?php
    /**
     * Register
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: register.tpl.php, v1.00 2022-04-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
?>
<div class="relative massive vertical padding">
  <div class="wojo-grid">
    <div class="row align center">
      <div class="columns screen-40 tablet-50 mobile-100 phone-100">
        <div class="wojo basic card zi2">
          <div class="content">
            <h4 class="center aligned">
                <?php echo Lang::$word->REG_TITLE; ?>
            </h4>
          </div>
          <div class="content">
            <form method="post" id="wojo_form" name="wojo_form">
              <div class="wojo form">
                <div class="wojo block fields">
                  <div class="field">
                    <label><?php echo Lang::$word->EMAIL; ?>
                      <i class="icon asterisk"></i></label>
                    <input name="email" type="text" placeholder="<?php echo Lang::$word->EMAIL; ?>">
                  </div>
                  <div class="field">
                    <label><?php echo Lang::$word->PASSWORD; ?>
                      <i class="icon asterisk"></i></label>
                    <input type="password" name="password" placeholder="<?php echo Lang::$word->PASSWORD; ?>">
                  </div>
                </div>
                <div class="wojo fields">
                  <div class="field">
                    <label><?php echo Lang::$word->FNAME; ?>
                      <i class="icon asterisk"></i></label>
                    <input type="text" placeholder="<?php echo Lang::$word->FNAME; ?>" name="fname">
                  </div>
                  <div class="field">
                    <label><?php echo Lang::$word->LNAME; ?>
                      <i class="icon asterisk"></i></label>
                    <input type="text" placeholder="<?php echo Lang::$word->LNAME; ?>" name="lname">
                  </div>
                </div>
                  <?php if ($this->core->enable_tax): ?>
                    <div class="wojo block fields">
                      <div class="field">
                        <label><?php echo Lang::$word->ADDRESS; ?>
                          <i class="icon asterisk"></i></label>
                        <input type="text" placeholder="<?php echo Lang::$word->ADDRESS; ?>" name="address">
                      </div>
                    </div>
                    <div class="wojo fields">
                      <div class="field">
                        <label><?php echo Lang::$word->CITY; ?>
                          <i class="icon asterisk"></i></label>
                        <input type="text" placeholder="<?php echo Lang::$word->CITY; ?>" name="city">
                      </div>
                      <div class="field">
                        <label><?php echo Lang::$word->STATE; ?>
                          <i class="icon asterisk"></i></label>
                        <input type="text" placeholder="<?php echo Lang::$word->STATE; ?>" name="state">
                      </div>
                    </div>
                    <div class="wojo fields">
                      <div class="field">
                        <label><?php echo Lang::$word->ZIP; ?>/<?php echo Lang::$word->COUNTRY; ?>
                          <i class="icon asterisk"></i>
                        </label>
                        <div class="wojo action input">
                          <input type="text" placeholder="<?php echo Lang::$word->ZIP; ?>" name="zip">
                          <select name="country">
                              <?php echo Utility::loopOptions($this->clist, "abbr", "name"); ?>
                          </select>
                        </div>
                      </div>
                    </div>
                  <?php endif; ?>
                <div class="wojo block fields">
                  <div class="field">
                    <label><?php echo Lang::$word->CAPTCHA; ?>
                      <i class="icon asterisk"></i></label>
                    <div class="wojo labeled input">
                      <input name="captcha" type="text">
                      <span class="wojo simple label"><?php echo Session::captcha(); ?></span>
                    </div>
                  </div>
                  <div class="field">
                    <div class="wojo inline fitted toggle checkbox">
                      <input name="agree" type="checkbox" value="1" id="agree_1">
                      <label for="agree_1">
                        <a href="<?php echo Url::url("/page", "privacy-policy"); ?>"
                          target="_blank"><?php echo Lang::$word->AGREE; ?></a>
                      </label>
                    </div>
                  </div>
                  <div class="field">
                    <button class="fluid wojo primary button" data-action="register" name="dosubmit"
                      type="button"><?php echo Lang::$word->REG_ACC; ?></button>
                  </div>
                  <div class="field basic">
                    <a href="<?php echo Url::url('/login'); ?>"
                      class="wojo demi text"><?php echo Lang::$word->BACKTOLOGIN; ?>?</a>
                  </div>
                </div>
              </div>
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