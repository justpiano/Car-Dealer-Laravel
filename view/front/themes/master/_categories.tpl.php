<?php
    /**
     * Categories
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: _categories.tpl.php, v1.00 2022-04-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
?>
  <!--category start-->
<?php if ($this->category): ?>
  <div id="categoryType">
    <div class="wojo-grid">
      <div class="row grid gutters screen-4 tablet-3 mobile-2 phone-2">
          <?php foreach ($this->category as $category): ?>
            <div class="columns center aligned">
              <a href="<?php echo Url::url("/listings", "?body=" . Url::doSeo($category->category_name)); ?>"
                class="secondary">
                <img
                  src="<?php echo UPLOADURL . '/catico/' . str_replace(" ", "-", strtolower($category->category_name)); ?>.png"
                  class="wojo normal inline image" alt="<?php echo $category->category_name; ?>">
                <div><?php echo $category->category_name; ?>
                  <span class="wojo demi text">(<?php echo $category->total; ?>)</span></div>
              </a>
            </div>
          <?php endforeach; ?>
      </div>
    </div>
  </div>
<?php endif; ?>