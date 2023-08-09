<?php
  /**
   * Members
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2020
   * @version $Id: _members_listings.tpl.php, v1.00 2020-03-05 10:12:05 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
?>
<h2><?php echo Lang::$word->CL_TITLE6;?>
  <small>/ <?php echo $this->row->fullname;?></small></h2>
<p class="wojo small text"><?php echo Lang::$word->CL_INFO6;?></p>
<?php if(!$this->data):?>
<div class="center aligned"><img src="<?php echo ADMINVIEW;?>/images/nop.svg" alt="">
  <p class="wojo small demi caps text"><?php echo Lang::$word->CL_NO_LIST;?></p>
</div>
<?php else:?>
<div class="wojo cards screen-3 tablet-3 mobile-2 phone-1">
  <?php foreach ($this->data as $row):?>
  <div class="card" id="item_<?php echo $row->id;?>">
    <div class="center aligned"><img src="<?php echo UPLOADURL . '/listings/thumbs/' . $row->thumb;?>" alt="" class="wojo inline rounded image"></div>
    <div class="content">
      <h5 class="center aligned"><?php echo Validator::truncate($row->title, 50);?></h5>
      <div class="center aligned">
        <div class="wojo inverted dark label"><?php echo Lang::$word->_YEAR;?>
          <span class="detail"><?php echo $row->year;?></span></div>
        <div class="wojo inverted dark label"><?php echo Lang::$word->PRICE;?>
          <span class="detail"><?php echo Utility::formatMoney($row->price);?></span></div>
        <div class="wojo inverted dark label"><?php echo Lang::$word->VISITS;?>
          <span class="detail"><?php echo $row->hits;?></span></div>
      </div>
    </div>
    <?php if(Auth::hasPrivileges('edit_items') and Auth::hasPrivileges('delete_items')):?>
    <div class="footer divided flex align spaced">
      <?php if(Auth::hasPrivileges('edit_items')):?>
      <a href="<?php echo Url::url("/admin/items/edit", $row->id);?>" class="wojo small inverted positive button"><i class="icon arrow left"></i>
      <?php echo Lang::$word->LST_EDIT;?></a>
      <?php endif;?>
      <?php if(Auth::hasPrivileges('delete_items')):?>
      <a data-set='{"option":[{"delete": "deleteItem","title": "<?php echo Validator::sanitize($row->title, "chars");?>","id":<?php echo $row->id;?>}],"action":"delete", "parent":"#item_<?php echo $row->id;?>"}' class="wojo small negative inverted right button data">
      <?php echo Lang::$word->LST_DELIST;?>
      <i class="icon arrow right"></i>
      </a>
      <?php endif;?>
    </div>
    <?php endif;?>
  </div>
  <?php endforeach;?>
</div>
<?php endif;?>