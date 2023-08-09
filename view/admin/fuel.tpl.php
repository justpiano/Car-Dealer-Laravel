<?php
    /**
     * Fuel
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: fuel.tpl.php, v1.00 2022-05-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
    
    if (!Auth::hasPrivileges('manage_fuel')): print Message::msgError(Lang::$word->NOACCESS);
        return; endif;
?>
  <div class="row gutters align middle">
    <div class="columns phone-100">
      <h2><?php echo Lang::$word->FUEL_SUB; ?></h2>
      <p class="wojo small text"><?php echo Lang::$word->FUEL_INFO; ?></p>
    </div>
    <div class="columns auto phone-100">
      <a data-set='{"option":[{"action":"newInventory","type": "fuel", "url":"<?php echo Url::uri(); ?>"}], "label":"<?php echo Lang::$word->SUBMIT; ?>", "redirect":true, "url":"helper.php", "parent":"#editable", "complete":"prepend", "modalclass":"normal"}' class="wojo small secondary stacked button action"><i class="icon plus alt"></i><?php echo Lang::$word->FUEL_ADD; ?></a>
    </div>
  </div>
<?php if (!$this->data): ?>
  <div class="center aligned"><img src="<?php echo ADMINVIEW; ?>/images/nop.svg" alt="">
    <p class="wojo small demi caps text"><?php echo Lang::$word->FUEL_NOFUEL; ?></p>
  </div>
<?php else: ?>
  <div class="row grid gutters screen-3 tablet-3 mobile-2 phone-1" id="editable">
      <?php foreach ($this->data as $row): ?>
        <div class="columns" id="item_<?php echo $row->id; ?>">
          <div class="wojo compact attached segment">
            <span data-editable="true" data-set='{"action": "editFuel", "id": <?php echo $row->id; ?>, "name":"<?php echo $row->name; ?>"}'><?php echo $row->name; ?></span>
            <a data-set='{"option":[{"delete": "deleteFuel","title": "<?php echo Validator::sanitize($row->name, "chars"); ?>","id": <?php echo $row->id; ?>}],"action":"delete","parent":"#item_<?php echo $row->id; ?>"}' class="wojo small simple negative attached top right icon button data"><i class="icon trash alt fill"></i></a>
          </div>
        </div>
      <?php endforeach; ?>
  </div>
<?php endif; ?>