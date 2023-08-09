<?php
    /**
     * Index
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: index.tpl.php, v1.00 2022-05-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
?>
  <div class="row gutters align middle">
    <div class="columns phone-100">
      <h2><?php echo Lang::$word->DASH_TITLE; ?></h2>
      <p class="wojo small text"><?php echo Lang::$word->DASH_INFO; ?></p>
    </div>
  </div>
  <div class="row gutters">
    <div class="columns screen-20 tablet-20 mobile-50 phone-100">
      <a class="wojo basic attached segment" href="<?php echo Url::url("/admin/members"); ?>">
        <div class="center aligned"><span class="wojo positive massive text"><?php echo $this->counters[0]; ?></span>
        </div>
        <div class="center aligned wojo positive text"><?php echo Lang::$word->DASH_RU; ?></div>
      </a>
    </div>
    <div class="columns screen-20 tablet-20 mobile-50 phone-100">
      <a class="wojo basic attached segment" href="<?php echo Url::url("/admin/items"); ?>">
        <div class="center aligned"><span class="wojo positive massive text"><?php echo $this->counters[1]; ?></span>
        </div>
        <div class="center aligned wojo positive text"><?php echo Lang::$word->DASH_TL; ?></div>
      </a>
    </div>
    <div class="columns screen-20 tablet-20 mobile-50 phone-100">
      <a class="wojo basic attached segment" href="<?php echo Url::url("/admin/items/expired"); ?>">
        <div class="center aligned"><span class="wojo negative massive text"><?php echo $this->counters[2]; ?></span>
        </div>
        <div class="center aligned wojo negative text"><?php echo Lang::$word->DASH_EL; ?></div>
      </a>
    </div>
    <div class="columns screen-20 tablet-20 mobile-50 phone-100">
      <a class="wojo basic attached segment" href="<?php echo Url::url("/admin/items/pending"); ?>">
        <div class="center aligned"><span class="wojo alert massive text"><?php echo $this->counters[3]; ?></span></div>
        <div class="center aligned wojo alert text"><?php echo Lang::$word->DASH_PL; ?></div>
      </a>
    </div>
    <div class="columns screen-20 tablet-20 mobile-50 phone-100">
      <a class="wojo basic attached segment" href="<?php echo Url::url("/admin/members/active"); ?>">
        <div class="center aligned"><span class="wojo primary massive text"><?php echo $this->counters[4]; ?></span>
        </div>
        <div class="center aligned wojo primary text"><?php echo Lang::$word->DASH_AM; ?></div>
      </a>
    </div>
  </div>
<?php if (Auth::checkAcl("owner")): ?>
  <h4><?php echo Lang::$word->DASH_TYEAR; ?></h4>
  <div class="row gutters align bottom">
    <div class="columns screen-80 tablet-70 mobile-100 phone-100">
      <div class="right aligned">
        <div id="legend" class="wojo small horizontal list"></div>
      </div>
      <div id="payment_chart" class="wojo basic segment h350"></div>
    </div>
    <div class="columns screen-20 tablet-30 mobile-100 phone-100">
      <div class="wojo basic segment">
        <div class="small padding">
          <p class="wojo semi text"><?php echo Utility::formatMoney($this->stats['totalsum']); ?></p>
          <div id="chart1" data-values="<?php echo $this->stats['amount_str']; ?>"></div>
        </div>
      </div>
      <div class="wojo basic segment">
        <div class="small padding">
          <p class="wojo semi text"><?php echo $this->stats['totalsales']; ?>
              <?php echo Lang::$word->TRX_SALES; ?></p>
          <div id="chart2" data-values="<?php echo $this->stats['sales_str']; ?>"></div>
        </div>
      </div>
    </div>
  </div>
  <h4><?php echo Lang::$word->TRX_POPMEM; ?></h4>
  <div class="right aligned">
    <div id="legend2" class="wojo small horizontal list"></div>
  </div>
  <div id="payment_chart2" class="wojo basic segment h350"></div>
  <h4><?php echo Lang::$word->DASH_LEXP; ?></h4>
  <div class="wojo fitted segment">
      <?php echo App::Date()->Calendar($this->listings, true); ?>
  </div>
<?php endif; ?>
<?php if (Auth::checkAcl("owner")): ?>
  <script type="text/javascript" src="<?php echo SITEURL; ?>/assets/morris.min.js"></script>
  <script type="text/javascript" src="<?php echo SITEURL; ?>/assets/raphael.min.js"></script>
  <script type="text/javascript" src="<?php echo SITEURL; ?>/assets/sparkline.min.js"></script>
  <script src="<?php echo ADMINVIEW; ?>/js/index.js"></script>
  <script type="text/javascript">
     // <![CDATA[
     $(document).ready(function () {
        $.Index({
           url: "<?php echo ADMINVIEW;?>",
        });
     });
     // ]]>
  </script>
<?php endif; ?>