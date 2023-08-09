<?php
    /**
     * Login
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: login.tpl.php, v1.00 2022-04-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
?>
<div class="container massive vertical relative padding">
  <div class="wojo-grid">
    <div class="row align center">
      <div class="columns screen-40 tablet-50 mobile-100 phone-100">
        <div class="wojo basic card zi2">
          <div class="content">
            <h4 class="center aligned">
                <?php echo Lang::$word->LOGIN; ?>
            </h4>
          </div>
          <div class="content">
            <div class="wojo form">
              <div id="loginform">
                <div class="wojo block fields">
                  <div class="field">
                    <label><?php echo Lang::$word->EMAIL; ?>
                      <i class="icon asterisk"></i></label>
                    <input name="username" type="text" placeholder="<?php echo Lang::$word->EMAIL; ?>">
                  </div>
                  <div class="field">
                    <label><?php echo Lang::$word->PASSWORD; ?>
                      <i class="icon asterisk"></i></label>
                    <input type="password" name="password" placeholder="<?php echo Lang::$word->PASSWORD; ?>">
                  </div>
                  <div class="field">
                    <button id="doLogin" type="button" name="submit"
                      class="wojo primary fluid button"><?php echo Lang::$word->LOGIN; ?></button>
                  </div>
                </div>
                <div class="wojo fields">
                  <div class="field basic">
                    <a id="passreset" class="wojo demi text"><?php echo Lang::$word->PASSWORD_L; ?>?</a>
                  </div>
                  <div class="field basic">
                    <a href="<?php echo Url::url('/register'); ?>"
                      class="wojo demi text"><?php echo Lang::$word->M_NOACC; ?>?</a>
                  </div>
                </div>
              </div>
              <div id="passform" class="hide-all">
                <div class="wojo block fields">
                  <div class="field">
                    <label><?php echo Lang::$word->EMAIL; ?>
                      <i class="icon asterisk"></i></label>
                    <input type="text" name="pEmail" id="pEmail" placeholder="<?php echo Lang::$word->EMAIL; ?>">
                  </div>
                  <div class="field">
                    <button id="doPass" type="button" name="doopass"
                      class="wojo primary fluid button"><?php echo Lang::$word->SUBMIT; ?></button>
                  </div>
                  <div class="field basic">
                    <a id="backto" class="wojo demi text"><?php echo Lang::$word->BACKTOLOGIN; ?></a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <figure class="absolute zi1 hp100 w100 pt pl">
    <svg viewBox="0 0 3000 1000" xmlns="http://www.w3.org/2000/svg">
      <path fill="#e5f8f3" d="M-.5-.5v611.1L2999.5-.5z"/>
    </svg>
  </figure>
</div>
<script type="text/javascript" src="<?php echo THEMEURL; ?>/js/login.js"></script>
<script type="text/javascript">
   $(document).ready(function () {
      $.Login({
         url: "<?php echo FRONTVIEW;?>",
         surl: "<?php echo SITEURL;?>"
      });
   });
</script>