<?php
    /**
     * Items
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: _items_list.tpl.php, v1.00 2022-03-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
?>
<div class="row gutters align middle">
  <div class="columns phone-100">
    <h2><?php echo Lang::$word->LST_TITLE; ?></h2>
    <p class="wojo small text"><?php echo Lang::$word->LST_INFO; ?></p>
  </div>
  <div class="columns auto phone-100">
      <?php if (Auth::hasPrivileges('add_items')): ?>
        <a href="<?php echo Url::url("/admin/items", "new"); ?>" class="wojo small secondary button"><i
            class="icon plus alt"></i><?php echo Lang::$word->LST_ADD; ?></a>
      <?php endif; ?>
  </div>
</div>
<div class="wojo form segment">
  <form method="post" id="wojo_form" action="<?php echo Url::url(Router::$path); ?>" name="wojo_form">
    <div class="row align center middle gutters">
      <div class="columns screen-30 tablet-40 mobile-100 phone-100">
        <div class="wojo icon input">
          <input name="fromdate" type="text" placeholder="<?php echo Lang::$word->FROM; ?>" readonly id="fromdate">
          <i class="icon calendar range"></i>
        </div>
      </div>
      <div class="columns screen-30 tablet-40 mobile-100 phone-100">
        <div class="wojo action input">
          <input name="enddate" type="text" placeholder="<?php echo Lang::$word->TO; ?>" readonly id="enddate">
          <button id="doDates" class="wojo icon primary inverted button"><i class="icon search"></i></button>
        </div>
      </div>
      <div class="columns auto phone-hide">
        <a href="<?php echo Url::url(Router::$path); ?>" class="wojo icon button"><i class="icon repeat"></i></a>
      </div>
    </div>
  </form>
  <div class="center aligned margin bottom">
    <div class="wojo small divided horizontal list">
      <div class="disabled item wojo bold text">
          <?php echo Lang::$word->SORTING_O; ?>
      </div>
      <a href="<?php echo Url::url(Router::$path); ?>" class="item<?php echo Url::setActive("order", false); ?>">
          <?php echo Lang::$word->RESET; ?>
      </a>
      <a href="<?php echo Url::url(Router::$path, "?order=year|DESC"); ?>"
        class="item<?php echo Url::setActive("order", "year"); ?>">
          <?php echo Lang::$word->_YEAR; ?>
      </a>
      <a href="<?php echo Url::url(Router::$path, "?order=make_id|DESC"); ?>"
        class="item<?php echo Url::setActive("order", "make_id"); ?>">
          <?php echo Lang::$word->LST_MAKE; ?>
      </a>
      <a href="<?php echo Url::url(Router::$path, "?order=model_id|DESC"); ?>"
        class="item<?php echo Url::setActive("order", "model_id"); ?>">
          <?php echo Lang::$word->LST_MODEL; ?>
      </a>
      <a href="<?php echo Url::url(Router::$path, "?order=category|DESC"); ?>"
        class="item<?php echo Url::setActive("order", "category"); ?>">
          <?php echo Lang::$word->LST_CAT; ?>
      </a>
      <a href="<?php echo Url::url(Router::$path, "?order=price|DESC"); ?>"
        class="item<?php echo Url::setActive("order", "price"); ?>">
          <?php echo Lang::$word->PRICE; ?>
      </a>
      <a href="<?php echo Url::sortItems(Url::url(Router::$path), "order"); ?>" class="item"><i
          class="icon caret <?php echo Url::ascDesc("order"); ?> link"></i></a>
    </div>
  </div>
  <div class="center aligned"><?php echo Validator::alphaBits(Url::url(Router::$path)); ?></div>
</div>
<?php if (!$this->data): ?>
  <div class="center aligned"><img src="<?php echo ADMINVIEW; ?>/images/nop.svg" alt="">
    <p class="wojo small demi caps text"><?php echo Lang::$word->LST_NOLIST; ?></p>
  </div>
<?php else: ?>
  <div class="wojo segment">
    <table class="wojo basic responsive table">
      <thead>
      <tr>
        <th class="disabled center aligned"></th>
        <th data-sort="string"><?php echo Lang::$word->PHOTO; ?></th>
        <th><?php echo Lang::$word->DESC; ?></th>
        <th><?php echo Lang::$word->LST_CAT; ?></th>
        <th><?php echo Lang::$word->CREATED; ?></th>
        <th class="right aligned"><?php echo Lang::$word->ACTIONS; ?></th>
      </tr>
      </thead>
        <?php foreach ($this->data as $row): ?>
          <tr id="item_<?php echo $row->id; ?>">
            <td class="auto"><span class="wojo small simple label"><?php echo $row->id; ?></span></td>
            <td class="auto relative"><?php if ($row->sold): ?>
                <div class="wojo bookmark"><?php echo Lang::$word->SOLD; ?></div>
                <?php endif; ?>
              <img src="<?php echo UPLOADURL . '/listings/thumbs/' . $row->thumb; ?>" alt="" class="wojo medium image">
            </td>
            <td><b><?php echo $row->title; ?></b> (<?php echo $row->year; ?>) <br/>
              <small><?php echo Lang::$word->BY; ?>:
                  <?php if (Auth::hasPrivileges('edit_members')): ?>
                    <a
                      href="<?php echo Url::url("/admin/members/edit", $row->user_id); ?>"><?php echo $row->username; ?></a>
                  <?php else: ?>
                      <?php echo $row->username; ?>
                  <?php endif; ?>
              </small><br/>
              #: <b><?php echo $row->stock_id; ?></b>
              <br/>
                <?php echo Lang::$word->LST_PRICE; ?>: (<?php echo Utility::formatMoney($row->price); ?>) <small
                class="wojo text negative"><?php echo Utility::formatMoney($row->price_sale); ?></small><br/>
                <?php echo Lang::$word->LST_COND; ?>: <b><?php echo $row->cdname; ?></b><br/>
                <?php echo Lang::$word->MODIFIED; ?>:
              <b><?php echo ($row->modified) ? Date::dodate("short_date", $row->modified) : '- ' . Lang::$word->NEVER . ' -' ?></b><br/>
                <?php if (Date::compareDates($row->expire, Date::today())): ?>
                  <div class="wojo positive label"><?php echo Lang::$word->EXPIRE; ?>: <span
                      class="detail"><?php echo Date::dodate("long_date", $row->expire); ?></span>
                  </div>
                <?php else: ?>
                  <div class="wojo negative label"><?php echo Lang::$word->EXPIRED; ?>: <span
                      class="detail"><?php echo Date::dodate("long_date", $row->expire); ?></span></div>
                <?php endif; ?></td>
            <td><?php echo $row->ctname; ?></td>
            <td
              data-sort-value="<?php echo strtotime($row->created); ?>"><?php echo Date::dodate("short_date", $row->created); ?></td>
            <td class="auto"><a data-wdropdown="#dropdown-itemData" class="wojo white small icon button" id="itemData">
                <i class="icon three dots vertical"></i>
              </a>
              <div class="wojo small dropdown pointing menu top-left" id="dropdown-itemData">
                <a
                  data-set='{"option":[{"iaction":"listingStatus", "id":<?php echo $row->id; ?>, "value":"<?php echo $row->status; ?>"}], "url":"/helper.php", "complete":"remove", "parent":"#item_<?php echo $row->id; ?>"}'
                  class="item iaction"><i
                    class="<?php echo ($row->status) ? "check positive" : "slash circle negative"; ?> icon"></i><?php echo Lang::$word->STATUS; ?>
                </a>
                <a
                  data-set='{"option":[{"iaction":"listingFeatured", "id":<?php echo $row->id; ?>, "value":"<?php echo $row->featured; ?>"}], "url":"/helper.php", "complete":"replaceWith", "parent":"#icon_featured_<?php echo $row->id; ?>"}'
                  class="item iaction"><i id="icon_featured_<?php echo $row->id; ?>"
                    class="<?php echo ($row->featured) ? "check positive" : "ban negative"; ?> icon"></i><?php echo Lang::$word->FEATURED; ?>
                </a>
                <a
                  data-set='{"option":[{"iaction":"listingSold", "id":<?php echo $row->id; ?>, "value":"<?php echo $row->sold; ?>"}], "url":"/helper.php", "complete":"replaceWith", "parent":"#icon_sold_<?php echo $row->id; ?>"}'
                  class="item iaction"><i id="icon_sold_<?php echo $row->id; ?>"
                    class="<?php echo ($row->sold) ? "check positive" : "slash circle negative"; ?> icon"></i><?php echo Lang::$word->SOLD; ?>
                </a>
              </div>
              <a href="<?php echo Url::url(Router::$path, "stats/" . $row->id); ?>"
                class="wojo icon dark small inverted button"><i class="icon pie chart fill"></i></a>
              <a href="<?php echo Url::url(Router::$path, "print/" . $row->id); ?>"
                class="wojo icon dark small inverted button"><i class="icon printer"></i></a>
              <div class="small margin bottom"></div>
              <a href="<?php echo Url::url(Router::$path, "images/" . $row->id); ?>"
                class="wojo icon positive small inverted button"><i class="icon images"></i></a>
                <?php if (Auth::hasPrivileges('edit_items')): ?>
                  <a href="<?php echo Url::url(Router::$path, "edit/" . $row->id); ?>"
                    class="wojo icon primary small inverted button"><i class="icon pencil square"></i></a>
                <?php endif; ?>
                <?php if (Auth::hasPrivileges('delete_items')): ?>
                  <a
                    data-set='{"option":[{"delete": "deleteListing","title": "<?php echo Validator::sanitize($row->title, "chars"); ?>","id": <?php echo $row->id; ?>}],"action":"delete","parent":"#item_<?php echo $row->id; ?>"}'
                    class="wojo negative small inverted icon button data"><i class="icon trash"></i></a>
                <?php endif; ?></td>
          </tr>
        <?php endforeach; ?>
    </table>
  </div>
<?php endif; ?>
<div class="row gutters align middle">
  <div class="columns auto mobile-100 phone-100">
    <div
      class="wojo small thick text"><?php echo Lang::$word->TOTAL . ': ' . $this->pager->items_total; ?> / <?php echo Lang::$word->CURPAGE . ': ' . $this->pager->current_page . ' ' . Lang::$word->OF . ' ' . $this->pager->num_pages; ?></div>
  </div>
  <div class="columns right aligned mobile-100 phone-100"><?php echo $this->pager->display_pages(); ?></div>
</div>