<?php
    /**
     * Members
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: _members_activity.tpl.php, v1.00 2022-03-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
?>
<h2><?php echo Lang::$word->CL_TITLE5; ?></h2>
<p class="wojo small text"><?php echo Lang::$word->CL_INFO5; ?></p>
<div class="row gutters">
  <div class="columns screen-30 tablet-100 mobile-100 phone-100">
    <form method="post" id="wojo_form" name="wojo_form">
      <div class="wojo card">
        <div class="header center aligned"><img class="wojo small inline rounded image"
            src="<?php echo UPLOADURL; ?>/avatars/<?php echo ($this->row->avatar) ?: "blank.svg"; ?>" alt="">
          <p><?php echo $this->row->fullname; ?></p>
        </div>
        <div class="content">
          <div class="wojo form">
            <div class="wojo fields">
              <div class="basic field">
                <label><?php echo Lang::$word->CL_QMSG; ?></label>
                <textarea name="message"></textarea>
                <input name="id" type="hidden" value="<?php echo $this->row->id; ?>">
              </div>
            </div>
          </div>
        </div>
        <div class="footer divided center aligned">
          <button type="button" data-action="quickMessage" name="dosubmit"
            class="wojo primary right button"><?php echo Lang::$word->CL_SENDMSG; ?><i class="icon arrow right"></i>
          </button>
        </div>
      </div>
    </form>
  </div>
  <div class="columns screen-70 tablet-100 mobile-100 phone-100">
    <div class="wojo segment">
      <div class="header">
        <h4><?php echo $this->row->fullname; ?> // <?php echo $this->row->email; ?></h4>
      </div>
        <?php if ($this->row->about): ?>
          <p><?php echo $this->row->about; ?></p>
          <div class="wojo divider"></div>
        <?php endif; ?>
      <div class="center aligned">
        <div class="wojo divided horizontal list margin bottom">
          <div class="item"><i class="icon earth"></i>
              <?php echo $this->row->lastip; ?></div>
          <div class="item"><i class="icon time"></i>
              <?php echo Date::timesince($this->row->created); ?></div>
          <a class="item" href="<?php echo Url::url("/admin/members/listings", $this->row->id); ?>"><i
              class="icon car"></i>
              <?php echo Lang::$word->LISTINGS; ?>
              <?php echo $this->row->listings; ?></a>
        </div>
      </div>
        <?php if (!$this->data): ?>
            <?php echo Message::msgSingleInfo(Lang::$word->CL_NO_ACC); ?>
        <?php else: ?>
          <ul class="wojo form activity scrollbox h500">
              <?php foreach ($this->data as $row): ?>
                <li>
                  <div class="intro"><i class="icon <?php echo Items::activityIcons($row->type) ?>"></i>
                      <?php echo Date::timesince($row->created); ?></div>
                  <div class="content">
                      <?php echo Items::activityTitle($row); ?>
                    <span class="wojo separator"></span><small><?php echo Items::activityDesc($row); ?></small>
                  </div>
                </li>
              <?php endforeach; ?>
          </ul>
        <?php endif; ?>
    </div>
  </div>
</div>