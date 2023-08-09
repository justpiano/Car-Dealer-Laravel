<?php
    /**
     * Stats
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: _items_stats.tpl.php, v1.00 2022-03-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
?>
<div class="row gutters align middle">
  <div class="columns phone-100">
    <h2><?php echo Lang::$word->LST_TITLE4; ?>
      <small class="wojo medium text">// <?php echo $this->row->nice_title; ?></small></h2>
    <p class="wojo small text"><?php echo Lang::$word->LST_INFO4; ?></p>
  </div>
  <div class="columns auto phone-100">
    <a class="wojo small black button" id="resetStats"><?php echo Lang::$word->LST_DELSTATS; ?></a>
  </div>
</div>
<div class="wojo card" id="lData">
  <div class="content h400" id="stats_chart"></div>
</div>
<script type="text/javascript" src="<?php echo ADMINVIEW; ?>/js/listing_stats.js"></script>
<script type="text/javascript" src="<?php echo SITEURL; ?>/assets/morris.min.js"></script>
<script type="text/javascript" src="<?php echo SITEURL; ?>/assets/raphael.min.js"></script>
<script type="text/javascript">
   // <![CDATA[
   $(document).ready(function () {
      $.Stats({
         url: "<?php echo ADMINVIEW;?>"
      });
   });
   // ]]>
</script>