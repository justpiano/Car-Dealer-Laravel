<?php
  /**
   * Members
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2022
   * @version $Id: _members_edit.tpl.php, v1.00 2022-03-05 10:12:05 gewa Exp $
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
        <label><?php echo Lang::$word->USERNAME;?></label>
        <input type="text" placeholder="<?php echo Lang::$word->USERNAME;?>" value="<?php echo $this->row->username;?>" disabled>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->EMAIL;?><i class="icon asterisk"></i></label>
        <input type="text" placeholder="<?php echo Lang::$word->EMAIL;?>" value="<?php echo $this->row->email;?>" name="email">
      </div>
    </div>
    <div class="wojo fields">
      <div class="field">
        <label><?php echo Lang::$word->FNAME;?><i class="icon asterisk"></i></label>
        <input type="text" placeholder="<?php echo Lang::$word->FNAME;?>" value="<?php echo $this->row->fname;?>" name="fname">
      </div>
      <div class="field">
        <label><?php echo Lang::$word->LNAME;?><i class="icon asterisk"></i></label>
        <input type="text" placeholder="<?php echo Lang::$word->LNAME;?>" value="<?php echo $this->row->lname;?>" name="lname">
      </div>
    </div>
    <div class="wojo fields">
      <div class="field">
        <label><?php echo Lang::$word->COMPANY;?></label>
        <input type="text" placeholder="<?php echo Lang::$word->COMPANY;?>" value="<?php echo $this->row->company;?>" name="company">
      </div>
      <div class="field">
        <label><?php echo Lang::$word->ADDRESS;?><i class="icon asterisk"></i></label>
        <input type="text" placeholder="<?php echo Lang::$word->ADDRESS;?>" value="<?php echo $this->row->address;?>" name="address">
      </div>
    </div>
    <div class="wojo fields">
      <div class="field">
        <label><?php echo Lang::$word->CITY;?><i class="icon asterisk"></i></label>
        <input type="text" placeholder="<?php echo Lang::$word->CITY;?>" value="<?php echo $this->row->city;?>" name="city">
      </div>
      <div class="field">
        <label><?php echo Lang::$word->STATE;?><i class="icon asterisk"></i></label>
        <input type="text" placeholder="<?php echo Lang::$word->STATE;?>" value="<?php echo $this->row->state;?>" name="state">
      </div>
      <div class="field">
        <label><?php echo Lang::$word->ZIP;?><i class="icon asterisk"></i></label>
        <input type="text" placeholder="<?php echo Lang::$word->ZIP;?>" value="<?php echo $this->row->zip;?>" name="zip">
      </div>
    </div>
    <div class="wojo fields">
      <div class="field">
        <label><?php echo Lang::$word->COUNTRY;?><i class="icon asterisk"></i></label>
        <select name="country">
          <option value="">-- <?php echo Lang::$word->CNT_SELECT;?> --</option>
          <?php echo Utility::loopOptions($this->countries, "abbr", "name", $this->row->country);?>
        </select>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->WEBSITE;?></label>
        <input type="text" placeholder="<?php echo Lang::$word->WEBSITE;?>" value="<?php echo $this->row->url;?>" name="url">
      </div>
      <div class="field">
        <label><?php echo Lang::$word->PASSWORD;?>
          <span data-tooltip="<?php echo Lang::$word->M_PASS_T;?>"><i class="icon question circle"></i></span></label>
        <input type="text" placeholder="<?php echo Lang::$word->PASSWORD;?>" name="password">
      </div>
    </div>
    <div class="wojo fields">
      <div class="field">
        <label><?php echo Lang::$word->MEMBERSHIP;?>
          <span data-tooltip="<?php echo Lang::$word->CL_NOMEMBERSHIP_T;?>" data-position="top left"><i class="icon question circle"></i></span></label>
        <select name="membership_id">
          <option value="0">--- <?php echo Lang::$word->CL_NOMEMBERSHIP;?> ---</option>
          <?php echo Utility::loopOptions($this->memberships, "id", "title", $this->row->membership_id);?>
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
        <label><?php echo Lang::$word->LASTLOGIN;?></label>
        <input type="text" value="<?php echo $this->row->last_active ? Date::doDate("long_date", $this->row->last_active) : Lang::$word->NEVER;?>" disabled>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->LASTIP;?></label>
        <input type="text" value="<?php echo $this->row->lastip;?>" disabled>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->CREATED;?></label>
        <input type="text" value="<?php echo Date::doDate("long_date", $this->row->created);?>" disabled>
      </div>
    </div>
    <div class="wojo fields">
      <div class="field">
        <label><?php echo Lang::$word->CL_COMMENTS;?>
          <span data-tooltip="<?php echo Lang::$word->CL_COMMENTS_T;?>"><i class="icon question circle"></i></span></label>
        <textarea name="comments"><?php echo $this->row->comments;?></textarea>
      </div>
      <div class="field">
        <label><?php echo Lang::$word->M_ABOUT;?></label>
        <textarea name="about"><?php echo $this->row->about;?></textarea>
      </div>
    </div>
    <div class="center aligned">
      <a href="<?php echo Url::url("/admin/members");?>" class="wojo small simple button"><?php echo Lang::$word->CANCEL;?></a>
      <button type="button" data-action="processMember" name="dosubmit" class="wojo secondary button"><?php echo Lang::$word->M_UPDATE;?></button>
    </div>
  </div>
  <input type="hidden" name="id" value="<?php echo $this->row->id;?>">
</form>