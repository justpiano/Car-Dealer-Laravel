<?php
    /**
     * Core Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2020
     * @version $Id: core.class.php, v1.00 2020-06-05 10:12:05 gewa Exp $
     */
    
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
    
    class Core
    {
        
        const sTable = "settings";
        const txTable = "trash";
        public static $language;
        
        public $locale;
        public $lang;
        public $weekstart;
        public $theme;
        public $perpage;
        public $sperpage;
        public $featured;
        public $listing_view;
        public $currency;
        public $offline;
        public $offline_msg;
        public $offline_d;
        public $offline_t;
        public $eucookie;
        public $sbackup;
        public $enable_tax;
        public $number_sold;
        public $notify_admin;
        public $notify_email;
        public $pagesize;
        public $inv_info;
        public $inv_note;
        public $show_home;
        public $home_content;
        public $show_slider;
        public $show_news;
        public $show_reviews;
        public $show_featured;
        public $show_brands;
        public $show_popular;
        public $autoapprove;
        public $trans_list;
        public $fuel_list;
        public $cond_list;
        public $cond_list_alt;
        public $odometer;
        public $minprice;
        public $maxprice;
        public $minyear;
        public $maxyear;
        public $minkm;
        public $maxkm;
        public $color;
        public $makes;
        public $year_list;
        public $category_list;
        public $make_list;
        public $company;
        public $site_dir;
        public $site_email;
        public $city;
        public $address;
        public $state;
        public $zip;
        public $phone;
        public $fax;
        public $country;
        public $logo;
        public $plogo;
        public $short_date;
        public $long_date;
        public $time_format;
        public $calendar_date;
        public $minsprice;
        public $maxsprice;
        public $model_list;
        public $dtz;
        public $vinapi;
        public $file_size;
        public $thumb_w;
        public $thumb_h;
        public $mapapi;
        public $analytics;
        public $metakeys;
        public $metadesc;
        public $mailer;
        public $smtp_host;
        public $smtp_user;
        public $smtp_pass;
        public $smtp_port;
        public $sendmail;
        public $is_ssl;
        public $social;
        public $wojov;
        public $wojon;
        
        
        public $_url;
        public $_urlParts;
        
        /**
         * Core::__construct()
         *
         */
        public function __construct()
        {
            $this->getSettings();
            ($this->dtz) ? ini_set('date.timezone', $this->dtz) : date_default_timezone_set('UTC');
            Locale::setDefault($this->locale);
        }
        
        /**
         * Core::Index()
         *
         * @return void
         */
        public function Index()
        {
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->crumbs = ['admin', "settings"];
            $tpl->template = 'admin/configuration.tpl.php';
            $tpl->title = Lang::$word->META_T14;
        }
        
        /**
         * Core::getSettings()
         *
         * @return void
         */
        private function getSettings()
        {
            $row = Db::Go()->select(self::sTable)->where("id", 1, "=")->first()->run();
            
            $this->company = $row->company;
            $this->site_dir = $row->site_dir;
            $this->site_email = $row->site_email;
            $this->address = $row->address;
            $this->city = $row->city;
            $this->state = $row->state;
            $this->zip = $row->zip;
            $this->phone = $row->phone;
            $this->fax = $row->fax;
            $this->country = $row->country;
            $this->logo = $row->logo;
            $this->plogo = $row->plogo;
            $this->short_date = $row->short_date;
            $this->long_date = $row->long_date;
            $this->time_format = $row->time_format;
            $this->calendar_date = $row->calendar_date;
            $this->dtz = $row->dtz;
            $this->locale = $row->locale;
            $this->lang = $row->lang;
            $this->weekstart = $row->weekstart;
            $this->theme = $row->theme;
            $this->perpage = $row->perpage;
            $this->sperpage = $row->sperpage;
            $this->featured = $row->featured;
            $this->currency = $row->currency;
            $this->offline = $row->offline;
            $this->offline_msg = $row->offline_msg;
            $this->offline_d = $row->offline_d;
            $this->offline_t = $row->offline_t;
            $this->eucookie = $row->eucookie;
            $this->sbackup = $row->sbackup;
            $this->enable_tax = $row->enable_tax;
            $this->number_sold = $row->number_sold;
            $this->notify_admin = $row->notify_admin;
            $this->notify_email = $row->notify_email;
            $this->inv_info = $row->inv_info;
            $this->inv_note = $row->inv_note;
            $this->show_home = $row->show_home;
            $this->home_content = $row->home_content;
            $this->show_slider = $row->show_slider;
            $this->show_news = $row->show_news;
            $this->show_reviews = $row->show_reviews;
            $this->show_featured = $row->show_featured;
            $this->show_brands = $row->show_brands;
            $this->show_popular = $row->show_popular;
            $this->listing_view = $row->listing_view;
            $this->autoapprove = $row->autoapprove;
            $this->trans_list = $row->trans_list;
            $this->fuel_list = $row->fuel_list;
            $this->cond_list = $row->cond_list;
            $this->cond_list_alt = $row->cond_list_alt;
            $this->odometer = $row->odometer;
            $this->minprice = $row->minprice;
            $this->maxprice = $row->maxprice;
            $this->minsprice = $row->minsprice;
            $this->maxsprice = $row->maxsprice;
            $this->minyear = $row->minyear;
            $this->maxyear = $row->maxyear;
            $this->minkm = $row->minkm;
            $this->maxkm = $row->maxkm;
            $this->color = $row->color;
            $this->makes = $row->makes;
            $this->year_list = $row->year_list;
            $this->category_list = $row->category_list;
            $this->make_list = $row->make_list;
            $this->model_list = $row->model_list;
            $this->vinapi = $row->vinapi;
            $this->file_size = $row->file_size;
            $this->thumb_w = $row->thumb_w;
            $this->thumb_h = $row->thumb_h;
            $this->mapapi = $row->mapapi;
            $this->analytics = $row->analytics;
            $this->metakeys = $row->metakeys;
            $this->metadesc = $row->metadesc;
            $this->mailer = $row->mailer;
            $this->smtp_host = $row->smtp_host;
            $this->smtp_user = $row->smtp_user;
            $this->smtp_pass = $row->smtp_pass;
            $this->smtp_port = $row->smtp_port;
            $this->sendmail = $row->sendmail;
            $this->is_ssl = $row->is_ssl;
            
            $this->social = json_decode($row->social_media);
            
            $this->wojov = $row->wojov;
            $this->wojon = $row->wojon;
            
        }
        
        /**
         * Core::processConfig()
         *
         * @return void
         */
        public function processConfig()
        {
            $validate = Validator::Run($_POST);
            $validate
                ->set("company", Lang::$word->CF_COMPANY)->required()->string()->min_len(2)->max_len(80)
                ->set("site_email", Lang::$word->CF_EMAIL)->email()
                ->set("theme", Lang::$word->CF_THEME)->required()->string()
                ->set("perpage", Lang::$word->CF_PERPAGE)->required()->numeric()
                ->set("thumb_w", Lang::$word->CF_THUMBWH)->required()->numeric()
                ->set("thumb_h", Lang::$word->CF_THUMBWH)->required()->numeric()
                ->set("listing_view", Lang::$word->CF_VIEW)->required()->string()
                ->set("sperpage", Lang::$word->CF_SPERPAGE)->required()->numeric();
            
            $validate
                ->set("address", Lang::$word->ADDRESS)->required()->string()
                ->set("city", Lang::$word->CITY)->required()->string()
                ->set("state", Lang::$word->STATE)->required()->string()
                ->set("zip", Lang::$word->ZIP)->required()->string()
                ->set("country", Lang::$word->COUNTRY)->required()->string()
                ->set("long_date", Lang::$word->CF_LONGDATE)->required()->string()
                ->set("short_date", Lang::$word->CF_LONGDATE)->required()->string()
                ->set("calendar_date", Lang::$word->CF_CALDATE)->required()->string();
            
            $validate
                ->set("time_format", Lang::$word->CF_TIME)->required()->string()
                ->set("dtz", Lang::$word->CF_DTZ)->required()->string()
                ->set("locale", Lang::$word->CF_LOCALES)->required()->string()
                ->set("weekstart", Lang::$word->CF_WEEKSTART)->required()->numeric()
                ->set("lang", Lang::$word->CF_LANG)->required()->string()->exact_len(2)
                ->set("eucookie", Lang::$word->CF_EUCOOKIE)->required()->numeric()
                ->set("offline", Lang::$word->CF_OFFLINE)->required()->numeric()
                ->set("featured", Lang::$word->CF_FEATURED)->required()->numeric();
            
            $validate
                ->set("number_sold", Lang::$word->CF_SOLD)->required()->numeric()
                ->set("odometer", Lang::$word->CF_SPEAD)->required()->string()
                ->set("show_home", Lang::$word->CF_SHOWHOME)->required()->numeric()
                ->set("show_slider", Lang::$word->CF_SHOWSLIDER)->required()->numeric()
                ->set("show_news", Lang::$word->CF_SHOWNEWS)->required()->numeric()
                ->set("show_popular", Lang::$word->CF_POPULAR)->required()->numeric()
                ->set("show_reviews", Lang::$word->CF_REVIEW)->required()->numeric()
                ->set("show_brands", Lang::$word->CF_BRANDS)->required()->numeric()
                ->set("show_featured", Lang::$word->CF_FEAT)->required()->numeric();
            
            $validate
                ->set("autoapprove", Lang::$word->CF_AUTOAPP)->required()->numeric()
                ->set("currency", Lang::$word->CF_CURRENCY)->required()->string()->min_len(3)->max_len(6)
                ->set("enable_tax", Lang::$word->CF_TAX)->required()->numeric()
                ->set("file_size", Lang::$word->CF_FILESIZE)->required()->numeric()
                ->set("notify_admin", Lang::$word->CF_NOTIFY)->required()->numeric()
                ->set("mailer", Lang::$word->CF_MAILER)->required()->string()
                ->set("is_ssl", Lang::$word->CF_SMTP_SSL)->required()->numeric()
                ->set("sendmail", Lang::$word->CF_SMAILPATH)->path();
            
            if ($_POST['notify_admin'] == 1) {
                $validate->set("notify_email", Lang::$word->CF_AUTOAPP)->required()->email();
            }
            
            switch ($_POST['mailer']) {
                case "SMTP":
                    $validate
                        ->set("smtp_host", Lang::$word->CF_SMTP_HOST)->required()->string()
                        ->set("smtp_user", Lang::$word->CF_SMTP_USER)->required()->string()
                        ->set("smtp_pass", Lang::$word->CF_SMTP_PASS)->required()->string()
                        ->set("smtp_port", Lang::$word->CF_SMTP_PORT)->required()->numeric();
                    break;
                
                case "SMAIL":
                    $validate->set("sendmail", Lang::$word->CF_SMAILPATH)->required()->path();
                    break;
            }
            
            $validate
                ->set("site_dir", Lang::$word->CF_DIR)->path()
                ->set("twitter", Lang::$word->CF_TWID)->string()
                ->set("facebook", Lang::$word->CF_FBID)->string()
                ->set("offline_d_submit", Lang::$word->CF_OFFLINE_DATE)->date()
                ->set("offline_t", Lang::$word->CF_OFFLINE_TIME)->time()
                ->set("inv_info", Lang::$word->CF_INVF)->text("basic")
                ->set("inv_note", Lang::$word->CF_INVI)->text("basic")
                ->set("offline_msg", Lang::$word->CF_OFFLINE_MSG)->text("basic")
                ->set("analytics", Lang::$word->CF_GA)->string()
                ->set("metakeys", Lang::$word->CF_METAKEY)->string()
                ->set("metadesc", Lang::$word->CF_METADESC)->string()
                ->set("phone", Lang::$word->CF_PHONE)->string()
                ->set("fax", Lang::$word->CF_FAX)->string()
                ->set("mapapi", Lang::$word->CF_MAPAPI)->string()
                ->set("vinapi", Lang::$word->CF_VINAPI)->string();
            
            $logo = File::upload("logo", 3145728, "png,jpg,svg");
            $plogo = File::upload("plogo", 3145728, "png,jpg,svg");
            
            $safe = $validate->safe();
            
            if (empty(Message::$msgs)) {
                $smedia['facebook'] = $safe->facebook;
                $smedia['twitter'] = $safe->twitter;
                
                $data = array(
                    'company' => $safe->company,
                    'site_dir' => $safe->site_dir,
                    'site_email' => $safe->site_email,
                    'address' => $safe->address,
                    'city' => $safe->city,
                    'state' => $safe->state,
                    'zip' => $safe->zip,
                    'country' => $safe->country,
                    'phone' => $safe->phone,
                    'fax' => $safe->fax,
                    'short_date' => $safe->short_date,
                    'long_date' => $safe->long_date,
                    'time_format' => $safe->time_format,
                    'calendar_date' => $safe->calendar_date,
                    'dtz' => $safe->dtz,
                    'lang' => $safe->lang,
                    'weekstart' => $safe->weekstart,
                    'locale' => $safe->locale,
                    'theme' => $safe->theme,
                    'offline' => $safe->offline,
                    'offline_msg' => $safe->offline_msg,
                    'offline_d' => Db::toDate($safe->offline_d_submit),
                    'offline_t' => $safe->offline_t,
                    'thumb_w' => $safe->thumb_w,
                    'thumb_h' => $safe->thumb_h,
                    'perpage' => $safe->perpage,
                    'sperpage' => $safe->sperpage,
                    'featured' => $safe->featured,
                    'enable_tax' => $safe->enable_tax,
                    'number_sold' => $safe->number_sold,
                    'notify_admin' => $safe->notify_admin,
                    'notify_email' => $safe->notify_email,
                    'show_home' => $safe->show_home,
                    'show_slider' => $safe->show_slider,
                    'show_news' => $safe->show_news,
                    'show_popular' => $safe->show_popular,
                    'show_reviews' => $safe->show_reviews,
                    'show_brands' => $safe->show_brands,
                    'show_featured' => $safe->show_featured,
                    'listing_view' => $safe->listing_view,
                    'autoapprove' => $safe->autoapprove,
                    'currency' => $safe->currency,
                    'eucookie' => $safe->eucookie,
                    'odometer' => $safe->odometer,
                    'metakeys' => $safe->metakeys,
                    'metadesc' => $safe->metadesc,
                    'analytics' => $safe->analytics,
                    'mapapi' => $safe->mapapi,
                    'vinapi' => $safe->vinapi,
                    'inv_info' => $safe->inv_info,
                    'inv_note' => $safe->inv_note,
                    'social_media' => json_encode($smedia),
                    'mailer' => $safe->mailer,
                    'sendmail' => $safe->sendmail,
                    'smtp_host' => $safe->smtp_host,
                    'smtp_user' => $safe->smtp_user,
                    'smtp_pass' => $safe->smtp_pass,
                    'smtp_port' => $safe->smtp_port,
                    'is_ssl' => $safe->is_ssl,
                );
                $logoPath = UPLOADS . "/";
                
                if (!empty($_FILES['logo']['name'])) {
                    File::deleteFile($logoPath . $this->logo);
                    $result = File::process($logo, $logoPath, false, "logo", false);
                    $data['logo'] = $result['fname'];
                }
                
                if (!empty($_FILES['plogo']['name'])) {
                    File::deleteFile($logoPath . $this->plogo);
                    $result = File::process($plogo, $logoPath, false, "print_logo", false);
                    $data['plogo'] = $result['fname'];
                }
                
                if (Validator::post('dellogo')) {
                    $data['logo'] = "NULL";
                }
                if (Validator::post('dellogop')) {
                    $data['plogo'] = "NULL";
                }
                //Debug::pre($safe);
                Db::Go()->update(self::sTable, $data)->where('id', 1, "=")->run();
                Message::msgReply(Db::Go()->affected(), 'success', Lang::$word->CF_UPDATED);
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * Core::processColors()
         *
         * @return void
         */
        public static function processColors()
        {
            $validate = Validator::Run($_POST);
            $validate
                ->set("alert-color", "Alert")->required()->color()
                ->set("alert-color-hover", "Alert Hover")->required()->color()
                ->set("alert-color-active", "Alert Active")->required()->color()
                ->set("alert-color-inverted", "Alert Inverted")->required()->color()
                ->set("alert-color-shadow", "Alert Shadow")->required()->color()
                ->set("info-color", "Info")->required()->color()
                ->set("info-color-hover", "Info Hover")->required()->color()
                ->set("info-color-active", "Info Active")->required()->color()
                ->set("info-color-inverted", "Info Inverted")->required()->color()
                ->set("info-color-shadow", "Info Shadow")->required()->color()
                ->set("light-color", "Light")->required()->color()
                ->set("light-color-hover", "Light Hover")->required()->color()
                ->set("light-color-active", "Light Active")->required()->color()
                ->set("light-color-inverted", "Light Inverted")->required()->color()
                ->set("light-color-shadow", "Light Shadow")->required()->color()
                ->set("dark-color", "Dark")->required()->color()
                ->set("dark-color-hover", "Dark Hover")->required()->color()
                ->set("dark-color-active", "Dark Active")->required()->color()
                ->set("dark-color-inverted", "Dark Inverted")->required()->color()
                ->set("dark-color-shadow", "Dark Shadow")->required()->color()
                ->set("grey-color", "Grey")->required()->color()
                ->set("grey-color-100", "Grey 100")->required()->color()
                ->set("grey-color-300", "Grey 300")->required()->color()
                ->set("grey-color-500", "Grey 500")->required()->color()
                ->set("grey-color-700", "Grey 700")->required()->color();
            
            $validate
                ->set("body-color", "Body Bg Color")->required()->color()
                ->set("body-bg-color", "Body Bg Color")->required()->color()
                ->set("primary-color", "Primary")->required()->color()
                ->set("primary-color-hover", "Primary Hover")->required()->color()
                ->set("primary-color-active", "Primary Active")->required()->color()
                ->set("primary-color-inverted", "Primary Inverted")->required()->color()
                ->set("primary-color-shadow", "Primary Shadow")->required()->color()
                ->set("secondary-color", "Secondary")->required()->color()
                ->set("secondary-color-hover", "Secondary Hover")->required()->color()
                ->set("secondary-color-active", "Secondary Active")->required()->color()
                ->set("secondary-color-inverted", "Secondary Inverted")->required()->color()
                ->set("secondary-color-shadow", "Secondary Shadow")->required()->color()
                ->set("positive-color", "Positive")->required()->color()
                ->set("positive-color-hover", "Positive Hover")->required()->color()
                ->set("positive-color-active", "Positive Active")->required()->color()
                ->set("positive-color-inverted", "Positive Inverted")->required()->color()
                ->set("positive-color-shadow", "Positive Shadow")->required()->color()
                ->set("negative-color", "Negative")->required()->color()
                ->set("negative-color-hover", "Negative Hover")->required()->color()
                ->set("negative-color-active", "Negative Active")->required()->color()
                ->set("negative-color-inverted", "Negative Inverted")->required()->color()
                ->set("negative-color-shadow", "Negative Shadow")->required()->color();
            
            $safe = $validate->safe();
            
            if (empty(Message::$msgs)) {
                $data = "
				  :root {
				   --body-color: " . $safe->{'body-color'} . ";
				   --body-bg-color: " . $safe->{'body-bg-color'} . ";
				   --primary-color: " . $safe->{'primary-color'} . ";
				   --primary-color-hover: " . $safe->{'primary-color-hover'} . ";
				   --primary-color-active: " . $safe->{'primary-color-active'} . ";
				   --primary-color-inverted: " . $safe->{'primary-color-inverted'} . ";
				   --primary-color-shadow: " . $safe->{'primary-color-shadow'} . ";
				   --secondary-color: " . $safe->{'secondary-color'} . ";
				   --secondary-color-hover: " . $safe->{'secondary-color-hover'} . ";
				   --secondary-color-active: " . $safe->{'secondary-color-active'} . ";
				   --secondary-color-inverted: " . $safe->{'secondary-color-inverted'} . ";
				   --secondary-color-shadow: " . $safe->{'secondary-color-shadow'} . ";
				   --positive-color: " . $safe->{'positive-color'} . ";
				   --positive-color-hover: " . $safe->{'positive-color-hover'} . ";
				   --positive-color-active: " . $safe->{'positive-color-active'} . ";
				   --positive-color-inverted: " . $safe->{'positive-color-inverted'} . ";
				   --positive-color-shadow: " . $safe->{'positive-color-shadow'} . ";
				   --negative-color: " . $safe->{'negative-color'} . ";
				   --negative-color-hover: " . $safe->{'negative-color-hover'} . ";
				   --negative-color-active: " . $safe->{'negative-color-active'} . ";
				   --negative-color-inverted: " . $safe->{'negative-color-inverted'} . ";
				   --negative-color-shadow: " . $safe->{'negative-color-shadow'} . ";
				   --alert-color: " . $safe->{'alert-color'} . ";
				   --alert-color-hover: " . $safe->{'alert-color-hover'} . ";
				   --alert-color-active: " . $safe->{'alert-color-active'} . ";
				   --alert-color-inverted: " . $safe->{'alert-color-inverted'} . ";
				   --alert-color-shadow: " . $safe->{'alert-color-shadow'} . ";
				   --info-color: " . $safe->{'info-color'} . ";
				   --info-color-hover: " . $safe->{'info-color-hover'} . ";
				   --info-color-active: " . $safe->{'info-color-active'} . ";
				   --info-color-inverted: " . $safe->{'info-color-inverted'} . ";
				   --info-color-shadow: " . $safe->{'info-color-shadow'} . ";
				   --light-color: " . $safe->{'light-color'} . ";
				   --light-color-hover: " . $safe->{'light-color-hover'} . ";
				   --light-color-active: " . $safe->{'light-color-active'} . ";
				   --light-color-inverted: " . $safe->{'light-color-inverted'} . ";
				   --light-color-shadow: " . $safe->{'light-color-shadow'} . ";
				   --dark-color: " . $safe->{'dark-color'} . ";
				   --dark-color-hover: " . $safe->{'dark-color-hover'} . ";
				   --dark-color-active: " . $safe->{'dark-color-active'} . ";
				   --dark-color-inverted: " . $safe->{'dark-color-inverted'} . ";
				   --dark-color-shadow: " . $safe->{'dark-color-shadow'} . ";
				   --black-color: #000;
				   --white-color: #fff;
				   --shadow-color: rgba(136, 152, 170, .15);
				   --grey-color: " . $safe->{'grey-color'} . ";
				   --grey-color-100: " . $safe->{'grey-color-100'} . ";
				   --grey-color-300: " . $safe->{'grey-color-300'} . ";
				   --grey-color-500: " . $safe->{'grey-color-500'} . ";
				   --grey-color-700: " . $safe->{'grey-color-700'} . ";
				  }
			  ";
                
                $filename = THEMEBASE . "/css/color.css";
                $file = THEMEURL . "/css/color.css";
                
                if (is_writable($filename)) {
                    File::writeToFile($filename, trim(preg_replace('/\t+/', '', $data)));
                    File::deleteFile(THEMEBASE . "/cache/master_main_ltr.css");
                    Message::msgReply($file, 'success', Message::formatSuccessMessage($file, Lang::$word->UTL_COLOR_OK));
                } else {
                    Message::msgReply($file, 'error', Message::formatErrorMessage($file, Lang::$word->CF_FILE_ERROR));
                }
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * Core::restoreFromTrash()
         *
         * @param $array
         * @param $table
         * @return void
         */
        public static function restoreFromTrash($array, $table)
        {
            if ($array) {
                $mapped = array_map(function ($k) {
                    return "`" . $k . "` = ?";
                }, array_keys((array)$array
                ));
                $stmt = Db::Go()->prepare("INSERT INTO `" . $table . "` SET " . implode(", ", $mapped));
                $stmt->execute(array_values((array)$array));
            }
        }
    }
