<?php
    /**
     * Front Class
     *
     * package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: front.class.php, v1.00 2022-04-20 18:20:24 gewa Exp $
     */
    
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
    
    class Front
    {
        
        
        /**
         * Front::Index()
         *
         * @return void
         */
        public function Index()
        {
            $core = App::Core();
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "front/themes/" . $core->theme . "/";
            $tpl->title = str_replace("[COMPANY]", $core->company, Lang::$word->META_WELCOME);
            
            $tpl->home = Db::Go()->select(Content::pgTable, array("title", "body", "keywords", "description"))->where("type", "home", "=")->where("active", 1, "=")->first()->run();
            $tpl->sliderdata = $core->show_slider ? Db::Go()->select(Content::slTable, array("thumb", "body", "caption", "url"))->orderBy("sorting", "ASC")->run() : null;
            
            $sql = "
              SELECT 
                l.id,
                l.idx,
                l.nice_title,
                l.slug,
                l.price,
                l.price_sale,
                l.thumb,
                l.year,
                l.drive_train,
                t.name AS transmission,
                f.name AS fuel 
              FROM 
                `" . Items::lTable . "` AS l 
                LEFT JOIN `" . Content::trTable . "` AS t 
                  ON t.id = l.transmission 
                LEFT JOIN `" . Content::fuTable . "` AS f 
                  ON f.id = l.fuel 
              WHERE l.featured = ? 
                AND l.status = ? 
              ORDER BY RAND () 
              LIMIT 5;";
            $array = Db::Go()->rawQuery($sql, array(1, 1))->run();
            
            $tpl->featured = ($array) ? array_slice($array, 0, 1) : null;
            $tpl->special = ($array) ? array_slice($array, 1) : null;
            $tpl->catnav = $core->show_featured ? Content::categoryCounters() : null;
            
            $tpl->popular = $core->show_popular ?
                Db::Go()->select(Items::lTable, array("idx", "nice_title", "slug", "thumb", "year", "price", "sold"))
                    ->where("status", 1, "=")
                    ->where("featured", 1, "=")
                    ->orderBy("created", "DESC")
                    ->limit(App::Core()->featured)
                    ->run() :
                null;
            $tpl->brands = $core->show_brands ? App::Items()->getBrands() : null;
            $tpl->reviews = $core->show_reviews ? App::Content()->getReviews() : null;
            
            $tpl->keywords = $tpl->home->keywords;
            $tpl->description = $tpl->home->description;
            $tpl->template = 'front/themes/' . $core->theme . '/index.tpl.php';
        }
        
        /**
         * Front::Login()
         *
         * @return void
         */
        public function Login()
        {
            if (App::Auth()->is_User()) {
                Url::redirect(URL::url('/dashboard'));
                exit;
            }
            $core = App::Core();
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "front/themes/" . $core->theme . "/";
            $tpl->template = 'front/themes/' . $core->theme . '/login.tpl.php';
            $tpl->crumbs = [array(0 => Lang::$word->HOME, 1 => ''), Lang::$word->LOGIN];
            $tpl->title = Lang::$word->LOGIN . ' - ' . $core->company;
            $tpl->keywords = '';
            $tpl->description = '';
        }
        
        /**
         * Front::Register()
         *
         * @return void
         */
        public function Register()
        {
            if (App::Auth()->is_User()) {
                Url::redirect(URL::url('/dashboard'));
                exit;
            }
            $core = App::Core();
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "front/themes/" . $core->theme . "/";
            
            $tpl->clist = $core->enable_tax ? App::Content()->getCountryList() : null;
            
            $tpl->template = 'front/themes/' . $core->theme . '/register.tpl.php';
            $tpl->crumbs = [array(0 => Lang::$word->HOME, 1 => ''), Lang::$word->META_M_REGISTER];
            $tpl->title = Lang::$word->META_M_REGISTER . ' - ' . $core->company;
            $tpl->keywords = '';
            $tpl->description = '';
        }
        
        /**
         * Front::Registration()
         *
         * @return void
         * @throws \PHPMailer\PHPMailer\Exception
         */
        public function Registration()
        {
            $validate = Validator::Run($_POST);
            $validate
                ->set("fname", Lang::$word->FNAME)->required()->string()->min_len(2)->max_len(60)
                ->set("lname", Lang::$word->LNAME)->required()->string()->min_len(2)->max_len(60)
                ->set("password", Lang::$word->PASSWORD)->required()->string()->min_len(8)->max_len(16)
                ->set("email", Lang::$word->EMAIL)->required()->email()
                ->set("agree", Lang::$word->PRIVACY)->required()->numeric()->min_len(8)->max_len(16)
                ->set("captcha", Lang::$word->CAPTCHA)->required()->numeric()->exact_len(5);
            
            if (App::Core()->enable_tax) {
                $validate
                    ->set("address", Lang::$word->ADDRESS)->required()->string()->min_len(3)->max_len(80)
                    ->set("city", Lang::$word->CITY)->required()->string()->min_len(2)->max_len(60)
                    ->set("zip", Lang::$word->ZIP)->required()->string()->min_len(3)->max_len(30)
                    ->set("state", Lang::$word->STATE)->required()->string()->min_len(2)->max_len(60)
                    ->set("country", Lang::$word->COUNTRY)->required()->string()->exact_len(2);
            }
            $safe = $validate->safe();
            
            if (App::Session()->get('wcaptcha') != $_POST['captcha']) {
                Message::$msgs['captcha'] = Lang::$word->CAPTCHA;
            }
            
            if (!empty($safe->email)) {
                if (Auth::emailExists($safe->email, "members"))
                    Message::$msgs['email'] = Lang::$word->EMAIL_R2;
            }
            
            
            if (empty(Message::$msgs)) {
                $hash = Auth::doHash($safe->password);
                $username = Utility::randomString();
                $core = App::Core();
                
                $data = array(
                    'username' => $username,
                    'email' => $safe->email,
                    'lname' => $safe->lname,
                    'fname' => $safe->fname,
                    'hash' => $hash,
                    'type' => "member",
                    'token' => Utility::randNumbers(),
                    'active' => "y",
                    'userlevel' => 1,
                );
                
                if (App::Core()->enable_tax) {
                    $data['address'] = $safe->address;
                    $data['city'] = $safe->city;
                    $data['state'] = $safe->state;
                    $data['zip'] = $safe->zip;
                    $data['country'] = $safe->country;
                }
                
                $last_id = Db::Go()->insert(Users::mTable, $data)->run();
                
                //User Email Notification
                $mailer = Mailer::sendMail();
                $subject = Lang::$word->M_ACCSUBJECT . ' ' . $data['fname'] . ' ' . $data['lname'];
                
                $html_message = Utility::getSnippets(BASEPATH . 'mailer/' . $core->lang . '/Member_Welcome_Message.tpl.php');
                
                $body = str_replace(array(
                    '[LOGO]',
                    '[FULLNAME]',
                    '[USERNAME]',
                    '[PASSWORD]',
                    '[DATE]',
                    '[COMPANY]',
                    '[LOGINURL]',
                    '[FB]',
                    '[TW]',
                    '[CEMAIL]',
                    '[SITEURL]'), array(
                    Utility::getLogo(),
                    $data['fname'] . ' ' . $data['lname'],
                    $safe->email,
                    $safe->password,
                    date('Y'),
                    $core->company,
                    Url::url("/login"),
                    $core->social->facebook,
                    $core->social->twitter,
                    $core->site_email,
                    SITEURL), $html_message);
                
                $mailer->setFrom($core->site_email, $core->company);
                $mailer->addAddress($safe->email, $data['fname'] . ' ' . $data['lname']);
                
                $mailer->isHTML();
                $mailer->Subject = $subject;
                $mailer->Body = $body;
                $mailer->send();
                
                //login user
                App::Auth()->userLogin($safe->email, $safe->password, false);
                $json['redirect'] = Url::url('/dashboard');
                
                if ($last_id) {
                    $json['type'] = 'success';
                    $json['title'] = Lang::$word->SUCCESS;
                    $json['message'] = Lang::$word->M_INFO07;
                } else {
                    $json['type'] = 'error';
                    $json['title'] = Lang::$word->ERROR;
                    $json['message'] = Lang::$word->M_INFO09;
                }
                print json_encode($json);
                
                if ($core->notify_admin and $core->notify_email) {
                    $mailer = Mailer::sendMail();
                    $html_message = Utility::getSnippets(BASEPATH . 'mailer/' . $core->lang . '/Admin_Notify_Registration.tpl.php');
                    $body = str_replace(array(
                        '[LOGO]',
                        '[CEMAIL]',
                        '[DATE]',
                        '[EMAIL]',
                        '[USERNAME]',
                        '[COMPANY]',
                        '[NAME]',
                        '[IP]',
                        '[FB]',
                        '[TW]',
                        '[SITEURL]'), array(
                        Utility::getLogo(),
                        $core->site_email,
                        date('Y'),
                        $safe->email,
                        $data['username'],
                        $core->company,
                        $data['fname'] . ' ' . $data['lname'],
                        Url::getIP(),
                        $core->social->facebook,
                        $core->social->twitter,
                        SITEURL), $html_message);
                    
                    $mailer->setFrom($core->site_email, $core->company);
                    $mailer->addAddress($core->notify_email, $core->company);
                    
                    $mailer->isHTML();
                    $mailer->Subject = Lang::$word->M_INFO08;
                    $mailer->Body = $body;
                    $mailer->send();
                }
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * Front::Dashboard()
         *
         * @return void
         */
        public function Dashboard()
        {
            if (!App::Auth()->is_User()) {
                Url::redirect(Url::url('/login'));
                exit;
            }
            
            $core = App::Core();
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "front/themes/" . $core->theme . "/";
            $tpl->template = 'front/themes/' . $core->theme . '/dashboard.tpl.php';
            $tpl->crumbs = [array(0 => Lang::$word->HOME, 1 => ''), Lang::$word->META_T1];
            $tpl->title = Lang::$word->META_T1 . ' - ' . $core->company;
            
            $tpl->data = Db::Go()->select(Content::msTable)->where("private", 1, "<")->where("active", 1, "=")->orderBy("price", "ASC")->run();
            $tpl->user = Db::Go()->select(Users::mTable, array("membership_id"))->where("id", App::Auth()->uid, "=")->first()->run();
            App::Auth()->membership_id = $tpl->user->membership_id;
            
            $tpl->mrow = App::Users()->getUserPackage();
            
            $tpl->keywords = '';
            $tpl->description = '';
        }
        
        /**
         * Front::Page()
         *
         * @param string $slug
         * @return void
         */
        public function Page($slug)
        {
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "front/themes/" . App::Core()->theme . "/";
            $tpl->title = str_replace("[COMPANY]", App::Core()->company, Lang::$word->META_WELCOME);
            $tpl->keywords = null;
            $tpl->description = null;
            
            if (!$row = Db::Go()->select(Content::pgTable)->where("slug", $slug, "=")->where("active", 1, "=")->first()->run()) {
                $tpl->template = 'front/themes/' . App::Core()->theme . '/404.tpl.php';
                if (DEBUG) {
                    Debug::AddMessage("errors", '<i>ERROR</i>', "Invalid page detected [front.class.php, ln.:" . __line__ . "] slug ['<b>" . $slug . "</b>']");
                }
            } else {
                $tpl->row = $row;
                if ($row->type == "faq") {
                    $tpl->questions = Db::Go()->select(Content::faqTable)->orderBy("sorting", "ASC")->run();
                }
                
                $tpl->title = Url::formatMeta($tpl->row->title);
                $tpl->keywords = $row->keywords;
                $tpl->description = $row->description;
                $tpl->crumbs = [array(0 => Lang::$word->HOME, 1 => ''), $row->title];
                $tpl->template = 'front/themes/' . App::Core()->theme . '/page.tpl.php';
            }
        }
        
        /**
         * Front::Sitemap()
         *
         * @return void
         */
        public function Sitemap()
        {
            
            $core = App::Core();
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "front/themes/" . $core->theme . "/";
            $tpl->title = str_replace("[COMPANY]", $core->company, Lang::$word->META_WELCOME);
            
            $tpl->data = Db::Go()->select(Items::lTable, array("slug", "idx", "nice_title"))->where("status", 1, "=")->orderBy("created", "DESC")->run();
            
            $tpl->keywords = $core->metakeys;
            $tpl->description = $core->metadesc;
            $tpl->template = 'front/themes/' . $core->theme . '/sitemap.tpl.php';
            
        }
        
        /**
         * Front::Packages()
         *
         * @return void
         */
        public function Packages()
        {
            
            $core = App::Core();
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "front/themes/" . $core->theme . "/";
            $tpl->crumbs = [array(0 => Lang::$word->HOME, 1 => ''), Lang::$word->HOME_SUB21];
            $tpl->title = Lang::$word->HOME_SUB21 . ' - ' . $core->company;
            
            $tpl->data = Db::Go()->select(Content::msTable)->where("private", 1, "<")->orderBy("price", "ASC")->run();
            
            $tpl->keywords = $core->metakeys;
            $tpl->description = $core->metadesc;
            $tpl->template = 'front/themes/' . $core->theme . '/packages.tpl.php';
            
        }
        
        /**
         * Front::Compare()
         *
         * @return void
         */
        public function Compare()
        {
            
            $core = App::Core();
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "front/themes/" . $core->theme . "/";
            
            $compareData = App::Session()->get('CDP_compare');
            $ids = $compareData ? array_keys($compareData) : null;
            
            $sql = "
              SELECT 
                li.model_name,
				li.make_name,
                li.condition_name,
                li.category_name,
                li.trans_name,
                li.fuel_name,
                li.color_name,
				l.id,
                l.thumb,
                l.idx,
				l.doors,
                l.nice_title,
                l.title,
                l.slug,
                l.price,
                l.price_sale,
                l.year,
                l.sold,
				l.engine,
				l.top_speed,
                l.mileage,
				l.features
              FROM
                `" . Items::lTable . "` AS l 
                LEFT JOIN `" . Items::liTable . "` AS li 
                  ON l.id = li.listing_id 
              WHERE l.id IN(" . Utility::implodeFields($ids) . ") 
			  AND li.lstatus = ? 
			  LIMIT 4";
            
            $tpl->data = $ids ? Db::Go()->rawQuery($sql, array(1))->run() : null;
            $tpl->features = Db::Go()->select(Content::fTable)->orderBy("sorting", "ASC")->run();
            
            $tpl->template = 'front/themes/' . $core->theme . '/compare.tpl.php';
            $tpl->crumbs = [array(0 => Lang::$word->HOME, 1 => ''), Lang::$word->HOME_SUB21];
            $tpl->title = Lang::$word->HOME_SUB21 . ' - ' . $core->company;
            $tpl->keywords = $core->metakeys;
            $tpl->description = $core->metadesc;
        }
        
        /**
         * Front::passReset()
         *
         * @return void
         * @throws \PHPMailer\PHPMailer\Exception
         */
        public function passReset()
        {
            $validate = Validator::Run($_POST);
            $validate->set("email", Lang::$word->EMAIL)->required()->email();
            $safe = $validate->safe();
            
            $json['type'] = 'error';
            $json['title'] = Lang::$word->ERROR;
            $json['message'] = Lang::$word->EMAIL_R5;
            
            if (!empty($safe->email)) {
                $row = Db::Go()->select(Users::mTable, array("email", "fname", "lname", "id"))
                    ->where("email", $safe->email, "=")
                    ->where("type", "member", "=")
                    ->where("active", "y", "=")
                    ->first()->run();
                if (!$row) {
                    Message::$msgs['fname'] = Lang::$word->EMAIL_R5;
                }
            }
            
            if (empty(Message::$msgs)) {
                $row = Db::Go()->select(Users::mTable, array("email", "fname", "lname", "id"))
                    ->where("email", $safe->email, "=")
                    ->where("type", "member", "=")
                    ->where("active", "y", "=")
                    ->first()->run();
                $mailer = Mailer::sendMail();
                $html_message = Utility::getSnippets(BASEPATH . 'mailer/' . Core::$language . '/Member_Pass_Reset.tpl.php');
                $subject = Lang::$word->M_ACCSUBJECT2 . ' ' . $row->fname . ' ' . $row->lname;
                $core = App::Core();
                $token = substr(md5(uniqid(rand(), true)), 0, 10);
                
                $body = str_replace(array(
                    '[LOGO]',
                    '[NAME]',
                    '[DATE]',
                    '[COMPANY]',
                    '[LINK]',
                    '[IP]',
                    '[FB]',
                    '[TW]',
                    '[CEMAIL]',
                    '[SITEURL]'), array(
                    Utility::getLogo(),
                    $row->fname . ' ' . $row->lname,
                    date('Y'),
                    $core->company,
                    Url::url('/password', $token),
                    Url::getIP(),
                    $core->social->facebook,
                    $core->social->twitter,
                    $core->site_email,
                    SITEURL), $html_message);
                
                $mailer->setFrom($core->site_email, $core->company);
                $mailer->addAddress($row->email, $row->fname . ' ' . $row->lname);
                
                $mailer->isHTML();
                $mailer->Subject = $subject;
                $mailer->Body = $body;
                
                Db::Go()->update(Users::mTable, array("token" => $token))->where("id", $row->id, "=")->run();
                
                if ($mailer->send()) {
                    $json['type'] = "success";
                    $json['title'] = Lang::$word->SUCCESS;
                    $json['message'] = Lang::$word->PASSWORD_RES_D;
                } else {
                    $json['message'] = $mailer->ErrorInfo;
                }
            }
            print json_encode($json);
        }
        
        /**
         * Front::Password()
         *
         * @param string $token
         * @return void
         */
        public function Password($token)
        {
            
            if (App::Auth()->is_User()) {
                Url::redirect(URL::url('/dashboard'));
                exit;
            }
            $core = App::Core();
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "front/themes/" . $core->theme . "/";
            $tpl->crumbs = [array(0 => Lang::$word->HOME, 1 => ''), Lang::$word->M_ACCSUBJECT2];
            $tpl->title = Lang::$word->M_ACCSUBJECT2 . ' - ' . $core->company;
            $tpl->keywords = '';
            $tpl->description = '';
            
            $urow = Db::Go()->select(Users::mTable, array("id", "CONCAT(fname,' ',lname) as name"))->where("token", $token, "=")->first()->run();
            $arow = Db::Go()->select(Users::aTable, array("id", "CONCAT(fname,' ',lname) as name"))->where("token", $token, "=")->first()->run();
            
            if (!$urow and !$arow) {
                $tpl->title = Lang::$word->META_ERROR;
                $tpl->template = 'front/themes/' . $core->theme . '/404.tpl.php';
                if (DEBUG) {
                    Debug::AddMessage("warnings", '<i>ERROR</i>', "Invalid token detected [front.class.php, ln.:" . __line__ . "] slug [" . $token . "]", "session");
                }
            } else {
                $tpl->row = ($urow ?: ($arow ?: null));
                $tpl->template = 'front/themes/' . $core->theme . '/password.tpl.php';
            }
        }
        
        /**
         * Front::passwordChange()
         *
         * @return void
         */
        public function passwordChange()
        {
            $validate = Validator::Run($_POST);
            $validate
                ->set("token", "Invalid Token")->required()->string()->exact_len(10)
                ->set("password", Lang::$word->NEWPASS)->required()->string()->min_len(8)->max_len(16);
            
            $safe = $validate->safe();
            
            $urow = Db::Go()->select(Users::mTable, array("id"))->where("token", $safe->token, "=")->first()->run();
            $arow = Db::Go()->select(Users::aTable, array("id"))->where("token", $safe->token, "=")->first()->run();
            
            if (!$urow and !$arow) {
                Message::$msgs['token'] = "Invalid Token.";
                $json['title'] = Lang::$word->ERROR;
                $json['message'] = "Invalid Token.";
                $json['type'] = 'error';
            }
            
            if (empty(Message::$msgs)) {
                $data = array(
                    'hash' => Auth::doHash($safe->password),
                    'token' => 0,
                );
                if ($urow) {
                    $table = Users::mTable;
                    $url = Url::url('/login');
                    $row = $urow;
                } else {
                    $table = Users::aTable;
                    $url = Url::url('/admin');
                    $row = $arow;
                }
                
                Db::Go()->update($table, $data)->where("id", $row->id, "=")->run();
                $json['type'] = "success";
                $json['title'] = Lang::$word->SUCCESS;
                $json['redirect'] = $url;
                $json['message'] = Lang::$word->ACC_PASS_CHANGE_OK;
                print json_encode($json);
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * Front::Profile()
         *
         * @return void
         */
        public function Profile()
        {
            if (!App::Auth()->is_User()) {
                Url::redirect(Url::url('/login'));
                exit;
            }
            $core = App::Core();
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "front/themes/" . $core->theme . "/";
            $tpl->template = 'front/themes/' . $core->theme . '/profile.tpl.php';
            $tpl->crumbs = [array(0 => Lang::$word->HOME, 1 => ''), Lang::$word->META_T1, Lang::$word->MY_ACCOUNT];
            $tpl->title = Lang::$word->MY_ACCOUNT . ' - ' . $core->company;
            $tpl->keywords = '';
            $tpl->description = '';
            
            $tpl->row = Db::Go()->select(Users::mTable)->where("id", App::Auth()->uid, "=")->first()->run();
            $tpl->clist = $core->enable_tax ? App::Content()->getCountryList() : null;
        }
        
        /**
         * Front::updateProfile()
         *
         * @return void
         */
        public function updateProfile()
        {
            $validate = Validator::Run($_POST);
            $validate
                ->set("fname", Lang::$word->FNAME)->required()->string()->min_len(2)->max_len(60)
                ->set("lname", Lang::$word->LNAME)->required()->string()->min_len(2)->max_len(60)
                ->set("email", Lang::$word->EMAIL)->required()->email();
            
            if (App::Core()->enable_tax) {
                $validate
                    ->set("address", Lang::$word->ADDRESS)->required()->string()->min_len(3)->max_len(80)
                    ->set("city", Lang::$word->CITY)->required()->string()->min_len(2)->max_len(60)
                    ->set("zip", Lang::$word->ZIP)->required()->string()->min_len(3)->max_len(30)
                    ->set("state", Lang::$word->STATE)->required()->string()->min_len(2)->max_len(60)
                    ->set("country", Lang::$word->COUNTRY)->required()->string()->exact_len(2);
            }
            
            $avatar = File::upload("avatar", 512000, "png,jpg");
            $safe = $validate->safe();
            
            if (empty(Message::$msgs)) {
                $data = array(
                    'email' => $safe->email,
                    'lname' => $safe->lname,
                    'fname' => $safe->fname,
                );
                
                if (App::Core()->enable_tax) {
                    $data['address'] = $safe->address;
                    $data['city'] = $safe->city;
                    $data['zip'] = $safe->zip;
                    $data['state'] = $safe->state;
                    $data['country'] = $safe->country;
                }
                
                if (!empty($_POST['password'])) {
                    $data['hash'] = Auth::doHash($_POST['password']);
                }
                
                if (!empty($_FILES['avatar']['name'])) {
                    $thumbPath = UPLOADS . "/avatars/";
                    if (Auth::$udata->avatar != "") {
                        File::deleteFile(UPLOADS . "/avatars/" . Auth::$udata->avatar);
                    }
                    $result = File::process($avatar, $thumbPath, "AVT_");
                    App::Auth()->avatar = App::Session()->set('avatar', $result['fname']);
                    $data['avatar'] = $result['fname'];
                }
                
                $affected = Db::Go()->update(Users::mTable, $data)->where("id", App::Auth()->uid, "=")->run();
                
                Message::msgReply($affected, 'success', Lang::$word->M_UPDATED);
                if ($affected) {
                    App::Auth()->email = App::Session()->set('email', $data['email']);
                    App::Auth()->fname = App::Session()->set('fname', $data['fname']);
                    App::Auth()->lname = App::Session()->set('lname', $data['lname']);
                    App::Auth()->name = App::Session()->set('name', $data['fname'] . ' ' . $data['lname']);
                    if (App::Core()->enable_tax) {
                        App::Auth()->country = App::Session()->set('country', $data['country']);
                    }
                }
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * Front::Validate()
         *
         * @return void
         */
        public function Validate()
        {
            if (!App::Auth()->is_User()) {
                Url::redirect(Url::url('/login'));
                exit;
            }
            
            $core = App::Core();
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "front/themes/" . $core->theme . "/";
            $tpl->template = 'front/themes/' . $core->theme . '/_validate.tpl.php';
            $tpl->crumbs = [array(0 => Lang::$word->HOME, 1 => ''), Lang::$word->META_T1, Lang::$word->META_M_VALIDATE];
            $tpl->title = Lang::$word->META_M_VALIDATE . ' - ' . $core->company;
            $tpl->keywords = '';
            $tpl->description = '';
        }
        
        /**
         * Front::History()
         *
         * @return void
         */
        public function History()
        {
            if (!App::Auth()->is_User()) {
                Url::redirect(Url::url('/login'));
                exit;
            }
            $core = App::Core();
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "front/themes/" . $core->theme . "/";
            $tpl->data = Stats::userHistory(App::Auth()->uid, 'expire');
            $tpl->totals = Stats::userTotals();
            $tpl->template = 'front/themes/' . $core->theme . '/history.tpl.php';
            $tpl->title = Lang::$word->ACC_PAYTRANS . ' - ' . $core->company;
            $tpl->keywords = '';
            $tpl->description = '';
            $tpl->crumbs = [array(0 => Lang::$word->HOME, 1 => ''), Lang::$word->META_T1, Lang::$word->ACC_PAYTRANS];
        }
        
        /**
         * Front::buyMembership()
         *
         * @return void
         */
        public function buyMembership()
        {
            
            if ($row = Db::Go()->select(Content::msTable)->where("id", Filter::$id, "=")->where("private", 1, "<")->first()->run()) {
                $gaterows = Db::Go()->select(Content::gwTable)->where("active", 1, "=")->run();
                
                if ($row->price == 0) {
                    $data = array(
                        'membership_id' => $row->id,
                        'mem_expire' => Date::calculateDays($row->id),
                    );
                    
                    Db::Go()->update(Users::mTable, $data)->where("id", App::Auth()->uid, "=")->run();
                    App::Auth()->membership_id = App::Session()->set('membership_id', $row->id);
                    App::Auth()->mem_expire = App::Session()->set('mem_expire', $data['mem_expire']);
                    
                    $json['message'] = Message::msgSingleOk(str_replace("[NAME]", $row->title, Lang::$word->M_INFO10), false);
                } else {
                    Db::Go()->delete(Content::xTable)->where("user_id", App::Auth()->uid, "=")->run();
                    $tax = Content::calculateTax();
                    $data = array(
                        'user_id' => App::Auth()->uid,
                        'membership_id' => $row->id,
                        'originalprice' => $row->price,
                        'tax' => Validator::sanitize($tax, "float"),
                        'totaltax' => Validator::sanitize($row->price * $tax, "float"),
                        'total' => $row->price,
                        'totalprice' => Validator::sanitize($tax * $row->price + $row->price, "float"),
                    );
                    
                    Db::Go()->insert(Content::xTable, $data)->run();
                    $cart = Content::getCart();
                    
                    $tpl = App::View(THEMEBASE . '/snippets/');
                    $tpl->row = $row;
                    $tpl->gateways = $gaterows;
                    $tpl->cart = $cart;
                    $tpl->template = 'loadSummary.tpl.php';
                    $json['message'] = $tpl->render();
                }
            } else {
                $json['type'] = "error";
            }
            print json_encode($json);
        }
        
        /**
         * Front::getCoupon()
         *
         * @return void
         */
        public function getCoupon()
        {
            $sql = "SELECT * FROM `" . Content::dcTable . "` WHERE FIND_IN_SET(" . Filter::$id . ", membership_id) AND code = ? AND active = ?";
            if ($row = Db::Go()->rawQuery($sql, array(Validator::sanitize($_POST['code'], "string"), 1))->first()->run()) {
                $row2 = Db::Go()->select(Content::msTable)->where("id", Filter::$id, "=")->first()->run();
                
                Db::Go()->delete(Content::xTable)->where("user_id", App::Auth()->uid, "=")->first()->run();
                $tax = Content::calculateTax();
                
                if ($row->type == "p") {
                    $disc = Validator::sanitize($row2->price / 100 * $row->discount, "float");
                } else {
                    $disc = Validator::sanitize($row->discount, "float");
                }
                $gtotal = Validator::sanitize($row2->price - $disc, "float");
                
                $data = array(
                    'user_id' => App::Auth()->uid,
                    'membership_id' => $row2->id,
                    'coupon_id' => $row->id,
                    'tax' => Validator::sanitize($tax, "float"),
                    'totaltax' => Validator::sanitize($gtotal * $tax, "float"),
                    'coupon' => $disc,
                    'total' => $gtotal,
                    'originalprice' => Validator::sanitize($row2->price, "float"),
                    'totalprice' => Validator::sanitize($tax * $gtotal + $gtotal, "float"),
                );
                Db::Go()->insert(Content::xTable, $data)->run();
                
                $json['type'] = "success";
                $json['is_full'] = $row->discount;
                $json['disc'] = "- " . Utility::formatMoney($disc);
                $json['tax'] = Utility::formatMoney($data['totaltax']);
                $json['gtotal'] = Utility::formatMoney($data['totalprice']);
            } else {
                $json['type'] = "error";
            }
            print json_encode($json);
        }
        
        /**
         * Front::activateCoupon()
         *
         * @return void
         */
        public function activateCoupon()
        {
            
            $cart = Content::getCart();
            if ($row = Db::Go()->select(Content::msTable)->where("id", $cart->mid, "=")->first()->run()) {
                // insert payemnt record
                $data = array(
                    'txn_id' => time(),
                    'membership_id' => $row->id,
                    'user_id' => App::Auth()->uid,
                    'amount' => $cart->total,
                    'coupon' => $cart->coupon,
                    'total' => $cart->totalprice,
                    'tax' => $cart->totaltax,
                    'currency' => App::Core()->currency,
                    'ip' => Url::getIP(),
                    'pp' => "Coupon",
                    'status' => 1,
                );
                $last_id = Db::Go()->insert(Content::txTable, $data)->run();
                
                //insert user membership
                $udata = array(
                    'transaction_id' => $last_id,
                    'user_id' => App::Auth()->uid,
                    'membership_id' => $row->id,
                    'expire' => Date::calculateDays($row->id),
                    'recurring' => 0,
                    'active' => 1,
                );
                
                //update user record
                $xdata = array(
                    'membership_id' => $row->id,
                    'mem_expire' => $udata['expire'],
                );
                
                Db::Go()->insert(Content::mhTable, $udata)->run();
                Db::Go()->update(Users::mTable, $xdata)->where("id", App::Auth()->uid, "=")->run();
                Db::Go()->delete(Content::xTable)->where("uid", App::Auth()->uid, "=")->run();
                
                $json['type'] = "success";
            } else {
                $json['type'] = "error";
            }
            print json_encode($json);
        }
        
        /**
         * Membership::selectGateway()
         *
         * @return void
         */
        public function selectGateway()
        {
            
            if ($cart = Content::getCart()) {
                $gateway = Db::Go()->select(Admin::gTable)->where("id", Filter::$id, "=")->where("active", 1, "=")->first()->run();
                $row = Db::Go()->select(Content::msTable)->where("id", $cart->membership_id, "=")->first()->run();
                $tpl = App::View(BASEPATH . 'gateways/' . $gateway->dir . '/');
                $tpl->cart = $cart;
                $tpl->gateway = $gateway;
                $tpl->row = $row;
                $tpl->template = 'form.tpl.php';
                $json['gateway'] = $gateway->name;
                $json['message'] = $tpl->render();
            } else {
                $json['message'] = Message::msgSingleError(Lang::$word->SYSERROR, false);
            }
            print json_encode($json);
        }
        
        /**
         * Front::MyListings()
         *
         * @return void
         */
        public function MyListings()
        {
            if (!App::Auth()->is_User()) {
                Url::redirect(Url::url('/login'));
                exit;
            }
            
            $core = App::Core();
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "front/themes/" . $core->theme . "/";
            $tpl->crumbs = [array(0 => Lang::$word->HOME, 1 => ''), Lang::$word->HOME_SUB40];
            $tpl->title = Lang::$word->HOME_SUB40 . ' - ' . $core->company;
            
            $tpl->data = Db::Go()->select(Items::lTable)->where("user_id", App::Auth()->uid, "=")->orderBy("created", "DESC")->run();
            
            $tpl->keywords = $core->metakeys;
            $tpl->description = $core->metadesc;
            $tpl->template = 'front/themes/' . $core->theme . '/mylistings.tpl.php';
            
        }
        
        /**
         * Front::NewListing()
         *
         * @return void
         */
        public function NewListing()
        {
            if (!App::Auth()->is_User()) {
                Url::redirect(Url::url('/login'));
                exit;
            }
            
            $core = App::Core();
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "front/themes/" . $core->theme . "/";
            $tpl->crumbs = [array(0 => Lang::$word->HOME, 1 => ''), Lang::$word->LST_ADD];
            $tpl->title = Lang::$word->LST_ADD . ' - ' . $core->company;
            
            App::Session()->set(App::Auth()->username . "_imgtoken", Utility::randNumbers(8));
            $tpl->row = App::Users()->getUserPackage();
            
            $tpl->features = Db::Go()->select(Content::fTable)->orderBy("sorting", "ASC")->run();
            $tpl->locations = Db::Go()->select(Content::lcTable, array("id", "name"))->where("user_id", App::Auth()->uid, "=")->run();
            $tpl->makes = Db::Go()->select(Content::mkTable, array("id", "name"))->run();
            $tpl->categories = Db::Go()->select(Content::ctTable, array("id", "name"))->run();
            $tpl->conditions = Db::Go()->select(Content::cdTable, array("id", "name"))->run();
            $tpl->transmissions = Db::Go()->select(Content::trTable, array("id", "name"))->run();
            $tpl->fuel = Db::Go()->select(Content::fuTable, array("id", "name"))->run();
            $tpl->colors = Content::colorList();
            
            $tpl->keywords = $core->metakeys;
            $tpl->description = $core->metadesc;
            $tpl->template = 'front/themes/' . $core->theme . '/newlisting.tpl.php';
            
        }
        
        /**
         * Front::Reviews()
         *
         * @return void
         */
        public function Reviews()
        {
            if (!App::Auth()->is_User()) {
                Url::redirect(Url::url('/login'));
                exit;
            }
            
            $core = App::Core();
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "front/themes/" . $core->theme . "/";
            $tpl->crumbs = [array(0 => Lang::$word->HOME, 1 => ''), Lang::$word->HOME_SUB40];
            $tpl->title = Lang::$word->HOME_SUB40 . ' - ' . $core->company;
            
            $tpl->keywords = $core->metakeys;
            $tpl->description = $core->metadesc;
            $tpl->template = 'front/themes/' . $core->theme . '/reviews.tpl.php';
            
        }
        
        /**
         * Front::addListing()
         *
         * @return void
         * @throws \PHPMailer\PHPMailer\Exception
         */
        public function addListing()
        {
            $validate = Validator::Run($_POST);
            $validate
                ->set("make_id", Lang::$word->LST_MAKE)->required()->numeric()
                ->set("model_id", Lang::$word->LST_MODEL)->required()->numeric()
                ->set("year", Lang::$word->LST_YEAR)->required()->numeric()->exact_len(4)
                ->set("location", Lang::$word->LST_ROOM)->required()->numeric()
                ->set("category", Lang::$word->LST_CAT)->required()->numeric()
                ->set("vcondition", Lang::$word->LST_COND)->required()->numeric()
                ->set("transmission", Lang::$word->LST_TRANS)->required()->numeric()
                ->set("mileage", Lang::$word->LST_ODM)->required()->numeric()
                ->set("fuel", Lang::$word->LST_FUEL)->required()->numeric()
                ->set("doors", Lang::$word->LST_DOORS)->required()->numeric()
                ->set("expire_submit", Lang::$word->EXPIRE)->required()->date()
                ->set("price", Lang::$word->LST_PRICE)->required()->float();
            
            $validate
                ->set("mileage", Lang::$word->LST_ODM)->numeric()
                ->set("torque", Lang::$word->LST_TORQUE)->string()
                ->set("color_e", Lang::$word->LST_INTC)->string()
                ->set("color_i", Lang::$word->LST_EXTC)->string()
                ->set("drive_train", Lang::$word->LST_COND)->string()
                ->set("engine", Lang::$word->LST_ENGINE)->string()
                ->set("top_speed", Lang::$word->LST_SPEED)->numeric()
                ->set("horse_power", Lang::$word->LST_POWER)->string()
                ->set("towing_capacity", Lang::$word->LST_TOWING)->string()
                ->set("vin", Lang::$word->LST_VIN)->string()
                ->set("title", Lang::$word->LST_NAME)->string()
                ->set("body", Lang::$word->LST_DESC)->text("basic")
                ->set("features", Lang::$word->LST_FEATURES)->one();
            
            if (empty($_FILES['thumb']['name'])) {
                Message::$msgs['thumb'] = Lang::$word->LST_IMAGE;
            }
            
            $thumb = File::upload("thumb", Items::MAXIMG, "png,jpg,jpeg,webp");
            
            $safe = $validate->safe();
            
            if (empty(Message::$msgs)) {
                $mid = Items::doTitle($safe->model_id);
                $user = App::Users()->getUserPackage();
                $core = App::Core();
                $data = array(
                    'user_id' => App::Auth()->uid,
                    'slug' => (empty($safe->title)) ? $safe->year . '-' . $mid : Url::doSeo($safe->title),
                    'nice_title' => ucwords(str_replace("-", " ", $mid)),
                    'idx' => Utility::randNumbers(),
                    'location' => $safe->location,
                    'vin' => $safe->vin,
                    'make_id' => $safe->make_id,
                    'model_id' => $safe->model_id,
                    'year' => $safe->year,
                    'vcondition' => $safe->vcondition,
                    'category' => $safe->category,
                    'mileage' => $safe->mileage,
                    'torque' => $safe->torque,
                    'price' => $safe->price,
                    'color_e' => $safe->color_e,
                    'color_i' => $safe->color_i,
                    'doors' => $safe->doors,
                    'fuel' => $safe->fuel,
                    'drive_train' => $safe->drive_train,
                    'engine' => $safe->engine,
                    'transmission' => $safe->transmission,
                    'top_speed' => $safe->top_speed,
                    'horse_power' => $safe->horse_power,
                    'features' => json_encode($safe->features),
                    'towing_capacity' => $safe->towing_capacity,
                    'body' => $safe->body ? Url::in_url($safe->body) : "",
                    'expire' => $user->membership_expire,
                    'status' => $core->autoapprove,
                    'is_owner' => 0,
                    'featured' => $user->featured
                );
                
                $datax['title'] = (empty($safe->title)) ? str_replace("-", " ", $data['slug']) : $safe->title;
                $temp_id = App::Session()->get(App::Auth()->username . "_imgtoken");
                
                File::makeDirectory(UPLOADS . "/listings/pics___temp___" . $temp_id . "/thumbs");
                
                //process thumb
                if (!empty($_FILES['thumb']['name'])) {
                    $thumbPath = UPLOADS . "/listings/";
                    $result = File::process($thumb, $thumbPath, false);
                    try {
                        $img = new Image($thumbPath . $result['fname']);
                        $img->bestFit(Items::THUMBW, Items::THUMBH)->save($thumbPath . 'thumbs/' . $result['fname']);
                    } catch (exception $e) {
                        Debug::AddMessage("errors", '<i>Error</i>', $e->getMessage(), "session");
                    }
                    $datax['thumb'] = $result['fname'];
                }
                
                $last_id = Db::Go()->insert(Items::lTable, array_merge($data, $datax))->run();
                
                //Add to listings info
                $make_name = Db::Go()->select(Content::mkTable, array("name"))->where("id", $data['make_id'], "=")->one()->run();
                $category_name = Db::Go()->select(Content::ctTable, array("name"))->where("id", $data['category'], "=")->one()->run();
                $location_name = Db::Go()->select(Content::lcTable)->where("id", $data['location'], "=")->first()->run();
                
                $idata = array(
                    'listing_id' => $last_id,
                    'make_name' => $make_name,
                    'make_slug' => Url::doSeo($make_name),
                    'model_name' => Db::Go()->select(Content::mdTable, array("name"))->where("id", $data['model_id'], "=")->one()->run(),
                    'location_name' => json_encode($location_name),
                    'location_slug' => Url::doSeo($location_name->name_slug),
                    'condition_name' => Db::Go()->select(Content::cdTable, array("name"))->where("id", $data['vcondition'], "=")->one()->run(),
                    'category_name' => $category_name,
                    'category_slug' => Url::doSeo($category_name),
                    'fuel_name' => Db::Go()->select(Content::fuTable, array("name"))->where("id", $data['fuel'], "=")->one()->run(),
                    'trans_name' => Db::Go()->select(Content::trTable, array("name"))->where("id", $data['transmission'], "=")->one()->run(),
                    'color_name' => $safe->color_e,
                    'feature_name' => Db::Go()->select(Content::fTable, array("name"))->where("id", Utility::implodeFields($safe->features), "IN")->orderBy("sorting", "ASC")->run('json'),
                    'price' => $safe->price,
                    'year' => $safe->year,
                    'mileage' => $safe->mileage,
                    'special' => 0,
                    'lstatus' => $core->autoapprove
                );
                
                Db::Go()->insert(Items::liTable, $idata);
                
                //process gallery
                if ($rows = Db::Go()->select(Items::gTable, array("id", "listing_id"))->where("listing_id", $temp_id, "=")->run()) {
                    $query = "UPDATE `" . Items::gTable . "` SET `listing_id` = CASE ";
                    $idlist = '';
                    foreach ($rows as $item):
                        $query .= " WHEN id = " . $item->id . " THEN " . $last_id;
                        $idlist .= $item->id . ',';
                    endforeach;
                    $idlist = substr($idlist, 0, -1);
                    $query .= "
						  END
						  WHERE id IN (" . $idlist . ")";
                    
                    Db::Go()->rawQuery($query)->run();
                    
                    $images = Db::Go()->select(Items::gTable)->where("listing_id", $last_id, "=")->orderBy("sorting", "ASC")->run('json');
                    Db::Go()->update(Items::lTable, array("gallery" => $images))->where("id", $last_id, "=")->run();
                }
                //rename temp folder
                File::renameDirectory(UPLOADS . "/listings/pics___temp___" . $temp_id, UPLOADS . "/listings/pics" . $last_id);
                
                if ($core->autoapprove) {
                    $count = Db::Go()->count(Items::lTable)->where("user_id", App::Auth()->uid, "=")->where("status", 1, "=")->run();
                    Db::Go()->update(Users::mTable, array("listings" => $count))->where("id", App::Auth()->uid, "=")->run();
                    // Add to core
                    Items::doCalc();
                    
                    $json['type'] = "success";
                    $json['title'] = Lang::$word->SUCCESS;
                    $json['message'] = Message::formatSuccessMessage($datax['title'], Lang::$word->HOME_LISTADD_OK);
                } else {
                    $json['type'] = "success";
                    $json['title'] = Lang::$word->SUCCESS;
                    $json['message'] = Message::formatSuccessMessage($datax['title'], Lang::$word->HOME_LISTADD_P);
                }
                $json['redirect'] = Url::url("/dashboard/mylistings");
                print json_encode($json);
                
                //Admin Email Notification
                if ($core->notify_admin and $core->notify_email) {
                    $mailer = Mailer::sendMail();
                    $subject = Lang::$word->M_ACCSUBJECT4 . ' ' . App::Auth()->name;
                    
                    $html_message = Utility::getSnippets(BASEPATH . 'mailer/' . $core->lang . '/Admin_Notify_Submission.tpl.php');
                    
                    $body = str_replace(array(
                        '[LOGO]',
                        '[USERNAME]',
                        '[NAME]',
                        '[EMAIL]',
                        '[LISTING]',
                        '[ID]',
                        '[IP]',
                        '[DATE]',
                        '[COMPANY]',
                        '[FB]',
                        '[TW]',
                        '[CEMAIL]',
                        '[SITEURL]'), array(
                        Utility::getLogo(),
                        App::Auth()->username,
                        App::Auth()->name,
                        App::Auth()->email,
                        $data['nice_title'],
                        $last_id,
                        Url::getIP(),
                        date('Y'),
                        $core->company,
                        $core->social->facebook,
                        $core->social->twitter,
                        $core->site_email,
                        SITEURL), $html_message);
                    
                    $mailer->setFrom($core->site_email, $core->company);
                    $mailer->addAddress($core->notify_email, $core->company);
                    
                    $mailer->isHTML();
                    $mailer->Subject = $subject;
                    $mailer->Body = $body;
                    $mailer->send();
                }
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * Front::contactSeller()
         *
         * @return void
         * @throws \PHPMailer\PHPMailer\Exception
         */
        public function contactSeller()
        {
            $validate = Validator::Run($_POST);
            $validate
                ->set("name", Lang::$word->NAME)->required()->string()->min_len(2)->max_len(60)
                ->set("message", Lang::$word->MESSAGE)->required()->string()->min_len(10)->max_len(200)
                ->set("email", Lang::$word->EMAIL)->required()->email()
                ->set("agree", Lang::$word->PRIVACY)->required()->numeric()
                ->set("location", Lang::$word->LOCATION)->required()->numeric()
                ->set("item_id", "Item ID")->required()->string()
                ->set("stock_id", Lang::$word->LST_STOCK)->required()->string();
            
            $safe = $validate->safe();
            
            if (empty(Message::$msgs)) {
                if ($row = Db::Go()->select(Content::lcTable)->where("id", $safe->location, "=")->first()->run()) {
                    $core = App::Core();
                    $html_message = Utility::getSnippets(BASEPATH . 'mailer/' . $core->lang . '/Seller_Contact_Request.tpl.php');
                    $subject = str_replace("[COMPANY]", $core->company, Lang::$word->HOME_MSG_CREQ);
                    $mailer = Mailer::sendMail();
                    
                    $body = str_replace(array(
                        '[LOGO]',
                        '[NAME]',
                        '[DATE]',
                        '[COMPANY]',
                        '[SENDER]',
                        '[ITEM_URL]',
                        '[STOCK]',
                        '[MESSAGE]',
                        '[FB]',
                        '[TW]',
                        '[CEMAIL]',
                        '[SITEURL]'), array(
                        Utility::getLogo(),
                        $row->name,
                        date('Y'),
                        $core->company,
                        $safe->name,
                        Url::url("/listing", $safe->item_id),
                        $safe->message,
                        $safe->stock_id,
                        $core->social->facebook,
                        $core->social->twitter,
                        $core->site_email,
                        SITEURL), $html_message);
                    
                    $mailer->setFrom($core->site_email, $core->company);
                    $mailer->addAddress($row->email, $row->name);
                    $mailer->addReplyTo($safe->email, $safe->name);
                    
                    $mailer->isHTML();
                    $mailer->Subject = $subject;
                    $mailer->Body = $body;
                    
                    if ($mailer->send()) {
                        $json['type'] = 'success';
                        $json['title'] = Lang::$word->SUCCESS;
                        $json['message'] = Lang::$word->HOME_MSG_SENTOK;
                    } else {
                        $json['type'] = 'error';
                        $json['title'] = Lang::$word->ERROR;
                        $json['message'] = Lang::$word->EMN_ALERT;
                    }
                    print json_encode($json);
                    
                } else {
                    Message::msgReply(false, 'error', Lang::$word->EMN_ALERT);
                }
            } else {
                Message::msgSingleStatus();
            }
        }
    }