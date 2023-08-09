<?php
    /**
     * Members Active
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: _members_active.tpl.php, v1.00 2022-03-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
?>
<div class="row gutters align middle">
  <div class="columns phone-100">
    <h2><?php echo Lang::$word->ADM_MEMS; ?></h2>
    <p class="wojo small text"><?php echo Lang::$word->CL_INFO7; ?></p>
  </div>
</div>
<div class="row gutters align center">
  <div class="columns screen-40 tablet-50 mobile-100 phone-100">
    <form method="get" id="wojo_form" name="wojo_form" class="wojo form">
      <div class="wojo action input">
        <input name="find" placeholder="<?php echo Lang::$word->SEARCH; ?>" type="text">
        <button class="wojo icon button">
          <i class="icon search"></i></button>
      </div>
    </form>
  </div>
</div>
<div class="wojo form">
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
  <table class="wojo fitted segment responsive table">
    <thead>
    <tr>
      <th><?php echo Lang::$word->NAME; ?></th>
      <th><?php echo Lang::$word->EMAIL; ?></th>
      <th><?php echo Lang::$word->MEMBERSHIP; ?></th>
      <th><?php echo Lang::$word->LISTINGS; ?></th>
      <th><?php echo Lang::$word->ACTIONS; ?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($this->data as $row): ?>
      <tr id="item_<?php echo $row->id; ?>">
        <td><?php echo $row->fullname; ?></td>
        <td><a
            href="<?php echo Url::url("/admin/mailer", "?mailid=" . $row->email . "&clients=true"); ?>"><?php echo $row->email; ?></a>
        </td>
        <td><?php echo $row->mtitle; ?></td>
        <td><a href="<?php echo Url::url("/admin/members/listings", $row->id); ?>" class="wojo secondary label">
            <i class="icon car"></i>
                <?php echo $row->listings; ?></a></td>
        <td class="auto"><?php if (Auth::hasPrivileges('manage_upay')): ?>
            <a href="<?php echo Url::url("/admin/members/payments", $row->id); ?>"
              class="wojo small primary inverted icon button"><i class="icon collection"></i></a>
            <?php endif; ?>
          <a href="<?php echo Url::url("/admin/members/activity", $row->id); ?>"
            class="wojo small secondary inverted icon button"><i class="icon person lines"></i></a>
            <?php if (Auth::hasPrivileges('edit_members')): ?>
              <a href="<?php echo Url::url("/admin/members/edit", $row->id); ?>"
                class="wojo small positive inverted icon button"><i class="icon icon pen alt"></i></a>
            <?php endif; ?>
            <?php if (Auth::hasPrivileges('delete_members')): ?>
              <a
                data-set='{"option":[{"trash": "trashUser","title": "<?php echo Validator::sanitize($row->fullname, "chars"); ?>","id":<?php echo $row->id; ?>}],"action":"trash","subtext":"<?php echo Lang::$word->DELCONFIRM3; ?>", "parent":"#item_<?php echo $row->id; ?>"}'
                class="wojo small negative inverted icon button data">
                <i class="icon trash"></i>
              </a>
            <?php endif; ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>
<div class="row gutters align middle">
  <div class="columns auto mobile-100 phone-100">
    <div
      class="wojo small thick text"><?php echo Lang::$word->TOTAL . ': ' . $this->pager->items_total; ?> / <?php echo Lang::$word->CURPAGE . ': ' . $this->pager->current_page . ' ' . Lang::$word->OF . ' ' . $this->pager->num_pages; ?></div>
  </div>
  <div class="columns right aligned mobile-100 phone-100"><?php echo $this->pager->display_pages(); ?></div>
</div>