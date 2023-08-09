<?php
    /**
     * Expired
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: _items_expired.tpl.php, v1.00 2022-03-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
?>
<?php if (!Auth::hasPrivileges('manage_approval')): print Message::msgError(Lang::$word->NOACCESS);
    return; endif; ?>
<div class="row gutters align middle">
  <div class="columns">
    <h2><?php echo Lang::$word->LST_TITLE6; ?></h2>
    <p class="wojo small text"><?php echo Lang::$word->LST_INFO6; ?></p>
  </div>
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
        <th data-sort="string"><?php echo Lang::$word->PHOTO; ?></th>
        <th><?php echo Lang::$word->DESC; ?></th>
        <th><?php echo Lang::$word->LST_CAT; ?></th>
        <th><?php echo Lang::$word->CREATED; ?></th>
        <th class="right aligned"><?php echo Lang::$word->ACTIONS; ?></th>
      </tr>
      </thead>
        <?php foreach ($this->data as $row): ?>
          <tr id="item_<?php echo $row->id; ?>">
            <td class="auto"><img src="<?php echo UPLOADURL . '/listings/thumbs/' . $row->thumb; ?>" alt=""
                class="wojo medium image"></td>
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
            <td class="auto"><?php if (Auth::hasPrivileges('delete_items')): ?>
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