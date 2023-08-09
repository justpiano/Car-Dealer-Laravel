<?php
    /**
     * Staff
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: _staff_grid.tpl.php, v1.00 2022-04-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
?>
  <div class="row gutters align middle">
    <div class="columns phone-100">
      <h2><?php echo Lang::$word->M_TITLE6; ?></h2>
      <p class="wojo small text"><?php echo Lang::$word->M_INFO6; ?></p>
    </div>
    <div class="columns auto phone-100">
      <a href="<?php echo Url::url(Router::$path, "new/"); ?>" class="wojo small secondary stacked button"><i
          class="icon plus alt"></i><?php echo Lang::$word->M_ADD; ?></a>
    </div>
  </div>
<?php if (!$this->data): ?>
  <div class="center aligned"><img src="<?php echo ADMINVIEW; ?>/images/nop.svg" alt="">
    <p class="wojo small demi caps text"><?php echo Lang::$word->M_NO_MEMBERS; ?></p>
  </div>
<?php else: ?>
  <div class="wojo mason">
      <?php foreach ($this->data as $row): ?>
        <div class="item" id="item_<?php echo $row->id; ?>">
          <div class="wojo attached card">
            <div class="header">
              <div class="row align middle">
                <div class="columns">
                  <a class="wojo bold grey text"
                    href="<?php echo Url::url("/admin/staff/edit", $row->id); ?>"><?php echo $row->fullname; ?></a>
                </div>
                <div class="columns auto">
                  <a data-wdropdown="#userDrop_<?php echo $row->id; ?>"
                    class="wojo small primary inverted icon circular button">
                    <i class="icon three dots horizontal"></i>
                  </a>
                  <div class="wojo dropdown small pointing top-right" id="userDrop_<?php echo $row->id; ?>">
                    <a class="item" href="<?php echo Url::url("/admin/staff/edit", $row->id); ?>"><i
                        class="icon pencil"></i>
                        <?php echo Lang::$word->EDIT; ?></a>
                    <div class="divider"></div>
                    <a
                      data-set='{"option":[{"trash": "trashStaff","title": "<?php echo Validator::sanitize($row->fullname, "chars"); ?>","id":<?php echo $row->id; ?>}],"action":"trash","subtext":"<?php echo Lang::$word->DELCONFIRM3; ?>", "parent":"#item_<?php echo $row->id; ?>"}'
                      class="item wojo demi text data">
                      <i class="icon trash"></i><?php echo Lang::$word->TRASH; ?>
                    </a>
                  </div>
                </div>
              </div>
            </div>
            <div class="content">
              <div class="center aligned">
                <a href="<?php echo Url::url("/admin/staff/edit", $row->id); ?>"><img
                    src="<?php echo UPLOADURL; ?>/avatars/<?php echo $row->avatar ?: "blank.svg"; ?>" alt=""
                    class="wojo basic circular normal inline image"></a>
              </div>
              <div class="row align middle">
                <div class="columns"><span
                    class="wojo small text"><?php echo Date::doDate("short_date", $row->created); ?></span></div>
                <div class="columns"><?php echo Utility::accountLevelToType($row->userlevel); ?></div>
                <div class="columns auto"><?php echo Utility::status($row->active, $row->id); ?>
                </div>
              </div>
            </div>
            <div class="footer">
              <p><?php echo Lang::$word->EMAIL; ?>: <a
                  href="<?php echo Url::url("/admin/mailer", "?mailid=" . $row->email); ?>"><?php echo $row->email; ?></a>
              </p>
              <p>ip: <?php echo $row->lastip; ?></p>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
  </div>
<?php endif; ?>