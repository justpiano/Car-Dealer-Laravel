<?php
/**
 * Index
 *
 * @package Wojo Framework
 * @author wojoscripts.com
 * @copyright 2022
 * @version $Id: index.tpl.php, v1.00 2022-04-05 10:12:05 gewa Exp $
 */
if (!defined("_WOJO"))
  die('Direct access to this location is not allowed.');
?>
  <?php if($this->core->show_slider):?>
  <!--/* Home Slider Start */-->
  <?php include THEMEBASE . "/_home_slider.tpl.php";?>
  <!--/* Home Slider End */-->
  <?php endif;?>
	
  <!--/* Home Search Start */-->
  <?php include THEMEBASE . "/_home_search.tpl.php";?>
  <!--/* Home Search End */-->

  <?php if($this->core->show_featured):?>
  <!--/* Featured Section Start */-->
  <?php include THEMEBASE . "/_home_featured.tpl.php";?>
  <!--/* Featured Section Ends */-->
  <?php endif;?>
	
  <?php if($this->core->show_popular):?>
  <!--/* Popular Section Start */-->
  <?php include THEMEBASE . "/_home_popular.tpl.php";?>
  <!--/* Popular Section Ends */-->
  <?php endif;?>
	
  <?php if($this->core->show_brands):?>
  <!--/* Brands Section Start */-->
  <?php include THEMEBASE . "/_home_brands.tpl.php";?>
  <!--/* Brands Section Ends */-->
  <?php endif;?>
	
  <?php if($this->core->show_reviews):?>
  <!--/* Review Section Start */-->
  <?php include THEMEBASE . "/_home_reviews.tpl.php";?>
  <!--/* Review Section Start */-->
  <?php endif;?>

  <?php if($this->core->home_content):?>
  <!--/* Home Section Start */-->
  <div class="wojo-grid"><?php echo Url::out_url($this->core->home_content);?></div>
  <!--/* Home Section Start */-->
  <?php endif;?>