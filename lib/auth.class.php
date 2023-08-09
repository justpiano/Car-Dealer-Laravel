<?php
    /**
     * Authentication Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: auth.class.php, v1.00 2022-06-05 10:12:05 gewa Exp $
     */
    
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
    
    class Auth
    {
        
        public ?int $logged_in = 0;
        public ?int $uid = 0;
        public ?string $username = null;
        public string $sesid;
        public ?string $email = null;
        public ?string $name = null;
        public ?string $fullname = null;
        public ?string $fname = null;
        public ?string $lname = null;
        public ?string $country = null;
        public ?string $avatar = null;
        public ?string $usertype = null;
        public ?int $userlevel = 0;
        public ?int $membership_id = 0;
        public ?string $mem_expire;
        public ?string $lastlogin;
        public ?string $lastip;
        public ?array $acl = array();
        public $access = null;
        public static $userdata = array();
        public static $udata = array();
        
        const cost = 10;
        
        /**
         * Auth::__construct()
         *
         */
        public function __construct()
        {
            $this->logged_in = $this->loginCheck();
            
            if (!$this->logged_in) {
                $this->username = App::Session()->set('WCD_username', '');
                $this->sesid = sha1(session_id());
                $this->userlevel = 0;
            }
        }
        
        /**
         * Auth::loginCheck()
         *
         * @return bool
         */
        private function loginCheck()
        {
            $session = App::Session();
            if ($session->isExists('WCD_username') and $session->get('WCD_username') != "") {
                $this->uid = $session->get('userid');
                $this->username = $session->get('WCD_username');
                $this->email = $session->get('email');
                $this->fname = $session->get('fname');
                $this->lname = $session->get('lname');
                $this->name = $session->get('fname') . ' ' . $session->get('lname');
                $this->country = ($this->userlevel == 1) ? $session->get('country') : null;
                $this->avatar = $session->get('avatar');
                $this->lastlogin = $session->get('lastlogin');
                $this->lastip = $session->get('lastip');
                $this->sesid = sha1(session_id());
                $this->usertype = $session->get('type');
                $this->userlevel = $session->get('userlevel');
                $this->membership_id = ($this->userlevel == 1) ? $session->get('membership_id') : null;
                $this->mem_expire = ($this->userlevel == 1) ? $session->get('mem_expire') : null;
                $this->acl = ($this->is_Admin()) ? $session->get('acl') : null;
                $this->access = $session->get('access');
                self::$userdata = $session->get('userdata');
                self::$udata = $this;
                
                return true;
            } else {
                return false;
            }
        }
        
        /**
         * Auth::is_Admin()
         *
         * @return bool
         */
        public function is_Admin()
        {
            $level = array(9, 8, 7);
            return (in_array($this->userlevel, $level));
            
        }
        
        /**
         * Auth::is_User()
         *
         * @return bool
         */
        public function is_User()
        {
            $level = array(1);
            return (in_array($this->userlevel, $level) and $this->usertype == "member");
            
        }
        
        /**
         * Auth::adminLogin()
         *
         * @param mixed $username
         * @param mixed $password
         * @return void
         */
        public function adminLogin($username, $password)
        {
            $status = "n";
            if ($username == "" && $password == "") {
                $json['message'] = Lang::$word->LOGIN_R5;
            } else {
                $status = $this->checkStatus($username, $password);
                
                switch ($status) {
                    case "e":
                        $json['message'] = Lang::$word->LOGIN_R1;
                        break;
                    
                    case "b":
                        $json['message'] = Lang::$word->LOGIN_R2;
                        break;
                    
                    case "n":
                        $json['message'] = Lang::$word->LOGIN_R3;
                        break;
                    
                    case "t":
                        $json['message'] = Lang::$word->LOGIN_R4;
                        break;
                }
            }
            if (empty($json['message']) && $status == "y") {
                $session = App::Session();
                $row = $this->getUserInfo($username);
                $this->uid = $session->set('userid', $row->id);
                $this->username = $session->set('WCD_username', $row->username);
                $this->fullname = $session->set('fullname', $row->fname . ' ' . $row->lname);
                $this->fname = $session->set('fname', $row->fname);
                $this->lname = $session->set('lname', $row->lname);
                $this->email = $session->set('email', $row->email);
                $this->userlevel = $session->set('userlevel', $row->userlevel);
                $this->usertype = $session->set('type', $row->usertype);
                $this->avatar = $session->set('avatar', $row->avatar);
                $this->lastlogin = $session->set('lastlogin', Db::toDate());
                $this->lastip = $session->set('lastip', Url::getIP());
                
                $result = self::getAcl($row->usertype);
                $privileges = array();
                for ($i = 0; $i < count($result); $i++) {
                    $privileges[$result[$i]->code] = $result[$i]->active == 1;
                }
                $this->acl = $session->set('acl', $privileges);
                
                $data = array('lastlogin' => Db::toDate(), 'lastip' => Validator::sanitize(Url::getIP()));
                Db::Go()->update(Users::aTable, $data)->where("id", $row->id, "=")->run();
                self::$userdata = $session->set('userdata', $row);
                self::setUserCookies($session->get('WCD_username'), $session->get('fullname'), $session->get('avatar'));
                
                $json['type'] = "success";
                $json['title'] = Lang::$word->SUCCESS;
            } else {
                $json['type'] = "error";
                $json['title'] = Lang::$word->ERROR;
            }
            print json_encode($json);
        }
        
        /**
         * Auth::userLogin()
         *
         * @param mixed $username
         * @param mixed $password
         * @param bool $auto
         * @return void
         */
        public function userLogin($username, $password, $auto = true)
        {
            $json['type'] = "error";
            $json['title'] = Lang::$word->ERROR;
            $json['message'] = Lang::$word->LOGIN_R5;
            $status = "n";
            
            if ($username == "" && $password == "") {
                Message::$msgs['username'] = Lang::$word->LOGIN_R5;
            } else {
                $status = $this->checkStatus($username, $password, "members");
                
                switch ($status) {
                    case "e":
                        Message::$msgs['username'] = Lang::$word->LOGIN_R1;
                        $json['message'] = Lang::$word->LOGIN_R1;
                        break;
                    
                    case "b":
                        Message::$msgs['username'] = Lang::$word->LOGIN_R2;
                        $json['message'] = Lang::$word->LOGIN_R2;
                        break;
                    
                    case "n":
                        Message::$msgs['username'] = Lang::$word->LOGIN_R3;
                        $json['message'] = Lang::$word->LOGIN_R3;
                        break;
                    
                    case "t":
                        Message::$msgs['username'] = Lang::$word->LOGIN_R4;
                        $json['message'] = Lang::$word->LOGIN_R4;
                        break;
                }
            }
            if (empty(Message::$msgs) && $status == "y") {
                $session = App::Session();
                $row = $this->getUserInfo($username, "members");
                $this->uid = $session->set('userid', $row->id);
                $this->username = $session->set('WCD_username', $row->username);
                $this->fullname = $session->set('fullname', $row->fname . ' ' . $row->lname);
                $this->fname = $session->set('fname', $row->fname);
                $this->lname = $session->set('lname', $row->lname);
                $this->email = $session->set('email', $row->email);
                $this->userlevel = $session->set('userlevel', $row->userlevel);
                $this->usertype = $session->set('type', $row->usertype);
                $this->membership_id = $session->set('membership_id', $row->membership_id);
                $this->mem_expire = $session->set('mem_expire', $row->membership_expire);
                $this->avatar = $session->set('avatar', $row->avatar);
                $this->lastlogin = $session->set('lastlogin', Db::toDate());
                $this->lastip = $session->set('lastip', Url::getIP());
                
                $data = array('last_active' => Db::toDate(), 'lastip' => Validator::sanitize(Url::getIP()));
                Db::Go()->update(Users::mTable, $data)->where("id", $row->id, "=")->run();
                self::$userdata = $session->set('userdata', $row);
                self::setUserCookies($session->get('WCD_username'), $session->get('fullname'), $session->get('avatar'));
                
                $json['type'] = "success";
                $json['title'] = Lang::$word->SUCCESS;
            }
            if ($auto) {
                print json_encode($json);
            }
        }
        
        /**
         * Auth::checkStatus()
         *
         * @param mixed $username
         * @param mixed $pass
         * @param string $table
         * @return string|void
         */
        public function checkStatus($username, $pass, $table = "admin")
        {
            
            $username = Validator::sanitize($username, "string");
            $pass = Validator::sanitize($pass);
            $table = ($table == "admin") ? Users::aTable : Users::mTable;
            
            $row = Db::Go()->select($table, array("id", "hash", "active"))
                ->where("username", $username, "=")
                ->orWhere("email", $username, "=")
                ->first()->run();
            
            if (!$row)
                return "e";
            
            $valid_pass = password_verify($pass, $row->hash);
            
            if (!$valid_pass)
                return "e";
            
            switch ($row->active) {
                case "b":
                    return "b";
                    break;
                
                case "n":
                    return "n";
                    break;
                
                case "t":
                    return "t";
                    break;
                
                case "y" and $valid_pass == true:
                    if (password_needs_rehash($row->hash, PASSWORD_DEFAULT, array('cost' => self::cost))) {
                        $hash = self::doHash($pass);
                        
                        Db::Go()->update($table, array("hash" => $hash))->where("id", $row->id, "=")->run();
                    }
                    return "y";
                    break;
            }
            
        }
        
        /**
         * Auth::getUserInfo()
         *
         * @param mixed $username
         * @param string $table
         * @return int|mixed
         */
        public function getUserInfo($username, $table = "admin")
        {
            $username = Validator::sanitize($username, "string");
            $uTable = ($table == "admin") ? Users::aTable : Users::mTable;
            
            $row = Db::Go()->select($uTable)->where("username", $username, "=")->orWhere("email", $username, "=")->first()->run();
            
            return ($row) ?: 0;
        }
        
        /**
         * Auth::getAcl()
         *
         * @param mixed $role
         * @return array|false|string|null
         */
        public static function getAcl($role = '')
        {
            $sql = "
              SELECT
                p.code,
                p.name,
                p.description,
                rp.active
              FROM `" . Users::rpTable . "` rp
                INNER JOIN `" . Users::rTable . "` r
                  ON rp.rid = r.id
                INNER JOIN `" . Users::pTable . "` p
                  ON rp.pid = p.id
              WHERE r.code = ? ;";
            
            return Db::Go()->rawQuery($sql, array($role))->run();
            
        }
        
        /**
         * Auth::hasPrivileges()
         *
         * @param mixed $code
         * @param mixed $val
         * @return bool
         */
        public static function hasPrivileges($code = '', $val = '')
        {
            $privileges_info = App::Session()->get('acl');
            if (!empty($val)) {
                if (isset($privileges_info[$code]) && is_array($privileges_info[$code])) {
                    return in_array($val, $privileges_info[$code]);
                } else {
                    return ($privileges_info[$code] == $val);
                }
            } else {
                return isset($privileges_info[$code]) && $privileges_info[$code] == true;
            }
        }
        
        /**
         * Auth::logout()
         *
         * @return void
         */
        public function logout()
        {
            App::Session()->endSession();
            $this->logged_in = false;
            $this->username = "Guest";
            $this->userlevel = 0;
        }
        
        /**
         * Auth::usernameExists()
         *
         * @param mixed $username
         * @param string $table
         * @return false|int
         */
        public static function usernameExists($username, $table = "admin")
        {
            $username = Validator::sanitize($username, "string");
            $table = $table == "admin" ? Users::aTable : Users::mTable;
            if (strlen($username) < 4)
                return 1;
            
            //Username should contain only alphabets, numbers, or underscores.Should be between 4 to 15 characters long
            $valid_uname = "/^[a-zA-Z0-9_]{4,15}$/";
            if (!preg_match($valid_uname, $username))
                return 2;
            
            $row = Db::Go()->select($table, array('username'))->where("username", $username, "=")->first()->run();
            
            return ($row) ? 3 : false;
        }
        
        /**
         * Auth::emailExists()
         *
         * @param $email
         * @param $table
         * @return bool
         */
        public static function emailExists($email, $table)
        {
            $table_name = ($table == "admin") ? Users::aTable : Users::mTable;
            $row = Db::Go()->select($table_name, array('email'))->where("email", $email, "=")->first()->run();
            
            return (bool)$row;
        }
        
        /**
         * Auth::checkAcl()
         *
         * @return bool
         */
        public static function checkAcl()
        {
            
            $access_types = func_get_args();
            foreach ($access_types as $type) {
                $args = explode(',', $type);
                if (in_array(App::Auth()->usertype, $args)) {
                    return true;
                }
            }
            return false;
        }
        
        /**
         * Auth::setUserCookies()
         *
         * @param mixed $username
         * @param mixed $name
         * @param mixed $avatar
         * @return void
         */
        public static function setUserCookies($username, $name, $avatar)
        {
            $avatar = empty($avatar) ? "blank.png" : $avatar;
            setcookie("WCD_loginData[0]", $username, strtotime('+30 days'));
            setcookie("WCD_loginData[1]", $name, strtotime('+30 days'));
            setcookie("WCD_loginData[2]", $avatar, strtotime('+30 days'));
        }
        
        /**
         * Auth::getUserCookies()
         *
         * @return array|false
         */
        public static function getUserCookies()
        {
            if (isset($_COOKIE['WCD_loginData'])) {
                return array(
                    'username' => $_COOKIE['WCD_loginData'][0],
                    'name' => $_COOKIE['WCD_loginData'][1],
                    'avatar' => $_COOKIE['WCD_loginData'][2]
                );
            } else {
                return false;
            }
            
        }
        
        /**
         * Auth::doHash()
         *
         * @param string $password
         */
        public static function doHash($password)
        {
            return password_hash($password, PASSWORD_DEFAULT, array('cost' => self::cost));
            
        }
        
        /**
         * Auth::generateToken()
         *
         * @param int $length
         * @return string
         */
        public static function generateToken($length = 24)
        {
            return bin2hex(openssl_random_pseudo_bytes($length));
            
        }
    }