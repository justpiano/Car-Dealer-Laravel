<?php
    /**
     * Index
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: index.php, v1.00 2022-06-05 10:12:05 gewa Exp $
     */
    const _WOJO = true;
    
    include 'init.php';
    $router = new Router();
    $core = App::Core();
    $tpl = App::View(BASEPATH.'view/');
    
    //admin routes
    $router->mount('/admin', function () use ($router, $tpl) {
        //admin login
        $router->match('GET|POST', '/login', function () use ($tpl) {
            if (App::Auth()->is_Admin()) {
                Url::redirect(SITEURL.'/admin/');
                exit;
            }
            
            $tpl->template = 'admin/login.tpl.php';
            $tpl->title = Lang::$word->LOGIN;
        });
        
        //admin index
        $router->get('/', 'Admin@Index');
        
        //admin members
        $router->mount('/members', function () use ($router, $tpl) {
            $router->match('GET|POST', '/', 'Users@Index');
            $router->match('GET|POST', '/grid', 'Users@Index');
            $router->get('/edit/(\d+)', 'Users@Edit');
            $router->get('/new', 'Users@Save');
            $router->get('/payments/(\d+)', 'Users@Payments');
            $router->get('/listings/(\d+)', 'Users@Listings');
            $router->get('/activity/(\d+)', 'Users@Activity');
            $router->get('/active', 'Users@Active');
        });
        
        //admin staff
        $router->mount('/staff', function () use ($router, $tpl) {
            $router->match('GET|POST', '/', 'Users@StaffIndex');
            $router->get('/edit/(\d+)', 'Users@StaffEdit');
            $router->get('/new', 'Users@StaffSave');
        });
        
        //admin account
        $router->get('/myaccount', 'Admin@Account');
        $router->get('/mypassword', 'Admin@Password');
        
        //inventory
        $router->mount('/items', function () use ($router, $tpl) {
            $router->match('GET|POST', '/', 'Items@Index');
            $router->get('/edit/(\d+)', 'Items@Edit');
            $router->get('/new', 'Items@Save');
            $router->get('/images/(\d+)', 'Items@Gallery');
            $router->get('/stats/(\d+)', 'Items@Stats');
            $router->get('/print/(\d+)', 'Items@Print');
            $router->get('/pending', 'Items@Pending');
            $router->get('/expired', 'Items@Expired');
        });
        
        $router->get('/transmissions', 'Content@Transmissions');
        $router->get('/fuel', 'Content@Fuel');
        $router->get('/conditions', 'Content@Conditions');
        $router->get('/features', 'Content@Features');
        $router->get('/makes', 'Content@Makes');
        $router->get('/models', 'Content@Models');
        
        $router->mount('/categories', function () use ($router, $tpl) {
            $router->match('GET|POST', '/', 'Content@Categories');
            $router->get('/edit/(\d+)', 'Content@CategoryEdit');
            $router->get('/new', 'Content@CategorySave');
        });
        
        //pages
        $router->mount('/pages', function () use ($router, $tpl) {
            $router->match('GET|POST', '/', 'Content@Pages');
            $router->get('/edit/(\d+)', 'Content@PageEdit');
            $router->get('/new', 'Content@PageSave');
        });
        
        //menus
        $router->mount('/menus', function () use ($router, $tpl) {
            $router->match('GET|POST', '/', 'Content@Menus');
            $router->get('/edit/(\d+)', 'Content@MenuEdit');
        });
        
        //coupons
        $router->mount('/coupons', function () use ($router, $tpl) {
            $router->match('GET|POST', '/', 'Content@Coupons');
            $router->get('/edit/(\d+)', 'Content@CouponEdit');
            $router->get('/new', 'Content@CouponSave');
        });
        
        //faq
        $router->mount('/faq', function () use ($router, $tpl) {
            $router->match('GET|POST', '/', 'Content@Faq');
            $router->get('/edit/(\d+)', 'Content@FaqEdit');
            $router->get('/new', 'Content@FaqSave');
        });
        
        //slider
        $router->mount('/slider', function () use ($router, $tpl) {
            $router->match('GET|POST', '/', 'Content@Slider');
            $router->get('/edit/(\d+)', 'Content@SliderEdit');
            $router->get('/new', 'Content@SliderSave');
        });
        
        //reviews
        $router->mount('/reviews', function () use ($router, $tpl) {
            $router->match('GET|POST', '/', 'Content@Reviews');
        });
        
        //advert
        $router->mount('/advert', function () use ($router, $tpl) {
            $router->match('GET|POST', '/', 'Content@Advert');
            $router->get('/edit/(\d+)', 'Content@AdvertEdit');
            $router->get('/new', 'Content@AdvertSave');
        });
        
        //email templates
        $router->mount('/etemplates', function () use ($router, $tpl) {
            $router->match('GET|POST', '/', 'Content@Etemplates');
            $router->get('/edit/(\d+)', 'Content@EtemplateEdit');
        });
        
        //admin countries
        $router->mount('/countries', function () use ($router, $tpl) {
            $router->get('/', 'Content@Countries');
            $router->get('/edit/(\d+)', 'Content@CountryEdit');
        });
        
        //admin gateways
        $router->mount('/gateways', function () use ($router, $tpl) {
            $router->get('/', 'Admin@Gateways');
            $router->get('/edit/(\d+)', 'Admin@GatewayEdit');
        });
        
        //packages
        $router->mount('/packages', function () use ($router, $tpl) {
            $router->match('GET|POST', '/', 'Content@Packages');
            $router->get('/history/(\d+)', 'Content@PackageHistory');
            $router->get('/edit/(\d+)', 'Content@PackageEdit');
            $router->get('/new', 'Content@PackageSave');
        });
        
        //locations
        $router->mount('/locations', function () use ($router, $tpl) {
            $router->match('GET|POST', '/', 'Content@Locations');
            $router->get('/edit/(\d+)', 'Content@LocationEdit');
            $router->get('/new', 'Content@LocationSave');
        });
        
        //mailer
        $router->get('/mailer', 'Content@Mailer');
        
        //language manager
        $router->get('/language', 'Lang@Index');
        
        //admin transactions
        $router->match('GET|POST', '/transactions', 'Admin@Transactions');
        
        //admin system
        $router->get('/system', 'Admin@System');
        
        //admin backup
        $router->get('/backup', 'Admin@Backup');
        
        //admin configuration
        $router->get('/configuration', 'Core@Index');
        
        //Utilities manager
        $router->get('/utilities', 'Admin@Utilities');
        
        //admin permissions
        $router->mount('/roles', function () use ($router, $tpl) {
            $router->get('/', 'Admin@Permissions');
            $router->get('/privileges/(\d+)', 'Admin@Privileges');
        });
        
        //admin trash
        $router->get('/trash', 'Admin@Trash');
        
        //logout
        $router->get('/logout', function () {
            App::Auth()->logout();
            Url::redirect(SITEURL.'/admin/');
        });
        
    });
    
    //front end routes
    
    //home
    $router->match('GET|POST', '/', 'Front@Index');
    $router->match('GET|POST', '/login', 'Front@Login');
    $router->match('GET|POST', '/register', 'Front@Register');
    $router->match('GET|POST', '/password/([a-z0-9_-]+)', 'Front@Password');
    
    //front page
    $router->match('GET|POST', '/page/([a-z0-9_-]+)', 'Front@Page');
    
    //sitemap
    $router->get('/sitemap', 'Front@Sitemap');
    
    //compare
    $router->get('/compare', 'Front@Compare');
    
    //packages
    $router->get('/packages', 'Front@Packages');
    
    //listings
    $router->match('GET|POST', '/listings', 'Items@Listings');
    
    //search
    $router->match('GET|POST', '/search', 'Items@Search');
    
    //seller listings
    $router->get('/seller/([a-z0-9_-]+)', 'Items@Seller');
    
    //listing
    $router->get('/listing/(\d+)/([a-z0-9_-]+)', 'Items@Render');
    
    //dashboard
    $router->mount('/dashboard', function () use ($router, $tpl) {
        $router->get('/', 'Front@Dashboard');
        $router->get('/history', 'Front@History');
        $router->get('/profile', 'Front@Profile');
        $router->get('/reviews', 'Front@Reviews');
        $router->match('GET|POST', '/mylistings', 'Front@MyListings');
        $router->match('GET|POST', '/new', 'Front@NewListing');
    });
    
    //account activation
    $router->get('/activation', 'Front@Activation');
    
    //payment activation
    $router->match('GET|POST', '/validate', 'Front@Validate');
    
    //Custom Routes add here
    
    $router->get('/logout', function () {
        App::Auth()->logout();
        Url::redirect(SITEURL.'/');
    });
    
    //404
    $router->set404(function () use ($core, $router) {
        $tpl = App::View(BASEPATH.'view/');
        $tpl->dir = $router->segments[0] == "admin" ? "admin/" : "front/themes/".$core->theme."/";
        $tpl->segments = $router->segments;
        $tpl->data = null;
        $tpl->title = Lang::$word->META_ERROR;
        $tpl->keywords = null;
        $tpl->description = null;
        $tpl->core = $core;
        $tpl->crumbs = [array(0 => Lang::$word->HOME, 1 => ''), Lang::$word->META_ERROR2];
        if ($router->segments[0] != "admin") {
            $tpl->menus = Content::getMenus();
            $tpl->compareData = (App::Session()->get('CDP_compare')) ? App::Session()->get('CDP_compare') : array();
        }
        $tpl->template = $router->segments[0] == "admin" ? 'admin/404.tpl.php' : "front/themes/".$core->theme."/404.tpl.php";
        echo $tpl->render();
    });
    
    // Maintenance mode
    if ($core->offline == 1 && !App::Auth()->is_Admin() && !str_contains($_SERVER['REQUEST_URI'], "admin/")) {
        url::redirect(SITEURL."/maintenance.php");
        exit;
    }
    
    // Run router
    $router->run(function () use ($tpl, $core, $router) {
        $tpl->segments = $router->segments;
        $tpl->core = $core;
        if ($router->segments[0] != "admin") {
            $tpl->menus = Content::getMenus();
            $tpl->compareData = (App::Session()->get('CDP_compare')) ? App::Session()->get('CDP_compare') : array();
        }
        Content::$segments = $router->segments;
        echo $tpl->render();
    });
 