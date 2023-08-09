<?php
    /**
     * Items
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: items.tpl.php, v1.00 2022-05-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
?>
<?php switch (Url::segment($this->segments)): case "edit": ?>
  <!-- Start edit -->
    <?php include("_items_edit.tpl.php"); ?>
    <?php break; ?>
  <!-- Start new -->
<?php case "new": ?>
    <?php include("_items_new.tpl.php"); ?>
    <?php break; ?>
  <!-- Start Gallery -->
<?php case "images": ?>
    <?php include("_items_gallery.tpl.php"); ?>
    <?php break; ?>
  <!-- Start Stats -->
<?php case "stats": ?>
    <?php include("_items_stats.tpl.php"); ?>
    <?php break; ?>
  <!-- Start Print -->
<?php case "print": ?>
    <?php include("_items_print.tpl.php"); ?>
    <?php break; ?>
  <!-- Start Pending -->
<?php case "pending": ?>
    <?php include("_items_pending.tpl.php"); ?>
    <?php break; ?>
  <!-- Start Expired -->
<?php case "expired": ?>
    <?php include("_items_expired.tpl.php"); ?>
    <?php break; ?>
  <!-- Start default -->
<?php default: ?>
    <?php include("_items_list.tpl.php"); ?>
    <?php break; ?>
<?php endswitch; ?>