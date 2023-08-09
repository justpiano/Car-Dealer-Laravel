<?php
    /**
     * Content Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: cache.class.php, v1.00 2022-04-20 18:20:24 gewa Exp $
     */
    
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
    
    class Content
    {
        const cTable = "countries";
        const blTable = "banlist";
        const pgTable = "pages";
        const muTable = "menus";
        const faqTable = "faq";
        const ctTable = "categories";
        const cdTable = "conditions";
        const fTable = "features";
        const fuTable = "fuel";
        const trTable = "transmissions";
        const mkTable = "makes";
        const mdTable = "models";
        const lcTable = "locations";
        const slTable = "slider";
        const dcTable = "coupons";
        const rwTable = "reviews";
        const nwaTable = "advert";
        const gwTable = "gateways";
        const msTable = "memberships";
        const mhTable = "membership_history";
        const nwTable = "newsletter";
        const txTable = "payments";
        const inTable = "invoices";
        const xTable = "cart";
        
        public static array $segments = array();
        
        
        /**
         * Content::__construct()
         *
         */
        public function __construct()
        {
        
        }
        
        /**
         * Content::Locations()
         *
         * @return void
         */
        public function Locations()
        {
            
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->crumbs = ['admin', Lang::$word->LOC_TITLE];
            $tpl->data = Db::Go()->select(self::lcTable)->where("ltype", "owner", "=")->orderBy("name", "ASC")->run();
            $tpl->title = Lang::$word->LOC_TITLE;
            $tpl->template = 'admin/locations.tpl.php';
        }
        
        /**
         * Content::LocationEdit()
         *
         * @param $id
         * @return void
         */
        public function LocationEdit($id)
        {
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->title = Lang::$word->LOC_SUB1;
            $tpl->crumbs = ['admin', "locations", "edit"];
            
            if (!$row = Db::Go()->select(self::lcTable)->where("id", $id, "=")->first()->run()) {
                $tpl->template = 'admin/error.tpl.php';
                $tpl->error = DEBUG ? "Invalid ID ($id) detected [Content.class.php, ln.:" . __line__ . "]" : Lang::$word->META_ERROR;
            } else {
                $tpl->row = $row;
                $tpl->template = 'admin/locations.tpl.php';
            }
        }
        
        /**
         * Content::LocationSave()
         *
         * @return void
         */
        public function LocationSave()
        {
            
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->crumbs = ['admin', "locations", "new"];
            $tpl->template = 'admin/locations.tpl.php';
            $tpl->title = Lang::$word->MSM_SUB2;
        }
        
        /**
         * Membership::processLocation()
         *
         * @param bool $front
         * @return void
         */
        public function processLocation($front = false)
        {
            $validate = Validator::Run($_POST);
            $validate
                ->set("name", Lang::$word->NAME)->required()->string()
                ->set("email", Lang::$word->EMAIL)->required()->string()
                ->set("address", Lang::$word->ADDRESS)->required()->string()
                ->set("city", Lang::$word->CITY)->required()->string()
                ->set("state", Lang::$word->STATE)->required()->string()
                ->set("country", Lang::$word->COUNTRY)->required()->string()
                ->set("zip", Lang::$word->ZIP)->required()->string()
                ->set("phone", Lang::$word->CF_PHONE)->string()
                ->set("fax", Lang::$word->CF_FAX)->string()
                ->set("url", Lang::$word->CF_WEBURL)->url()
                ->set("lat", "LAT")->float()
                ->set("lng", "LONG")->float()
                ->set("zoom", "ZOOM")->numeric();
            
            $logo = File::upload("logo", 3145728, "png,jpg,jpeg");
            $safe = $validate->safe();
            
            if (empty(Message::$msgs)) {
                $data = array(
                    'name' => $safe->name,
                    'name_slug' => Url::doSeo(Utility::randNumbers(4) . '-' . $safe->name),
                    'ltype' => $front ? "user" : "owner",
                    'user_id' => $front ? App::Auth()->uid : 0,
                    'email' => $safe->email,
                    'address' => $safe->address,
                    'city' => $safe->city,
                    'state' => $safe->state,
                    'zip' => $safe->zip,
                    'country' => $safe->country,
                    'phone' => $safe->phone,
                    'fax' => $safe->fax,
                    'url' => $safe->url,
                    'lat' => $safe->lat,
                    'lng' => $safe->lng,
                    'zoom' => $safe->zoom,
                );
                
                if (!empty($_FILES['logo']['name'])) {
                    $logodir = UPLOADS . "/showrooms/";
                    if (Filter::$id && $row = Db::Go()->select(self::lcTable, array("logo"))->where("id", Filter::$id, "=")->first()->run()) {
                        File::deleteFile($logodir . $row->logo);
                    }
                    $result = File::process($logo, $logodir, "logo_");
                    $data['logo'] = $result['fname'];
                }
                
                $last_id = 0;
                (Filter::$id) ? Db::Go()->update(self::lcTable, $data)->where("id", Filter::$id, "=")->run() : $last_id = Db::Go()->insert(self::lcTable, $data)->run();
                
                $message = Filter::$id ? Message::formatSuccessMessage($data['name'], Lang::$word->LOC_UPDATED) : Message::formatSuccessMessage($data['name'], Lang::$word->LOC_ADDED);
                
                if ($front) {
                    $json['type'] = "success";
                    $json['title'] = Lang::$word->SUCCESS;
                    $json['message'] = $message;
                    $json['html'] = '<option value="' . $last_id . '" selected="selected">' . $data['name'] . '</option>';
                    print json_encode($json);
                } else {
                    Message::msgReply(Db::Go()->affected(), 'success', $message);
                }
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * Content::Packages()
         *
         * @return void
         */
        public function Packages()
        {
            
            $sql = "
              SELECT m.*,
                (SELECT
                  COUNT(p.membership_id)
                FROM
                  `" . Content::txTable . "` as p
                WHERE p.membership_id = m.id) AS total
              FROM
                `" . Content::msTable . "` as m";
            
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->crumbs = ['admin', Lang::$word->MSM_TITLE];
            $tpl->template = 'admin/packages.tpl.php';
            $tpl->data = Db::Go()->rawQuery($sql)->run();
            $tpl->title = Lang::$word->MSM_TITLE;
        }
        
        /**
         * Content::PackageEdit()
         *
         * @param $id
         * @return void
         */
        public function PackageEdit($id)
        {
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->title = Lang::$word->MSM_SUB1;
            $tpl->crumbs = ['admin', "packages", "edit"];
            
            if (!$row = Db::Go()->select(self::msTable)->where("id", $id, "=")->first()->run()) {
                $tpl->template = 'admin/error.tpl.php';
                $tpl->error = DEBUG ? "Invalid ID ($id) detected [Content.class.php, ln.:" . __line__ . "]" : Lang::$word->META_ERROR;
            } else {
                $tpl->row = $row;
                $tpl->template = 'admin/packages.tpl.php';
            }
        }
        
        /**
         * Content::PackageHistory()
         *
         * @param $id
         * @return void
         */
        public function PackageHistory($id)
        {
            
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->title = Lang::$word->MSM_SUB3;
            $tpl->crumbs = ['admin', "packages", "history"];
            
            if (!$row = Db::Go()->select(self::msTable)->where("id", $id, "=")->first()->run()) {
                $tpl->template = 'admin/error.tpl.php';
                $tpl->error = DEBUG ? "Invalid ID ($id) detected [content.class.php, ln.:" . __line__ . "]" : Lang::$word->META_ERROR;
            } else {
                
                $pager = Paginator::instance();
                $pager->items_total = Db::Go()->count(Content::txTable)->where("membership_id", $id, "=")->where("status", "1", "=")->run();
                $pager->default_ipp = App::Core()->perpage;
                $pager->path = Url::url(Router::$path, "?");
                $pager->paginate();
                
                
                $sql = "
                  SELECT
                    p.amount,
                    p.tax,
                    p.coupon,
                    p.total,
                    p.currency,
                    p.created,
                    p.user_id,
                    CONCAT(u.fname,' ',u.lname) as name
                  FROM
                    `" . Content::txTable . "` AS p
                    LEFT JOIN " . Users::mTable . " AS u
                      ON u.id = p.user_id
                  WHERE p.membership_id = ?
                  AND p.status = ?
                  ORDER BY p.created DESC" . $pager->limit;
                
                $tpl->data = $row;
                $tpl->plist = Db::Go()->rawQuery($sql, array($id, 1))->run();
                $tpl->pager = $pager;
                $tpl->template = 'admin/packages.tpl.php';
            }
        }
        
        /**
         * Content::PackageSave()
         *
         * @return void
         */
        public function PackageSave()
        {
            
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->crumbs = ['admin', "packages", "save"];
            $tpl->template = 'admin/packages.tpl.php';
            $tpl->title = Lang::$word->MSM_SUB2;
        }
        
        /**
         * Membership::processPackage()
         *
         * @return void
         */
        public function processPackage()
        {
            $validate = Validator::Run($_POST);
            $validate
                ->set("title", Lang::$word->MSM_NAME)->required()->string()->min_len(3)->max_len(60)
                ->set("price", Lang::$word->MSM_PRICE)->required()->float()
                ->set("days", Lang::$word->MSM_PERIOD)->required()->numeric()
                ->set("period", Lang::$word->MSM_PERIOD)->required()->alpha()->exact_len(1)
                ->set("featured", Lang::$word->MSM_FEATURED)->required()->numeric()
                ->set("listings", Lang::$word->MSM_LISTS)->required()->numeric()
                ->set("private", Lang::$word->MSM_PRIVATE)->required()->numeric()
                ->set("active", Lang::$word->PUBLISHED)->required()->numeric()
                ->set("description", Lang::$word->DESC)->string();
            
            $thumb = File::upload("thumb", 3145728, "png,jpg,jpeg");
            $safe = $validate->safe();
            
            if (empty(Message::$msgs)) {
                $data = array(
                    'title' => $safe->title,
                    'description' => $safe->description,
                    'price' => $safe->price,
                    'days' => $safe->days,
                    'period' => $safe->period,
                    'featured' => $safe->featured,
                    'listings' => $safe->listings,
                    'private' => $safe->private,
                    'active' => $safe->active,
                );
                
                if (!empty($_FILES['thumb']['name'])) {
                    $thumbpath = UPLOADS . "/memberships/";
                    $result = File::process($thumb, $thumbpath, "MEM_");
                    $data['thumb'] = $result['fname'];
                }
                
                (Filter::$id) ? Db::Go()->update(self::msTable, $data)->where("id", Filter::$id, "=")->run() : Db::Go()->insert(self::msTable, $data)->run();
                $message = Filter::$id ? Message::formatSuccessMessage($data['title'], Lang::$word->MSM_UPDATED) : Message::formatSuccessMessage($data['title'], Lang::$word->MSM_ADDED);
                
                Message::msgReply(Db::Go()->affected(), 'success', $message);
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * Content::Countries()
         *
         * @return void
         */
        public function Countries()
        {
            
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->crumbs = ['admin', Lang::$word->CNT_TITLE];
            $tpl->template = 'admin/countries.tpl.php';
            $tpl->data = $this->getCountryList();
            $tpl->title = Lang::$word->CNT_TITLE;
        }
        
        /**
         * Content::CountryEdit()
         *
         * @param $id
         * @return void
         */
        public function CountryEdit($id)
        {
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->title = Lang::$word->CNT_SUB1;
            $tpl->crumbs = ['admin', "countries", "edit"];
            
            if (!$row = Db::Go()->select(self::cTable)->where("id", $id, "=")->first()->run()) {
                $tpl->template = 'admin/error.tpl.php';
                $tpl->error = DEBUG ? "Invalid ID ($id) detected [Content.class.php, ln.:" . __line__ . "]" : Lang::$word->META_ERROR;
            } else {
                $tpl->row = $row;
                $tpl->template = 'admin/countries.tpl.php';
            }
        }
        
        /**
         * Content::processCountry()
         *
         * @return void
         */
        public function processCountry()
        {
            $validate = Validator::Run($_POST);
            $validate
                ->set("name", Lang::$word->NAME)->required()->string()->min_len(3)->max_len(60)
                ->set("abbr", Lang::$word->CNT_ABBR)->required()->string()->exact_len(2)
                ->set("active", Lang::$word->ACTIVE)->required()->numeric()
                ->set("home", Lang::$word->DEFAULT)->required()->numeric()
                ->set("sorting", Lang::$word->SORTING)->required()->numeric()
                ->set("vat", Lang::$word->TRX_TAX)->required()->numeric()->min_numeric(0)->max_numeric(50);
            
            $safe = $validate->safe();
            
            if (empty(Message::$msgs)) {
                $data = array(
                    'name' => $safe->name,
                    'abbr' => $safe->abbr,
                    'sorting' => $safe->sorting,
                    'home' => $safe->home,
                    'active' => $safe->active,
                    'vat' => $safe->vat,
                );
                
                if ($data['home'] == 1) {
                    Db::Go()->rawQuery("UPDATE `" . self::cTable . "` SET `home`= DEFAULT(home)")->run();
                }
                
                Db::Go()->update(self::cTable, $data)->where("id", Filter::$id, "=")->run();
                Message::msgReply(Db::Go()->affected(), 'success', Message::formatSuccessMessage($data['name'], Lang::$word->CNT_UPDATED));
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * Content::getCountryList()
         * @return array|false|int|string
         */
        public function getCountryList()
        {
            $row = Db::Go()->select(self::cTable)->orderBy("sorting", "DESC")->run();
            
            return ($row) ?: 0;
            
        }
        
        /**
         * Content::Menus()
         *
         * @return void
         */
        public function Menus()
        {
            
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->crumbs = ['admin', "menus"];
            $tpl->template = 'admin/menus.tpl.php';
            $tpl->contenttype = self::getContentType();
            $tpl->tree = Db::Go()->select(self::muTable)->orderBy("sorting", "ASC")->run();
            $tpl->sortlist = $this->getSortMenuList($tpl->tree);
            $tpl->title = Lang::$word->MENU_TITLE;
        }
        
        /**
         * Content::MenuEdit()
         *
         * @param $id
         * @return void
         */
        public function MenuEdit($id)
        {
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->title = Lang::$word->MENU_SUB1;
            $tpl->crumbs = ['admin', "menus", "edit"];
            
            if (!$row = Db::Go()->select(self::muTable)->where("id", $id, "=")->first()->run()) {
                $tpl->template = 'admin/error.tpl.php';
                $tpl->error = DEBUG ? "Invalid ID ($id) detected [Content.class.php, ln.:" . __line__ . "]" : Lang::$word->META_ERROR;
            } else {
                $tpl->row = $row;
                $tpl->contenttype = self::getContentType();
                $tpl->tree = Db::Go()->select(self::muTable)->orderBy("sorting", "ASC")->run();
                $tpl->sortlist = $this->getSortMenuList($tpl->tree);
                $tpl->pagelist = Db::Go()->select(self::pgTable, array("id", "title"))->orderBy("title", "ASC")->run();
                $tpl->template = 'admin/menus.tpl.php';
            }
        }
        
        /**
         * Content::processMenu()
         *
         * @return void
         */
        public function processMenu()
        {
            $validate = Validator::Run($_POST);
            $validate
                ->set("name", Lang::$word->MENU_NAME)->required()->string()->min_len(3)->max_len(60)
                ->set("content_type", Lang::$word->MENU_TYPE)->required()->string()->min_len(3)->max_len(8)
                ->set("active", Lang::$word->PUBLISHED)->required()->numeric();
            
            $safe = $validate->safe();
            
            if ($_POST['content_type'] == "page" and empty($_POST['page_id'])) {
                Message::$msgs['page_id'] = Lang::$word->MENU_CPAGE;
            }
            
            if (empty(Message::$msgs)) {
                $data = array(
                    'name' => $safe->name,
                    'content_type' => $safe->content_type,
                    'link' => (isset($_POST['web'])) ? Validator::sanitize($_POST['web']) : "NULL",
                    'target' => (isset($_POST['target'])) ? Validator::sanitize($_POST['target'], "db") : "NULL",
                    'page_id' => (isset($_POST['page_id'])) ? Validator::sanitize($_POST['page_id'], "int") : 0,
                    'active' => $safe->active,
                );
                
                (Filter::$id) ? Db::Go()->update(self::muTable, $data)->where("id", Filter::$id, "=")->run() : Db::Go()->insert(self::muTable, $data)->run();
                if (Filter::$id) {
                    $message = Message::formatSuccessMessage($data['name'], Lang::$word->MENU_UPDATED);
                    Message::msgReply(Db::Go()->affected(), 'success', $message);
                } else {
                    if (Db::Go()->getLastInsertId()) {
                        $message = Message::formatSuccessMessage($data['name'], Lang::$word->MENU_ADDED);
                        $json['type'] = "success";
                        $json['title'] = Lang::$word->SUCCESS;
                        $json['message'] = $message;
                        $json['redirect'] = Url::url("/admin/menus");
                    } else {
                        $json['type'] = "alert";
                        $json['title'] = Lang::$word->ALERT;
                        $json['message'] = Lang::$word->NOPROCCESS;
                    }
                    print json_encode($json);
                }
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * Content::Pages()
         *
         * @return void
         */
        public function Pages()
        {
            
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->crumbs = ['admin', Lang::$word->PAG_TITLE];
            $tpl->template = 'admin/pages.tpl.php';
            $tpl->data = Db::Go()->select(self::pgTable)->run();
            $tpl->title = Lang::$word->PAG_TITLE;
        }
        
        /**
         * Users::PageEdit()
         *
         * @param int $id
         * @return void
         */
        public function PageEdit($id)
        {
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->title = Lang::$word->PAG_SUB1;
            $tpl->crumbs = ['admin', "pages", "edit"];
            
            if (!$row = Db::Go()->select(self::pgTable)->where("id", $id, "=")->first()->run()) {
                $tpl->template = 'admin/error.tpl.php';
                $tpl->error = DEBUG ? "Invalid ID ($id) detected [users.class.php, ln.:" . __line__ . "]" : Lang::$word->META_ERROR;
            } else {
                $tpl->row = $row;
                $tpl->template = 'admin/pages.tpl.php';
            }
        }
        
        /**
         * Content::PageSave()
         *
         * @return void
         */
        public function PageSave()
        {
            
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->crumbs = ['admin', "pages", "new"];
            $tpl->template = 'admin/pages.tpl.php';
            $tpl->title = Lang::$word->PAG_SUB2;
        }
        
        /**
         * Content::processPage()
         *
         * @return void
         */
        public function processPage()
        {
            $validate = Validator::Run($_POST);
            $validate
                ->set("title", Lang::$word->PAG_NAME)->required()->string()->min_len(3)->max_len(100)
                ->set("slug", Lang::$word->PAG_SLUG)->string()
                ->set("keywords", Lang::$word->METAKEYS)->string()
                ->set("description", Lang::$word->METADESC)->string()
                ->set("body", Lang::$word->DESC)->text("advanced")
                ->set("active", Lang::$word->PUBLISHED)->required()->numeric();
            
            $safe = $validate->safe();
            
            if (empty(Message::$msgs)) {
                $data = array(
                    'title' => $safe->title,
                    'slug' => empty($safe->slug) ? Url::doSeo($safe->title) : Url::doSeo($safe->slug),
                    'body' => Url::in_url($safe->body),
                    'keywords' => $safe->keywords,
                    'description' => $safe->description,
                    'active' => $safe->active,
                );
                
                if (Filter::$id) {
                    Db::Go()->update(Core::sTable, array("home_content" => $data['body']))->where("id", 1, "=")->run();
                    if (Filter::$id == 1) {
                        Db::Go()->update(self::pgTable, $data)->where("id", Filter::$id, "=")->run();
                    }
                } else {
                    Db::Go()->insert(self::pgTable, $data);
                }
                
                $message = Filter::$id ? Message::formatSuccessMessage($data['title'], Lang::$word->PAG_UPDATED) : Message::formatSuccessMessage($data['title'], Lang::$word->PAG_ADDED);
                Message::msgReply(Db::Go()->affected(), 'success', $message);
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * Content::Coupons()
         *
         * @return void
         */
        public function Coupons()
        {
            
            $sql = "
              SELECT *,
                CASE
                  WHEN type = 'a'
                  THEN '" . Lang::$word->DC_TYPE_A . "'
                  ELSE '" . Lang::$word->DC_TYPE_P . "'
                END type
              FROM `" . self::dcTable . "`
              ORDER BY created DESC";
            
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->crumbs = ['admin', Lang::$word->DC_TITLE];
            $tpl->template = 'admin/coupons.tpl.php';
            $tpl->data = Db::Go()->rawQuery($sql)->run();
            $tpl->title = Lang::$word->DC_TITLE;
        }
        
        /**
         * Content::CouponEdit()
         *
         * @param $id
         * @return void
         */
        public function CouponEdit($id)
        {
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->title = Lang::$word->DC_SUB1;
            $tpl->crumbs = ['admin', "coupons", "edit"];
            
            if (!$row = Db::Go()->select(self::dcTable)->where("id", $id, "=")->first()->run()) {
                $tpl->template = 'admin/error.tpl.php';
                $tpl->error = DEBUG ? "Invalid ID ($id) detected [Content.class.php, ln.:" . __line__ . "]" : Lang::$word->META_ERROR;
            } else {
                $tpl->row = $row;
                $tpl->mlist = Db::Go()->select(self::msTable)->where("private", 1, "<")->orderBy("price", "ASC")->run();
                $tpl->template = 'admin/coupons.tpl.php';
            }
        }
        
        /**
         * Content::CouponSave()
         *
         * @return void
         */
        public function CouponSave()
        {
            
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->crumbs = ['admin', "coupons", "new"];
            $tpl->mlist = Db::Go()->select(self::msTable)->where("private", 1, "<")->orderBy("price", "ASC")->run();
            $tpl->template = 'admin/coupons.tpl.php';
            $tpl->title = Lang::$word->DC_SUB2;
        }
        
        /**
         * Content::processCoupon()
         *
         * @return void
         */
        public function processCoupon()
        {
            $validate = Validator::Run($_POST);
            $validate
                ->set("title", Lang::$word->DC_NAME)->required()->string()->min_len(3)->max_len(100)
                ->set("code", Lang::$word->DC_CODE)->required()->string()->min_len(3)->max_len(20)
                ->set("type", Lang::$word->PUBLISHED)->string()
                ->set("active", Lang::$word->PUBLISHED)->required()->numeric();
            
            if ($_POST['type'] == "p") {
                $validate->set("discount", Lang::$word->DC_DISC)->required()->numeric()->min_numeric(1)->max_numeric(99);
            } else {
                $validate->set("discount", Lang::$word->DC_DISC)->required()->numeric();
            }
            
            $safe = $validate->safe();
            
            if (empty(Message::$msgs)) {
                $data = array(
                    'title' => $safe->title,
                    'code' => $safe->code,
                    'discount' => $safe->discount,
                    'type' => $safe->type,
                    'membership_id' => Validator::post('membership_id') ? Utility::implodeFields($_POST['membership_id']) : 0,
                    'active' => $safe->active,
                );
                
                (Filter::$id) ? Db::Go()->update(self::dcTable, $data)->where("id", Filter::$id, "=")->run() : Db::Go()->insert(self::dcTable, $data)->run();
                
                $message = Filter::$id ? Message::formatSuccessMessage($data['title'], Lang::$word->DC_UPDATED) : Message::formatSuccessMessage($data['title'], Lang::$word->DC_ADDED);
                Message::msgReply(Db::Go()->affected(), 'success', $message);
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * Content::Faq()
         *
         * @return void
         */
        public function Faq()
        {
            
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->crumbs = ['admin', Lang::$word->FAQ_TITLE];
            $tpl->template = 'admin/faq.tpl.php';
            $tpl->data = Db::Go()->select(self::faqTable)->orderBy("sorting", "ASC")->run();
            $tpl->title = Lang::$word->FAQ_TITLE;
        }
        
        /**
         * Content::FaqEdit()
         *
         * @param $id
         * @return void
         */
        public function FaqEdit($id)
        {
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->title = Lang::$word->FAQ_SUB1;
            $tpl->crumbs = ['admin', "faq", "edit"];
            
            if (!$row = Db::Go()->select(self::faqTable)->where("id", $id, "=")->first()->run()) {
                $tpl->template = 'admin/error.tpl.php';
                $tpl->error = DEBUG ? "Invalid ID ($id) detected [Content.class.php, ln.:" . __line__ . "]" : Lang::$word->META_ERROR;
            } else {
                $tpl->row = $row;
                $tpl->template = 'admin/faq.tpl.php';
            }
        }
        
        /**
         * Content::FaqSave()
         *
         * @return void
         */
        public function FaqSave()
        {
            
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->crumbs = ['admin', "faq", "new"];
            $tpl->template = 'admin/faq.tpl.php';
            $tpl->title = Lang::$word->PAG_SUB2;
        }
        
        /**
         * Content::processFaq()
         *
         * @return void
         */
        public function processFaq()
        {
            $validate = Validator::Run($_POST);
            $validate
                ->set("question", Lang::$word->FAQ_NAME)->required()->string()->min_len(5)->max_len(100)
                ->set("answer", Lang::$word->FAQ_BODY)->text("advanced");
            
            $safe = $validate->safe();
            
            if (empty(Message::$msgs)) {
                $data = array(
                    'question' => $safe->question,
                    'answer' => Url::in_url($safe->answer),
                );
                
                (Filter::$id) ? Db::Go()->update(self::faqTable, $data)->where("id", Filter::$id, "=")->run() : Db::Go()->insert(self::faqTable, $data)->run();
                
                $message = Filter::$id ? Message::formatSuccessMessage($data['question'], Lang::$word->FAQ_UPDATED) : Message::formatSuccessMessage($data['question'], Lang::$word->FAQ_ADDED);
                Message::msgReply(Db::Go()->affected(), 'success', $message);
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * Content::Slider()
         *
         * @return void
         */
        public function Slider()
        {
            
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->crumbs = ['admin', Lang::$word->SLD_SUB];
            $tpl->template = 'admin/slider.tpl.php';
            $tpl->data = Db::Go()->select(self::slTable)->orderBy("sorting", "ASC")->run();
            $tpl->title = Lang::$word->SLD_SUB;
        }
        
        /**
         * Content::SliderEdit()
         *
         * @param $id
         * @return void
         */
        public function SliderEdit($id)
        {
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->title = Lang::$word->SLD_SUB1;
            $tpl->crumbs = ['admin', "slider", "edit"];
            
            if (!$row = Db::Go()->select(self::slTable)->where("id", $id, "=")->first()->run()) {
                $tpl->template = 'admin/error.tpl.php';
                $tpl->error = DEBUG ? "Invalid ID ($id) detected [Content.class.php, ln.:" . __line__ . "]" : Lang::$word->META_ERROR;
            } else {
                $tpl->row = $row;
                $tpl->template = 'admin/slider.tpl.php';
            }
        }
        
        /**
         * Content::SliderSave()
         *
         * @return void
         */
        public function SliderSave()
        {
            
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->crumbs = ['admin', "slider", "new"];
            $tpl->template = 'admin/slider.tpl.php';
            $tpl->title = Lang::$word->SLD_SUB2;
        }
        
        /**
         * Content::processSlide()
         *
         * @return void
         */
        public function processSlide()
        {
            $validate = Validator::Run($_POST);
            $validate
                ->set("caption", Lang::$word->SLD_NAME)->required()->string()->min_len(5)->max_len(100)
                ->set("url", Lang::$word->SLD_URL)->required()->url()
                ->set("body", Lang::$word->FAQ_BODY)->text("advanced");
            
            $safe = $validate->safe();
            
            if (!Filter::$id and empty($_FILES['thumb']['name'])) {
                Message::$msgs['thumb'] = Lang::$word->SLD_IMAGE;
            }
            
            $thumb = File::upload("thumb", 4194304, "png,jpg,jpeg");
            
            if (empty(Message::$msgs)) {
                $data = array(
                    'caption' => $safe->caption,
                    'url' => $safe->url,
                    'body' => Url::in_url($safe->body),
                );
                
                /* == Process Image == */
                if (!empty($_FILES['thumb']['name'])) {
                    $thumbDir = UPLOADS . "/slider/";
                    if (Filter::$id && $row = Db::Go()->select(self::slTable, array("thumb"))->where("id", Filter::$id, "=")->first()->run()) {
                        File::deleteFile($thumbDir . $row->thumb);
                    }
                    $result = File::process($thumb, $thumbDir, "SLIDE_");
                    $data['thumb'] = $result['fname'];
                }
                
                (Filter::$id) ? Db::Go()->update(self::slTable, $data)->where("id", Filter::$id, "=")->run() : Db::Go()->insert(self::slTable, $data)->run();
                
                $message = Filter::$id ? Message::formatSuccessMessage($data['caption'], Lang::$word->SLD_UPDATED) : Message::formatSuccessMessage($data['caption'], Lang::$word->SLD_ADDED);
                Message::msgReply(Db::Go()->affected(), 'success', $message);
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * Content::Reviews()
         *
         * @return void
         */
        public function Reviews()
        {
            
            $sql = "
              SELECT r.*,
                CONCAT(m.fname,' ',m.lname) as name,
                m.avatar,
                m.id AS uid
              FROM
                `" . self::rwTable . "` AS r
                LEFT JOIN `" . Users::mTable . "` AS m
                  ON m.id = r.user_id
              ORDER BY r.created DESC";
            
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->crumbs = ['admin', Lang::$word->SRW_TITLE];
            $tpl->template = 'admin/reviews.tpl.php';
            $tpl->data = Db::Go()->rawQuery($sql)->run();
            $tpl->title = Lang::$word->SRW_TITLE;
        }
        
        /**
         * Content::editReview()
         *
         * @return void
         */
        public static function editReview()
        {
            $validate = Validator::Run($_POST);
            
            $validate
                ->set("content", Lang::$word->DESCRIPTION)->required()->string()->min_len(3)->max_len(300)
                ->set("twitter", Lang::$word->CF_TWID)->required()->string()
                ->set("status", Lang::$word->PUBLISHED)->required()->numeric();
            
            $safe = $validate->safe();
            
            if (empty(Message::$msgs)) {
                $data = array(
                    'content' => $safe->content,
                    'twitter' => $safe->twitter,
                    'status' => $safe->status);
                
                Db::Go()->update(self::rwTable, $data)->where("id", Filter::$id, "=")->run();
                Message::msgModalReply(Db::Go()->affected(), 'success', Lang::$word->SRW_UPDATED, $data['content']);
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * Content::getReviews()
         *
         * @return array|false|int|string
         */
        public function getReviews()
        {
            
            $sql = "
              SELECT r.*,
                CONCAT(m.fname,' ',m.lname) as name,
                m.avatar,
                m.id AS uid
              FROM
                `" . self::rwTable . "` AS r
                LEFT JOIN `" . Users::mTable . "` AS m
                  ON m.id = r.user_id
              WHERE r.status = ?
              ORDER BY r.created DESC";
            
            $row = Db::Go()->rawQuery($sql, array(1))->run();
            
            return ($row) ?: 0;
        }
        
        /**
         * Content::addReview()
         *
         * @return void
         * @throws \PHPMailer\PHPMailer\Exception
         */
        public function addReview()
        {
            $validate = Validator::Run($_POST);
            $validate
                ->set("content", Lang::$word->SRW_DESC)->required()->string()->min_len(5)->max_len(300)
                ->set("twitter", "Twitter")->string();
            
            $safe = $validate->safe();
            
            if (empty(Message::$msgs)) {
                $data = array(
                    'content' => $safe->content,
                    'twitter' => $safe->twitter,
                    'user_id' => App::Auth()->uid
                );
                
                $last_id = Db::Go()->insert(self::rwTable, $data)->run();
                
                if (Db::Go()->getLastInsertId()) {
                    $json['type'] = "success";
                    $json['title'] = Lang::$word->SUCCESS;
                    
                    $json['message'] = Lang::$word->SRW_ADDDED;
                    $json['redirect'] = Url::url("/dashboard");
                } else {
                    $json['type'] = "alert";
                    $json['title'] = Lang::$word->ALERT;
                    $json['message'] = Lang::$word->NOPROCCESS;
                }
                
                print json_encode($json);
                
                if ($last_id) {
                    $core = App::Core();
                    $html_message = Utility::getSnippets(BASEPATH . 'mailer/' . $core->lang . '/Admin_Notify_Review.tpl.php');
                    $mailer = Mailer::sendMail();
                    
                    $body = str_replace(array(
                        '[LOGO]',
                        '[NAME]',
                        '[DATE]',
                        '[COMPANY]',
                        '[USERNAME]',
                        '[CONTENT]',
                        '[IP]',
                        '[FB]',
                        '[TW]',
                        '[CEMAIL]',
                        '[SITEURL]'), array(
                        Utility::getLogo(),
                        $data['fname'] . ' ' . $data['lname'],
                        date('Y'),
                        $core->company,
                        App::Auth()->email,
                        $safe->content,
                        Url::getIP(),
                        $core->social->facebook,
                        $core->social->twitter,
                        $core->site_email,
                        SITEURL), $html_message);
                    
                    $mailer->setFrom($core->site_email, $core->company);
                    $mailer->addAddress($core->site_email, $core->company);
                    
                    $mailer->isHTML();
                    $mailer->Subject = Lang::$word->SRW_SUBJECT . ' ' . App::get('Auth')->name;
                    $mailer->Body = $body;
                    
                    $mailer->send();
                }
            } else {
                Message::msgSingleStatus();
            }
            
        }
        
        /**
         * Content::Advert()
         *
         * @return void
         */
        public function Advert()
        {
            
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->crumbs = ['admin', Lang::$word->NWA_TITLE];
            $tpl->template = 'admin/advert.tpl.php';
            $tpl->data = Db::Go()->select(self::nwaTable)->orderBy("created", "ASC")->run();
            $tpl->title = Lang::$word->NWA_TITLE;
        }
        
        /**
         * Content::AdvertEdit()
         *
         * @param $id
         * @return void
         */
        public function AdvertEdit($id)
        {
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->title = Lang::$word->NWA_SUB1;
            $tpl->crumbs = ['admin', "advert", "edit"];
            
            if (!$row = Db::Go()->select(self::nwaTable)->where("id", $id, "=")->first()->run()) {
                $tpl->template = 'admin/error.tpl.php';
                $tpl->error = DEBUG ? "Invalid ID ($id) detected [Content.class.php, ln.:" . __line__ . "]" : Lang::$word->META_ERROR;
            } else {
                $tpl->row = $row;
                $tpl->template = 'admin/advert.tpl.php';
            }
        }
        
        /**
         * Content::AdvertSave()
         *
         * @return void
         */
        public function AdvertSave()
        {
            
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->crumbs = ['admin', "advert", "new"];
            $tpl->template = 'admin/advert.tpl.php';
            $tpl->title = Lang::$word->NWA_SUB2;
        }
        
        /**
         * Content::processAdvert()
         *
         * @return void
         */
        public function processAdvert()
        {
            $validate = Validator::Run($_POST);
            $validate
                ->set("title", Lang::$word->NWA_NAME)->required()->string()->min_len(3)->max_len(100)
                ->set("body", Lang::$word->DESC)->text("advanced")
                ->set("active", Lang::$word->PUBLISHED)->string();
            
            $safe = $validate->safe();
            
            if (empty(Message::$msgs)) {
                $data = array(
                    'title' => $safe->title,
                    'body' => Url::in_url($safe->body),
                    'active' => $safe->active,
                );
                
                (Filter::$id) ? Db::Go()->update(self::nwaTable, $data)->where("id", Filter::$id, "=")->run() : Db::Go()->insert(self::nwaTable, $data)->run();
                
                $message = Filter::$id ? Message::formatSuccessMessage($data['title'], Lang::$word->NWA_UPDATED) : Message::formatSuccessMessage($data['title'], Lang::$word->NWA_ADDED);
                Message::msgReply(Db::Go()->affected(), 'success', $message);
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * Content::Etemplates()
         *
         * @return void
         */
        public function Etemplates()
        {
            
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->crumbs = ['admin', Lang::$word->ET_TITLE];
            $tpl->data = File::getMailerTemplates();
            $tpl->template = 'admin/etemplates.tpl.php';
            $tpl->title = Lang::$word->ET_TITLE;
        }
        
        /**
         * Content::processEtemplate()
         *
         * @return void
         */
        public function processEtemplate()
        {
            $validate = Validator::Run($_POST);
            $validate
                ->set("filename", Lang::$word->NWA_NAME)->required()->string()
                ->set("body", Lang::$word->DESC)->text("advanced");
            
            $safe = $validate->safe();
            
            if (empty(Message::$msgs)) {
                $file = Validator::sanitize($safe->filename);
                $path = BASEPATH . "mailer/" . Core::$language . "/" . $file;
                
                if (is_file($path)) {
                    if (isset($_POST['backup'])) {
                        if ($data = file_get_contents($path)) {
                            file_put_contents($path . '.bak', $data);
                        }
                    }
                }
                
                if (!file_put_contents($path, Url::in_url($safe->body))) {
                    $message = Message::formatSuccessMessage($safe->filename, Lang::$word->ERROR);
                    Message::msgReply(false, 'error', $message);
                } else {
                    $message = Message::formatSuccessMessage($safe->filename, Lang::$word->ET_UPDATED);
                    Message::msgReply(true, 'success', $message);
                }
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * Content::Mailer()
         *
         * @return void
         */
        public function Mailer()
        {
            
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->crumbs = ['admin', Lang::$word->EMN_SUB];
            $tpl->template = 'admin/mailer.tpl.php';
            $tpl->title = Lang::$word->EMN_SUB;
        }
        
        /**
         * Content::processEmail()
         *
         * @return void
         * @throws \PHPMailer\PHPMailer\Exception
         */
        public function processEmail()
        {
            $validate = Validator::Run($_POST);
            $validate
                ->set("subject", Lang::$word->EMN_REC_SUBJECT)->required()->string()
                ->set("recipient", Lang::$word->EMN_REC_SEL)->required()->string();
            
            $safe = $validate->safe();
            
            if (empty(Message::$msgs)) {
                $to = $safe->recipient;
                $subject = $safe->subject;
                $body = $safe->body;
                $numSent = 0;
                $userRow = null;
                $row = null;
                $failedRecipients = array();
                $core = App::Core();
                
                $mailer = Mailer::sendMail();
                $mailer->Subject = $subject;
                $mailer->setFrom($core->site_email, $core->company);
                $mailer->isHTML();
                
                switch ($to) {
                    case "members":
                        $userRow = Db::Go()->select(Users::mTable, array('email', 'CONCAT(fname," ",lname) as name'))->where("active", "y", "=")->run();
                        break;
                    
                    case "staff":
                        $userRow = Db::Go()->select(Users::aTable, array('email', 'CONCAT(fname," ",lname) as name'))->where("userlevel", 9, "<>")->run();
                        break;
                    
                    case "sellers":
                        $userRow = Db::Go()->select(Users::mTable, array('email', 'CONCAT(fname," ",lname) as name'))->where("listings", 1, ">=")->run();
                        break;
                    
                    case "newsletter":
                        $userRow = Db::Go()->select(self::nwTable, array('email', 'name'))->run();
                        break;
                    
                    default:
                        $table = isset($_POST['clients']) ? Users::mTable : Users::aTable;
                        $row = Db::Go()->rawQuery("SELECT email, CONCAT(fname,' ',lname) as name FROM `" . $table . "` WHERE email LIKE '%" . $to . "%'")->run();
                        break;
                    
                }
                
                switch ($to) {
                    case "members":
                    case "staff":
                    case "sellers":
                    case "newsletter":
                        if ($userRow) {
                            foreach ($userRow as $row) {
                                $html[$row->email] = str_replace(array(
                                    '[LOGO]',
                                    '[NAME]',
                                    '[DATE]',
                                    '[COMPANY]',
                                    '[FB]',
                                    '[TW]',
                                    '[CEMAIL]',
                                    '[SITEURL]'), array(
                                    Utility::getLogo(),
                                    $row->name,
                                    date('Y'),
                                    $core->company,
                                    $core->social->facebook,
                                    $core->social->twitter,
                                    $core->site_email,
                                    SITEURL), $body);
                                
                                $mailer->Body = $html;
                                $mailer->addAddress($row->email, $row->name);
                                
                                try {
                                    $mailer->send();
                                    $numSent++;
                                } catch (exception) {
                                    $failedRecipients[] = htmlspecialchars($row->email);
                                    $mailer->getSMTPInstance()->reset();
                                }
                                $mailer->clearAddresses();
                                $mailer->clearAttachments();
                                
                            }
                            unset($row);
                        }
                        break;
                    default:
                        if ($row) {
                            $newbody = str_replace(array(
                                '[COMPANY]',
                                '[LOGO]',
                                '[NAME]',
                                '[URL]',
                                '[FB]',
                                '[TW]',
                                '[CEMAIL]',
                                '[DATE]'), array(
                                $core->company,
                                Utility::getLogo(),
                                $row->name,
                                SITEURL,
                                $core->social->facebook,
                                $core->social->twitter,
                                $core->site_email,
                                date('Y')), $body);
                            
                            $mailer->addAddress($to, $row->name);
                            $mailer->Body = $newbody;
                            
                            $numSent++;
                            $mailer->send();
                        }
                        break;
                }
                
                if ($numSent) {
                    $json['type'] = 'success';
                    $json['title'] = Lang::$word->SUCCESS;
                    $json['message'] = $numSent . ' ' . Lang::$word->EMN_SENT;
                } else {
                    $json['type'] = 'error';
                    $json['title'] = Lang::$word->ERROR;
                    $res = '<ul>';
                    foreach ($failedRecipients as $failed) {
                        $res .= '<li>' . $failed . '</li>';
                    }
                    $res .= '</ul>';
                    $json['message'] = Lang::$word->EMN_ALERT . $res;
                    
                    unset($failed);
                }
                print json_encode($json);
                
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * Content::processNewsletter()
         *
         * @return void
         */
        public function processNewsletter()
        {
            
            $validate = Validator::Run($_POST);
            $validate
                ->set("name", Lang::$word->EMN_NLN)->required()->string()
                ->set("email", Lang::$word->EMN_NLE)->required()->email();
            
            $safe = $validate->safe();
            
            if (empty(Message::$msgs)) {
                if (Db::Go()->select(Content::nwTable, array('email'))->where("email", $safe->email, "=")->first()->run()) {
                    Db::Go()->delete(Content::nwTable)->where("email", $safe->email, "=")->run();
                    $json['message'] = Lang::$word->EMN_MSG2;
                } else {
                    Db::Go()->insert(Content::nwTable, array("name" => $safe->name, "email" => $safe->email))->run();
                    $json['message'] = Lang::$word->EMN_MSG1;
                }
                
                $json['type'] = 'success';
                $json['title'] = Lang::$word->SUCCESS;
                print json_encode($json);
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * Content::Transmissions()
         *
         * @return void
         */
        public function Transmissions()
        {
            
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->crumbs = ['admin', Lang::$word->TRNS_SUB];
            $tpl->template = 'admin/transmissions.tpl.php';
            $tpl->data = Db::Go()->select(self::trTable)->orderBy("name", "ASC")->run();
            $tpl->title = Lang::$word->TRNS_SUB;
        }
        
        /**
         * Content::Fuel()
         *
         * @return void
         */
        public function Fuel()
        {
            
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->crumbs = ['admin', Lang::$word->FUEL_SUB];
            $tpl->template = 'admin/fuel.tpl.php';
            $tpl->data = Db::Go()->select(self::fuTable)->orderBy("name", "ASC")->run();
            $tpl->title = Lang::$word->FUEL_SUB;
        }
        
        /**
         * Content::Conditions()
         *
         * @return void
         */
        public function Conditions()
        {
            
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->crumbs = ['admin', Lang::$word->COND_SUB];
            $tpl->template = 'admin/conditions.tpl.php';
            $tpl->data = Db::Go()->select(self::cdTable)->orderBy("name", "ASC")->run();
            
            $tpl->title = Lang::$word->COND_SUB;
        }
        
        /**
         * Content::Features()
         *
         * @return void
         */
        public function Features()
        {
            
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->crumbs = ['admin', Lang::$word->FEAT_SUB];
            $tpl->template = 'admin/features.tpl.php';
            $tpl->data = Db::Go()->select(self::fTable)->orderBy("sorting", "ASC")->run();
            $tpl->title = Lang::$word->FEAT_SUB;
        }
        
        /**
         * Content::Categories()
         *
         * @return void
         */
        public function Categories()
        {
            
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->crumbs = ['admin', Lang::$word->CAT_TITLE];
            $tpl->template = 'admin/categories.tpl.php';
            $tpl->data = Db::Go()->select(self::ctTable)->orderBy("name", "ASC")->run();
            $tpl->title = Lang::$word->CAT_TITLE;
        }
        
        /**
         * Users::CategoryEdit()
         *
         * @param int $id
         * @return void
         */
        public function CategoryEdit($id)
        {
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->title = Lang::$word->CAT_SUB1;
            $tpl->crumbs = ['admin', "categories", "edit"];
            
            if (!$row = Db::Go()->select(self::ctTable)->where("id", $id, "=")->first()->run()) {
                $tpl->template = 'admin/error.tpl.php';
                $tpl->error = DEBUG ? "Invalid ID ($id) detected [users.class.php, ln.:" . __line__ . "]" : Lang::$word->META_ERROR;
            } else {
                $tpl->row = $row;
                $tpl->template = 'admin/categories.tpl.php';
            }
        }
        
        /**
         * Content::CategorySave()
         *
         * @return void
         */
        public function CategorySave()
        {
            
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->crumbs = ['admin', "categories", "new"];
            $tpl->template = 'admin/categories.tpl.php';
            $tpl->title = Lang::$word->CAT_TITLE;
        }
        
        /**
         * Content::processCategory()
         *
         * @return void
         */
        public function processCategory()
        {
            $validate = Validator::Run($_POST);
            $validate
                ->set("name", Lang::$word->NAME)->required()->string()->min_len(3)->max_len(60)
                ->set("slug", Lang::$word->CAT_SLUG)->string();
            
            $safe = $validate->safe();
            
            $thumb = File::upload("image", 1048576, "png,jpg,jpeg");
            
            if (empty(Message::$msgs)) {
                $data = array(
                    'name' => $safe->name,
                    'slug' => empty($safe->slug) ? Url::doSeo($safe->name) : Url::doSeo($safe->slug),
                );
                
                //process thumb
                if (!empty($_FILES['image']['name'])) {
                    $thumbdir = UPLOADS . "/catico/";
                    $result = File::process($thumb, $thumbdir, false);
                    if (Filter::$id && $row = Db::Go()->select(self::ctTable, array("image"))->where("id", Filter::$id, "=")->first()->run()) {
                        File::deleteFile($thumbdir . $row->image);
                    }
                    $data['image'] = $result['fname'];
                }
                
                (Filter::$id) ? Db::Go()->update(self::ctTable, $data)->where("id", Filter::$id, "=")->run() : Db::Go()->insert(self::ctTable, $data)->run();
                $message = (Filter::$id) ? Lang::$word->CAT_UPDATED : Lang::$word->CAT_ADDED;
                Message::msgReply(Db::Go()->affected(), 'success', $message);
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * Content::categoryCounters()
         *
         * @return array|false|int|string
         */
        public static function categoryCounters()
        {
            
            $sql = "
              SELECT 
                c.id,
                c.name,
                c.slug,
                c.image,
                COUNT(l.id) AS listings 
              FROM 
                `" . self::ctTable . "` AS c 
                LEFT JOIN `" . Items::lTable . "` AS l 
                  ON l.category = c.id 
              WHERE l.status = ? 
                AND l.featured = ?  
              GROUP BY c.id 
              LIMIT " . App::Core()->featured;
            
            $row = Db::Go()->rawQuery($sql, array(1, 1))->run();
            return ($row) ?: 0;
        }
        
        /**
         * Content::Makes()
         *
         * @return void
         */
        public function Makes()
        {
            
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->crumbs = ['admin', Lang::$word->MAKE_SUB];
            $tpl->template = 'admin/makes.tpl.php';
            $tpl->data = Db::Go()->select(self::mkTable)->orderBy("name", "ASC")->run();
            $tpl->title = Lang::$word->MAKE_SUB;
        }
        
        /**
         * Content::Models()
         *
         * @return void
         */
        public function Models()
        {
            
            $find = isset($_GET['find']) ? Validator::sanitize($_GET['find'], "default", 30) : null;
            
            if (Filter::$id) {
                $counter = Db::Go()->count(self::mdTable, "WHERE make_id = " . Filter::$id . " LIMIT 1")->run();
                $where = " WHERE md.make_id = " . Filter::$id;
                
            } elseif (isset($_GET['find'])) {
                $counter = Db::Go()->count(self::mdTable, "WHERE `name` LIKE '%" . trim($find) . "%'")->run();
                $where = " WHERE md.name LIKE '%" . trim($find) . "%'";
                
            } elseif (isset($_GET['letter'])) {
                $letter = Validator::sanitize($_GET['letter'], 'string', 2);
                $where = " WHERE md.name REGEXP '^" . $letter . "'";
                $counter = Db::Go()->count(self::mdTable, "WHERE `name` REGEXP '^" . $letter . "' LIMIT 1")->run();
            } else {
                $counter = Db::Go()->count(self::mdTable)->run();
                $where = null;
            }
            
            $pager = Paginator::instance();
            $pager->items_total = $counter;
            $pager->default_ipp = 45;
            $pager->path = Url::url(Router::$path, "?");
            $pager->paginate();
            
            $sql = "
              SELECT
                md.id AS mdid,
                md.name AS mdname,
                mk.name AS mkname
              FROM
                `" . self::mdTable . "` AS md
                LEFT JOIN `" . self::mkTable . "` AS mk
                  ON mk.id = md.make_id
              $where
              ORDER BY md.name " . $pager->limit;
            $data = Db::Go()->rawQuery($sql)->run();
            
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->crumbs = ['admin', Lang::$word->MODL_SUB];
            $tpl->template = 'admin/models.tpl.php';
            $tpl->pager = $pager;
            $tpl->data = $data;
            $tpl->makes = Db::Go()->select(self::mkTable)->orderBy("name", "ASC")->run();
            $tpl->title = Lang::$word->MODL_SUB;
        }
        
        /**
         * Content::newInventory()
         *
         * @return void
         */
        public function newInventory()
        {
            $validate = Validator::Run($_POST);
            $validate->set("name", Lang::$word->NAME)->required()->string()->min_len(3)->max_len(60);
            $safe = $validate->safe();
            $message = null;
            $table = null;
            
            
            switch ($_POST['type']) {
                case "transmission":
                    $table = self::trTable;
                    $message = Lang::$word->TRNS_ADDED;
                    break;
                
                case "fuel":
                    $table = self::fuTable;
                    $message = Lang::$word->FUEL_ADDED;
                    break;
                
                case "conditions":
                    $table = self::cdTable;
                    $message = Lang::$word->COND_ADDED;
                    break;
                
                case "features":
                    $table = self::fTable;
                    $message = Lang::$word->FEAT_ADDED;
                    break;
                
                case "makes":
                    $table = self::mkTable;
                    $message = Lang::$word->MAKE_ADDED;
                    break;
            }
            
            if (empty(Message::$msgs)) {
                $data = array('name' => $safe->name);
                
                Db::Go()->insert($table, $data)->run();
                
                $json['message'] = Message::formatSuccessMessage($data['name'], $message);
                $json['title'] = Lang::$word->SUCCESS;
                $json['type'] = "success";
                $json['redirect'] = Url::url($_POST['url']);
                print json_encode($json);
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * Content::newModel()
         *
         * @return void
         */
        public function newModel()
        {
            $validate = Validator::Run($_POST);
            $validate->set("make_id", Lang::$word->MAKE_SUB2)->required()->numeric();
            
            $safe = $validate->safe();
            
            if (!array_key_exists('name', $_POST)) {
                Message::$msgs['name'] = LANG::$word->MODL_NAME_R;
            }
            
            if (array_key_exists('name', $_POST)) {
                if (!array_filter($_POST['name'])) {
                    Message::$msgs['name'] = LANG::$word->MODL_NAME_R;
                }
            }
            
            if (empty(Message::$msgs)) {
                $values = array_filter($_POST['name']);
                $dataArray = array();
                foreach ($values as $row) {
                    $dataArray[] = array('make_id' => $safe->make_id, 'name' => $row);
                }
                Db::Go()->batch(self::mdTable, $dataArray)->run();
                
                $json['message'] = Lang::$word->MODL_ADDED;
                $json['title'] = Lang::$word->SUCCESS;
                $json['type'] = "success";
                $json['redirect'] = Url::url($_POST['url']);
                print json_encode($json);
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * Content::calculateDays()
         *
         * @param bool $membership_id
         * @return string
         */
        public static function calculateDays($membership_id)
        {
            
            $row = Db::Go()->select(self::msTable, array('days', 'period'))->where("id", $membership_id, "=")->first()->run();
            if ($row) {
                $diff = match ($row->period) {
                    "D" => ' day',
                    "W" => ' week',
                    "M" => ' month',
                    "Y" => ' year',
                    default => ' none',
                };
                $expire = Date::NumberOfDays('+' . $row->days . $diff);
            } else {
                $expire = "";
            }
            return $expire;
        }
        
        /**
         * Content::getSortMenuList()
         *
         * @param $array
         * @return string
         */
        public function getSortMenuList($array)
        {
            
            $icon = '<i class="icon negative trash"></i>';
            $html = "<ol class=\"dd-list\">\n";
            
            foreach ($array as $row) {
                $html .= '<li class="dd-item dd3-item clearfix" data-id="' . $row->id . '"><div class="dd-handle dd3-handle"></div>' . '<div class="dd3-content"><span class="actions"><a class="data" data-set=\'{"option":[{"trash": "trashMenu","title": "' . Validator::sanitize($row->name, "chars") . '","id":' . $row->id .
                    '}],"action":"trash","parent":"li", "redirect":"' . Url::url("/admin/menus") . '"}\'>' . $icon . '</a></span>' . ' <a href="' . Url::url("/admin/menus/edit", $row->id) . '">' . $row->name . '</a>' . '</div>';
                $html .= "</li>\n";
            }
            unset($row);
            
            $html .= "</ol>\n";
            
            return $html;
        }
        
        /**
         * Content::getMenus()
         *
         * @return array|false|int|string
         */
        public static function getMenus()
        {
            
            $sql = "
              SELECT
                m.id,
                m.name,
                m.content_type,
                m.link,
                m.target,
                p.slug,
                p.type
              FROM
                `" . self::muTable . "` AS m
                LEFT JOIN `" . self::pgTable . "` AS p
                  ON p.id = m.page_id
              WHERE m.active = ?
              ORDER BY m.sorting";
            
            $row = Db::Go()->rawQuery($sql, array(1))->run();
            
            return ($row) ?: 0;
        }
        
        /**
         * Content::renderMenu()
         *
         * @param array $array
         * @return string
         */
        public function renderMenu($array)
        {
            
            $html = '';
            if (is_array($array) && count($array) > 0) {
                $html .= "<ul class=\"top-menu\">\n";
                
                foreach ($array as $row) {
                    $url = Url::url("/page", $row->slug . '/');
                    $active = (in_array($row->slug, self::$segments) ? "active" : "normal");
                    $link = '<a href="' . $url . '" class="' . $active . '">' . $row->name . '</a>';
                    $html .= '<li class="nav-item">';
                    $html .= $link;
                    $html .= "</li>\n";
                }
                
                $html .= "</ul>\n";
                
            }
            return $html;
        }
        
        /**
         * Content::getCart()
         *
         * @param bool $uid
         * @return int|mixed
         */
        public static function getCart($uid = false)
        {
            $id = ($uid) ? intval($uid) : App::Auth()->uid;
            $row = Db::Go()->select(self::xTable)->where("user_id", $id, "=")->first()->run();
            
            return ($row) ?: 0;
        }
        
        /**
         * Content::calculateTax()
         *
         * @param bool $uid
         * @return float|int
         */
        public static function calculateTax($uid = false)
        {
            if (App::Core()->enable_tax) {
                if ($uid) {
                    $cnt = Db::Go()->select(Users::mTable, array("country"))->where("id", $uid, "=")->first()->run();
                    if ($cnt) {
                        $row = Db::Go()->select(self::cTable, array("vat"))->where("abbr", $cnt->country, "=")->first()->run();
                        return ($row->vat / 100);
                    } else {
                        return 0;
                    }
                } else {
                    if (App::Auth()->country) {
                        $row = Db::Go()->select(self::cTable, array("vat"))->where("abbr", App::Auth()->country, "=")->first()->run();
                        return ($row->vat / 100);
                    } else {
                        return 0;
                    }
                }
            } else {
                return 0;
            }
        }
        
        /**
         * Content::writeSiteMap()
         *
         * @return void
         */
        public static function writeSiteMap()
        {
            
            $filename = BASEPATH . 'sitemap.xml';
            $file = SITEURL . '/sitemap.xml';
            if (is_writable($filename)) {
                File::writeToFile($filename, self::makeSiteMap());
                Message::msgReply($file, 'success', Message::formatSuccessMessage($file, Lang::$word->UTL_MAP_OK));
            } else {
                Message::msgReply($file, 'error', Message::formatErrorMessage($file, Lang::$word->UTL_MAP_ERROR));
            }
        }
        
        /**
         * Content::makeSiteMap()
         *
         * @return string
         */
        public static function makeSiteMap()
        {
            
            $html = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\r\n";
            $html .= "<urlset xmlns=\"https://www.sitemaps.org/schemas/sitemap/0.9\" xmlns:xsi=\"https://www.w3.org/2001/XMLSchema-instance\" xsi:schemaLocation=\"https://www.sitemaps.org/schemas/sitemap/0.9 https://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\">\r\n";
            $html .= "<url>\r\n";
            $html .= "<loc>" . SITEURL . "/</loc>\r\n";
            $html .= "<lastmod>" . date('Y-m-d') . "</lastmod>\r\n";
            $html .= "</url>\r\n";
            
            //listings
            $pages = "SELECT slug, idx FROM `" . Items::lTable . "` WHERE status = ? ORDER BY created DESC";
            $query = Db::Go()->rawQuery($pages, array(1));
            
            foreach ($query->results() as $row) {
                $html .= "<url>\r\n";
                $html .= "<loc>" . Url::url('/listing/' . $row->idx, $row->slug) . "</loc>\r\n";
                $html .= "<lastmod>" . date('Y-m-d') . "</lastmod>\r\n";
                $html .= "<changefreq>weekly</changefreq>\r\n";
                $html .= "</url>\r\n";
            }
            unset($row, $query);
            
            $html .= "</urlset>";
            
            return $html;
        }
        
        /**
         * Content::colorList()
         *
         * @return array
         */
        public static function colorList()
        {
            return array(
                Lang::$word->WHITE => Lang::$word->WHITE,
                Lang::$word->BLACK => Lang::$word->BLACK,
                Lang::$word->SILVER => Lang::$word->SILVER,
                Lang::$word->GRAY => Lang::$word->GRAY,
                Lang::$word->RED => Lang::$word->RED,
                Lang::$word->BLUE => Lang::$word->BLUE,
                Lang::$word->BEIGE => Lang::$word->BEIGE,
                Lang::$word->YELLOW => Lang::$word->YELLOW,
                Lang::$word->GREEN => Lang::$word->GREEN,
                Lang::$word->BROWN => Lang::$word->BROWN,
                Lang::$word->BURGUNDY => Lang::$word->BURGUNDY,
                Lang::$word->CHARCOAL => Lang::$word->CHARCOAL,
                Lang::$word->GOLD => Lang::$word->GOLD,
                Lang::$word->PINK => Lang::$word->PINK,
                Lang::$word->PURPLE => Lang::$word->PURPLE,
                Lang::$word->TAN => Lang::$word->TAN,
                Lang::$word->TURQUOISE => Lang::$word->TURQUOISE,
            );
        }
        
        /**
         * Content::getContentType()
         *
         * @return array
         */
        public static function getContentType()
        {
            return array('page' => Lang::$word->MENU_CPAGE, 'web' => Lang::$word->MENU_ELINK);
        }
    }
