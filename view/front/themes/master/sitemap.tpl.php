<?php
    /**
     * Sitemap
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: sitemap.tpl.php", v1.00 2022-04-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
?>
<div class="wojo-grid">
  <div class="mt3">
    <h4><?php echo Lang::$word->LST_TITLE7; ?></h4>
  </div>
    <?php if ($this->data): ?>
      <div class="row small grid gutters screen-2">
          <?php foreach ($this->data as $row): ?>
            <div class="columns">
              <div class="item">
                <i class="icon small chevron right"></i>
                <a
                  href="<?php echo Url::url('/listing/' . $row->idx, $row->slug); ?>"><?php echo $row->nice_title; ?></a>
              </div>
            </div>
          <?php endforeach; ?>
      </div>
    <?php endif; ?>
</div>
