<?php
    /**
     * Users Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: users.class.php, v1.00 2022-03-05 10:12:05 gewa Exp $
     */
    
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
    
    class Users
    {
        
        const mTable = "members";
        const aTable = "admins";
        const rTable = "roles";
        const rpTable = "role_privileges";
        const mxTable = "memberships";
        const pTable = "privileges";
        const blTable = 'banlist';
        const xTable = 'activity';
        const msTable = "memberships";
        
        
        /**
         * Users::__construct()
         *
         */
        public function __construct()
        {
        }
        
        /**
         * Users::Index()
         *
         * @return void
         */
        public function Index()
        {
            
            
            $enddate = (isset($_POST['enddate']) && $_POST['enddate'] <> "") ? Validator::sanitize(Db::toDate($_POST['enddate'], false)) : date("Y-m-d");
            $fromdate = isset($_POST['fromdate']) ? Validator::sanitize(Db::toDate($_POST['fromdate'], false)) : null;
            
            $find = isset($_GET['find']) ? Validator::sanitize($_GET['find'], "default", 30) : null;
            
            if (isset($_GET['letter']) and $find) {
                $letter = Validator::sanitize($_GET['letter'], 'string', 2);
                $counter = Db::Go()->count(self::mTable, "WHERE `created` BETWEEN '" . trim($fromdate) . "' AND '" . trim($enddate) . " 23:59:59' AND `fname` REGEXP '^" . $letter . "'")->run();
                $where = "WHERE `created` BETWEEN '" . trim($fromdate) . "' AND '" . trim($enddate) . " 23:59:59' AND `fname` REGEXP '^" . $letter . "'";
                
            } elseif (isset($_GET['find'])) {
                $counter = Db::Go()->count(self::mTable, "WHERE `fname` LIKE '%" . trim($find) . "%' OR `lname` LIKE '%" . trim($find) . "%'")->run();
                $where = "WHERE `fname` LIKE '%" . trim($find) . "%' OR `lname` LIKE '%" . trim($find) . "%'";
                
            } elseif (isset($_GET['letter'])) {
                $letter = Validator::sanitize($_GET['letter'], 'string', 2);
                $where = "WHERE `fname` REGEXP '^" . $letter . "'";
                $counter = Db::Go()->count(self::mTable, "WHERE `fname` REGEXP '^" . $letter . "' OR lname REGEXP '^" . $letter . "' LIMIT 1")->run();
            } else {
                $counter = Db::Go()->count(self::mTable)->run();
                $where = null;
            }
            
            if (isset($_GET['order']) and count(explode("|", $_GET['order'])) == 2) {
                $items = explode("|", $_GET['order']);
                $sort = Validator::sanitize($items[0], "default", 16);
                $order = Validator::sanitize($items[1], "default", 5);
                if (in_array($sort, array(
                    "fname",
                    "lname",
                    "email",
                    "membership_id",
                    "listings"))) {
                    $ord = ($order == 'DESC') ? " DESC" : " ASC";
                    $sorting = $sort . $ord;
                } else {
                    $sorting = " u.created DESC";
                }
            } else {
                $sorting = " u.created DESC";
            }
            
            $pager = Paginator::instance();
            $pager->items_total = $counter;
            $pager->default_ipp = App::Core()->perpage;
            $pager->path = Url::url(Router::$path, "?");
            $pager->paginate();
            
            $sql = "
              SELECT
                u.*,
                CONCAT(fname, ' ', lname) AS fullname,
                mx.title as mtitle
              FROM
                `" . self::mTable . "`  AS u
                LEFT JOIN `" . self::msTable . "` AS mx
                  ON mx.id = u.membership_id
              $where
              ORDER BY $sorting" . $pager->limit;
            
            $data = Db::Go()->rawQuery($sql)->run();
            
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->template = 'admin/members.tpl.php';
            $tpl->title = Lang::$word->META_T2;
            $tpl->crumbs = ['admin', "members"];
            $tpl->pager = $pager;
            $tpl->data = $data;
        }
        
        /**
         * Users::Edit()
         *
         * @param int $id
         * @return void
         */
        public function Edit($id)
        {
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->title = Lang::$word->CL_SUB4;
            $tpl->crumbs = ['admin', "members", "edit"];
            
            if (!$row = Db::Go()->select(self::mTable)->where("id", $id, "=")->first()->run()) {
                $tpl->template = 'admin/error.tpl.php';
                $tpl->error = DEBUG ? "Invalid ID ($id) detected [users.class.php, ln.:" . __line__ . "]" : Lang::$word->META_ERROR;
            } else {
                $tpl->row = $row;
                $tpl->countries = Db::Go()->select(Content::cTable)->orderBy("sorting", "DESC")->run();
                $tpl->memberships = Db::Go()->select(self::mxTable)->orderBy("price", "ASC")->run();
                $tpl->template = 'admin/members.tpl.php';
            }
        }
        
        /**
         * Users::Save()
         *
         * @return void
         */
        public function Save()
        {
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->title = Lang::$word->CL_SUB2;
            $tpl->countries = Db::Go()->select(Content::cTable)->orderBy("sorting", "DESC")->run();
            $tpl->memberships = Db::Go()->select(self::mxTable)->orderBy("price", "ASC")->run();
            $tpl->template = 'admin/members.tpl.php';
        }
        
        /**
         * Users::processMember()
         *
         * @return void
         * @throws \PHPMailer\PHPMailer\Exception
         */
        public function processMember()
        {
            $validate = Validator::Run($_POST);
            $validate
                ->set("fname", Lang::$word->FNAME)->required()->string()->min_len(3)->max_len(60)
                ->set("lname", Lang::$word->LNAME)->required()->string()->min_len(3)->max_len(60)
                ->set("email", Lang::$word->EMAIL)->email()
                ->set("company", Lang::$word->COMPANY)->string()
                ->set("url", Lang::$word->WEBSITE)->url()
                ->set("membership_id", Lang::$word->CL_NOMEMBERSHIP)->numeric()
                ->set("comments", Lang::$word->CL_COMMENTS)->string()
                ->set("about", Lang::$word->M_ABOUT)->string()
                ->set("address", Lang::$word->ADDRESS)->required()->string()
                ->set("city", Lang::$word->CITY)->required()->string()
                ->set("state", Lang::$word->STATE)->required()->string()
                ->set("zip", Lang::$word->ZIP)->required()->string()
                ->set("country", Lang::$word->COUNTRY)->required()->string();
            
            
            if (Validator::post('extend_membership')) {
                $validate->set("mem_expire_submit", Lang::$word->CL_DATEEX)->date();
            }
            
            (Filter::$id) ? $this->_updateMember($validate) : $this->_addMember($validate);
        }
        
        /**
         * Users::_addMember()
         *
         * @param $validate
         * @return void
         * @throws \PHPMailer\PHPMailer\Exception
         */
        public function _addMember($validate)
        {
            $validate->set("password", Lang::$word->PASSWORD)->required()->string()->min_len(6)->max_len(20);
            $safe = $validate->safe();
            
            if (!empty($safe->email)) {
                if (Auth::emailExists($safe->email, "front"))
                    Message::$msgs['email'] = Lang::$word->EMAIL_R2;
            }
            
            if (empty(Message::$msgs)) {
                $hash = Auth::doHash($safe->password);
                $username = Utility::randomString();
                
                $data = array(
                    'username' => $username,
                    'email' => $safe->email,
                    'lname' => $safe->lname,
                    'fname' => $safe->fname,
                    'address' => $safe->address,
                    'city' => $safe->city,
                    'state' => $safe->state,
                    'zip' => $safe->zip,
                    'country' => $safe->country,
                    'hash' => $hash,
                    'company' => $safe->company,
                    'url' => $safe->url,
                    'about' => $safe->about,
                    'comments' => $safe->comments,
                );
                
                if ($_POST['membership_id'] > 0) {
                    $data['mem_expire'] = Content::calculateDays($safe->membership_id);
                    $data['membership_id'] = $safe->membership_id;
                }
                
                if (Validator::post('extend_membership')) {
                    $data['mem_expire'] = Db::toDate($safe->mem_expire_submit);
                }
                
                $last_id = Db::Go()->insert(self::mTable, $data)->run();
                
                if ($last_id) {
                    $message = Message::formatSuccessMessage($data['fname'] . ' ' . $data['lname'], Lang::$word->M_ADDED);
                    Message::msgReply(true, 'success', $message);
                    
                    if (Validator::post('notify') && intval($_POST['notify']) == 1) {
                        $core = App::Core();
                        $html_message = Utility::getSnippets(BASEPATH . 'mailer/' . $core->lang . '/Member_Welcome_Message.tpl.php');
                        $pass = Validator::cleanOut($safe->password);
                        $subject = Lang::$word->M_ACCSUBJECT . $data['fname'] . ' ' . $data['lname'];
                        $mailer = Mailer::sendMail();
                        
                        $body = str_replace(array(
                            '[LOGO]',
                            '[NAME]',
                            '[DATE]',
                            '[COMPANY]',
                            '[USERNAME]',
                            '[PASSWORD]',
                            '[LINK]',
                            '[FB]',
                            '[TW]',
                            '[CEMAIL]',
                            '[SITEURL]'), array(
                            Utility::getLogo(),
                            $data['fname'] . ' ' . $data['lname'],
                            date('Y'),
                            $core->company,
                            $data['email'],
                            $pass,
                            Url::url("/login"),
                            $core->social->facebook,
                            $core->social->twitter,
                            $core->site_email,
                            SITEURL), $html_message);
                        
                        $mailer->setFrom($core->site_email, $core->company);
                        $mailer->addAddress($data['email'], $data['fname'] . ' ' . $data['lname']);
                        
                        $mailer->isHTML();
                        $mailer->Subject = $subject;
                        $mailer->Body = $body;
                        
                        $mailer->send();
                    }
                } else {
                    $json['type'] = 'alert';
                    $json['title'] = Lang::$word->ALERT;
                    $json['message'] = Lang::$word->NOPROCCESS;
                    print json_encode($json);
                }
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * Users::_updateMember()
         *
         * @param $validate
         * @return void
         */
        public function _updateMember($validate)
        {
            $safe = $validate->safe();
            
            
            if (empty(Message::$msgs)) {
                $data = array(
                    'email' => $safe->email,
                    'lname' => $safe->lname,
                    'fname' => $safe->fname,
                    'address' => $safe->address,
                    'city' => $safe->city,
                    'state' => $safe->state,
                    'zip' => $safe->zip,
                    'country' => $safe->country,
                    'url' => $safe->url,
                    'about' => $safe->about,
                    'comments' => $safe->comments,
                );
                
                if (!empty($_POST['password'])) {
                    $data['hash'] = Auth::doHash($_POST['password']);
                }
                
                if (Validator::post('update_membership')) {
                    if ($_POST['membership_id'] > 0) {
                        $data['membership_expire'] = Content::calculateDays($safe->membership_id);
                        $data['membership_id'] = $safe->membership_id;
                    } else {
                        $data['membership_id'] = 0;
                    }
                }
                
                if (Validator::post('extend_membership')) {
                    $data['membership_expire'] = Db::toDate($safe->mem_expire_submit);
                }
                
                Db::Go()->update(self::mTable, $data)->where("id", Filter::$id, "=")->run();
                $message = Message::formatSuccessMessage($data['fname'] . ' ' . $data['lname'], Lang::$word->M_UPDATED);
                Message::msgReply(Db::Go()->affected(), 'success', $message);
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * Users::StaffIndex()
         *
         * @return void
         */
        public function StaffIndex()
        {
            $where = match (App::Auth()->usertype) {
                "owner" => 'WHERE (usertype = \'staff\' || usertype = \'editor\' || usertype = \'manager\')',
                "staff" => 'WHERE (usertype = \'editor\' || usertype = \'manager\')',
                "manager" => 'WHERE (usertype = \'editor\')',
                default => null,
            };
            
            $sql = "
              SELECT *, CONCAT(fname,' ',lname) as fullname
              FROM   `" . self::aTable . "`
              $where
              ORDER BY created DESC";
            
            $data = Db::Go()->rawQuery($sql)->run();
            
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->data = $data;
            $tpl->title = Lang::$word->M_TITLE6;
            $tpl->template = 'admin/staff.tpl.php';
        }
        
        /**
         * Users::StaffEdit()
         *
         * @param int $id
         * @return void
         */
        public function StaffEdit($id)
        {
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->title = Lang::$word->M_SUB1;
            $tpl->crumbs = ['admin', "staff", "edit"];
            
            if (!$row = Db::Go()->select(self::aTable)->where("id", $id, "=")->first()->run()) {
                $tpl->template = 'admin/error.tpl.php';
                $tpl->error = DEBUG ? "Invalid ID ($id) detected [users.class.php, ln.:" . __line__ . "]" : Lang::$word->META_ERROR;
            } else {
                $tpl->row = $row;
                $tpl->template = 'admin/staff.tpl.php';
            }
        }
        
        /**
         * Users::StaffSave()
         *
         * @return void
         */
        public function StaffSave()
        {
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->title = Lang::$word->M_SUB2;
            $tpl->template = 'admin/staff.tpl.php';
        }
        
        /**
         * Users::processStaff()
         *
         * @return void
         * @throws \PHPMailer\PHPMailer\Exception
         */
        public function processStaff()
        {
            $validate = Validator::Run($_POST);
            $validate
                ->set("fname", Lang::$word->FNAME)->required()->string()->min_len(3)->max_len(60)
                ->set("lname", Lang::$word->LNAME)->required()->string()->min_len(3)->max_len(60)
                ->set("email", Lang::$word->EMAIL)->email()
                ->set("usertype", Lang::$word->TYPE)->required()->string();
            
            (Filter::$id) ? $this->_updateStaff($validate) : $this->_addStaff($validate);
        }
        
        /**
         * Users::_addStaff()
         *
         * @param $validate
         * @return void
         * @throws \PHPMailer\PHPMailer\Exception
         */
        public function _addStaff($validate)
        {
            $validate->set("password", Lang::$word->PASSWORD)->required()->string()->min_len(6)->max_len(20);
            $safe = $validate->safe();
            
            if (!empty($safe->email)) {
                if (Auth::emailExists($safe->email, "admin"))
                    Message::$msgs['email'] = Lang::$word->EMAIL_R2;
            }
            
            if (empty(Message::$msgs)) {
                $hash = Auth::doHash($safe->password);
                $username = Utility::randomString();
                
                $data = array(
                    'username' => $username,
                    'email' => $safe->email,
                    'lname' => $safe->lname,
                    'fname' => $safe->fname,
                    'hash' => $hash,
                    'usertype' => $safe->usertype,
                );
                
                $last_id = Db::Go()->insert(self::aTable, $data);
                
                if ($last_id) {
                    $message = Message::formatSuccessMessage($data['fname'] . ' ' . $data['lname'], Lang::$word->M_ADDED);
                    Message::msgReply(true, 'success', $message);
                    
                    if (Validator::post('notify') && intval($_POST['notify']) == 1) {
                        $core = App::Core();
                        $html_message = Utility::getSnippets(BASEPATH . 'mailer/' . $core->lang . '/Member_Welcome_Message.tpl.php');
                        $subject = Lang::$word->M_ACCSUBJECT . $data['fname'] . ' ' . $data['lname'];
                        $mailer = Mailer::sendMail();
                        
                        $body = str_replace(array(
                            '[LOGO]',
                            '[NAME]',
                            '[DATE]',
                            '[COMPANY]',
                            '[USERNAME]',
                            '[PASSWORD]',
                            '[LINK]',
                            '[FB]',
                            '[TW]',
                            '[CEMAIL]',
                            '[SITEURL]'), array(
                            Utility::getLogo(),
                            $data['fname'] . ' ' . $data['lname'],
                            date('Y'),
                            $core->company,
                            $data['email'],
                            $safe->password,
                            Url::url("/admin"),
                            $core->social->facebook,
                            $core->social->twitter,
                            $core->site_email,
                            SITEURL), $html_message);
                        
                        $mailer->setFrom($core->site_email, $core->company);
                        $mailer->addAddress($data['email'], $data['fname'] . ' ' . $data['lname']);
                        
                        $mailer->isHTML();
                        $mailer->Subject = $subject;
                        $mailer->Body = $body;
                        
                        $mailer->send();
                    }
                } else {
                    $json['type'] = 'alert';
                    $json['title'] = Lang::$word->ALERT;
                    $json['message'] = Lang::$word->NOPROCCESS;
                    print json_encode($json);
                }
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * Users::_updateStaff()
         *
         * @param $validate
         * @return void
         */
        public function _updateStaff($validate)
        {
            $safe = $validate->safe();
            
            if (empty(Message::$msgs)) {
                $data = array(
                    'email' => $safe->email,
                    'lname' => $safe->lname,
                    'fname' => $safe->fname,
                    'usertype' => $safe->usertype,
                );
                
                if (!empty($_POST['password'])) {
                    $data['hash'] = Auth::doHash($_POST['password']);
                }
                
                Db::Go()->update(self::aTable, $data)->where("id", Filter::$id, "=")->run();
                $message = Message::formatSuccessMessage($data['fname'] . ' ' . $data['lname'], Lang::$word->M_UPDATED);
                Message::msgReply(Db::Go()->affected(), 'success', $message);
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * Users::Active()
         *
         * @return void
         */
        public function Active()
        {
            
            $enddate = (isset($_POST['enddate']) && $_POST['enddate'] <> "") ? Validator::sanitize(Db::toDate($_POST['enddate'], false)) : date("Y-m-d");
            $fromdate = isset($_POST['fromdate']) ? Validator::sanitize(Db::toDate($_POST['fromdate'], false)) : null;
            
            $find = isset($_GET['find']) ? Validator::sanitize($_GET['find'], "default", 30) : null;
            
            if (isset($_GET['letter']) and $find) {
                $letter = Validator::sanitize($_GET['letter'], 'string', 2);
                $counter = Db::Go()->count(self::mTable . "` WHERE `created` BETWEEN '" . trim($fromdate) . "' AND '" . trim($enddate) . " 23:59:59' AND `fname` REGEXP '^" . $letter . "' AND membership_id > 0")->run();
                $and = "AND `created` BETWEEN '" . trim($fromdate) . "' AND '" . trim($enddate) . " 23:59:59' AND `fname` REGEXP '^" . $letter . "'";
                
            } elseif (isset($_GET['find'])) {
                $counter = Db::Go()->count(self::mTable, "WHERE `fname` LIKE '%" . trim($find) . "%' OR `lname` LIKE '%" . trim($find) . "%' AND membership_id > 0 LIMIT 1")->run();
                $and = "AND `fname` LIKE '%" . trim($find) . "%' OR `lname` LIKE '%" . trim($find) . "%'";
                
            } elseif (isset($_GET['letter'])) {
                $letter = Validator::sanitize($_GET['letter'], 'string', 2);
                $and = "AND `fname` REGEXP '^" . $letter . "'";
                $counter = Db::Go()->count(self::mTable, "WHERE `fname` REGEXP '^" . $letter . "' OR lname REGEXP '^" . $letter . "' AND membership_id > 0 LIMIT 1")->run();
            } else {
                $counter = Db::Go()->count(self::mTable, " WHERE membership_id <> 0 LIMIT 1")->run();
                $and = null;
            }
            
            if (isset($_GET['order']) and count(explode("|", $_GET['order'])) == 2) {
                $items = explode("|", $_GET['order']);
                $sort = Validator::sanitize($items[0], "default", 16);
                $order = Validator::sanitize($items[1], "default", 5);
                if (in_array($sort, array(
                    "fname",
                    "lname",
                    "email",
                    "membership_id",
                    "listings"))) {
                    $ord = ($order == 'DESC') ? " DESC" : " ASC";
                    $sorting = $sort . $ord;
                } else {
                    $sorting = " u.created DESC";
                }
            } else {
                $sorting = " u.created DESC";
            }
            
            $pager = Paginator::instance();
            $pager->items_total = $counter;
            $pager->default_ipp = App::Core()->perpage;
            $pager->path = Url::url(Router::$path, "?");
            $pager->paginate();
            
            $sql = "
              SELECT
                u.*,
                CONCAT(fname, ' ', lname) AS fullname,
                mx.title as mtitle
              FROM
                `" . self::mTable . "`  AS u
                LEFT JOIN `" . self::msTable . "` AS mx
                  ON mx.id = u.membership_id
                WHERE u.membership_id > 0
              $and
              ORDER BY $sorting" . $pager->limit;
            
            $data = Db::Go()->rawQuery($sql)->run();
            
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->template = 'admin/members.tpl.php';
            $tpl->title = Lang::$word->ADM_MEMS;
            $tpl->crumbs = ['admin', "members", Lang::$word->ADM_MEMS];
            $tpl->pager = $pager;
            $tpl->data = $data;
        }
        
        /**
         * Users::Payments()
         *
         * @param int $id
         * @return void
         */
        public function Payments($id)
        {
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->title = Lang::$word->ACC_PAYTRANS;
            $tpl->crumbs = ['admin', "members", Lang::$word->ACC_PAYTRANS];
            
            if (!$row = Db::Go()->select(self::mTable, array("id", "CONCAT(fname,' ',lname) as fullname"))->where("id", $id, "=")->first()->run()) {
                $tpl->template = 'admin/error.tpl.php';
                $tpl->error = DEBUG ? "Invalid ID ($id) detected [users.class.php, ln.:" . __line__ . "]" : Lang::$word->META_ERROR;
            } else {
                $tpl->row = $row;
                $tpl->data = $this->userTransactions($id);
                $tpl->template = 'admin/members.tpl.php';
            }
        }
        
        /**
         * Users::Activity()
         *
         * @param int $id
         * @return void
         */
        public function Activity($id)
        {
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->title = Lang::$word->CL_TITLE6;
            $tpl->crumbs = ['admin', "members", Lang::$word->CL_TITLE6];
            
            $columns = array("id", "avatar", "email", "about", "lastip", "created", "listings", "CONCAT(fname,' ',lname) as fullname");
            if (!$row = Db::Go()->select(self::mTable, $columns)->where("id", $id, "=")->first()->run()) {
                $tpl->template = 'admin/error.tpl.php';
                $tpl->error = DEBUG ? "Invalid ID ($id) detected [users.class.php, ln.:" . __line__ . "]" : Lang::$word->META_ERROR;
            } else {
                $tpl->row = $row;
                $tpl->data = $this->getUserActivity($id);
                $tpl->template = 'admin/members.tpl.php';
            }
        }
        
        /**
         * Users::Listings()
         *
         * @param int $id
         * @return void
         */
        public function Listings($id)
        {
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->title = Lang::$word->CL_TITLE5;
            $tpl->crumbs = ['admin', "members", Lang::$word->CL_TITLE5];
            
            $columns = array("id", "avatar", "email", "about", "lastip", "created", "listings", "CONCAT(fname,' ',lname) as fullname");
            if (!$row = Db::Go()->select(self::mTable, $columns)->where("id", $id, "=")->first()->run()) {
                $tpl->template = 'admin/error.tpl.php';
                $tpl->error = DEBUG ? "Invalid ID ($id) detected [users.class.php, ln.:" . __line__ . "]" : Lang::$word->META_ERROR;
            } else {
                $tpl->row = $row;
                $tpl->data = $this->getUserItems($id);
                $tpl->template = 'admin/members.tpl.php';
            }
        }
        
        /**
         * Users::getUserActivity()
         *
         * @param bool $id
         * @return array|int|string
         */
        public function getUserActivity($id = false)
        {
            $user_id = ($id) ?: Filter::$id;
            $row = Db::Go()->select(self::xTable)->where("user_id", $user_id, "=")->orderBy("created", "DESC")->run();
            
            return ($row) ?: 0;
            
        }
        
        /**
         * Users::getUserItems()
         *
         * @param bool $id
         * @return array|int|string
         */
        public function getUserItems($id = false)
        {
            $user_id = ($id) ?: Filter::$id;
            $row = Db::Go()->select(Items::lTable)->where("user_id", $user_id, "=")->orderBy("created", "DESC")->run();
            
            return ($row) ?: 0;
            
        }
        
        /**
         * Users::userTransactions()
         *
         * @param $id
         * @return array|int|string
         */
        public function userTransactions($id)
        {
            
            $sql = "
              SELECT
                p.*,
                p.id AS id,
                m.id AS mid,
                m.title
              FROM
                `" . Content::txTable . "` AS p
                LEFT JOIN `" . self::msTable . "` AS m
                  ON m.id = p.membership_id
              WHERE user_id = ?
              ORDER BY p.created DESC";
            
            $row = Db::Go()->rawQuery($sql, array($id))->run();
            return ($row) ?: 0;
        }
        
        /**
         * Users::userInvoice()
         *
         * @param int $pid
         * @param int $uid
         * @return int|mixed
         */
        public function userInvoice($pid, $uid)
        {
            $sql = "
              SELECT
                p.*,
                DATE_FORMAT(p.created,'%Y%m%d') as invid,
                m.title,
                m.description,
                CONCAT(u.fname, ' ', u.lname) AS fullname,
                u.company,
                u.email,
                u.address,
                u.city,
                u.state,
                u.zip,
                u.phone,
                c.name AS country
              FROM `" . Content::txTable . "` AS p
                LEFT JOIN `" . Content::msTable . "` as m
                  ON m.id = p.membership_id
                LEFT JOIN `" . self::mTable . "` as u
                  ON u.id = p.user_id
                LEFT JOIN `" . Content::cTable . "` as c
                  ON c.abbr = u.country
              WHERE p.id = ?
              AND p.user_id = ?
              AND p.status = 1";
            
            $row = Db::Go()->rawQuery($sql, array($pid, $uid))->first()->run();
            
            return ($row) ?: 0;
        }
        
        /**
         * Users::getRoles()
         *
         * @return array|int|string
         */
        public function getRoles()
        {
            
            $row = Db::Go()->select(self::rTable)->run();
            
            return ($row) ?: 0;
            
        }
        
        /**
         * Users::getPrivileges()
         *
         * @param $id
         * @return array|int|string
         */
        public function getPrivileges($id)
        {
            $sql = "
              SELECT
                rp.id,
                rp.active,
                p.id as prid,
                p.name,
                p.type,
                p.description,
                p.mode
              FROM `" . self::rpTable . "` as rp
                INNER JOIN `" . self::rTable . "` as r
                  ON rp.rid = r.id
                INNER JOIN `" . self::pTable . "` as p
                  ON rp.pid = p.id
              WHERE rp.rid = ?
              ORDER BY p.type;";
            
            $row = Db::Go()->rawQuery($sql, array($id))->run();
            
            return ($row) ?: 0;
            
        }
        
        /**
         * Users::updateRoleDescription()
         *
         * @return void
         */
        public static function updateRoleDescription()
        {
            $validate = Validator::Run($_POST);
            $validate
                ->set("name", Lang::$word->NAME)->required()->string()->min_len(2)->max_len(60)
                ->set("description", Lang::$word->DESCRIPTION)->required()->string()->min_len(2)->max_len(150);
            
            $safe = $validate->safe();
            
            if (empty(Message::$msgs)) {
                $data = array(
                    'name' => $safe->name,
                    'description' => $safe->description
                );
                
                Db::Go()->update(self::rTable, $data)->where("id", Filter::$id, "=")->run();
                Message::msgModalReply(Db::Go()->affected(), 'success', Lang::$word->ACC_ROLO_UPDATE_OK, Validator::truncate($data['description'], 80));
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * Users::quickMessage()
         *
         * @return void
         * @throws \PHPMailer\PHPMailer\Exception
         */
        public function quickMessage()
        {
            $validate = Validator::Run($_POST);
            $validate
                ->set("name", Lang::$word->CL_QMSG)->required()->string()->min_len(2)->max_len(200)
                ->set("id", "ID")->required()->numeric();
            
            $safe = $validate->safe();
            
            if (empty(Message::$msgs)) {
                $mailer = Mailer::sendMail();
                $core = App::Core();
                $row = Db::Go()->select(self::mTable, array('email', 'CONCAT(fname," ",lname) as name'))->where("id", Filter::$id, "=")->first()->run();
                
                $html_message = Utility::getSnippets(BASEPATH . 'mailer/' . $core->lang . '/Quick_Email_From_Admin.tpl.php');
                
                $newbody = str_replace(array(
                    '[COMPANY]',
                    '[LOGO]',
                    '[FULLNAME]',
                    '[MSG]',
                    '[DATE]',
                    '[FB]',
                    '[TW]',
                    '[CEMAIL]',
                    '[SITEURL]'), array(
                    $core->company,
                    Utility::getLogo(),
                    $row->name,
                    $safe->message,
                    date('Y'),
                    $core->social->facebook,
                    $core->social->twitter,
                    $core->site_email,
                    SITEURL), $html_message);
                
                $mailer->setFrom($core->site_email, $core->company);
                $mailer->addAddress($row->email, $row->name);
                
                $mailer->isHTML();
                $mailer->Subject = Lang::$word->EMN_NOTEFROM . ' ' . $core->company;
                $mailer->Body = $newbody;
                
                if ($mailer->send()) {
                    $json['type'] = 'success';
                    $json['title'] = Lang::$word->SUCCESS;
                    $json['message'] = Lang::$word->EMN_SENT;
                } else {
                    $json['type'] = 'error';
                    $json['title'] = Lang::$word->ERROR;
                    $json['message'] = $mailer->ErrorInfo;
                }
                print json_encode($json);
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * Users::getUserPackage()
         *
         * @return int|mixed
         */
        public function getUserPackage()
        {
            
            $sql = "
              SELECT
                u.membership_id, u.membership_expire, u.listings,
                m.title, m.listings as total, m.featured
              FROM
                `" . self::mTable . "` AS u
                LEFT JOIN `" . Content::msTable . "` AS m
                  ON m.id = u.membership_id
              WHERE u.id = ?;";
            $row = Db::Go()->rawQuery($sql, array(App::Auth()->uid))->first()->run();
            
            return ($row) ?: 0;
        }
        
        /**
         * Users::accountLevelToTypeLabel()
         *
         * @param mixed $level
         * @return string|void
         */
        public static function accountLevelToTypeLabel($level)
        {
            switch ($level) {
                case 9:
                    return '<span class="wojo small positive label">' . Lang::$word->OWNER . '</span>';
                
                case 8:
                    return '<span class="wojo small primary label">' . Lang::$word->LEV8_1 . '</span>';
                
                case 7:
                    return '<span class="wojo small secondary label">' . Lang::$word->LEV7 . '</span>';
                
                case 1:
                    return '<span class="wojo small black label">' . Lang::$word->LEV1 . '</span>';
            }
        }
        
        /**
         * Users::accountLevelToType()
         *
         * @param mixed $level
         * @return string|void
         */
        public static function accountLevelToType($level)
        {
            switch ($level) {
                case 9:
                    return '<span class="wojo small bold positive text">' . Lang::$word->OWNER . '</span>';
                
                case 8:
                    return '<span class="wojo small bold primary text">' . Lang::$word->LEV8_1 . '</span>';
                
                case 7:
                    return '<span class="wojo small bold secondary text">' . Lang::$word->LEV7 . '</span>';
                
                case 1:
                    return '<span class="wojo small bold black text">' . Lang::$word->LEV1 . '</span>';
            }
        }
    }