<?php
    /**
     * Page
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: page.tpl.php, v1.00 2022-04-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
?>
<?php switch ($this->row->type): case "contact": ?>
    <?php include_once(THEMEBASE . '/_contact.tpl.php'); ?>
    <?php break; ?>
<?php case "faq": ?>
    <?php include_once(THEMEBASE . '/_faq.tpl.php'); ?>
    <?php break; ?>
<?php default: ?>
  <div class="wojo big vertical padding">
    <div class="wojo-grid">
      <h1><?php echo $this->row->title; ?></h1>
        <?php echo Url::out_url($this->row->body); ?>
    </div>
  </div>
    <?php break; ?>
<?php endswitch; ?>