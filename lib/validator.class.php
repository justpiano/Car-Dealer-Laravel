<?php
    /**
     * Validator Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: validator.class.php, v1.00 2022-01-20 18:20:24 gewa Exp $
     */
    if (!defined("_WOJO")) {
        die('Direct access to this location is not allowed.');
    }
    
    
    class Validator
    {
        protected static $instance = null;
        public array $raw = array();
        public array $clean = array();
        protected array $_data = array();
        protected array $_errors = array();
        
        private bool $next = true;
        
        public static $trues = [true, "yes", "on"];
        public static $falses = [false, "no", "off"];
        
        public static $basic_tags = ["br", "p", "a", "strong", "b", "i", "em", "img", "blockquote", "code", "dd", "dl", "hr", "h1", "h2", "h3", "h4", "h5", "h6", "label", "ul", "li", "span", "sub", "sup", "u", "div"];
        
        public static $script_tags = ["script"];
        
        public static $advanced_tags = [
            "iframe", "body", "html", "section", "article", "video", "audio", "source", "div", "table", "td", "tr", "th", "tbody", "thead", "img", "svg", "figure", "br", "p", "a", "strong", "u", "b", "i", "em", "img", "pre", "blockquote", "code", "dd", "dl", "hr", "h1", "h2", "h3", "h4", "h5", "h6",
            "label", "ol", "ul", "li", "span", "sub", "sup", "button", "defs", "path", "clipPath", "use", "image", "style", "ellipse", "circle", "g", "polyline", "line", "rect", "polygon", "form", "input", "select", "option"
        ];
        
        /**
         * Validator::__construct()
         *
         */
        private function __construct($raw)
        {
            $this->raw = $raw;
        }
        
        /**
         * Validator::Run()
         *
         * @param $raw
         * @return Validator|null
         */
        public static function Run($raw)
        {
            if (self::$instance === null) {
                self::$instance = new self($raw);
            }
            return self::$instance;
        }
        
        /**
         * Validator::set()
         *
         * @param $value
         * @param $alias
         * @return Validator
         */
        public function set($value, $alias)
        {
            if (array_key_exists($value, $this->raw)) {
                $this->_data['value'] = $this->raw[$value];
                $this->next = true;
            } else {
                $this->_data['value'] = null;
                $this->next = false;
            }
            $this->_data['name'] = $value;
            $this->_data['text'] = $alias;
            return $this;
            
        }
        
        /**
         * Validator::required()
         *
         * @return Validator
         */
        public function required()
        {
            if (!self::exists() and ($this->_data['value'] !== "0" or $this->_data['value'] != 0)) {
                $this->_errors[$this->_data['name']] =
                Message::$msgs[$this->_data['name']] =
                    Lang::$word->FIELD_R0 . ' ' . $this->_data['text'] . ' ' . Lang::$word->FIELD_R100;
                $this->next = false;
            }
            return $this;
        }
        
        
        /**
         * Validator::exists()
         *
         * @return bool
         */
        private function exists()
        {
            
            if (!isset($this->_data['value']) or !$this->_data['value'] or empty($this->_data['value'])) {
                return false;
            }
            return true;
        }
        
        /**
         * Validator::Safe()
         *
         */
        public function safe()
        {
            return (object) $this->clean;
        }
        
        /**
         * Validator::string()
         *
         * @param bool $clean
         * @param bool $multiline
         * @return $this
         */
        public function string($clean = true, $multiline = false)
        {
            $pattern = $multiline ? '/(^&amp)*[<>%;`(){}]+/' : '/(^&amp)*[<>\n%;`(){}]+/';
            if ($this->next and !preg_match_all($pattern, $this->_data['value'] ?? "") === false) {
                $this->_errors[$this->_data['name']] =
                Message::$msgs[$this->_data['name']] =
                    Lang::$word->FIELD_R0 . ' ' . $this->_data['text'] . ' ' . Lang::$word->FIELD_R8;
                $this->next = false;
            } else {
                if ($clean) {
                    $this->clean[$this->_data['name']] = $this->_data['value'] = trim(htmlspecialchars($this->_data['value'] ?? "", ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'));
                }
            }
            
            return $this;
        }
        
        /**
         * Validator::email()
         *
         * @return Validator
         */
        public function email()
        {
            if ($this->exists()) {
                if ($this->next and filter_var($this->_data['value'], FILTER_VALIDATE_EMAIL) === false) {
                    $this->_errors[$this->_data['name']] =
                    Message::$msgs[$this->_data['name']] =
                        Lang::$word->FIELD_R0 . ' ' . $this->_data['text'] . ' ' . Lang::$word->FIELD_R18;
                    $this->next = false;
                }
            }
            $this->clean[$this->_data['name']] = $this->_data['value'];
            return $this;
        }
        
        /**
         * Validator::alpha()
         *
         * @param $type
         * @return $this
         */
        public function alpha($type = "full")
        {
            $pattern = $type == "full" ? '/^([a-zÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖßÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ])+$/i' : '/^([a-zA-Z])+$/i';
            if ($this->exists()) {
                if ($this->next and !preg_match($pattern, $this->_data['value'])) {
                    $this->_errors[$this->_data['name']] =
                    Message::$msgs[$this->_data['name']] =
                        Lang::$word->FIELD_R0 . ' ' . $this->_data['text'] . ' ' . Lang::$word->FIELD_R8;
                    $this->next = false;
                }
            }
            $this->clean[$this->_data['name']] = $this->_data['value'];
            return $this;
        }
        
        /**
         * Validator::alpha_numeric()
         *
         * @param $type
         * @return $this
         */
        public function alpha_numeric($type = "full")
        {
            $pattern = $type == "full" ? '/^([a-z0-9ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖßÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ])+$/i' : '/^([a-zA-Z0-9])+$/i';
            if ($this->exists()) {
                if ($this->next and !preg_match($pattern, $this->_data['value'])) {
                    $this->_errors[$this->_data['name']] =
                    Message::$msgs[$this->_data['name']] =
                        Lang::$word->FIELD_R0 . ' ' . $this->_data['text'] . ' ' . Lang::$word->FIELD_R9;
                    $this->next = false;
                }
            }
            $this->clean[$this->_data['name']] = $this->_data['value'];
            return $this;
        }
        
        /**
         * Validator::contains()
         * Set of chars in one string ex. "abc123"
         * or an array "abc|123|yes"
         *
         * @param $chars
         * @param $separator
         *
         * @return $this
         */
        public function contains($chars, $separator = false)
        {
            $str = "";
            if (!is_array($chars)) {
                if (!$separator or is_null($chars)) {
                    $str = $chars;
                    $chars = array();
                } else {
                    $chars = explode($separator, $chars);
                    $str = implode(', ', $chars);
                }
            }
            if ($this->exists()) {
                if ($this->next and !in_array($this->_data['value'], $chars)) {
                    $this->_errors[$this->_data['name']] =
                    Message::$msgs[$this->_data['name']] =
                        Lang::$word->FIELD_R0 . ' ' . $this->_data['text'] . ' ' . str_replace("[VAL]", $str, Lang::$word->FIELD_R11);
                    $this->next = false;
                }
            }
            $this->clean[$this->_data['name']] = $this->_data['value'];
            return $this;
        }
        
        /**
         * Validator::lowercase()
         *
         * @return Validator
         */
        public function lowercase()
        {
            if ($this->exists()) {
                if ($this->next and $this->_data['value'] !== mb_strtolower($this->_data['value'], mb_detect_encoding($this->_data['value']))) {
                    $this->_errors[$this->_data['name']] =
                    Message::$msgs[$this->_data['name']] =
                        Lang::$word->FIELD_R0 . ' ' . $this->_data['text'] . ' ' . Lang::$word->FIELD_R23;
                    $this->next = false;
                }
            }
            $this->clean[$this->_data['name']] = $this->_data['value'];
            return $this;
        }
        
        /**
         * Validator::uppercase()
         *
         * @return Validator
         */
        public function uppercase()
        {
            if ($this->exists()) {
                if ($this->next and $this->_data['value'] !== mb_strtoupper($this->_data['value'], mb_detect_encoding($this->_data['value']))) {
                    $this->_errors[$this->_data['name']] =
                    Message::$msgs[$this->_data['name']] =
                        Lang::$word->FIELD_R0 . ' ' . $this->_data['text'] . ' ' . Lang::$word->FIELD_R24;
                    $this->next = false;
                }
            }
            $this->clean[$this->_data['name']] = $this->_data['value'];
            return $this;
        }
        
        /**
         * Validator::min_len()
         *
         * @param int $value
         * @return Validator
         */
        public function min_len($value)
        {
            if ($this->exists()) {
                if ($this->next and mb_strlen($this->_data['value']) < $value) {
                    $this->_errors[$this->_data['name']] =
                    Message::$msgs[$this->_data['name']] =
                        Lang::$word->FIELD_R0 . ' ' . $this->_data['text'] . ' ' . str_replace("[X]", $value, Lang::$word->FIELD_R2);
                    $this->next = false;
                }
            }
            $this->clean[$this->_data['name']] = $this->_data['value'];
            return $this;
        }
        
        /**
         * Validator::max_len()
         *
         * @param int $value
         * @return Validator
         */
        public function max_len($value)
        {
            if ($this->exists()) {
                if ($this->next and mb_strlen($this->_data['value']) > $value) {
                    $this->_errors[$this->_data['name']] =
                    Message::$msgs[$this->_data['name']] =
                        Lang::$word->FIELD_R0 . ' ' . $this->_data['text'] . ' ' . str_replace("[X]", $value, Lang::$word->FIELD_R1);
                    $this->next = false;
                }
            }
            $this->clean[$this->_data['name']] = $this->_data['value'];
            return $this;
        }
        
        /**
         * Validator::exact_len()
         *
         * @param int $value
         * @return Validator
         */
        public function exact_len($value)
        {
            if ($this->exists()) {
                if ($this->next and mb_strlen($this->_data['value']) != $value) {
                    $this->_errors[$this->_data['name']] =
                    Message::$msgs[$this->_data['name']] =
                        Lang::$word->FIELD_R0 . ' ' . $this->_data['text'] . ' ' . str_replace("[X]", $value, Lang::$word->FIELD_R7);
                    $this->next = false;
                }
            }
            $this->clean[$this->_data['name']] = $this->_data['value'];
            return $this;
        }
        
        /**
         * Validator::numeric()
         *
         * @return $this
         */
        public function numeric()
        {
            if ($this->exists()) {
                if ($this->next and !is_numeric($this->_data['value'])) {
                    $this->_errors[$this->_data['name']] =
                    Message::$msgs[$this->_data['name']] =
                        Lang::$word->FIELD_R0 . ' ' . $this->_data['text'] . ' ' . Lang::$word->FIELD_R5;
                    $this->next = false;
                }
            }
            $this->clean[$this->_data['name']] = $this->_data['value'] = filter_var($this->_data['value'], FILTER_SANITIZE_NUMBER_INT);
            return $this;
        }
        
        /**
         * Validator::integer()
         *
         * @return $this
         */
        public function integer()
        {
            if ($this->exists()) {
                if ($this->next and !filter_var($this->_data['value'], FILTER_VALIDATE_INT)) {
                    $this->_errors[$this->_data['name']] =
                    Message::$msgs[$this->_data['name']] =
                        Lang::$word->FIELD_R0 . ' ' . $this->_data['text'] . ' ' . Lang::$word->FIELD_R6;
                    $this->next = false;
                }
            }
            $this->clean[$this->_data['name']] = $this->_data['value'] = intval($this->_data['value']);
            return $this;
        }
        
        /**
         * Validator::float()
         *
         * @return Validator
         */
        public function float()
        {
            if ($this->exists()) {
                if ($this->next and !is_float(filter_var($this->_data['value'], FILTER_VALIDATE_FLOAT))) {
                    $this->_errors[$this->_data['name']] =
                    Message::$msgs[$this->_data['name']] =
                        Lang::$word->FIELD_R0 . ' ' . $this->_data['text'] . ' ' . Lang::$word->FIELD_R19;
                    $this->next = false;
                }
            }
            $this->clean[$this->_data['name']] = $this->_data['value'] = filter_var($this->_data['value'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            return $this;
        }
        
        /**
         * Validator::boolean()
         *
         * @return Validator
         */
        public function boolean()
        {
            if ($this->next and !is_bool($this->_data['value'])) {
                $this->_errors[$this->_data['name']] =
                Message::$msgs[$this->_data['name']] =
                    Lang::$word->FIELD_R0 . ' ' . $this->_data['text'] . ' ' . Lang::$word->FIELD_R25;
                $this->next = false;
            }
            $this->clean[$this->_data['name']] = $this->_data['value'] = in_array($this->_data['value'], self::$trues, true);
            return $this;
        }
        
        /**
         * Validator::min_numeric()
         *
         * @param int $value
         * @return Validator
         */
        public function min_numeric($value)
        {
            if ($this->exists()) {
                if ($this->next and (intval($this->_data['value']) < $value)) {
                    $this->_errors[$this->_data['name']] =
                    Message::$msgs[$this->_data['name']] =
                        Lang::$word->FIELD_R0 . ' ' . $this->_data['text'] . ' ' . str_replace("[X]", $value, Lang::$word->FIELD_R5_1);
                    $this->next = false;
                }
            }
            $this->clean[$this->_data['name']] = $this->_data['value'];
            return $this;
        }
        
        /**
         * Validator::max_numeric()
         *
         * @param int $value
         * @return Validator
         */
        public function max_numeric($value)
        {
            if ($this->exists()) {
                if ($this->next and (intval($this->_data['value']) > $value)) {
                    $this->_errors[$this->_data['name']] =
                    Message::$msgs[$this->_data['name']] =
                        Lang::$word->FIELD_R0 . ' ' . $this->_data['text'] . ' ' . str_replace("[X]", $value, Lang::$word->FIELD_R5_2);
                    $this->next = false;
                }
            }
            $this->clean[$this->_data['name']] = $this->_data['value'];
            return $this;
        }
        
        /**
         * Validator::equals()
         *
         * @param int $value
         * @param bool $identical
         * @return Validator
         */
        public function equals($value, $identical = false)
        {
            $verify = ($identical === true ? $this->_data['value'] === $value : strtolower($this->_data['value']) == strtolower($value));
            if ($this->exists()) {
                if ($this->next and !$verify) {
                    $this->_errors[$this->_data['name']] =
                    Message::$msgs[$this->_data['name']] =
                        Lang::$word->FIELD_R0 . ' ' . $this->_data['text'] . ' ' . str_replace("[VAL]", $value, Lang::$word->FIELD_R13);
                    $this->next = false;
                }
            }
            $this->clean[$this->_data['name']] = $this->_data['value'];
            return $this;
        }
        
        /**
         * Validator::date()
         *
         * @return Validator
         */
        public function date()
        {
            if ($this->exists()) {
                $error = date_parse($this->_data['value']);
                if ($this->next and $error['warning_count'] == 1 or $error['error_count'] == 1) {
                    $this->_errors[$this->_data['name']] =
                    Message::$msgs[$this->_data['name']] =
                        Lang::$word->FIELD_R0 . ' ' . $this->_data['text'] . ' ' . Lang::$word->FIELD_R4;
                    $this->next = false;
                }
            }
            $this->clean[$this->_data['name']] = $this->_data['value'];
            return $this;
        }
        
        /**
         * Validator::time()
         *
         * @return Validator
         */
        public function time()
        {
            if ($this->exists()) {
                $error = preg_match("/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/", $this->_data['value']);
                if ($this->next and !$error) {
                    $this->_errors[$this->_data['name']] =
                    Message::$msgs[$this->_data['name']] =
                        Lang::$word->FIELD_R0 . ' ' . $this->_data['text'] . ' ' . Lang::$word->FIELD_R4_1;
                    $this->next = false;
                }
            }
            $this->clean[$this->_data['name']] = $this->_data['value'];
            return $this;
        }
        
        /**
         * Validator::path()
         *
         * @return Validator
         */
        public function path()
        {
            if ($this->exists()) {
                $pattern = '#^([a-z0-9_/: -.])+$#i';
                if ($this->next and !preg_match($pattern, $this->_data['value'])) {
                    $this->_errors[$this->_data['name']] =
                    Message::$msgs[$this->_data['name']] =
                        Lang::$word->FIELD_R0 . ' ' . $this->_data['text'] . ' ' . Lang::$word->FIELD_R21;
                    $this->next = false;
                }
            }
            $this->clean[$this->_data['name']] = $this->_data['value'];
            return $this;
        }
        
        /**
         * Validator::url()
         *
         * @return Validator
         */
        public function url()
        {
            if ($this->exists()) {
                if ($this->next and (!filter_var($this->_data['value'], FILTER_VALIDATE_URL))) {
                    $this->_errors[$this->_data['name']] =
                    Message::$msgs[$this->_data['name']] =
                        Lang::$word->FIELD_R0 . ' ' . $this->_data['text'] . ' ' . Lang::$word->FIELD_R14;
                    $this->next = false;
                }
            }
            $this->clean[$this->_data['name']] = $this->_data['value'];
            return $this;
        }
        
        /**
         * Validator::start()
         *
         * @return Validator
         */
        public function start($value)
        {
            if ($this->exists()) {
                if ($this->next and (!str_starts_with($this->_data['value'], $value))) {
                    $this->_errors[$this->_data['name']] =
                    Message::$msgs[$this->_data['name']] =
                        Lang::$word->FIELD_R0 . ' ' . $this->_data['text'] . ' ' . str_replace("[VAL]", $value, Lang::$word->FIELD_R12);
                    $this->next = false;
                }
            }
            $this->clean[$this->_data['name']] = $this->_data['value'];
            return $this;
        }
        
        /**
         * Validator::end()
         *
         * @return Validator
         */
        public function end($value)
        {
            if ($this->exists()) {
                if ($this->next and (!str_ends_with($this->_data['value'], $value))) {
                    $this->_errors[$this->_data['name']] =
                    Message::$msgs[$this->_data['name']] =
                        Lang::$word->FIELD_R0 . ' ' . $this->_data['text'] . ' ' . str_replace("[VAL]", $value, Lang::$word->FIELD_R12_1);
                    $this->next = false;
                }
            }
            $this->clean[$this->_data['name']] = $this->_data['value'];
            return $this;
        }
        
        /**
         * Validator::color()
         *
         * @return Validator
         */
        public function color()
        {
            if ($this->exists()) {
                $pattern = '/(?:#|0x)(?:[a-f0-9]{3}|[a-f0-9]{6})\b|(?:rgb|hsl)a?\([^)]*\)/m';
                if ($this->next and (!preg_match_all($pattern, $this->_data['value']))) {
                    $this->_errors[$this->_data['name']] =
                    Message::$msgs[$this->_data['name']] =
                        Lang::$word->FIELD_R0 . ' ' . $this->_data['text'] . ' ' . Lang::$word->FIELD_R20;
                    $this->next = false;
                }
            }
            $this->clean[$this->_data['name']] = $this->_data['value'];
            return $this;
        }
        
        /**
         * Validator::json()
         *
         * @return Validator
         */
        public function json()
        {
            if ($this->exists()) {
                try {
                    json_decode($this->_data['value'], true, 512, JSON_THROW_ON_ERROR);
                } catch (Exception) {
                    $this->_errors[$this->_data['name']] =
                    Message::$msgs[$this->_data['name']] =
                        Lang::$word->FIELD_R0 . ' ' . $this->_data['text'] . ' ' . Lang::$word->FIELD_R26;
                    $this->next = false;
                }
            }
            $this->clean[$this->_data['name']] = $this->_data['value'];
            return $this;
        }
        
        /**
         * Validator::text()
         *
         * @param string $type
         * @return Validator
         */
        public function text($type = "default")
        {
            $clean = "";
            if ($this->exists() and $this->next) {
                $clean = match ($type) {
                    "advanced" => self::$advanced_tags,
                    "basic" => self::$basic_tags,
                    "script" => self::$script_tags,
                    default => null,
                };
            }
            $this->clean[$this->_data['name']] = $this->_data['value'] = strip_tags($this->_data['value'], $clean);
            return $this;
        }
        
        /**
         * Validator::one()
         *
         * @return Validator
         */
        public function one()
        {
            if (!array_key_exists($this->_data['name'], $this->raw)) {
                $this->_errors[$this->_data['name']] =
                Message::$msgs[$this->_data['name']] =
                    Lang::$word->FIELD_R0 . ' ' . $this->_data['text'] . ' ' . Lang::$word->FIELD_R22;
                $this->next = false;
            }
            $this->clean[$this->_data['name']] = $this->_data['value'];
            return $this;
        }
        
        /**
         * Validator::sanitize()
         *
         * @param $data
         * @param $type
         * @param $trim
         * @return array|mixed|string|string[]|null
         */
        public static function sanitize($data, $type = "default", $trim = false)
        {
            switch ($type) {
                case "string":
                    $data = preg_replace('/\x00|<[^>]*>?/', '', $data);
                    $data = str_replace(["'", '"'], ['&#39;', '&#34;'], $data);
                    $data = htmlspecialchars($data ?? "", ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                    $data = $trim ? substr($data, 0, $trim) : $data;
                    break;
                
                case "search":
                    $data = str_replace(array('_', '%'), array('', ''), $data);
                    $data = strip_tags($data);
                    break;
                
                case "email":
                    $data = filter_var($data, FILTER_SANITIZE_EMAIL);
                    break;
                
                case "url":
                    $data = filter_var($data, FILTER_SANITIZE_URL);
                    break;
                
                case "alpha":
                    $data = preg_replace('/[^A-Za-z]/', '', $data);
                    $data = $trim ? substr($data, 0, $trim) : $data;
                    break;
                
                case "alphalow":
                    $data = preg_replace('/[^a-z]/', '', $data);
                    $data = $trim ? substr($data, 0, $trim) : $data;
                    break;
                
                case "alphahi":
                    $data = preg_replace('/[^A-Z]/', '', $data);
                    $data = $trim ? substr($data, 0, $trim) : $data;
                    break;
                
                case "alphanumeric":
                    $data = preg_replace('/[^A-Za-z0-9]/', '', $data);
                    $data = $trim ? substr($data, 0, $trim) : $data;
                    break;
                
                case "chars":
                case "spchar":
                    $data = htmlspecialchars(str_replace(array('\'', '"'), '', $data), ENT_QUOTES, 'UTF-8');
                    break;
                
                case "emailalt":
                    $data = preg_replace('/[^a-zA-Z0-9\/_|@+ .-]/', '', $data);
                    break;
                
                case "year":
                    $data = substr(preg_replace('/[^0-9]/', '', $data), 0, 4);
                    break;
                
                case "time":
                    $data = preg_replace('/[^0-9:]/', '', $data);
                    break;
                
                case "date":
                    $data = preg_replace('/[^0-9,-]/', '', $data);
                    break;
                
                case "file":
                    $data = preg_replace('/[^a-zA-Z0-9\/_ .-]/', '', $data);
                    break;
                
                case "implode":
                    $data = preg_replace('/[^0-9,]/', '', $data);
                    break;
                
                case "int":
                    $data = filter_var($data, FILTER_SANITIZE_NUMBER_INT);
                    $data = $trim ? substr($data, 0, $trim) : $data;
                    break;
                
                case "float":
                    $data = filter_var($data, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    break;
                
                case "db":
                    $data = preg_replace('/[^A-Za-z0-9_\-]/', '', $data);
                    $data = $trim ? substr($data, 0, $trim) : $data;
                    break;
                
                case "default":
                default:
                    $data = htmlspecialchars($data ?? "", ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                    $data = trim($data);
                    $data = stripslashes($data);
                    $data = strip_tags($data);
                    $data = $trim ? self::truncate($data, $trim) : $data;
                    break;
            }
            
            return $data;
            
        }
        
        /**
         * Validator::alphaBits()
         *
         * @param bool $url
         * @param string $class
         * @return string
         */
        public static function alphaBits($url, $class = "wojo small horizontal compact divided list")
        {
            $charset = explode(",", Lang::$word->CHARSET);
            $html = "<div class=\"$class\">\n";
            foreach ($charset as $key) {
                $active = ($key == self::get('letter')) ? ' active' : null;
                $html .= "<a class=\"item$active\" href=\"" . $url . "?letter=" . $key . "\">" . $key . "</a>\n";
            }
            $active = (!self::get('letter')) ? ' active' : null;
            $html .= "<a class=\"item$active\" href=\"" . $url . "\">" . Lang::$word->ALL . "</a>\n";
            $html .= "</div>\n";
            unset($key);
            
            return $html;
        }
        
        /**
         * Validator::censored()
         *
         * @param mixed $string
         * @param mixed $blacklist
         * @return array|mixed|string|string[]|null
         */
        public static function censored($string, $blacklist)
        {
            $array = explode(",", $blacklist);
            if (count($array) > 1) {
                foreach ($array as $row) {
                    $string = preg_replace("`$row`", "***", $string);
                }
            }
            return $string;
        }
        
        /**
         * Validator::truncate()
         *
         * @param mixed $string
         * @param mixed $length
         * @param bool $ellipsis
         * @return mixed|string
         */
        public static function truncate($string, $length, $ellipsis = true)
        {
            $wide = mb_strlen(preg_replace('/[^A-Z0-9_@#%$&]/', '', $string));
            $length = round($length - $wide * 0.2);
            $clean_string = preg_replace('/&[^;]+;/', '-', $string);
            if (mb_strlen($clean_string) <= $length) {
                return $string;
            }
            $difference = $length - mb_strlen($clean_string);
            $result = mb_substr($string, 0, $difference);
            if ($result != $string and $ellipsis) {
                $result = self::add_ellipsis($result);
            }
            return $result;
        }
        
        /**
         * Validator::add_ellipsis()
         *
         * @param mixed $string
         * @return string
         */
        public static function add_ellipsis($string)
        {
            $string = mb_substr($string, 0, mb_strlen($string) - 3);
            return trim(preg_replace('/ .{1,3}$/', '', $string)) . '...';
        }
        
        /**
         * Validator::cleanOut()
         *
         * @param $data
         * @return string
         */
        public static function cleanOut($data)
        {
            
            $data = strtr($data ?? '', array('\r\n' => "", '\r' => "", '\n' => ""));
            $data = html_entity_decode($data, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            return stripslashes(trim($data));
        }
        
        /**
         * Validator::arrayClean()
         *
         * @param mixed $data
         * @return array|mixed
         */
        public static function arrayClean(array $data)
        {
            foreach ($data as $k => $v) {
                $data[$k] = strip_tags($v);
            }
            
            return $data;
        }
        
        /**
         * Validator::compareNumbers()
         *
         * @param mixed $float1
         * @param mixed $float2
         * @param string $operator
         * @return bool|void
         */
        public static function compareNumbers($float1, $float2, $operator = '=')
        {
            // Check numbers to 5 digits of precision
            $epsilon = 0.00001;
            
            $float1 = (float) $float1;
            $float2 = (float) $float2;
            
            switch ($operator) {
                // equal
                case "=":
                case "eq":
                    if (abs($float1 - $float2) < $epsilon) {
                        return true;
                    }
                    break;
                // less than
                case "<":
                case "lt":
                    if (abs($float1 - $float2) < $epsilon) {
                        return false;
                    } else {
                        if ($float1 < $float2) {
                            return true;
                        }
                    }
                    break;
                // less than or equal
                case "<=":
                case "lte":
                    if (self::compareNumbers($float1, $float2, '<') || self::compareNumbers($float1, $float2)) {
                        return true;
                    }
                    break;
                // greater than
                case ">":
                case "gt":
                    if (abs($float1 - $float2) < $epsilon) {
                        return false;
                    } else {
                        if ($float1 > $float2) {
                            return true;
                        }
                    }
                    break;
                // greater than or equal
                case ">=":
                case "gte":
                    if (self::compareNumbers($float1, $float2, '>') || self::compareNumbers($float1, $float2)) {
                        return true;
                    }
                    break;
                
                case "<>":
                case "!=":
                case "ne":
                    if (abs($float1 - $float2) > $epsilon) {
                        return true;
                    }
                    break;
                default:
                    die("Unknown operator '" . $operator . "' in compareNumbers()");
            }
            
            return false;
        }
        
        /**
         * Validator::request()
         *
         * @param mixed $var
         * @return mixed|void
         */
        public static function request($var)
        {
            if (isset($_REQUEST[$var])) {
                return $_REQUEST[$var];
            }
        }
        
        /**
         * Validator::post()
         *
         * @param mixed $var
         * @return mixed|void
         */
        public static function post($var)
        {
            if (isset($_POST[$var])) {
                return $_POST[$var];
            }
        }
        
        /**
         * Validator::isPostSet()
         *
         * @param $key
         * @param $val
         * @return bool
         */
        public static function isPostSet($key, $val)
        {
            if (isset($_POST[$key]) and $_POST[$key] == $val) {
                return true;
            } else {
                return false;
            }
        }
        
        /**
         * Validator::notEmptyPost()
         *
         * @param mixed $var
         * @return mixed|void
         */
        public static function notEmptyPost($var)
        {
            if (!empty($_POST[$var])) {
                return $_POST[$var];
            }
        }
        
        /**
         * Validator::checkPost()
         *
         * @param mixed $index
         * @param mixed $msg
         * @return void
         */
        public static function checkPost($index, $msg)
        {
            
            if (empty($_POST[$index])) {
                Message::$msgs[$index] = $msg;
            }
        }
        
        /**
         * Validator::checkSetPost()
         *
         * @param mixed $index
         * @param mixed $msg
         * @return void
         */
        public static function checkSetPost($index, $msg)
        {
            
            if (!isset($_POST[$index])) {
                Message::$msgs[$index] = $msg;
            }
        }
        
        /**
         * Validator::get()
         *
         * @param mixed $var
         * @return mixed|void
         */
        public static function get($var)
        {
            if (isset($_GET[$var])) {
                return $_GET[$var];
            }
        }
        
        /**
         * Validator::notEmptyGet()
         *
         * @param mixed $var
         * @return mixed|void
         */
        public static function notEmptyGet($var)
        {
            if (!empty($_GET[$var])) {
                return $_GET[$var];
            }
        }
        
        /**
         * Validator::isGetSet()
         *
         * @param mixed $key
         * @param mixed $val
         * @return bool
         */
        public static function isGetSet($key, $val)
        {
            if (isset($_GET[$key]) and $_GET[$key] == $val) {
                return true;
            } else {
                return false;
            }
        }
        
        /**
         * Validator::has()
         *
         * @param mixed $value
         * @param mixed $string
         * @return mixed
         */
        public static function has($value, $string = '-/-')
        {
            return (isset($value)) ? $value : $string;
        }
        
        /**
         * Validator::getChecked()
         *
         * @param $row
         * @param $status
         * @return string|void
         */
        public static function getChecked($row, $status)
        {
            if ($row == $status) {
                return "checked=\"checked\"";
            }
        }
        
        /**
         * Validator::getSelected()
         *
         * @param $row
         * @param $status
         * @return false|string
         */
        public static function getSelected($row, $status)
        {
            if ($row == $status) {
                return "selected=\"selected\"";
            }
            return false;
        }
        
        /**
         * Validator::getActive()
         *
         * @param mixed $row
         * @param mixed $status
         * @return false|string
         */
        public static function getActive($row, $status)
        {
            if ($row == $status) {
                return "active";
            }
            return false;
        }
    }