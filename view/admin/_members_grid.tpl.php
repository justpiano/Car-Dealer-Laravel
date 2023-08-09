<?php
    /**
     * Members
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2019
     * @version $Id: _members_grid.tpl.php, v1.00 2020-03-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
?>
<div class="row gutters align middle">
  <div class="columns phone-100">
    <h2><?php echo Lang::$word->ADM_MEMBER; ?></h2>
    <p class="wojo small text"><?php echo Lang::$word->CL_INFO2; ?></p>
  </div>
  <div class="columns auto phone-100">
      <?php if (Auth::hasPrivileges('add_members')): ?>
        <a href="<?php echo Url::url("/admin/members", "new"); ?>" class="wojo small secondary  button"><i
            class="icon plus alt"></i>
            <?php echo Lang::$word->ACC_ADD; ?></a>
      <?php endif; ?>
    <a class="wojo basic small icon disabled button"><i class="icon grid"></i></a>
    <a href="<?php echo Url::url("/admin/members"); ?>" class="wojo primary small icon button"><i
        class="icon unordered list"></i></a>
  </div>
</div>
<div class="row gutters align center">
  <div class="columns screen-40 tablet-50 mobile-100 phone-100">
    <form method="get" id="wojo_form" name="wojo_form" class="wojo form">
      <div class="wojo action input">
        <input name="find" placeholder="<?php echo Lang::$word->SEARCH; ?>" type="text">
        <button class="wojo primary inverted icon button">
          <i class="icon search"></i></button>
      </div>
    </form>
  </div>
</div>
<div class="wojo form margin bottom">
  <div class="vertical margin center aligned">
    <div class="wojo divided horizontal link list">
      <div class="disabled item wojo semi text">
          <?php echo Lang::$word->SORTING_O; ?>
      </div>
      <a href="<?php echo Url::url(Router::$path); ?>" class="item<?php echo Url::setActive("order", false); ?>">
          <?php echo Lang::$word->RESET; ?>
      </a>
      <a href="<?php echo Url::url(Router::$path, "?order=fname|DESC"); ?>"
        class="item<?php echo Url::setActive("order", "fname"); ?>">
          <?php echo Lang::$word->FNAME; ?>
      </a>
      <a href="<?php echo Url::url(Router::$path, "?order=lname|DESC"); ?>"
        class="item<?php echo Url::setActive("order", "lname"); ?>">
          <?php echo Lang::$word->LNAME; ?>
      </a>
      <a href="<?php echo Url::url(Router::$path, "?order=email|DESC"); ?>"
        class="item<?php echo Url::setActive("order", "email"); ?>">
          <?php echo Lang::$word->EMAIL; ?>
      </a>
      <a href="<?php echo Url::url(Router::$path, "?order=membership_id|DESC"); ?>"
        class="item<?php echo Url::setActive("order", "membership_id"); ?>">
          <?php echo Lang::$word->MEMBERSHIP; ?>
      </a>
      <a href="<?php echo Url::url(Router::$path, "?order=listings|DESC"); ?>"
        class="item<?php echo Url::setActive("order", "listings"); ?>">
          <?php echo Lang::$word->LISTINGS; ?>
      </a>
      <a href="<?php echo Url::sortItems(Url::url(Router::$path), "order"); ?>" class="item"><i
          class="icon caret <?php echo Url::ascDesc("order"); ?> link"></i></a>
    </div>
  </div>
  <div class="center aligned"><?php echo Validator::alphaBits(Url::url(Router::$path)); ?></div>
</div>
<?php if (!$this->data): ?>
  <div class="center aligned"><img src="<?php echo ADMINVIEW; ?>/images/nop.svg" alt="">
    <p class="wojo small demi caps text"><?php echo Lang::$word->ACC_EMPTY; ?></p>
  </div>
<?php else: ?>
  <div class="wojo cards screen-3 tablet-3 mobile-2 phone-1">
      <?php foreach ($this->data as $row): ?>
        <div class="card" id="item_<?php echo $row->id; ?>">
          <div class="header">
            <div class="row align middle">
              <div class="columns">
                <a class="wojo bold grey text"
                  href="<?php echo Url::url("/admin/members/edit", $row->id); ?>"><?php echo $row->fullname; ?></a>
              </div>
              <div class="columns auto">
                <a data-wdropdown="#userDrop_<?php echo $row->id; ?>"
                  class="wojo small primary inverted icon circular button">
                  <i class="icon three dots vertical"></i>
                </a>
                <div class="wojo dropdown small pointing top-right" id="userDrop_<?php echo $row->id; ?>">
                    <?php if (Auth::hasPrivileges('edit_members')): ?>
                      <a class="item" href="<?php echo Url::url("/admin/members/edit", $row->id); ?>"><i
                          class="icon pencil square"></i>
                          <?php echo Lang::$word->EDIT; ?></a>
                    <?php endif; ?>
                    <?php if (Auth::hasPrivileges('manage_upay')): ?>
                      <a class="item" href="<?php echo Url::url("/admin/members/payments", $row->id); ?>"><i
                          class="icon collection"></i><?php echo Lang::$word->ACC_PAYTRANS; ?></a>
                    <?php endif; ?>
                  <a class="item" href="<?php echo Url::url("/admin/members/activity", $row->id); ?>"><i
                      class="icon person lines"></i><?php echo Lang::$word->ACTIVITY; ?></a>
                    <?php if (Auth::hasPrivileges('delete_members')): ?>
                      <div class="divider"></div>
                      <a
                        data-set='{"option":[{"trash": "trashUser","title": "<?php echo Validator::sanitize($row->fullname, "chars"); ?>","id":<?php echo $row->id; ?>}],"action":"trash", "parent":"#item_<?php echo $row->id; ?>"}'
                        class="item wojo demi text data">
                        <i class="icon trash"></i><?php echo Lang::$word->TRASH; ?>
                      </a>
                    <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
          <div class="content">
            <div class="center aligned bottom margin">
              <a href="<?php echo Url::url("/admin/members/edit", $row->id); ?>"><img
                  src="<?php echo UPLOADURL; ?>/avatars/<?php echo $row->avatar ?: "blank.svg"; ?>" alt=""
                  class="wojo basic circular normal inline image"></a>
            </div>
            <div class="row align middle">
              <div class="columns"><span
                  class="wojo small text"><?php echo Date::doDate("short_date", $row->created); ?></span></div>
              <div class="columns auto"><?php echo Utility::status($row->active, $row->id); ?>
              </div>
            </div>
          </div>
          <div class="footer">
            <div class="wojo celled list">
              <div class="item"><?php echo Lang::$word->EMAIL; ?>:<a
                  href="<?php echo Url::url("/admin/mailer", "?mailid=" . $row->email . "&clients=true"); ?>"
                  class="description"><?php echo $row->email; ?></a></div>
              <div
                class="item align middle"><?php echo Lang::$word->MEMBERSHIP; ?>: <?php echo $row->mtitle ?: "N/A"; ?>
                <small
                  class="description"> (<?php echo $row->membership_expire == null ? "-/-" : Date::doDate("long_date", $row->membership_expire); ?>)</small>
              </div>
              <div class="item"><?php echo Lang::$word->LISTINGS; ?>: <a
                  href="<?php echo Url::url("/admin/members/listings", $row->id); ?>">
                  <span class="description"><?php echo $row->listings; ?></span>
                </a>
              </div>
              <div class="item">ip: <span class="description"><?php echo $row->lastip; ?></span></div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
  </div>
<?php endif; ?>
<div class="row gutters align middle">
  <div class="columns auto mobile-100 phone-100">
    <div
      class="wojo small thick text"><?php echo Lang::$word->TOTAL . ': ' . $this->pager->items_total; ?> / <?php echo Lang::$word->CURPAGE . ': ' . $this->pager->current_page . ' ' . Lang::$word->OF . ' ' . $this->pager->num_pages; ?></div>
  </div>
  <div class="columns right aligned mobile-100 phone-100"><?php echo $this->pager->display_pages(); ?></div>
</div>