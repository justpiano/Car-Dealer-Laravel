<?php
    /**
     * Home Brands
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: _home_brands.tpl.php, v1.00 2022-04-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
?>
<?php if ($this->brands): ?>
  <div id="mainBrands">
    <div class="wojo-grid">
      <div class="center aligned mb3">
        <h4><?php echo Lang::$word->HOME_SUB2; ?></h4>
        <p><?php echo Lang::$word->HOME_SUB2P; ?></p>
      </div>
      <div class="row grid gutters screen-8 tablet-6 mobile-4 phone-2">
          <?php foreach ($this->brands as $brow): ?>
            <div class="columns center aligned">
              <a href="<?php echo Url::url("/listings", "?make=" . Url::doSeo($brow->name)); ?>"
                class="wojo notification color image">
                <img
                  src="<?php echo UPLOADURL . '/brandico/' . strtolower(str_replace(" ", "-", $brow->name)) . '.svg'; ?>"
                  alt="<?php echo $brow->name; ?>"></a>
            </div>
          <?php endforeach; ?>
      </div>
    </div>
  </div>
<?php endif; ?>