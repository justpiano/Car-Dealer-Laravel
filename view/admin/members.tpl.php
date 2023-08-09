<?php
    /**
     * Members
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: members.tpl.php, v1.00 2022-05-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
?>
<?php switch (Url::segment($this->segments)): case "edit": ?>
  <!-- Start edit -->
    <?php include("_members_edit.tpl.php"); ?>
    <?php break; ?>
  <!-- Start new -->
<?php case "new": ?>
    <?php include("_members_new.tpl.php"); ?>
    <?php break; ?>
  <!-- Start activity -->
<?php case "activity": ?>
    <?php include("_members_activity.tpl.php"); ?>
    <?php break; ?>
  <!-- Start listings -->
<?php case "listings": ?>
    <?php include("_members_listings.tpl.php"); ?>
    <?php break; ?>
  <!-- Start history -->
<?php case "payments": ?>
    <?php include("_members_payments.tpl.php"); ?>
    <?php break; ?>
  <!-- Start Active -->
<?php case "active": ?>
    <?php include("_members_active.tpl.php"); ?>
    <?php break; ?>
  <!-- Start grid -->
<?php case "grid": ?>
    <?php include("_members_grid.tpl.php"); ?>
    <?php break; ?>
  <!-- Start default -->
<?php default: ?>
    <?php include("_members_list.tpl.php"); ?>
    <?php break; ?>
<?php endswitch; ?>