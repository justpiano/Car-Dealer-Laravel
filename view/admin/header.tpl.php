<?php
    /**
     * Header
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: header.tpl.php, v1.00 2022-02-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
    
    if (!App::Auth()->is_Admin()) {
        Url::redirect(SITEURL . '/admin/login/');
        exit;
    }
?>
<!DOCTYPE html>
<head>
  <meta charset="utf-8">
  <title><?php echo $this->title; ?></title>
  <link
    href="<?php echo ADMINVIEW . '/cache/' . Cache::cssCache(array('base.css', 'transition.css', 'label.css', 'form.css', 'input.css', 'dropdown.css', 'button.css', 'message.css', 'image.css', 'list.css', 'table.css', 'icon.css', 'card.css', 'modal.css', 'editor.css', 'tooltip.css', 'menu.css', 'progress.css', 'utility.css', 'style.css'), ADMINBASE); ?>"
    rel="stylesheet" type="text/css"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <script type="text/javascript" src="<?php echo SITEURL; ?>/assets/jquery.js"></script>
  <script type="text/javascript" src="<?php echo SITEURL; ?>/assets/global.js"></script>
</head>
<body data-theme="<?php echo Session::cookieExists("CDPA_THEME", "dark") ? "dark" : "light"; ?>">
<header>
  <div class="wojo-grid">
    <div class="row horizontal small gutters align middle">
      <div class="columns">
        <a href="<?php echo Url::url("/admin"); ?>" class="logo">
            <?php echo (App::Core()->logo) ? '<img src="' . SITEURL . '/uploads/' . $this->core->logo . '" alt="' . $this->core->company . '">' : $this->core->company; ?>
        </a>
      </div>
      <div class="columns auto">
        <a data-wdropdown="#dropdown-uMenu" class="wojo right icon white text"
          id="uName"><span><?php echo App::Auth()->name; ?></span>
          <div
            class="wojo primary inverted initials big label"><?php echo Utility::getInitials(App::Auth()->name); ?></div>
        </a>
        <div class="wojo small dropdown top-right" id="dropdown-uMenu">
          <div class="wojo small circular center image">
            <img
              src="<?php echo UPLOADURL; ?>/avatars/<?php echo (App::Auth()->avatar) ? App::Auth()->avatar : "blank.svg"; ?>"
              alt="">
          </div>
          <h5 class="wojo small dimmed text center aligned"><?php echo App::Auth()->name; ?></h5>
          <a class="item" href="<?php echo Url::url("/admin/myaccount"); ?>"><i class="icon person"></i>
              <?php echo Lang::$word->ADM_MYACC; ?></a>
          <a class="item" href="<?php echo Url::url("/admin/mypassword"); ?>"><i class="icon key fill"></i>
              <?php echo Lang::$word->ADM_PASSCHANGE; ?></a>
          <a class="atheme-switch item"
            data-mode="<?php echo Session::cookieExists("CDPA_THEME", "dark") ? "dark" : "light"; ?>"><i
              class="icon circle half"></i><span><?php echo Session::cookieExists("CDPA_THEME", "dark") ? "Light" : "Dark"; ?></span></a>
          <div class="divider"></div>
          <a class="item" href="<?php echo Url::url("/admin/logout"); ?>"><i class="icon power"></i>
              <?php echo Lang::$word->LOGOUT; ?></a>
        </div>
      </div>
        <?php if (Auth::checkAcl("owner")): ?>
          <div class="columns auto">
            <a data-wdropdown="#dropdown-aMenu" class="wojo icon simple transparent button">
              <i class="icon gears"></i>
            </a>
            <div class="wojo small dropdown menu top-right" id="dropdown-aMenu">
              <a class="item" href="<?php echo Url::url("/admin/system"); ?>">
                <i class="icon laptop"></i><span><?php echo Lang::$word->ADM_SYS; ?></span></a>
              <a class="item" href="<?php echo Url::url("/admin/backup"); ?>">
                <i class="icon server"></i><span><?php echo Lang::$word->ADM_BCK; ?></span></a>
              <a class="item" href="<?php echo Url::url("/admin/utilities"); ?>">
                <i class="icon sliders vertical alt"></i><span><?php echo Lang::$word->ADM_UTL; ?></span></a>
              <div class="wojo basic divider"></div>
              <a class="item wojo bold text" href="<?php echo Url::url("/admin/trash"); ?>"><i class="icon trash"></i>
                <span><?php echo Lang::$word->TRASH; ?></span></a>
            </div>
          </div>
        <?php endif; ?>
      <div class="columns auto hide-all" id="mobileToggle">
        <a class="wojo transparent icon button menu-mobile"><i class="icon white reorder"></i></a>
      </div>
    </div>
  </div>
</header>
<div class="navbar">
  <div class="wojo-grid">
    <nav class="menu">
      <ul>
        <li
          class="has-sub <?php if (Utility::in_array_any(["roles", "members", "staff"], $this->segments)) echo 'active'; ?>">
          <a href="#"><img src="<?php echo ADMINVIEW; ?>/images/menu_users.svg"
              alt=""><span><?php echo Lang::$word->ADM_ACC; ?></span>
            <i class="icon chevron down"></i></a>
          <ul>
            <li>
              <a<?php if (in_array("members", $this->segments)) echo ' class="active"'; ?>
                href="<?php echo Url::Url("/admin/members"); ?>"><?php echo Lang::$word->ADM_MEMBER; ?></a>
            </li>
            <li>
              <a href="<?php echo Url::Url("/admin/staff"); ?>"><?php echo Lang::$word->ADM_STAF; ?></a>
            </li>
            <li>
              <a<?php if (in_array("roles", $this->segments)) echo ' class="active"'; ?>
                href="<?php echo Url::Url("/admin/roles"); ?>"><?php echo Lang::$word->ADM_ROLES; ?></a>
            </li>
          </ul>
        </li>
        <li
          class="has-sub <?php if (Utility::in_array_any(["items", "pending", "categories", "makes", "models", "features", "conditions", "fuel", "transmissions"], $this->segments)) echo 'active'; ?>">
          <a href="#"><img src="<?php echo ADMINVIEW; ?>/images/menu_inventory.svg"
              alt=""><span><?php echo Lang::$word->ADM_ITEMS; ?></span>
            <i class="icon chevron down"></i></a>
          <ul>
            <li>
              <a href="<?php echo Url::Url("/admin/items"); ?>"><?php echo Lang::$word->ADM_INV; ?></a>
            </li>
            <li>
              <a href="<?php echo Url::Url("/admin/items/pending"); ?>"><?php echo Lang::$word->ADM_PENDING; ?></a>
            </li>
            <li>
              <a href="<?php echo Url::Url("/admin/categories"); ?>"><?php echo Lang::$word->ADM_CATS; ?></a>
            </li>
            <li>
              <a href="<?php echo Url::Url("/admin/makes"); ?>"><?php echo Lang::$word->ADM_MAKES; ?></a>
            </li>
            <li>
              <a href="<?php echo Url::Url("/admin/models"); ?>"><?php echo Lang::$word->ADM_MODELS; ?></a>
            </li>
            <li>
              <a href="<?php echo Url::Url("/admin/features"); ?>"><?php echo Lang::$word->ADM_FEATURE; ?></a>
            </li>
            <li>
              <a href="<?php echo Url::Url("/admin/conditions"); ?>"><?php echo Lang::$word->ADM_COND; ?></a>
            </li>
            <li>
              <a href="<?php echo Url::Url("/admin/fuel"); ?>"><?php echo Lang::$word->ADM_FUEL; ?></a>
            </li>
            <li>
              <a href="<?php echo Url::Url("/admin/transmissions"); ?>"><?php echo Lang::$word->ADM_TRANS; ?></a>
            </li>
          </ul>
        </li>
        <li
          class="has-sub <?php if (Utility::in_array_any(["pages", "menus", "coupons", "faq", "slider", "reviews", "etemplates", "mailer", "lmanager", "advert"], $this->segments)) echo 'active'; ?>">
          <a href="#"><img src="<?php echo ADMINVIEW; ?>/images/menu_content.svg"
              alt=""><span><?php echo Lang::$word->ADM_CON; ?></span><i class="icon chevron down"></i></a>
          <ul>
            <li>
              <a href="<?php echo Url::Url("/admin/pages"); ?>"><?php echo Lang::$word->ADM_PAGES; ?></a>
            </li>
            <li>
              <a href="<?php echo Url::Url("/admin/menus"); ?>"><?php echo Lang::$word->ADM_MENUS; ?></a>
            </li>
            <li>
              <a href="<?php echo Url::Url("/admin/coupons"); ?>"><?php echo Lang::$word->ADM_COUPONS; ?></a>
            </li>
            <li>
              <a href="<?php echo Url::Url("/admin/faq"); ?>"><?php echo Lang::$word->ADM_FAQ; ?></a>
            </li>
            <li>
              <a href="<?php echo Url::Url("/admin/slider"); ?>"><?php echo Lang::$word->ADM_SLIDER; ?></a>
            </li>
            <li>
              <a href="<?php echo Url::Url("/admin/reviews"); ?>"><?php echo Lang::$word->ADM_REVIEW; ?></a>
            </li>
            <li>
              <a href="<?php echo Url::Url("/admin/advert"); ?>"><?php echo Lang::$word->ADM_NEWS; ?></a>
            </li>
            <li>
              <a href="<?php echo Url::Url("/admin/etemplates"); ?>"><?php echo Lang::$word->ADM_ETEMPLATES; ?></a>
            </li>
            <li>
              <a href="<?php echo Url::Url("/admin/mailer"); ?>"><?php echo Lang::$word->ADM_NLETTER; ?></a>
            </li>
            <li>
              <a href="<?php echo Url::Url("/admin/language"); ?>"><?php echo Lang::$word->ADM_LANG; ?></a>
            </li>
          </ul>
        </li>
        <li>
          <a href="<?php echo Url::Url("/admin/transactions"); ?>"><img
              src="<?php echo ADMINVIEW; ?>/images/menu_payments.svg"
              alt=""><span><?php echo Lang::$word->ADM_PAYS; ?></span></a>
        </li>
        <li
          class="has-sub <?php if (Utility::in_array_any(["configuration", "countries", "gateways", "locations", "packages", "bans"], $this->segments)) echo 'active'; ?>">
          <a href="#"><img src="<?php echo ADMINVIEW; ?>/images/menu_configuration.svg"
              alt=""><span><?php echo Lang::$word->ADM_CONF; ?></span><i class="icon chevron down"></i></a>
          <ul>
            <li>
              <a href="<?php echo Url::Url("/admin/configuration"); ?>"><?php echo Lang::$word->ADM_SYSCONF; ?></a>
            </li>
            <li>
              <a href="<?php echo Url::Url("/admin/countries"); ?>"><?php echo Lang::$word->ADM_COUNTRIES; ?></a>
            </li>
            <li>
              <a href="<?php echo Url::Url("/admin/gateways"); ?>"><?php echo Lang::$word->ADM_GATEWAY; ?></a>
            </li>
            <li>
              <a href="<?php echo Url::Url("/admin/locations"); ?>"><?php echo Lang::$word->ADM_SHOWROOM; ?></a>
            </li>
            <li>
              <a href="<?php echo Url::Url("/admin/packages"); ?>"><?php echo Lang::$word->ADM_PACKAGES; ?></a>
            </li>
          </ul>
        </li>
      </ul>
    </nav>
  </div>
</div>
<div class="colorbar">
    <?php for ($i = 1; $i <= 7; $i++): ?>
      <div></div>
    <?php endfor; ?>
</div>
<main>
  <div class="wojo-grid">
    <div class="wojo small breadcrumb">
        <?php echo Url::crumbs(($this->crumbs ?? $this->segments), "//", Lang::$word->HOME); ?>
    </div>
