<?php
    /**
     * Class Admin
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: admin.class.php, v1.00 2022-04-20 18:20:24 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
    
    
    class Admin
    {
        
        const gTable = "gateways";
        
        
        /**
         * Admin::Index()
         *
         * @return void
         */
        public function Index()
        {
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->counters = Stats::indexStats();
            $tpl->stats = Stats::indexSalesStats();
            $tpl->listings = Stats::listingsExpireMonth();
            $tpl->template = 'admin/index.tpl.php';
            $tpl->title = Lang::$word->META_T1;
        }
        
        /**
         * Admin::Account()
         *
         * @return void
         */
        public function Account()
        {
            
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->crumbs = ['admin', Lang::$word->ADM_MYACC];
            $tpl->template = 'admin/myaccount.tpl.php';
            $tpl->data = Db::Go()->select(Users::aTable)->where("id", App::Auth()->uid, "=")->first()->run();
            $tpl->title = Lang::$word->ADM_MYACC;
            
        }
        
        /**
         * Admin::Password()
         *
         * @return void
         */
        public function Password()
        {
            
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->crumbs = ['admin', array(0 => Lang::$word->ADM_MYACC, 1 => "myaccount"), Lang::$word->ADM_PASSCHANGE];
            $tpl->template = 'admin/mypassword.tpl.php';
            $tpl->title = Lang::$word->ADM_PASSCHANGE;
            
        }
        
        /**
         * Admin::updateAccount()
         *
         * @return void
         */
        public function updateAccount()
        {
            $validate = Validator::Run($_POST);
            $validate
                ->set("email", Lang::$word->EMAIL)->required()->email()
                ->set("fname", Lang::$word->FNAME)->required()->string()->min_len(2)->max_len(60)
                ->set("lname", Lang::$word->LNAME)->required()->string()->min_len(2)->max_len(60);
            
            $thumb = File::upload("avatar", 512000, "png,jpg,jpeg");
            $safe = $validate->safe();
            
            if (empty(Message::$msgs)) {
                $data = array(
                    'email' => $safe->email,
                    'lname' => $safe->lname,
                    'fname' => $safe->fname);
                
                if (!empty($_FILES['avatar']['name'])) {
                    $thumbPath = UPLOADS . "/avatars/";
                    if (Auth::$udata->avatar != "") {
                        File::deleteFile(UPLOADS . "/avatars/" . Auth::$udata->avatar);
                    }
                    $result = File::process($thumb, $thumbPath, "AVT_");
                    App::Auth()->avatar = App::Session()->set('avatar', $result['fname']);
                    $data['avatar'] = $result['fname'];
                }
                Db::Go()->update(Users::aTable, $data)->where("id", App::Auth()->uid, "=")->run();
                if (Db::Go()->affected()) {
                    App::Auth()->fname = App::Session()->set('fname', $data['fname']);
                    App::Auth()->lname = App::Session()->set('lname', $data['lname']);
                    App::Auth()->email = App::Session()->set('email', $data['email']);
                }
                $message = str_replace("[NAME]", "", Lang::$word->ACC_UPDATE_OK);
                Message::msgReply(Db::Go()->affected(), 'success', $message);
            } else {
                Message::msgSingleStatus();
            }
        }
        
        
        /**
         * Admin::updateAdminPassword()
         *
         * @return void
         */
        public function updateAdminPassword()
        {
            $validate = Validator::Run($_POST);
            $validate->set("password", Lang::$word->NEWPASS)->required()->string()->min_len(6)->max_len(20);
            $validate->set("password2", Lang::$word->CONPASS)->required()->string()->equals($_POST['password'])->min_len(6)->max_len(20);
            
            $safe = $validate->safe();
            
            if ($_POST['password'] != $_POST['password2']) {
                Message::$msgs['pass'] = Lang::$word->PASSWORD_R3;
            }
            
            if (empty(Message::$msgs)) {
                $data['hash'] = Auth::doHash($safe->password);
                
                Db::Go()->update(Users::aTable, $data)->where("id", App::Auth()->uid, "=")->run();
                Message::msgReply(Db::Go()->affected(), 'success', Lang::$word->ACC_PASS_OK);
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * Admin::passReset()
         *
         * @return void
         * @throws \PHPMailer\PHPMailer\Exception
         */
        public function passReset()
        {
            $validate = Validator::Run($_POST);
            $validate->set("email", Lang::$word->EMAIL)->required()->email();
            
            $safe = $validate->safe();
            
            if (!empty($safe->email)) {
                $row = Db::Go()->select(Users::aTable, array("email", "fname", "lname", "id"))->where("email", $safe->email, "=")->first()->run();
                if (!$row) {
                    Message::$msgs['email'] = Lang::$word->EMAIL_R5;
                    $json['title'] = Lang::$word->ERROR;
                    $json['message'] = Lang::$word->EMAIL_R5;
                    $json['type'] = 'error';
                }
            }
            
            
            if (empty(Message::$msgs)) {
                $mailer = Mailer::sendMail();
                $core = App::Core();
                $row = Db::Go()->select(Users::aTable, array("email", "fname", "lname", "id"))->where("email", $safe->email, "=")->first()->run();
                $subject = Lang::$word->M_ACCSUBJECT2 . ' ' . $row->fname . ' ' . $row->lname;
                $token = substr(md5(uniqid(rand(), true)), 0, 10);
                $html_message = Utility::getSnippets(BASEPATH . 'mailer/' . Core::$language . '/Admin_Pass_Reset.tpl.php');
                
                $body = str_replace(array(
                    '[LOGO]',
                    '[NAME]',
                    '[DATE]',
                    '[COMPANY]',
                    '[LINK]',
                    '[FB]',
                    '[TW]',
                    '[CEMAIL]',
                    '[IP]',
                    '[SITEURL]'), array(
                    Utility::getLogo(),
                    $row->fname . ' ' . $row->lname,
                    date('Y'),
                    $core->company,
                    Url::url('/password', $token),
                    $core->social->facebook,
                    $core->social->twitter,
                    $core->site_email,
                    Url::getIP(),
                    SITEURL), $html_message);
                
                $mailer->setFrom($core->site_email, $core->company);
                $mailer->addAddress($row->email, $row->fname . ' ' . $row->lname);
                
                $mailer->isHTML();
                $mailer->Subject = $subject;
                $mailer->Body = $body;
                
                if ($mailer->send()) {
                    $json['type'] = "success";
                    $json['title'] = Lang::$word->SUCCESS;
                    $json['message'] = Lang::$word->PASSWORD_RES_D;
                    print json_encode($json);
                    
                    Db::Go()->update(Users::aTable, array("token" => $token))->where("id", $row->id, "=")->run();
                }
            } else {
                $json['type'] = "error";
                $json['title'] = Lang::$word->ERROR;
                $json['message'] = Lang::$word->EMAIL_R5;
                print json_encode($json);
            }
        }
        
        /**
         * Admin::Gateways()
         *
         * @return void
         */
        public function Gateways()
        {
            
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->crumbs = ['admin', Lang::$word->GW_TITLE];
            $tpl->data = Db::Go()->select(Content::gwTable)->orderBy("name", "ASC")->run();
            $tpl->template = 'admin/gateways.tpl.php';
            $tpl->title = Lang::$word->GW_TITLE;
            
        }
        
        /**
         * Admin::GatewayEdit()
         *
         * @param $id
         * @return void
         */
        public function GatewayEdit($id)
        {
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->title = Lang::$word->GW_TITLE1;
            $tpl->crumbs = ['admin', "gateways", "edit"];
            
            if (!$row = Db::Go()->select(Content::gwTable)->where("id", $id, "=")->first()->run()) {
                $tpl->template = 'admin/error.tpl.php';
                $tpl->error = DEBUG ? "Invalid ID ($id) detected [admin.class.php, ln.:" . __line__ . "]" : Lang::$word->META_ERROR;
            } else {
                $tpl->row = $row;
                $tpl->template = 'admin/gateways.tpl.php';
            }
        }
        
        /**
         * Admin::processGateway()
         *
         * @return void
         */
        public function processGateway()
        {
            $validate = Validator::Run($_POST);
            $validate
                ->set("displayname", Lang::$word->GW_NAME)->required()->string()->min_len(3)->max_len(60)
                ->set("extra", Lang::$word->GW_NAME)->required()->string()
                ->set("extra2", Lang::$word->GW_NAME)->string()
                ->set("extra3", Lang::$word->GW_NAME)->string()
                ->set("live", Lang::$word->ACTIVE)->numeric()
                ->set("active", "ID")->numeric();
            
            $safe = $validate->safe();
            
            if (empty(Message::$msgs)) {
                $data = array(
                    'displayname' => $safe->displayname,
                    'extra' => $safe->extra,
                    'extra2' => $safe->extra2,
                    'extra3' => $safe->extra3,
                    'live' => $safe->live,
                    'active' => $safe->active,
                );
                
                Db::Go()->update(Content::gwTable, $data)->where("id", Filter::$id, "=")->run();
                Message::msgReply(Db::Go()->affected(), 'success', Message::formatSuccessMessage($data['displayname'], Lang::$word->GW_UPDATED));
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * Admin::Backup()
         *
         * @return void
         */
        public function Backup()
        {
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->dbdir = UPLOADS . '/backups/';
            $tpl->data = File::findFiles($tpl->dbdir, array('fileTypes' => array('sql'), 'returnType' => 'fileOnly'));
            $tpl->template = 'admin/backup.tpl.php';
            $tpl->title = Lang::$word->DBM_TITLE;
        }
        
        /**
         * Admin::System()
         *
         * @return void
         */
        public function System()
        {
            
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->crumbs = ['admin', Lang::$word->SYS_TITLE];
            $tpl->core = App::Core();
            $tpl->template = 'admin/system.tpl.php';
            $tpl->title = Lang::$word->SYS_TITLE;
        }
        
        /**
         * Admin::Permissions()
         *
         * @return void
         */
        public function Permissions()
        {
            
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->crumbs = ['admin', "permissions"];
            $tpl->data = App::Users()->getRoles();
            $tpl->template = 'admin/roles.tpl.php';
            $tpl->title = Lang::$word->ACC_TITLE4;
            
        }
        
        /**
         * Admin::Privileges()
         *
         * @param $id
         * @return void
         */
        public function Privileges($id)
        {
            
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->title = Lang::$word->ACC_TITLE5;
            $tpl->crumbs = ['admin', "roles", Lang::$word->ACC_TITLE5];
            
            if (!$row = Db::Go()->select(Users::rTable)->where("id", $id, "=")->first()->run()) {
                $tpl->template = 'admin/error.tpl.php';
                $tpl->error = DEBUG ? "Invalid ID ($id) detected [admin.class.php, ln.:" . __line__ . "]" : Lang::$word->META_ERROR;
            } else {
                $tpl->role = $row;
                $tpl->result = Utility::groupToLoop(App::Users()->getPrivileges($id), "type");
                $tpl->template = 'admin/roles.tpl.php';
            }
            
        }
        
        /**
         * Admin::Utilities()
         *
         * @return void
         */
        public function Utilities()
        {
            
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->crumbs = ['admin', Lang::$word->UTL_TITLE];
            
            
            $css = File::loadFile(THEMEBASE . "/css/color.css");
            $result = array();
            if ($css) {
                $data = str_replace(array(
                    ":root",
                    "{",
                    "}"), array(
                    "",
                    "",
                    ""), $css);
                $data = trim($data);
                $data = explode(PHP_EOL, $data);
                $data = array_map('trim', str_replace(array(
                    " ",
                    ";",
                    "--"), array(
                    "",
                    "",
                    ""), $data));
                foreach ($data as $colors) {
                    $row = explode(":", $colors);
                    $result[$row[0]] = $row[1];
                }
            }
            
            $tpl->colors = $result;
            
            $tpl->template = 'admin/utilities.tpl.php';
            $tpl->title = Lang::$word->ADM_UTL;
            
        }
        
        /**
         * Admin::Trash()
         *
         * @return void
         */
        public function Trash()
        {
            
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $data = Db::Go()->select(Core::txTable)->run();
            $tpl->data = Utility::groupToLoop($data, "type");
            $tpl->crumbs = ['admin', Lang::$word->TRS_TITLE2];
            $tpl->template = 'admin/trash.tpl.php';
            $tpl->title = Lang::$word->TRS_TITLE2;
            
        }
        
        /**
         * Admin::Transactions()
         *
         * @return void
         */
        public function Transactions()
        {
            
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->crumbs = ['admin', Lang::$word->TRX_TITLE];
            $data = Stats::getAllStats();
            $tpl->data = $data[0];
            $tpl->pager = $data[1];
            $tpl->template = 'admin/transactions.tpl.php';
            $tpl->title = Lang::$word->TRX_TITLE;
            
        }
    }