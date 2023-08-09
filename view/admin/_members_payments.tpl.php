<?php
    /**
     * Members
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: _members_payments.tpl.php, v1.00 2022-05-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
    
    if (!Auth::hasPrivileges('manage_upay')): print Message::msgError(Lang::$word->NOACCESS);
        return; endif;
?>
<h2><?php echo Lang::$word->ACC_PAYTRANS; ?>
  <small>// <?php echo $this->row->fullname; ?></small></h2>
<p><?php echo Lang::$word->TRX_INFO2; ?></p>
<div class="wojo segment">
  <div class="flex align end">
    <div id="legend" class="wojo small horizontal list"></div>
  </div>
  <div id="payment_chart" class="h300"></div>
</div>
<?php if (!$this->data): ?>
  <div class="center aligned"><img src="<?php echo ADMINVIEW; ?>/images/nop.svg" alt="">
    <p class="wojo small demi caps text"><?php echo Lang::$word->TRX_NOTRANS; ?></p>
  </div>
<?php else: ?>
  <div class="right aligned">
    <a href="<?php echo ADMINVIEW . '/helper.php?action=userPaymentsCvs&amp;id=' . $this->row->id; ?>"
      class="wojo small secondary button"><i class="icon wysiwyg table"></i><?php echo Lang::$word->EXPORT; ?></a>
  </div>
  <table class="wojo table segment responsive">
    <thead>
    <tr>
      <th><?php echo Lang::$word->NAME; ?></th>
      <th><?php echo Lang::$word->AMOUNT; ?></th>
      <th><?php echo Lang::$word->VAT; ?></th>
      <th><?php echo Lang::$word->COUPON; ?></th>
      <th><?php echo Lang::$word->TOTALAMT; ?></th>
      <th><?php echo Lang::$word->CREATED; ?></th>
      <th>&nbsp;</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($this->data as $row): ?>
      <tr id="item_<?php echo $row->id; ?>">
        <td><?php echo $row->title; ?></td>
        <td><?php echo Utility::formatMoney($row->amount); ?></td>
        <td><?php echo Utility::formatMoney($row->tax); ?></td>
        <td><?php echo Utility::formatMoney($row->coupon); ?></td>
        <td><?php echo Utility::formatMoney($row->total); ?></td>
        <td><?php echo Date::doDate("short_date", $row->created); ?></td>
        <td class="auto"><a
            href="<?php echo ADMINVIEW; ?>/helper.php?action=invoice&amp;id=<?php echo $row->id; ?>&amp;uid=<?php echo $row->user_id; ?>"
            class="wojo small inverted primary icon button"><i class="icon printer "></i></a></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>
<script type="text/javascript" src="<?php echo SITEURL; ?>/assets/morris.min.js"></script>
<script type="text/javascript" src="<?php echo SITEURL; ?>/assets/raphael.min.js"></script>
<script type="text/javascript">
   // <![CDATA[
   $(document).ready(function () {
      $("#payment_chart").parent().addClass('loading');
      $.ajax({
         type: 'GET',
         url: "<?php echo ADMINVIEW . '/helper.php?action=userPaymentsChart&id=' . $this->row->id;?>&timerange=all",
         dataType: 'json'
      }).done(function (json) {
         let legend = '';
         json.legend.map(function (val) {
            legend += val;
         });
         $("#legend").html(legend);
         Morris.Area({
            element: 'payment_chart',
            data: json.data,
            xkey: 'm',
            ykeys: json.label,
            labels: json.label,
            parseTime: false,
            lineWidth: 1,
            pointSize: 6,
            lineColors: json.color,
            gridTextFamily: "Wojo Sans",
            gridTextColor: "rgba(0,0,0,0.6)",
            gridTextSize: 14,
            fillOpacity: '.75',
            hideHover: 'auto',
            preUnits: json.preUnits,
            behaveLikeLine: true,
            hoverCallback: function (index, json, content) {
               const text = $(content)[1].textContent;
               return content.replace(text, text.replace(json.preUnits, ""));
            },
            smooth: true,
            resize: true,
         });
         $("#payment_chart").parent().removeClass('loading');
      });
   });
   // ]]>
</script>