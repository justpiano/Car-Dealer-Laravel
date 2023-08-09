<?php
    /**
     * Reviews
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: reviews.tpl.php, v1.00 2022-05-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
    
    if (!Auth::hasPrivileges('manage_reviews')): print Message::msgError(Lang::$word->NOACCESS);
        return; endif;
?>
  <h2><?php echo Lang::$word->SRW_TITLE; ?></h2>
  <p class="wojo small text"><?php echo Lang::$word->SRW_INFO; ?></p>
<?php if (!$this->data): ?>
  <div class="center aligned"><img src="<?php echo ADMINVIEW; ?>/images/nop.svg" alt="">
    <p class="wojo small demi caps text"><?php echo Lang::$word->SRW_NOREV; ?></p>
  </div>
<?php else: ?>
  <div class="wojo mason">
      <?php foreach ($this->data as $row): ?>
        <div class="item" id="item_<?php echo $row->id; ?>">
          <div class="wojo attached card">
            <div class="content">
              <div class="center aligned">
                <img src="<?php echo UPLOADURL; ?>/avatars/<?php echo ($row->avatar) ?: "blank.svg"; ?>" alt=""
                  class="wojo rounded inline normal image">
                <p class="vertical margin">
                    <?php if (Auth::hasPrivileges('edit_members')): ?>
                      <a href="<?php echo Url::url("/admin/members/edit", $row->uid); ?>"><?php echo $row->name; ?></a>
                    <?php else: ?>
                        <?php echo $row->name; ?>
                    <?php endif; ?>
                </p>
              </div>
              <div class="wojo small text" id="content_<?php echo $row->id; ?>"><?php echo $row->content; ?></div>
            </div>
            <div class="footer divided">
              <div class="footer divided center aligned">
                <a
                  data-set='{"option":[{"action":"editReview","id": <?php echo $row->id; ?>}], "label":"<?php echo Lang::$word->SRW_EDIT; ?>", "url":"helper.php", "parent":"#content_<?php echo $row->id; ?>", "complete":"replace", "modalclass":"normal"}'
                  class="wojo icon primary circular inverted button action">
                  <i class="icon pencil"></i></a>
                <a
                  data-set='{"option":[{"delete": "deleteReview","title": "<?php echo Validator::sanitize($row->name, "chars"); ?>","id":<?php echo $row->id; ?>}],"action":"delete","subtext":"<?php echo Lang::$word->DELCONFIRM3; ?>", "parent":"#item_<?php echo $row->id; ?>"}'
                  class="wojo icon circular inverted negative button data">
                  <i class="icon trash"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
  </div>
<?php endif; ?>