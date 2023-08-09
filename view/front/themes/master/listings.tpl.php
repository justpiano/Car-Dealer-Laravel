<?php
    /**
     * Listings
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: listings.tpl.php, v1.00 2022-04-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
?>
<div class="wojo bottom padding">
  <div id="listingHeader">
    <div class="wojo-grid">
      <h4><?php echo Lang::$word->HOME_SUB6; ?></h4>
      <p
        class="mb3"><?php echo str_replace("[N]", '<strong>' . $this->pager->items_total . '</strong>', Lang::$word->HOME_SUB6P); ?></p>
      <div class="row gutters align middle">
        <div class="columns phone-100">
          <a data-wdropdown="#dropdown-sortMenu" class="wojo right basic primary button">
              <?php echo Lang::$word->SORTING_O; ?><i class="icon chevron down"></i>
          </a>
          <div class="wojo small dropdown menu top-left" id="dropdown-sortMenu">
            <a href="<?php echo Url::url(Router::$path, "?order=year|DESC"); ?>"
              class="item <?php echo Url::isActive("order", "year|DESC"); ?>">
                <?php echo Lang::$word->_YEAR; ?>: <span
                class="wojo mini text"><?php echo Lang::$word->LOWEST; ?></span>
            </a>
            <a href="<?php echo Url::url(Router::$path, "?order=year|ASC"); ?>"
              class="item <?php echo Url::isActive("order", "year|ASC"); ?>">
                <?php echo Lang::$word->_YEAR; ?>: <span
                class="wojo mini text"><?php echo Lang::$word->HIGHEST; ?></span>
            </a>
            <a href="<?php echo Url::url(Router::$path, "?order=price|DESC"); ?>"
              class="item <?php echo Url::isActive("order", "price|DESC"); ?>">
                <?php echo Lang::$word->PRICE; ?>: <span
                class="wojo mini text"><?php echo Lang::$word->LOWEST; ?></span>
            </a>
            <a href="<?php echo Url::url(Router::$path, "?order=price|ASC"); ?>"
              class="item <?php echo Url::isActive("order", "price|ASC"); ?>">
                <?php echo Lang::$word->PRICE; ?>: <span
                class="wojo mini text"><?php echo Lang::$word->HIGHEST; ?></span>
            </a>
            <a href="<?php echo Url::url(Router::$path, "?order=make|DESC"); ?>"
              class="item <?php echo Url::isActive("order", "make|DESC"); ?>">
                <?php echo Lang::$word->LST_MAKE; ?>: <span
                class="wojo mini text"><?php echo Lang::$word->LOWEST; ?></span>
            </a>
            <a href="<?php echo Url::url(Router::$path, "?order=make|ASC"); ?>"
              class="item <?php echo Url::isActive("order", "make|ASC"); ?>">
                <?php echo Lang::$word->LST_MAKE; ?>: <span
                class="wojo mini text"><?php echo Lang::$word->HIGHEST; ?></span>
            </a>
            <a href="<?php echo Url::url(Router::$path, "?order=model|DESC"); ?>"
              class="item <?php echo Url::isActive("order", "model|DESC"); ?>">
                <?php echo Lang::$word->LST_MODEL; ?>: <span
                class="wojo mini text"><?php echo Lang::$word->LOWEST; ?></span>
            </a>
            <a href="<?php echo Url::url(Router::$path, "?order=model|ASC"); ?>"
              class="item <?php echo Url::isActive("order", "model|ASC"); ?>">
                <?php echo Lang::$word->LST_MODEL; ?>: <span
                class="wojo mini text"><?php echo Lang::$word->HIGHEST; ?></span>
            </a>
            <a href="<?php echo Url::url(Router::$path, "?order=mileage|DESC"); ?>"
              class="item <?php echo Url::isActive("order", "mileage|DESC"); ?>">
                <?php echo $this->core->odometer == "km" ? Lang::$word->KM : Lang::$word->MI; ?>: <span
                class="wojo mini text"><?php echo Lang::$word->LOWEST; ?></span>
            </a>
            <a href="<?php echo Url::url(Router::$path, "?order=mileage|ASC"); ?>"
              class="item <?php echo Url::isActive("order", "mileage|ASC"); ?>">
                <?php echo $this->core->odometer == "km" ? Lang::$word->KM : Lang::$word->MI; ?>: <span
                class="wojo mini text"><?php echo Lang::$word->HIGHEST; ?></span>
            </a>
          </div>
            <?php if (isset($this->parts['query'])): ?>
              <a href="<?php echo Url::url(Router::$path); ?>" class="wojo small simple right button">
                  <?php echo Lang::$word->RESET; ?><i class="icon x alt"></i>
              </a>
            <?php endif; ?>
        </div>
        <div class="columns auto phone-100">
          <a class="wojo white right button doCompare"><span
              class="small margin right <?php echo ($this->compareData) ? null : "hide-all"; ?>">(<?php echo count($this->compareData); ?>)</span> <?php echo Lang::$word->COMPARE; ?>
            <i class="icon collection"></i></a>
          <a <?php if (!Validator::isGetSet("view", "grid")) echo 'href="' . Url::url(Router::$path, Url::buildUrl("view", "grid", false)) . '"'; ?>
            class="wojo white icon button<?php if (Validator::isGetSet("view", "grid")) echo ' simple primary passive'; ?>">
            <i class="icon grid"></i>
          </a>
          <a <?php if (Validator::isGetSet("view", "grid")) echo 'href="' . Url::url(Router::$path, Url::buildUrl("view", "list", false)) . '"'; ?>
            class="wojo white icon button<?php if (!Validator::isGetSet("view", "grid")) echo ' simple primary passive'; ?>">
            <i class="icon view stacked"></i>
          </a>
        </div>
        <div class="columns screen-hide tablet-hide mobile-100 phone-100">
          <a class="wojo fluid white button" data-slide="true" data-trigger="#wojo_form"><i
              class="icon sliders horizontal"></i><?php echo Lang::$word->FILTERS; ?></a>
        </div>
      </div>
    </div>
  </div>
  <div class="wojo-grid">
    <div class="row gutters">
      <div class="columns screen-30 tablet-40 mobile-100 phone-100">
          <?php include THEMEBASE . "/_sidebar_filter.tpl.php"; ?>
      </div>
      <div class="columns screen-70 tablet-60 mobile-100 phone-100">
          <?php if (Validator::isGetSet("view", "grid")): ?>
              <?php include THEMEBASE . "/_grid.tpl.php"; ?>
          <?php else: ?>
              <?php include THEMEBASE . "/_list.tpl.php"; ?>
          <?php endif; ?>
        <div class="row gutters align middle">
          <div class="columns auto mobile-100 phone-100">
            <div class="wojo small semi text">
                <?php echo Lang::$word->TOTAL . ': ' . $this->pager->items_total; ?> / <?php echo Lang::$word->CURPAGE . ': ' . $this->pager->current_page . ' ' . Lang::$word->OF . ' ' . $this->pager->num_pages; ?>
            </div>
          </div>
          <div class="columns right aligned mobile-100 phone-100">
              <?php echo $this->pager->display_pages(); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>