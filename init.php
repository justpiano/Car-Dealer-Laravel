<?php
    /**
     * Init
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: init.php, v1.00 2022-03-05 10:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
    
    $BASEPATH = str_replace("init.php", "", realpath(__FILE__));
    define("BASEPATH", $BASEPATH);
    
    $configFile = BASEPATH . "lib/config.ini.php";
    if (file_exists($configFile)) {
        require_once($configFile);
        if (file_exists(BASEPATH . 'setup/')) {
            print '<div style="position:absolute;width:50%;top:50%;left:50%;transform: translate(-50%, -50%);padding:2rem;color:#fff;font-family:arial,sans-serif;background-color: #ef5350;box-shadow: 0 4px 20px 0 rgba(0, 0, 0, 0.14), 0 7px 10px -5px rgba(244, 67, 54, 0.4);">Please remove <strong>/setup/</strong> directory first!</div>';
            exit;
        }
    } else {
        header("Location: setup/");
        exit;
    }
    
    require_once(BASEPATH . "bootstrap.php");
    Bootstrap::init();
    wError::run();
    Filter::run();
    Lang::Run();
    Debug::run();
    
    const ADMIN = BASEPATH . "admin/";
    const FRONT = BASEPATH . "front/";
    
    $dir = (App::Core()->site_dir) ? '/' . App::Core()->site_dir : '';
    $url = preg_replace("#/+#", "/", $_SERVER['HTTP_HOST'] . $dir);
    $site_url = Url::protocol() . "://" . $url;
    
    define("SITEURL", $site_url);
    const UPLOADURL = SITEURL . '/uploads';
    const UPLOADS = BASEPATH . 'uploads';
    
    const ADMINURL = SITEURL . '/admin';
    const ADMINVIEW = SITEURL . '/view/admin';
    const ADMINBASE = BASEPATH . 'view/admin';
    
    const FRONTVIEW = SITEURL . '/view/front';
    const FRONTBASE = BASEPATH . 'view/front';
    
    define("THEMEURL", FRONTVIEW . '/themes/' . App::Core()->theme);
    define("THEMEBASE", FRONTBASE . '/themes/' . App::Core()->theme);