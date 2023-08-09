<?php
    /**
     * Staff
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: _staff_new.tpl.php, v1.00 2022-04-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
?>
<h2><?php echo Lang::$word->M_SUB2; ?></h2>
<form method="post" id="wojo_form" name="wojo_form">
  <div class="wojo form segment">
    <div class="wojo fields align middle">
      <div class="field four wide labeled">
        <label><?php echo Lang::$word->FNAME; ?>
          <i class="icon asterisk"></i></label>
      </div>
      <div class="field">
        <input name="fname" type="text" placeholder="<?php echo Lang::$word->FNAME; ?>">
      </div>
    </div>
    <div class="wojo fields align middle">
      <div class="field four wide labeled">
        <label><?php echo Lang::$word->LNAME; ?>
          <i class="icon asterisk"></i></label>
      </div>
      <div class="field">
        <input name="lname" type="text" placeholder="<?php echo Lang::$word->LNAME; ?>">
      </div>
    </div>
    <div class="wojo fields align middle">
      <div class="field four wide labeled">
        <label><?php echo Lang::$word->EMAIL; ?>
          <i class="icon asterisk"></i></label>
      </div>
      <div class="field">
        <input name="email" type="text" placeholder="<?php echo Lang::$word->EMAIL; ?>">
      </div>
    </div>
    <div class="wojo fields align middle">
      <div class="field four wide labeled">
        <label><?php echo Lang::$word->NEWPASS; ?><i class="icon asterisk"></i></label>
      </div>
      <div class="field">
        <div class="wojo input action">
          <input type="text" name="password">
        </div>
      </div>
    </div>
    <div class="wojo fields align middle">
      <div class="field four wide labeled">
        <label><?php echo Lang::$word->TYPE; ?></label>
      </div>
      <div class="field">
        <div class="wojo checkbox radio fitted inline">
          <input name="usertype" type="radio" value="staff" id="staff" checked="checked">
          <label for="staff"><?php echo Lang::$word->STAFF; ?></label>
        </div>
        <div class="wojo checkbox radio fitted inline">
          <input name="usertype" type="radio" value="manager" id="manager">
          <label for="manager"><?php echo Lang::$word->MANAGER; ?></label>
        </div>
        <div class="wojo checkbox radio fitted inline">
          <input name="usertype" type="radio" value="editor" id="editor">
          <label for="editor"><?php echo Lang::$word->EDITOR; ?></label>
        </div>
      </div>
    </div>
    <div class="center aligned">
      <a href="<?php echo Url::url("/admin/staff"); ?>"
        class="wojo small simple button"><?php echo Lang::$word->CANCEL; ?></a>
      <button type="button" data-action="processStaff" name="dosubmit"
        class="wojo primary button"><?php echo Lang::$word->M_ADD; ?></button>
    </div>
  </div>
</form>