<?php
    /**
     * Search
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: search.tpl.php, v1.00 2022-04-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
?>
<div class="wojo bottom padding">
  <div id="listingHeader">
    <div class="wojo-grid">
      <h4><?php echo Lang::$word->SEARCHR; ?></h4>
      <p
        class="mb3 wojo dark color"><?php echo str_replace("[N]", '<strong>' . $this->pager->items_total . '</strong>', Lang::$word->HOME_SUB6P); ?></p>
      <div class="screen-hide tablet-hide padding bottom">
        <a class="wojo fluid white button" data-slide="true" data-trigger="#wojo_form"><i
            class="icon sliders horizontal"></i><?php echo Lang::$word->FILTERS; ?></a>
      </div>
      <div class="wojo form">
        <form method="get" id="wojo_form" name="sform" class="phone-block-hide mobile-block-hide">
          <div class="wojo fields">
            <div class="field">
              <select name="make_id" class="wojo select" data-class="medium" id="make_id">
                <option value="">-- <?php echo Lang::$word->LST_MAKE; ?> --</option>
                  <?php echo Utility::loopOptions(Utility::jSonToArray($this->core->make_list), "id", "name", Validator::notEmptyGet("make_id")); ?>
              </select>
            </div>
            <div class="field">
              <select name="model_id" class="wojo select" data-class="medium" id="model_id">
                <option value="">-- <?php echo Lang::$word->LST_MODEL; ?> --</option>
              </select>
            </div>
            <div class="field">
              <select name="transmission" class="wojo select" data-class="medium">
                <option value="">-- <?php echo Lang::$word->LST_TRANS; ?> --</option>
                  <?php echo Utility::loopOptions(Utility::jSonToArray($this->core->trans_list), "id", "name", Validator::notEmptyGet("transmission")); ?>
              </select>
            </div>
          </div>
          <div class="wojo fields">
            <div class="field">
              <select name="color" class="wojo select" data-class="medium">
                <option value="">-- <?php echo Lang::$word->LST_EXTC; ?> --</option>
                  <?php echo Utility::loopOptions(Utility::jSonToArray($this->core->color), "color_e", "color_e", Validator::notEmptyGet("color")); ?>
              </select>
            </div>
            <div class="field">
              <select name="body" class="wojo select" data-class="medium">
                <option value="">-- <?php echo Lang::$word->LST_CAT; ?> --</option>
                  <?php echo Utility::loopOptions($this->categories, "id", "name", Validator::notEmptyGet("body")); ?>
              </select>
            </div>
            <div class="field">
              <select name="condition" class="wojo select" data-class="medium">
                <option value="">-- <?php echo Lang::$word->LST_COND; ?> --</option>
                  <?php echo Utility::loopOptions(Utility::jSonToArray($this->core->cond_list_alt), "id", "name", Validator::notEmptyGet("condition")); ?>
              </select>
            </div>
          </div>
          <div class="wojo fields">
            <div class="field">
              <select name="price" class="wojo select" data-class="medium">
                <option value="">-- <?php echo Lang::$word->LST_PRICE; ?> --</option>
                <option value="10"<?php echo Validator::getSelected(Validator::get("price"), 10); ?>>&lt; 5000</option>
                <option value="20"<?php echo Validator::getSelected(Validator::get("price"), 20); ?>>5 000 - 10 000
                </option>
                <option value="30"<?php echo Validator::getSelected(Validator::get("price"), 30); ?>>10 000 - 20 000
                </option>
                <option value="40"<?php echo Validator::getSelected(Validator::get("price"), 40); ?>>20 000 - 30 000
                </option>
                <option value="50"<?php echo Validator::getSelected(Validator::get("price"), 50); ?>>30 000 - 50 000
                </option>
                <option value="60"<?php echo Validator::getSelected(Validator::get("price"), 60); ?>>50 000 - 75 000
                </option>
                <option value="70"<?php echo Validator::getSelected(Validator::get("price"), 70); ?>>75 000 - 100 000
                </option>
                <option value="80"<?php echo Validator::getSelected(Validator::get("price"), 80); ?>>100 000 +</option>
              </select>
            </div>
            <div class="basic field">
              <div class="wojo fields">
                <div class="field">
                  <select name="miles" class="wojo select" data-class="medium">
                    <option
                      value="">-- <?php echo $this->core->odometer == "km" ? Lang::$word->KM : Lang::$word->MI; ?> --
                    </option>
                    <option value="10"<?php echo Validator::getSelected(Validator::get("miles"), 10); ?>>&lt; 10000
                    </option>
                    <option
                      value="20"<?php echo Validator::getSelected(Validator::get("miles"), 20); ?>>10 000 - 30 000
                    </option>
                    <option
                      value="30"<?php echo Validator::getSelected(Validator::get("miles"), 30); ?>>30 000 - 60 000
                    </option>
                    <option
                      value="40"<?php echo Validator::getSelected(Validator::get("miles"), 40); ?>>60 000 - 100 000
                    </option>
                    <option
                      value="50"<?php echo Validator::getSelected(Validator::get("miles"), 50); ?>>100 000 - 150 000
                    </option>
                    <option
                      value="60"<?php echo Validator::getSelected(Validator::get("miles"), 60); ?>>150 000 - 200 000
                    </option>
                    <option value="70"<?php echo Validator::getSelected(Validator::get("miles"), 70); ?>>200 000 +
                    </option>
                  </select>
                </div>
                <div class="field">
                  <select name="year" class="wojo select" data-search="true" data-class="medium">
                    <option value="">-- <?php echo Lang::$word->LST_YEAR; ?> --</option>
                      <?php echo Utility::doRange($this->core->minyear, $this->core->maxyear, 1, Validator::notEmptyGet("year")); ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="basic field">
              <div class="wojo fields">
                <div class="field">
                  <select name="fuel" class="wojo select" data-class="medium">
                    <option value="">-- <?php echo Lang::$word->LST_FUEL; ?> --</option>
                      <?php echo Utility::loopOptions(Utility::jSonToArray($this->core->fuel_list), "id", "name", Validator::notEmptyGet("fuel")); ?>
                  </select>
                </div>
                <div class="field">
                  <button type="submit" name="search" class="wojo primary fluid shadow button"><i
                      class="icon search"></i><?php echo Lang::$word->HOME_BTN; ?></button>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="wojo-grid">
    <div class="row gutters align middle">
      <div class="columns phone-100">
        <a data-wdropdown="#dropdown-sortMenu" class="wojo right basic primary button">
            <?php echo Lang::$word->SORTING_O; ?><i class="icon chevron down"></i>
        </a>
        <div class="wojo small dropdown menu top-left" id="dropdown-sortMenu">
          <a href="<?php echo Url::url(Router::$path, "?order=year|DESC"); ?>"
            class="item <?php echo Url::isActive("order", "year|DESC"); ?>">
              <?php echo Lang::$word->_YEAR; ?>: <span class="wojo mini text"><?php echo Lang::$word->LOWEST; ?></span>
          </a>
          <a href="<?php echo Url::url(Router::$path, "?order=year|ASC"); ?>"
            class="item <?php echo Url::isActive("order", "year|ASC"); ?>">
              <?php echo Lang::$word->_YEAR; ?>: <span class="wojo mini text"><?php echo Lang::$word->HIGHEST; ?></span>
          </a>
          <a href="<?php echo Url::url(Router::$path, "?order=price|DESC"); ?>"
            class="item <?php echo Url::isActive("order", "price|DESC"); ?>">
              <?php echo Lang::$word->PRICE; ?>: <span class="wojo mini text"><?php echo Lang::$word->LOWEST; ?></span>
          </a>
          <a href="<?php echo Url::url(Router::$path, "?order=price|ASC"); ?>"
            class="item <?php echo Url::isActive("order", "price|ASC"); ?>">
              <?php echo Lang::$word->PRICE; ?>: <span class="wojo mini text"><?php echo Lang::$word->HIGHEST; ?></span>
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
            class="small margin right <?php echo ($this->compareData) ? null : "hide-all"; ?>">(<?php echo count($this->compareData); ?>)</span>
            <?php echo Lang::$word->COMPARE; ?><i class="icon collection"></i></a>
        <a <?php if (!Validator::isGetSet("view", "grid")) echo 'href="' . Url::url(Router::$path, Url::buildUrl("view", "grid", false)) . '"'; ?>
          class="wojo white icon button<?php if (Validator::isGetSet("view", "grid")) echo ' simple primary passive'; ?>">
          <i class="icon grid"></i>
        </a>
        <a <?php if (Validator::isGetSet("view", "grid")) echo 'href="' . Url::url(Router::$path, Url::buildUrl("view", "list", false)) . '"'; ?>
          class="wojo white icon button<?php if (!Validator::isGetSet("view", "grid")) echo ' simple primary passive'; ?>">
          <i class="icon view stacked"></i>
        </a>
      </div>
    </div>
      <?php if (Validator::isGetSet("view", "grid")): ?>
          <?php include THEMEBASE . "/_grid_search.tpl.php"; ?>
      <?php else: ?>
          <?php if ($this->core->show_news): ?>
          <div class="row gutters">
            <div class="columns screen-80 tablet-100 mobile-100 phone-100" id="context">
                <?php include THEMEBASE . "/_list_search.tpl.php"; ?>
            </div>
            <div class="columns screen-20 tablet-hide mobile-hide phone-hide">
              <div class="wojo native sticky">
                  <?php if ($this->advert): ?>
                      <?php echo Url::out_url($this->advert->body); ?>
                  <?php endif; ?>
              </div>
            </div>
          </div>
          <?php else: ?>
              <?php include THEMEBASE . "/_list_search.tpl.php"; ?>
          <?php endif; ?>
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