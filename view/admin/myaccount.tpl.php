<?php
    /**
     * My Account
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: myaccount.tpl.php, v1.00 2022-05-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');

?>
<form method="post" id="wojo_form" name="wojo_form">
  <div class="row gutters">
    <div class="columns phone-100">
      <div class="wojo segment form">
        <div class="wojo fields align middle">
          <div class="field four wide labeled">
            <label><?php echo Lang::$word->FNAME; ?>
              <i class="icon asterisk"></i></label>
          </div>
          <div class="field">
            <input type="text" value="<?php echo $this->data->fname; ?>" name="fname">
          </div>
        </div>
        <div class="wojo fields align middle">
          <div class="field four wide labeled">
            <label><?php echo Lang::$word->LNAME; ?>
              <i class="icon asterisk"></i></label>
          </div>
          <div class="field">
            <input type="text" value="<?php echo $this->data->lname; ?>" name="lname">
          </div>
        </div>
        <div class="wojo fields align middle">
          <div class="field four wide labeled">
            <label><?php echo Lang::$word->EMAIL; ?>
              <i class="icon asterisk"></i></label>
          </div>
          <div class="field">
            <input type="text" value="<?php echo $this->data->email; ?>" name="email">
          </div>
        </div>
        <div class="wojo fields disabled align middle">
          <div class="field four wide labeled">
            <label><?php echo Lang::$word->CREATED; ?></label>
          </div>
          <div class="field">
            <input type="text" value="<?php echo Date::doDate("short_date", $this->data->created); ?>" readonly>
          </div>
        </div>
        <div class="wojo fields disabled align middle">
          <div class="field four wide labeled">
            <label><?php echo Lang::$word->LASTLOGIN; ?></label>
          </div>
          <div class="field">
            <input type="text" value="<?php echo Date::doDate("short_date", $this->data->lastlogin); ?>">
          </div>
        </div>
        <div class="wojo fields disabled align middle">
          <div class="field four wide labeled">
            <label><?php echo Lang::$word->LASTIP; ?></label>
          </div>
          <div class="field">
            <input type="text" value="<?php echo $this->data->lastip; ?>">
          </div>
        </div>
        <div class="right aligned">
          <button type="button" data-action="updateAccount" name="dosubmit"
            class="wojo secondary button"><?php echo Lang::$word->ACC_UPDATE; ?></button>
        </div>
      </div>
    </div>
    <div class="columns auto  phone-100">
      <div class="wojo segment form">
        <div class="basic field">
          <input type="file" name="avatar" data-type="image"
            data-exist="<?php echo ($this->data->avatar) ? UPLOADURL . '/avatars/' . $this->data->avatar : UPLOADURL . '/avatars/blank.svg'; ?>"
            accept="image/png, image/jpeg">
        </div>
      </div>
    </div>
  </div>
</form>