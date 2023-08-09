<?php
    /**
     * Utility Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: utility.class.php, v1.00 2020-05-20 18:20:24 gewa Exp $
     */
    
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
    
    
    class Utility
    {
        
        /**
         * Utility::__construct()
         *
         */
        function __construct()
        {
        }
        
        /**
         * Utility::status()
         *
         * @param mixed $status
         * @param mixed $id
         * @return string
         */
        public static function status($status, $id)
        {
            switch ($status) {
                case "y":
                    $display = '<span class="wojo small inverted positive label">' . Lang::$word->ACTIVE . '</span>';
                    break;
                
                case "n":
                    $icon = '<i class="icon email"></i> ';
                    $display = '<a data-set=\'{"option":[{"action":"resendNotification","id": ' . $id . '}], "label":"' . Lang::$word->RESEND_EMAIL . '", "url":"helper.php", "parent":"#item_' . $id . '", "complete":"highlite", "modalclass":"normal"}\' class="wojo small inverted primary label action">' . $icon . Lang::$word->INACTIVE . '</a>';
                    break;
                
                case "t":
                    $display = '<span class="wojo small dark inverted label">' . Lang::$word->PENDING . '</span>';
                    break;
                
                case "b":
                    $display = '<span class="wojo small inverted negative label">' . Lang::$word->BANNED . '</span>';
                    break;
                
                default:
                    $display = null;
                    break;
            }
            
            return $display;
        }
        
        /**
         * Utility::isPublished()
         *
         * @param mixed $id
         * @return string
         */
        public static function isPublished($id)
        {
            
            return ($id == 1) ? '<i class="icon positive check"></i>' : '<i class="icon negative ban"></i>';
        }
        
        /**
         * Utility::userType()
         *
         * @param mixed $type
         * @return string
         */
        public static function userType($type)
        {
            return match ($type) {
                "owner" => '<span class="wojo small inverted secondary label">' . $type . '</span>',
                "staff" => '<span class="wojo small inverted primary label">' . $type . '</span>',
                "editor" => '<span class="wojo small inverted negative label">' . $type . '</span>',
                "manager" => '<span class="wojo small inverted positive label">' . $type . '</span>',
                "member" => '<span class="wojo small dark inverted label">' . $type . '</span>',
                default => null,
            };
        }
        
        /**
         * Utility::accountLevelToType()
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
                    return '<span class="wojo small bold primary text">' . Lang::$word->STAFF . '</span>';
                
                case 7:
                    return '<span class="wojo small bold secondary text">' . Lang::$word->EDITOR . '</span>';
                
                case 5:
                    return '<span class="wojo small bold negative text">' . Lang::$word->MANAGER . '</span>';
                
                case 1:
                    return '<span class="wojo small bold black text">' . Lang::$word->MEMBER . '</span>';
            }
        }
        
        /**
         * Utility::randName()
         *
         * @param mixed $char
         * @return string
         */
        public static function randName($char = 6)
        {
            $code = '';
            for ($x = 0; $x < $char; $x++) {
                $code .= '-' . substr(strtoupper(sha1(rand(0, 999999999999999))), 2, $char);
            }
            return substr($code, 1);
        }
        
        /**
         * Utility::randNumbers()
         *
         * @param int $digits
         * @return int
         */
        public static function randNumbers($digits = 7)
        {
            $min = pow(10, $digits - 1);
            $max = pow(10, $digits) - 1;
            return mt_rand($min, $max);
        }
        
        /**
         * Utility::randomString()
         *
         * @param int $length
         * @return string
         */
        public static function randomString($length = 8)
        {
            $keys = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));
            $key = '';
            for ($i = 0; $i < $length; $i++) {
                $key .= $keys[mt_rand(0, count($keys) - 1)];
            }
            return $key;
        }
        
        
        /**
         * Utility::getLogo()
         *
         * @return string
         */
        public static function getLogo()
        {
            $core = App::Core();
            if ($core->logo) {
                $logo = '<img src="' . UPLOADURL . '/' . $core->logo . '" alt="' . $core->company . '" style="border:0;width:150px"/>';
            } else {
                $logo = $core->company;
            }
            
            return $logo;
        }
        
        /**
         * Utility::formatMoney()
         *
         * @param $amount
         * @param bool $currency
         * @param bool $decimal
         * @return false|string
         */
        public static function formatMoney($amount, $currency = false, $decimal = true)
        {
            $code = $currency ?: App::Core()->currency;
            
            $fmt = new NumberFormatter(App::Core()->locale, NumberFormatter::CURRENCY);
            if (!$decimal) {
                $fmt->setTextAttribute(NumberFormatter::CURRENCY_CODE, $code);
                $fmt->setAttribute(NumberFormatter::FRACTION_DIGITS, 0);
            }
            return $fmt->formatCurrency($amount, $code);
        }
        
        /**
         * Utility::currencySymbol()
         *
         * @return false|string
         */
        public static function currencySymbol()
        {
            $fmt = new NumberFormatter(App::Core()->locale, NumberFormatter::CURRENCY);
            $fmt->setTextAttribute(NumberFormatter::CURRENCY_CODE, App::Core()->currency);
            
            return $fmt->getSymbol(NumberFormatter::CURRENCY_SYMBOL);
        }
        
        /**
         * Utility::formatNumber()
         *
         * @param bool $number
         * @return false|string
         */
        public static function formatNumber($number)
        {
            $fmt = new NumberFormatter(App::Core()->locale, NumberFormatter::DECIMAL);
            
            $fmt->setAttribute(NumberFormatter::FRACTION_DIGITS, 2);
            $fmt->setAttribute(NumberFormatter::MIN_FRACTION_DIGITS, 2);
            $fmt->setAttribute(NumberFormatter::MAX_FRACTION_DIGITS, 2);
            $fmt->setAttribute(NumberFormatter::DECIMAL_ALWAYS_SHOWN, 2);
            
            return $fmt->format($number);
        }
        
        /**
         * Utility::numberParse()
         *
         * @param bool $number
         * @return false|float|int|mixed
         */
        public static function numberParse($number)
        {
            $fmt = new NumberFormatter('en_US', NumberFormatter::DECIMAL);
            
            return $fmt->parse($number);
        }
        
        
        /**
         * Utility::loopOptions()
         *
         * @param mixed $array
         * @param $key
         * @param $value
         * @param bool $selected
         * @return false|string
         */
        public static function loopOptions($array, $key, $value, $selected = false)
        {
            $html = '';
            if (is_array($array)) {
                foreach ($array as $row) {
                    $html .= "<option value=\"" . $row->$key . "\"";
                    $html .= ($row->$key == $selected) ? ' selected="selected"' : '';
                    $html .= ">" . $row->$value . "</option>\n";
                }
                return $html;
            }
            return false;
        }
        
        /**
         * Utility::loopOptionsMultiple()
         *
         * @param mixed $array
         * @param $key
         * @param $value
         * @param $selected
         * @param $name
         * @param string $size
         * @return false|string
         */
        public static function loopOptionsMultiple($array, $key, $value, $selected, $name, $size = "small")
        {
            $arr = array();
            if ($selected) {
                $arr = explode(",", $selected);
            }
            $html = '';
            if (is_array($array)) {
                foreach ($array as $k => $row) {
                    $html .= "<div class=\"columns\">\n";
                    $html .= "<div class=\"wojo " . $size . " checkbox\">\n";
                    $html .= "<input type=\"checkbox\" name=\"" . $name . "[]\" value=\"" . $row->$key . "\" id=\"" . $name . "_" . $k . "\"";
                    $html .= (in_array($row->$key, $arr)) ? ' checked="checked"' : '';
                    $html .= ">\n";
                    $html .= "<label for=\"" . $name . "_" . $k . "\">" . $row->$value . "</label>\n";
                    $html .= "</div>\n";
                    $html .= "</div>\n";
                }
                return $html;
            }
            return false;
        }
        
        /**
         * Utility::loopOptionsSimpleMultiple()
         *
         * @param mixed $array
         * @param bool $selected
         * @return false|string
         */
        public static function loopOptionsSimpleMultiple($array, $selected = false)
        {
            $arr = array();
            if ($selected) {
                $arr = explode(",", $selected);
            }
            $html = '';
            if (is_array($array)) {
                foreach ($array as $row) {
                    $html .= "<option value=\"" . $row . "\"";
                    $html .= (in_array($row, $arr)) ? ' selected="selected"' : '';
                    $html .= ">" . $row . "</option>\n";
                }
                return $html;
            }
            return false;
        }
        
        /**
         * Utility::loopOptionsSimple()
         *
         * @param array $array
         * @param bool $selected
         * @return false|string
         */
        public static function loopOptionsSimple($array, $selected = false)
        {
            $html = '';
            if (is_array($array)) {
                foreach ($array as $row) {
                    $html .= "<option value=\"" . $row . "\"";
                    $html .= ($row == $selected) ? ' selected="selected"' : '';
                    $html .= ">" . $row . "</option>\n";
                }
                return $html;
            }
            return false;
        }
        
        /**
         * Utility::loopOptionsSimpleAlt()
         *
         * @param array $array
         * @param bool $selected
         * @return false|string
         */
        public static function loopOptionsSimpleAlt($array, $selected = false)
        {
            $html = '';
            if (is_array($array)) {
                foreach ($array as $key => $row) {
                    $html .= "<option value=\"" . $key . "\"";
                    $html .= ($key == $selected) ? ' selected="selected"' : '';
                    $html .= ">" . $row . "</option>\n";
                }
                return $html;
            }
            return false;
        }
        
        /**
         * Utility::loopSingleLine()
         *
         * @param array $array
         * @return false|string
         */
        public static function loopSingleLine($array)
        {
            $html = '';
            if (is_array($array)) {
                foreach ($array as $row) {
                    $html .= $row . PHP_EOL;
                }
                return $html;
            }
            return false;
        }
        
        /**
         * Utility::groupToLoop()
         *
         * @param $array
         * @param $key
         * @return array
         */
        public static function groupToLoop($array, $key)
        {
            $result = array();
            if (is_array($array)) {
                foreach ($array as $val) {
                    $itemName = $val->{$key};
                    if (!array_key_exists($itemName, $result)) {
                        $result[$itemName] = array();
                    }
                    $result[$itemName][] = $val;
                }
            }
            return $result;
        }
        
        /**
         * Utility::implodeFields()
         *
         * @param $array
         * @param $sep
         * @param $is_string
         * @return false|string
         */
        public static function implodeFields($array, $sep = ',', $is_string = false)
        {
            if (is_array($array)) {
                $result = array();
                foreach ($array as $row) {
                    if ($row != '') {
                        $result[] = Validator::sanitize($row);
                    }
                }
                return $is_string ? sprintf('"%s"', implode('","', $result)) : implode($sep, $result);
            }
            return false;
        }
        
        /**
         * Utility::implodeObject()
         * @param $array
         * @param $value
         * @return string
         */
        public static function implodeObject($array, $value)
        {
            $new_arr = array();
            foreach ($array as $row) {
                $new_arr[] = $row->$value;
            }
            
            return implode(', ', $new_arr);
        }
        
        /**
         * Utility::tableGrid()
         *
         * @param $array
         * @param $size
         * @return string
         */
        public static function tableGrid($array, $size)
        {
            $table_width = 100;
            $width = intval($table_width / $size);
            $tr = '<tr>';
            $td = "<td style=\"width:$width%%\">%s</td>";
            $grid = "<table class=\"wojo table\">$tr";
            $i = 0;
            
            foreach ($array as $e) {
                $grid .= sprintf($td, $e);
                $i++;
                if (!($i % $size)) {
                    $grid .= "$tr";
                }
            }
            
            while ($i % $size) {
                $grid .= sprintf($td, ' ');
                $i++;
            }
            
            $end_tr_len = strlen($tr) * -1;
            if (substr($grid, $end_tr_len) != $tr) {
                $grid .= '</tr>';
            } else {
                $grid = substr($grid, 0, $end_tr_len);
            }
            $grid .= '</table>';
            
            return $grid;
        }
        
        /**
         * Utility::findInArray()
         *
         * @param array $array
         * @param mixed $key
         * @param mixed $value
         * @return array|int|void
         */
        public static function findInArray($array, $key, $value)
        {
            if ($array) {
                $result = array();
                foreach ($array as $val) {
                    if ((is_object($val) ? ($val->$key == $value) : ($val[$key] == $value))) {
                        $result[] = $val;
                    }
                }
                return ($result) ?: 0;
            }
        }
        
        /**
         * Utility::searchForValue()
         *
         * @param mixed $key
         * @param mixed $value
         * @param mixed $array
         * @return false|int|string
         */
        public static function searchForValue($key, $value, $array)
        {
            if (is_array($array)) {
                foreach ($array as $val) {
                    if ((is_object($val) ? ($val->$key == $value) : ($val[$key] == $value))) {
                        return $key;
                    }
                }
            }
            return false;
        }
        
        /**
         * Utility::searchForValueName()
         *
         * @param string $key
         * @param string $value
         * @param mixed $return
         * @param array $array
         * @param bool $fullkey
         * @return false|mixed
         */
        public static function searchForValueName($key, $value, $return, $array, $fullkey = false)
        {
            
            if (is_array($array)) {
                foreach ($array as $k => $val) {
                    if (is_object($array)) {
                        if ($val->$key == $value) {
                            return $fullkey ? $array[$k] : $val->$return;
                        }
                    } else {
                        if ($val[$key] == $value) {
                            return $fullkey ? $array[$k] : $val[$return];
                        }
                    }
                }
            }
            return false;
        }
        
        /**
         * Utility::countInArray()
         *
         * @param $array
         * @param $key
         * @param $value
         * @return int
         */
        public static function countInArray($array, $key, $value)
        {
            $i = 0;
            if (is_array($array)) {
                foreach ($array as $v) {
                    if ((is_object($v) ? ($v->$key == $value) : ($v[$key] == $value))) {
                        $i++;
                    }
                }
            }
            return $i;
        }
        
        /**
         * Utility::sortArray()
         *
         * @param mixed $data
         * @param mixed $field
         * @return mixed
         * @sortArray($data, 'age');
         * @sortArray($data, array('lastname', 'firstname'));
         */
        public static function sortArray($data, $field)
        {
            $field = (array)$field;
            uasort($data, function ($a, $b) use ($field) {
                $retval = 0;
                foreach ($field as $fieldname) {
                    if ($retval == 0) $retval = strnatcmp($a[$fieldname], $b[$fieldname]);
                }
                return $retval;
            });
            return array_values($data);
        }
        
        /**
         * Utility::unserialToArray()
         *
         * @param string $string
         * @return false|mixed
         */
        public static function unserialToArray($string)
        {
            if ($string) {
                return unserialize($string);
            }
            return false;
        }
        
        /**
         * Utility::jSonToArray()
         *
         * @param $string
         * @return false|mixed
         */
        public static function jSonToArray($string)
        {
            if ($string) {
                return json_decode($string);
            }
            return false;
        }
        
        /**
         * Utility::jSonMergeToArray()
         *
         * @param array $array
         * @param string $jstring
         * @return array|false
         */
        public static function jSonMergeToArray($array, $jstring)
        {
            if ($array) {
                $allData = array();
                foreach ($array as $row) {
                    $data = self::jSonToArray($row->{$jstring});
                    if ($data != null) {
                        $allData = array_merge($allData, $data);
                    }
                }
                return $allData;
                
            }
            return false;
        }
        
        
        /**
         * Utility::parseJsonArray()
         *
         * @param $jsonArray
         * @param int $parent_id
         * @return array
         */
        public static function parseJsonArray($jsonArray, $parent_id = 0)
        {
            $data = array();
            foreach ($jsonArray as $subArray) {
                $returnSubSubArray = array();
                if (isset($subArray['children'])) {
                    $returnSubSubArray = self::parseJsonArray($subArray['children'], $subArray['id']);
                }
                $data[] = array('id' => $subArray['id'], 'parent_id' => $parent_id);
                $data = array_merge($data, $returnSubSubArray);
            }
            
            return $data;
        }
        
        /**
         * Utility::array_key_exists_wildcard()
         *
         * @param mixed $array
         * @param mixed $search
         * @param string $return
         * @return array|false|mixed
         */
        public static function array_key_exists_wildcard($array, $search, $return = '')
        {
            $search = str_replace('\*', '.*?', preg_quote($search, '/'));
            $result = preg_grep('/^' . $search . '$/i', array_keys($array));
            if ($return == 'key-value')
                return array_intersect_key($array, array_flip($result));
            return $result;
        }
        
        /**
         * Utility::array_value_exists_wildcard()
         *
         * @param mixed $array
         * @param mixed $search
         * @param string $return
         * @return array|false|mixed
         */
        public static function array_value_exists_wildcard($array, $search, $return = '')
        {
            $search = str_replace('\*', '.*?', preg_quote($search, '/'));
            $result = preg_grep('/^' . $search . '$/i', array_values($array));
            if ($return == 'key-value')
                return array_intersect($array, $result);
            return $result;
        }
        
        /**
         * Utility::encode()
         *
         * @param string $string
         * @return string
         */
        public static function encode($string)
        {
            return base64_encode(openssl_encrypt($string, "AES-256-CBC", hash('sha256', INSTALL_KEY), 0, substr(hash('sha256', INSTALL_KEY), 0, 16)));
        }
        
        /**
         * Utility::decode()
         *
         * @param $string
         * @return false|string
         */
        public static function decode($string)
        {
            return openssl_decrypt(base64_decode($string), "AES-256-CBC", hash('sha256', INSTALL_KEY), 0, substr(hash('sha256', INSTALL_KEY), 0, 16));
        }
        
        /**
         * Utility::in_array_any()
         * @param $needles
         * @param $haystack
         * @return bool
         */
        public static function in_array_any($needles, $haystack)
        {
            return !!array_intersect($needles, $haystack);
        }
        
        /**
         * Utility::in_array_all()
         *
         * @param mixed $needles
         * @param mixed $haystack
         * @return bool
         */
        public static function in_array_all($needles, $haystack)
        {
            return !array_diff($needles, $haystack);
        }
        
        /**
         * Utility::getInitials()
         *
         * @param mixed $string
         * @return string
         */
        public static function getInitials($string)
        {
            
            $result = '';
            foreach (explode(' ', $string) as $word)
                $result .= strtoupper($word[0]);
            return $result;
        }
        
        /**
         * Utility::getColors()
         *
         * @return string
         */
        public static function getColors()
        {
            
            static $colorCounter = -1;
            $colorArray = array('#f44336', '#673ab7', '#e91e63', '#3f51b5', '#9c27b0', '#2196f3', '#009688', '#03a9f4', '#4caf50', '#00bcd4', '#cddc39', '#8bc34a', '#ffc107', '#795548', '#607d8b');
            $colorCounter++;
            
            return $colorArray[$colorCounter % count($colorArray)];
        }
        
        /**
         * Utility::getCssClasses()
         *
         * @return string
         */
        public static function getCssClasses()
        {
            
            static $colorCounter = -1;
            $colorArray = array('red', 'cyan', 'purple', 'indigo', 'blue', 'pink', 'teal', 'green', 'lime', 'amber', 'orange', 'brown', 'grey');
            $colorCounter++;
            
            return $colorArray[$colorCounter % count($colorArray)];
        }
        
        /**
         * Utility::getExplodedValue()
         *
         * @param mixed $string
         * @param mixed $value
         * @param mixed $sep
         * @return string
         */
        public static function getExplodedValue($string, $value, $sep = ",")
        {
            $result = explode($sep, $string);
            return $result[$value];
        }
        
        /**
         * Utility::doPercent()
         *
         * @param string $number
         * @param string $total
         * @return float|int
         */
        public static function doPercent($number, $total)
        {
            
            return ($number > 0 and $total > 0) ? round(($number / $total) * 100) : 0;
        }
        
        /**
         * Utility::decimalToHour()
         *
         * @param string $number
         * @return array|string|string[]
         */
        public static function decimalToHour($number)
        {
            $number = number_format($number, 2);
            return str_replace(".", ":", $number);
        }
        
        /**
         * Utility::decimalToReadableHour()
         *
         * @param string $number
         * @return array
         */
        public static function decimalToReadableHour($number)
        {
            $data = explode(".", $number);
            $hour = $data[0] ?? 0;
            $min = $data[1] ?? 0;
            
            return [$hour, $min];
        }
        
        /**
         * Utility::shortName()
         *
         * @param string $fname
         * @param string $lname
         * @return string
         */
        public static function shortName($fname, $lname)
        {
            
            return $fname . ' ' . substr($lname, 0, 1) . '.';
        }
        
        /**
         * Utility::decimalToHumanHour()
         *
         * @param string $number
         * @return string
         */
        public static function decimalToHumanHour($number)
        {
            $data = explode(".", $number);
            $hour = isset($data[0]) ? $data[0] . ' ' . strtolower(Lang::$word->_HOURS) : 0;
            $min = (isset($data[1]) and $data[1] > 0) ? $data[1] . ' ' . strtolower(Lang::$word->_MINUTES) : '';
            
            return $hour . ' ' . $min;
        }
        
        /**
         * Utility::splitCurrency()
         *
         * @param mixed $currency
         * @return array
         */
        public static function splitCurrency($currency)
        {
            $data = array();
            
            if (!empty($currency)) {
                $iso = explode(",", $currency);
                $data['currency'] = $iso[0];
                $data['country'] = $iso[1];
            } else {
                $data['currency'] = App::get('Core')->currency;
                $data['country'] = isset($_POST['country']) ? Validator::sanitize($_POST['country'], "string") : "";
            }
            
            return $data;
        }
        
        /**
         * Utility::getSnippets()
         *
         * @param string $filename
         * @param array $data
         * @return false|string
         */
        public static function getSnippets($filename, $data = '')
        {
            if (File::is_File($filename)) {
                ob_start();
                if (is_array($data)) {
                    extract($data, EXTR_SKIP);
                }
                require($filename);
                $html = ob_get_contents();
                ob_end_clean();
                return $html;
            } else {
                return false;
            }
        }
        
        /**
         * Utility::doRange()
         *
         * @param mixed $min
         * @param mixed $max
         * @param mixed $step
         * @param bool $selected
         * @return string
         */
        public static function doRange($min, $max, $step, $selected = false)
        {
            $html = '';
            foreach (range($min, $max, $step) as $number) {
                $html .= "<option value=\"" . $number . "\"";
                $html .= ($number == $selected) ? ' selected="selected"' : '';
                $html .= ">" . $number . "</option>\n";
            }
            
            return $html;
        }
        
        /**
         * Utility::numberRange()
         *
         * @param array $min
         * @param array $max
         * @param int $step
         * @return string
         */
        public static function numberRange($min, $max, $step = 1)
        {
            
            return implode(",", range($min, $max, $step));
        }
        
        /**
         * Utility::sayHello()
         *
         * @return string
         */
        public static function sayHello()
        {
            $welcome = Lang::$word->HI . " ";
            if (date("H") < 12) {
                $welcome .= Lang::$word->HI_M;
            } else
                if (date('H') > 11 && date("H") < 18) {
                    $welcome .= Lang::$word->HI_A;
                } else
                    if (date('H') > 17) {
                        $welcome .= Lang::$word->HI_E;
                    }
            
            return $welcome;
        }
        
        /**
         * Utility::numberToWords()
         *
         * @param mixed $number
         * @return false|string
         */
        public static function numberToWords($number)
        {
            $words = array(
                'zero',
                'one',
                'two',
                'three',
                'four',
                'five',
                'six',
                'seven',
                'eight',
                'nine',
                'ten',
                'eleven',
                'twelve',
                'thirteen',
                'fourteen',
                'fifteen',
                'sixteen',
                'seventeen',
                'eighteen',
                'nineteen',
                'twenty',
                30 => 'thirty',
                40 => 'fourty',
                50 => 'fifty',
                60 => 'sixty',
                70 => 'seventy',
                80 => 'eighty',
                90 => 'ninety',
                100 => 'hundred',
                1000 => 'thousand');
            $number_in_words = '';
            if (is_numeric($number)) {
                $number = (int)round($number);
                if ($number < 0) {
                    $number = -$number;
                    $number_in_words = 'minus ';
                }
                if ($number > 1000) {
                    $number_in_words = $number_in_words . self::numberToWords(floor($number / 1000)) . " " . $words[1000];
                    $hundreds = $number % 1000;
                    $tens = $hundreds % 100;
                    if ($hundreds > 100) {
                        $number_in_words = $number_in_words . ", " . self::numberToWords($hundreds);
                    } elseif ($tens) {
                        $number_in_words = $number_in_words . " and " . self::numberToWords($tens);
                    }
                } elseif ($number > 100) {
                    $number_in_words = $number_in_words . self::numberToWords(floor($number / 100)) . " " . $words[100];
                    $tens = $number % 100;
                    if ($tens) {
                        $number_in_words = $number_in_words . " and " . self::numberToWords($tens);
                    }
                } elseif ($number > 20) {
                    $number_in_words = $number_in_words . " " . $words[10 * floor($number / 10)];
                    $units = $number % 10;
                    if ($units) {
                        $number_in_words = $number_in_words . self::numberToWords($units);
                    }
                } else {
                    $number_in_words = $number_in_words . " " . $words[$number];
                }
                return $number_in_words;
            }
            return false;
        }
        
        /**
         * Utility::colorToWord()
         *
         * @param string $string
         * @return string
         */
        public static function colorToWord($string)
        {
            $string = strtolower($string);
            return match (strtolower($string)) {
                "#1abc9c" => 'meadow',
                "#16a085" => 'meadow-dark',
                "#2ecc71" => 'shamrok',
                "#27ae60" => 'jungle',
                "#3498db" => 'blue',
                "#2980b9" => 'mariner',
                "#9b59b6" => 'wisteria',
                "#8e44ad" => 'studio',
                "#34495e" => 'bluewood',
                "#2c3e50" => 'bluewood-dark',
                "#f1c40f" => 'buttercup',
                "#f39c12" => 'buttercup-dark',
                "#e67e22" => 'zest',
                "#d35400" => 'orange',
                "#e74c3c" => 'cinnabar',
                "#c0392b" => 'poppy',
                "#ecf0f1" => 'porcelain',
                "#bdc3c7" => 'sand',
                "#95a5a6" => 'cascade',
                "#7f8c8d" => 'grey',
                default => 'blank-normal',
            };
        }
        
        
        /**
         * Utility::getColumnSize()
         *
         * @param int[] $size
         * @return int
         */
        public static function getColumnSize($size = array(40, 60, 50, 50, 60, 40, 50, 50, 100, 50, 50))
        {
            
            static $colorCounter = -1;
            $colorArray = $size;
            $colorCounter++;
            
            return $colorArray[$colorCounter % count($colorArray)];
        }
        
        /**
         * Utility::getHeaderBg()
         *
         * @return string
         */
        public static function getHeaderBg()
        {
            
            return isset($_COOKIE['headerBgColor']) ? ' style="background-color:' . $_COOKIE['headerBgColor'] . '"' : '';
        }
        
        /**
         * Utility::getSidearrBg()
         *
         * @return string
         */
        public static function getSidearrBg()
        {
            
            return isset($_COOKIE['sidebarBgColor']) ? ' style="background-color:' . $_COOKIE['sidebarBgColor'] . '"' : '';
        }
        
        /**
         * Utility::getImageUrl()
         *
         * @param mixed $ext
         * @param mixed $name
         * @return string
         */
        public static function getImageUrl($ext, $name)
        {
            
            return ($ext == "jpg" || $ext == "png" || $ext == "gif") ? UPLOADURL . 'files/' . $name : UPLOADURL . 'mime/' . $ext . '.png';
            
        }
    }