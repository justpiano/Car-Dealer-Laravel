<?php
  /**
   * Members
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2020
   * @version $Id: _members_new.tpl.php, v1.00 2020-03-05 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
?>
<h2><?php echo Lang::$word->CL_SUB4;?></h2>
<p class="wojo small text"><?php echo Lang::$word->CL_INFO4;?></p>
<form method="post" id="wojo_form" name="wojo_form">
  <div class="wojo segment form">
    <div class="wojo fields">
      <div class="field">
        <label><?php echo Lang::$word->EMAIL;?><i class="icon asterisk"></i></label>
        <input type="text" placeholder="<?php echo Lang::$word->EMAIL;?>" name="email">
      </div>
    </div>
    <div class="wojo fields">
      <div class="field">
        <label><?php echo Lang::$word->FNAME;?><i class="icon asterisk"></i></label>
        <input type="text" placeholder="<?php echo Lang::$word->FNAME;?>" name="fname">
      </div>
      <div class="field">
        <label><?php echo Lang::$word->LNAME;?><i class="icon asterisk"></i></label>
        <input type="text" placeholder="<?php echo Lang::$word->LNAME;?>" name="lname">
      </div>
    </div>
    <div class="wojo fields">
      <div class="field">
        <label><?php echo Lang::$word->COMPANY;?></label>
        <input type="text" placeholder="<?php echo Lang::$word->COMPANY;?>" name="company">
      </div>
      <div class="field">
        <label><?php echo Lang::$word->ADDRESS;?><i class="icon asterisk"></i></label>
        <input type="text" placeholder="<?php echo Lang::$word->ADDRESS;?>" name="address">
      </div>
    </div>
    <div class="wojo fields">
      <div class="field">
        <label><?php echo Lang::$word->CITY;?><i class="icon asterisk"></i></label>
        <input type="text" placeholder="<?php echo Lang::$word->CITY;?>" name="city">
      </div>
      <div class="field">
        <label><?php echo Lang::$word->STATE;?><i class="icon asterisk"></i></label>
        <input type="text" placeholder="<?php echo Lang::$word->STATE;?>" name="state">
      </div>
      <div class="field">
        <label><?php echo Lang::$word->ZIP;?><i class="icon asterisk"></i></label>
        <input type="text" placeholder="<?php echo Lang::$word->ZIP;?>" name="zip">
      </div>
    </div>
    <div class="wojo fields">
      <div class="field">
        <label><?php echo Lang::$word->COUNTRY;?><i class="icon asterisk"></i></label>
        <select name="country">
          <option value="">-- <?php echo Lang::$word->CNT_SELECT;?> --</option>
          <?php echo Utility::loopOptions($this->countries, "abbr", "name");?>
        </select>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->WEBSITE;?></label>
        <input type="text" placeholder="<?php echo Lang::$word->WEBSITE;?>" name="url">
      </div>
      <div class="field">
        <label><?php echo Lang::$word->PASSWORD;?><i class="icon asterisk"></i></label>
        <input type="text" placeholder="<?php echo Lang::$word->PASSWORD;?>" name="password">
      </div>
    </div>
    <div class="wojo fields">
      <div class="field">
        <label><?php echo Lang::$word->MEMBERSHIP;?>
          <span data-tooltip="<?php echo Lang::$word->CL_NOMEMBERSHIP_T;?>" data-position="top left"><i class="icon question circle"></i></span></label>
        <select name="membership_id">
          <option value="0">--- <?php echo Lang::$word->CL_NOMEMBERSHIP;?> ---</option>
          <?php echo Utility::loopOptions($this->memberships, "id", "title");?>
        </select>
      </div>
      <div class="field auto">
        <label><?php echo Lang::$word->CL_SUB5;?></label>
        <div class="wojo fitted inline toggle checkbox">
          <input name="update_membership" type="checkbox" value="1" id="update_membership">
          <label for="update_membership"><?php echo Lang::$word->YES;?></label>
        </div>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CL_DATEEX;?>
          <span data-tooltip="<?php echo Lang::$word->CL_DATEEX;?>"><i class="icon question circle"></i></span></label>
        <input placeholder="<?php echo Lang::$word->TO;?>" name="mem_expire" type="text" value="<?php echo Date::doDate("calendar", Date::NumberOfDays('+ 30 day'));?>" readonly class="datepick">
      </div>
      <div class="field auto">
        <label><?php echo Lang::$word->CL_SUB6;?></label>
        <div class="wojo fitted inline toggle checkbox">
          <input name="extend_membership" type="checkbox" value="1" id="extend_membership">
          <label for="extend_membership"><?php echo Lang::$word->YES;?></label>
        </div>
      </div>
    </div>
    <div class="wojo fields">
      <div class="field">
        <label><?php echo Lang::$word->CL_COMMENTS;?>
          <span data-tooltip="<?php echo Lang::$word->CL_COMMENTS_T;?>"><i class="icon question circle"></i></span></label>
        <textarea name="comments"></textarea>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->M_ABOUT;?></label>
        <textarea name="about"></textarea>
      </div>
    </div>
    <div class="wojo fields">
      <div class="field">
        <label><?php echo Lang::$word->M_NOTIFY;?>
          <span data-tooltip="<?php echo Lang::$word->M_NOTIFY_T;?>"><i class="icon question circle"></i></span></label>
        <div class="wojo checkbox toggle inline">
          <input name="notify" type="checkbox" value="1" id="notify_0">
          <label for="notify_0"><?php echo Lang::$word->YES;?></label>
        </div>
      </div>
    </div>
    <div class="center aligned">
      <a href="<?php echo Url::url("/admin/members");?>" class="wojo small simple button"><?php echo Lang::$word->CANCEL;?></a>
      <button type="button" data-action="processMember" name="dosubmit" class="wojo secondary button"><?php echo Lang::$word->CL_ADD;?></button>
    </div>
  </div>
</form>