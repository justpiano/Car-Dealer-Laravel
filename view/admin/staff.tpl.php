<?php
    /**
     * Staff
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: staff.tpl.php, v1.00 2022-04-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
    
    if (!Auth::checkAcl("owner", "admin")): print Message::msgError(Lang::$word->NOACCESS);
        return; endif;
?>
<?php switch (Url::segment($this->segments)): case "edit": ?>
  <!-- Start edit -->
    <?php include("_staff_edit.tpl.php"); ?>
    <?php break; ?>
  <!-- Start new -->
<?php case "new": ?>
    <?php include("_staff_new.tpl.php"); ?>
    <?php break; ?>
  <!-- Start default -->
<?php default: ?>
    <?php include("_staff_grid.tpl.php"); ?>
    <?php break; ?>
<?php endswitch; ?>