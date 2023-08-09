<?php
    /**
     * Brands
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: _brands.tpl.php, v1.00 2022-04-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
?>
  <!--brand start-->
<?php if ($this->makes): ?>
  <div class="wojo primary inverted bg" id="brandType">
    <div class="wojo-grid">
      <div class="row grid gutters screen-4 tablet-4 mobile-2 phone-2">
          <?php foreach ($this->makes as $make): ?>
            <div class="columns center aligned">
              <a href="<?php echo Url::url("/listings", "?make=" . Url::doSeo($make->make_name)); ?>"
                class="wojo white button default inline image">
                <img
                  src="<?php echo UPLOADURL . '/brandico/' . strtolower(str_replace(" ", "-", $make->make_name)) . '.svg'; ?>"
                  alt="<?php echo $make->make_name; ?>">
              </a>
              <a href="<?php echo Url::url("/listings", "?make=" . Url::doSeo($make->make_name)); ?>">
                <div><?php echo $make->make_name; ?>
                  <span class="wojo demi text">(<?php echo $make->total; ?>)</span></div>
              </a>
            </div>
          <?php endforeach; ?>
      </div>
    </div>
  </div>
<?php endif; ?>