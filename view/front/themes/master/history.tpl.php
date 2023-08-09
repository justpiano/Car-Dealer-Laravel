<?php
    /**
     * History
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: history.tpl.php", v1.00 2022-04-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
?>
<div class="wojo big vertical padding">
  <div class="wojo-grid">
      <?php include_once(THEMEBASE . '/snippets/dashNav.tpl.php'); ?>
    <h3><?php echo Lang::$word->ACC_PAYTRANS; ?></h3>
    <p class="wojo big bottom margin"><?php echo Lang::$word->HOME_SUB12P; ?></p>
      <?php if ($this->data): ?>
        <table class="wojo basic table">
          <thead>
          <tr>
            <th><?php echo Lang::$word->NAME; ?></th>
            <th><?php echo Lang::$word->ACTIVATED; ?></th>
            <th><?php echo Lang::$word->EXPIRE; ?></th>
            <th class="auto"></th>
          </tr>
          </thead>
            <?php foreach ($this->data as $row): ?>
              <tr id="item_<?php echo $row->transaction_id ?>">
                <td><strong><?php echo $row->title; ?></strong></td>
                <td><?php echo Date::doDate("long_date", $row->activated); ?></td>
                <td><?php echo Date::doDate("long_date", $row->expire); ?></td>
                <td class="center aligned"><a data-tooltip="<?php echo Lang::$word->INVOICE; ?>"
                    href="<?php echo FRONTVIEW; ?>/controller.php?action=invoice&amp;invoice_id=<?php echo Utility::encode($row->transaction_id); ?>"
                    class="wojo small primary icon button"><i class="icon save"></i></a></td>
              </tr>
            <?php endforeach; ?>
          <tfoot>
          <tr>
            <td colspan="5"><span class="wojo basic primary small inverted passive button">
        <?php echo Lang::$word->TRX_TOTAMT; ?>
        <?php echo Utility::formatMoney($this->totals); ?>
        </span></td>
          </tr>
          </tfoot>
        </table>
      <?php endif; ?>
  </div>
</div>