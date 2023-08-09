<?php
    /**
     * Session Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: session.class.php, v1.00 2022-01-20 18:20:24 gewa Exp $
     */
    
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
    
    
    class Session
    {
        
        protected $_defaultSessionName = 'wojo_framework';
        protected $_defaultSessionPrefix = 'wojo_';
        protected $_prefix = '';
        protected $_cookieMode = 'allow';
        
        
        /**
         * Session::__construct()
         *
         */
        public function __construct()
        {
            if ($this->_cookieMode !== 'only') {
                $this->_setCookieMode($this->_cookieMode);
            }
            
            $this->setSessionName('wojo_' . INSTALL_KEY);
            $this->_openSession();
        }
        
        
        /**
         * Session::set()
         *
         * @param mixed $name
         * @param mixed $value
         * @param mixed $cookie
         * @return void
         */
        public function set($name, $value, $cookie = false)
        {
            
            $cookie ? setcookie($name, $value, time() + 86400 * 300, '/') : $_SESSION[$this->_prefix . $name] = $value;
        }
        
        /**
         * Session::setKey()
         *
         * @param mixed $name
         * @param mixed $key
         * @param mixed $value
         * @return void
         */
        public function setKey($name, $key, $value)
        {
            
            $_SESSION[$this->_prefix . $name][$key] = $value;
        }
        
        /**
         * Session::get()
         *
         * @param mixed $name
         * @param string $default
         * @return mixed|string
         */
        public function get($name, $default = '')
        {
            return $_SESSION[$this->_prefix . $name] ?? $default;
        }
        
        /**
         * Session::getCookie()
         *
         * @param mixed $name
         * @return false|mixed
         */
        public static function getCookie($name)
        {
            return $_COOKIE[$name] ?? false;
        }
        
        /**
         * Session::remove()
         *
         * @param mixed $name
         * @return bool
         */
        public function remove($name)
        {
            if (isset($_SESSION[$this->_prefix . $name])) {
                unset($_SESSION[$this->_prefix . $name]);
                return true;
            }
            
            return false;
        }
        
        /**
         * Session::removeKey()
         *
         * @param mixed $name
         * @param mixed $key
         * @return bool
         */
        public function removeKey($name, $key)
        {
            if (isset($_SESSION[$this->_prefix . $name][$key])) {
                unset($_SESSION[$this->_prefix . $name][$key]);
                return true;
            }
            
            return false;
        }
        
        /**
         * Session::removeCookie()
         *
         * @param mixed $name
         * @return bool
         */
        public static function removeCookie($name)
        {
            if (isset($_COOKIE[$name])) {
                unset($_COOKIE[$name]);
                setcookie($name, '', time() - 3600, '/');
                return true;
            }
            
            return false;
        }
        
        /**
         * Session::cookieExists()
         *
         * @param mixed $name
         * @param mixed $value
         * @return bool
         */
        public static function cookieExists($name, $value)
        {
            return isset($_COOKIE[$name]) and $_COOKIE[$name] == $value;
        }
        
        /**
         * Session::cookieinArray()
         *
         * @param $value
         * @param $name
         * @param $json
         * @return bool
         */
        public static function cookieinArray($value, $name, $json = false)
        {
            if (isset($_COOKIE[$name])) {
                $array = $json ? json_decode($_COOKIE[$name]) : $_COOKIE[$name];
                return in_array($value, $array);
            } else {
                return false;
            }
        }
        
        /**
         * Session::isExists()
         *
         * @param mixed $name
         * @return bool
         */
        public function isExists($name)
        {
            return isset($_SESSION[$this->_prefix . $name]);
        }
        
        /**
         * Session::setSessionName()
         *
         * @param mixed $value
         * @return void
         */
        public function setSessionName($value)
        {
            if (empty($value))
                $value = $this->_defaultSessionName;
            
            session_name($value);
        }
        
        
        /**
         * Session::setSessionPrefix()
         *
         * @param mixed $value
         * @return void
         */
        public function setSessionPrefix($value)
        {
            if (empty($value))
                $value = $this->_defaultSessionPrefix;
            
            $this->_prefix = $value;
        }
        
        
        /**
         * Session::getSessionName()
         *
         * @return false|string
         */
        public static function getSessionName()
        {
            return session_name();
        }
        
        
        /**
         * Session::getTimeout()
         *
         * @return int
         */
        public static function getTimeout()
        {
            return (int)ini_get('session.gc_maxlifetime');
        }
        
        /**
         * Session::endSession()
         *
         * @return void
         */
        public function endSession()
        {
            if (session_id() !== '') {
                session_destroy();
            }
        }
        
        
        /**
         * Session::closeSession()
         *
         * @return true
         */
        public function closeSession()
        {
            return true;
        }
        
        /**
         * Session::captcha()
         *
         * @return mixed
         */
        public static function captcha()
        {
            
            App::Session()->set('wcaptcha', mt_rand(10000, 99999));
            return App::Session()->get('wcaptcha');
        }
        
        /**
         * Session::captchaAlt()
         *
         * @return mixed
         */
        public static function captchaAlt()
        {
            
            App::Session()->set('wacaptcha', mt_rand(10000, 99999));
            return App::Session()->get('wacaptcha');
        }
        
        /**
         * Session::getCookieMode()
         *
         * @return string
         */
        public function getCookieMode()
        {
            if (ini_get('session.use_cookies') === '0') {
                return 'none';
            } elseif (ini_get('session.use_only_cookies') === '0') {
                return 'allow';
            } else {
                return 'only';
            }
        }
        
        /**
         * Session::_openSession()
         *
         * @return void
         */
        private function _openSession()
        {
            if (strlen(session_id()) < 1) {
                session_start();
                //session_regenerate_id();
            }
            
            if (DEBUG && session_id() == '') {
                Debug::addMessage('errors', 'session', 'Failed to start session');
            }
        }
        
        /**
         * Session::_setCookieMode()
         *
         * @param string $value
         * @return void
         */
        private function _setCookieMode($value = '')
        {
            if ($value === 'none') {
                ini_set('session.use_cookies', '0');
                ini_set('session.use_only_cookies', '0');
            } elseif ($value === 'allow') {
                ini_set('session.use_cookies', '1');
                ini_set('session.use_only_cookies', '0');
            } elseif ($value === 'only') {
                ini_set('session.use_cookies', '1');
                ini_set('session.use_only_cookies', '1');
            } else {
                Debug::addMessage('warnings', 'session_cookie_mode', 'HttpSession.cookieMode can only be "none", "allow" or "only".');
            }
        }
        
    }