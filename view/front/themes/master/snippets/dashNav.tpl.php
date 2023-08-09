<?php
/**
 * Dash Nav
 *
 * @package Wojo Framework
 * @author wojoscripts.com
 * @copyright 2022
 * @version $Id: dashNav.tpl.php, v1.00 2022-03-02 10:12:05 gewa Exp $
 */
if (!defined("_WOJO"))
  die('Direct access to this location is not allowed.');
?>
<div class="row gutters align middle">
  <div class="columns">
    <div class="wojo navs">
      <ul class="nav">
        <li class="<?php if (count($this->segments) == 1) echo 'active';?>">
          <a href="<?php echo Url::url('/dashboard');?>"><i class="icon box"></i>
          <?php echo Lang::$word->MSM_TITLE;?></a>
        </li>
        <li class="<?php if (Utility::in_array_any(["mylistings"], $this->segments)) echo 'active';?>">
          <a href="<?php echo Url::url('/dashboard', 'mylistings');?>"><i class="icon car"></i>
          <?php echo Lang::$word->LISTINGS;?></a>
        </li>
        <li class="<?php if (Utility::in_array_any(["history"], $this->segments)) echo 'active';?>">
          <a href="<?php echo Url::url('/dashboard', 'history');?>"><i class="icon time history"></i>
          <?php echo Lang::$word->ACC_PAYTRANS;?></a>
        </li>
        <li class="<?php if (Utility::in_array_any(["profile"], $this->segments)) echo 'active';?>">
          <a href="<?php echo Url::url('/dashboard', 'profile');?>"><i class="icon person lines"></i>
          <?php echo Lang::$word->ADM_MYACC;?></a>
        </li>
        <li class="<?php if (Utility::in_array_any(["reviews"], $this->segments)) echo 'active';?>">
          <a href="<?php echo Url::url('/dashboard', 'reviews');?>"><i class="icon card text"></i>
          <?php echo Lang::$word->SRW_ADD;?></a>
        </li>
        <li>
          <a href="<?php echo Url::url('/logout');?>" class="last"><i class="icon power"></i>
          <?php echo Lang::$word->LOGOUT;?></a>
        </li>
      </ul>
    </div>
  </div>
</div>
<div id="mResult"></div>