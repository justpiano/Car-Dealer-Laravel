<?php
/**
 * Header
 *
 * @package Wojo Framework
 * @author wojoscripts.com
 * @copyright 2022
 * @version $Id: header.tpl.php, v1.00 2022-04-05 10:12:05 gewa Exp $
 */
if (!defined("_WOJO"))
  die('Direct access to this location is not allowed.');
?>
<!doctype html>
<html lang="<?php echo Core::$language;?>">
<head>
<meta charset="utf-8">
<title><?php echo $this->title;?></title>
<meta name="keywords" content="<?php echo $this->keywords;?>">
<meta name="description" content="<?php echo $this->description;?>">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="dcterms.rights" content="<?php echo $this->core->company;?> &copy; All Rights Reserved">
<meta name="robots" content="index">
<meta name="robots" content="follow">
<meta name="revisit-after" content="1 day">
<meta name="generator" content="Powered by DDP v<?php echo $this->core->wojov;?>">
<?php if((Utility::in_array_any(["listing"], $this->segments))):?>
<?php echo $this->meta;?>
<?php endif;?>
<link rel="shortcut icon" href="<?php echo SITEURL;?>/assets/favicon.ico" type="image/x-icon">
<link href="<?php echo THEMEURL . '/cache/' . Cache::cssCache(array('color.css', 'base.css','transition.css','dropdown.css','image.css','label.css','message.css','list.css','table.css','tooltip.css','editor.css','form.css','input.css','icon.css','button.css','card.css','modal.css','progress.css','utility.css','slider.css','style.css'), THEMEBASE);?>" rel="stylesheet" type="text/css">
<script src="<?php echo SITEURL;?>/assets/jquery.js"></script>
<script src="<?php echo SITEURL;?>/assets/global.js"></script>
</head>
<body>
<header id="header">
  <div class="wojo-grid">
    <div class="row align middle small gutters">
      <div class="columns auto">
        <a href="<?php echo SITEURL;?>/" class="logo"><?php echo ($this->core->logo) ? '<img src="' . SITEURL . '/uploads/' . $this->core->logo . '" alt="'.$this->core->company . '">': $this->core->company;?></a>
      </div>
      <div class="columns screen-hide tablet-hide right aligned">
        <a class="wojo icon white button menu-mobile"><i class="icon list"></i></a>
      </div>
      <div class="columns mobile-100 phone-100">
        <nav class="menu">
          <ul class="top-menu">
            <li class="nav-item">
              <a href="<?php echo Url::url("/listings");?>" class="<?php echo (in_array("listings", $this->segments) ? "active" : "normal")?>"><i class="icon car alt"></i><?php echo Lang::$word->BROWSE;?></a>
            </li>
            <?php if($this->menus):?>
            <?php foreach($this->menus as $row):?>
            <li class="nav-item">
              <a href="<?php echo Url::url("/page", $row->slug . '/');?>" class="<?php echo (in_array($row->slug, $this->segments) ? "active" : "normal")?>"><?php echo $row->name;?></a>
            </li>
            <?php endforeach;?>
            <?php endif;?>
            <li class="nav-item">
              <a href="<?php echo Url::url("/search");?>" class="<?php echo (in_array("search", $this->segments) ? "active" : "normal")?>"><i class="icon search"></i><?php echo Lang::$word->SEARCH;?></a>
            </li>
          </ul>
        </nav>
      </div>
      <div class="columns auto phone-100 mobile-100 center aligned">
        <?php if(App::Auth()->is_User()):?>
        <a href="<?php echo Url::url("/dashboard");?>" class="wojo icon text inverted">
        <i class="icon person inverted"></i><?php echo Lang::$word->HI;?>
        <?php echo App::Auth()->name;?>! </a>
        <?php else:?>
        <a href="<?php echo Url::url("/login");?>" class="wojo small fluid transparent button"><i class="icon person"></i><?php echo Lang::$word->SIGNIN;?></a>
        <?php endif;?>
      </div>
    </div>
  </div>
</header>
<main>
<?php if (Utility::in_array_any(["listings", "search", "listing", "compare", "seller"], $this->segments)):?>
<div class="wojo-grid">
  <div class="wojo small breadcrumb">
    <?php echo Url::crumbs(($this->crumbs ?? $this->segments), "//", Lang::$word->HOME); ?>
  </div>
</div>
<?php endif;?>