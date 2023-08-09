<?php
    /**
     * Date Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: date.class.php, v1.00 2022-08-20 18:20:24 gewa Exp $
     */
    
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
    
    
    class Date
    {
        
        /**
         * Date::__construct()
         *
         */
        function __construct()
        {
        }
        
        
        /**
         * Date::Calendar()
         *
         * @param array $events
         * @param bool $show_month
         * @return string
         */
        public function Calendar($events, $show_month = false)
        {
            $now = getdate();
            
            $wdays = array();
            $days = [0 => 'Sunday', 1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday'];
            foreach ($days as $day) {
                $wdays[] = $day;
            }
            
            self::arrayRotate($wdays, App::Core()->weekstart);
            
            $wday = date('N', mktime(0, 0, 1, $now['mon'], 1, $now['year'])) - App::Core()->weekstart;
            $no_days = date("t");
            $html = '<div class="wojo calendar static">';
            $html .= '<div class="calnav">';
            if ($show_month) {
                $html .= '<h3><span class="month">' . self::doDate("MMMM", $now['month']) . '</span><span class="year">' . $now['year'] . '</span></h3>';
            }
            $html .= '<div class="header">';
            for ($i = 0; $i < 7; $i++) {
                $html .= '<div>' . self::doDate("EE", $wdays[$i]) . '</div>';
            }
            $html .= "</div>";
            $html .= "</div>";
            $html .= '<section class="section">';
            
            $wday = ($wday + 7) % 7;
            
            if ($wday == 7) {
                $wday = 0;
            } else {
                $html .= str_repeat('<div class="empty">&nbsp;</div>', $wday);
            }
            
            $count = $wday + 1;
            for ($i = 1; $i <= $no_days; $i++) {
                $html .= '<div' . ($i == $now['mday'] && $now['mon'] == date('n') && $now['year'] == date('Y') ? ' class="today"' : '') . '>';
                $html .= '<div class="data">';
                
                $datetime = mktime(0, 0, 1, $now['mon'], $i, $now['year']);
                $html .= '<time datetime="' . date('Y-m-d', $datetime) . '" data-stamp="' . $datetime . '">' . $i . '</time>';
                
                if ($events) {
                    foreach ($events as $row) {
                        if ($row->expires == date('Y-m-d', $datetime)) {
                            $html .= '<div class="event">' . $row->total . '</div>';
                        }
                    }
                }
                
                $html .= "</div>";
                $html .= "</div>";
                
                if ($count > 6) {
                    $html .= "</section>\n" . ($i != $count ? '<section class="section">' : '');
                    $html .= "</section><section class=\"section\">\n";
                    $count = 0;
                }
                $count++;
            }
            $html .= ($count != 1 ? str_repeat('<div class="empty">&nbsp;</div>', 8 - $count) : '') . "</section>\n";
            $html .= "</div>";
            
            return $html;
        }
        
        /**
         * Date::compareDates()
         *
         * @param mixed $date1
         * @param mixed $date2
         * @return bool
         * @throws Exception
         */
        public static function compareDates($date1, $date2)
        {
            $date1 = new DateTime($date1);
            $date2 = new DateTime($date2);
            
            return $date1 > $date2;
        }
        
        /**
         * Date::NewDate()
         *
         * @param mixed $date
         * @param mixed $days
         * @return bool
         * @throws Exception
         */
        public static function NewDate($date, $days)
        {
            
            $cDate = new DateTime($date);
            $now = new DateTime();
            
            return $cDate->diff($now)->days < $days;
        }
        
        /**
         * Date::dateLabels()
         *
         * @param mixed $date
         * @return string|void
         */
        public static function dateLabels($date)
        {
            $now = new DateTime();
            $match_date = DateTime::createFromFormat("Y-m-d", $date);
            $diff = $now->diff($match_date);
            $diffDays = (int)$diff->format("%R%a");
            
            if ($diffDays >= 1) {
                echo "positive"; //Tomorrow
            } elseif ($diffDays < 0) {
                return "negative"; //Yesterday
            } else {
                return "primary"; //Today
            }
        }
        
        /**
         * Date::isWeekend()
         *
         * @param mixed $date
         * @return bool
         */
        public static function isWeekend($date)
        {
            $date = DateTime::createFromFormat('Y-m-d', $date, new DateTimeZone(App::Core()->dtz));
            return $date->format('N') >= 6;
        }
        
        /**
         * Date::doDate()
         *
         * @param mixed $format
         * @param mixed $date
         * @return false|string
         */
        public static function doDate($format, $date)
        {
            $cal = IntlCalendar::fromDateTime($date);
            if ($format == "long_date" or $format == "short_date") {
                return IntlDateFormatter::formatObject($cal, App::Core()->$format, App::Core()->locale);
            } elseif ($format == "calendar") {
                return IntlDateFormatter::formatObject($cal, str_replace('m', 'M', App::Core()->calendar_date), App::Core()->locale);
            } else {
                return IntlDateFormatter::formatObject($cal, $format);
            }
        }
        
        /**
         * Date::doTime()
         *
         * @param mixed $time
         * @return false|string
         */
        public static function doTime($time)
        {
            
            $cal = IntlCalendar::fromDateTime($time);
            return IntlDateFormatter::formatObject($cal, App::Core()->time_format);
        }
        
        /**
         * Date::doStime()
         *
         * @param mixed $time
         * @return false|string
         */
        public static function doStime($time)
        {
            
            $cal = IntlCalendar::fromDateTime($time);
            return IntlDateFormatter::formatObject($cal, 'HH:mm:ss');
        }
        
        /**
         * Date::getShortDate()
         *
         * @param bool $selected
         * @return string
         */
        public static function getShortDate($selected = false)
        {
            
            $cal = IntlCalendar::fromDateTime(date('Y-m-d H:i:s'));
            $arr = array(
                'MM-dd-yyyy' => IntlDateFormatter::formatObject($cal, 'MM-dd-yyyy'),
                'd-MM-YYYY' => IntlDateFormatter::formatObject($cal, 'd-MM-YYYY'),
                'MM-d-yy' => IntlDateFormatter::formatObject($cal, 'MM-d-yy'),
                'd-MM-yy' => IntlDateFormatter::formatObject($cal, 'd-MM-yy'),
                'dd MMM yyyy' => IntlDateFormatter::formatObject($cal, 'dd MMM yyyy'));
            
            $shortdate = '';
            foreach ($arr as $key => $val) {
                if ($key == $selected) {
                    $shortdate .= "<option selected=\"selected\" value=\"" . $key . "\">" . $val . "</option>\n";
                } else
                    $shortdate .= "<option value=\"" . $key . "\">" . $val . "</option>\n";
            }
            unset($val);
            return $shortdate;
        }
        
        /**
         * Date::getLongDate()
         *
         * @param bool $selected
         * @return string
         */
        public static function getLongDate($selected = false)
        {
            
            $cal = IntlCalendar::fromDateTime(date('Y-m-d H:i:s'));
            $arr = array(
                'MMMM dd, yyyy hh:mm a' => IntlDateFormatter::formatObject($cal, 'MMMM dd, yyyy hh:mm a'),
                'dd MMMM yyyy hh:mm a' => IntlDateFormatter::formatObject($cal, 'dd MMMM yyyy hh:mm a'),
                'MMMM dd, yyyy' => IntlDateFormatter::formatObject($cal, 'MMMM dd, yyyy'),
                'dd MMMM, yyyy' => IntlDateFormatter::formatObject($cal, 'dd MMMM, yyyy'),
                'EEEE dd MMMM yyyy' => IntlDateFormatter::formatObject($cal, 'EEEE dd MMMM yyyy'),
                'EEEE dd MMMM yyyy HH:mm' => IntlDateFormatter::formatObject($cal, 'EEEE dd MMMM yyyy HH:mm'),
                'EE dd, MMM. yyyy' => IntlDateFormatter::formatObject($cal, 'EE dd, MMM. yyyy'));
            
            $longdate = '';
            foreach ($arr as $key => $val) {
                if ($key == $selected) {
                    $longdate .= "<option selected=\"selected\" value=\"" . $key . "\">" . $val . "</option>\n";
                } else
                    $longdate .= "<option value=\"" . $key . "\">" . $val . "</option>\n";
            }
            unset($val);
            return $longdate;
        }
        
        /**
         * Date::getCalendarDate()
         *
         * @param bool $selected
         * @return string
         */
        public static function getCalendarDate($selected = false)
        {
            
            $cal = IntlCalendar::fromDateTime(date('Y-m-d H:i:s'));
            $arr = array(
                'mm/dd/yyyy' => IntlDateFormatter::formatObject($cal, 'MM/dd/yyyy'),
                'mm-dd-yyyy' => IntlDateFormatter::formatObject($cal, 'MM-dd-yyyy'),
                'dd/mm/yyyy' => IntlDateFormatter::formatObject($cal, 'dd/MM/yyyy'),
                'dd-mm-yyyy' => IntlDateFormatter::formatObject($cal, 'dd-MM-yyyy'),
            );
            
            $shortdate = '';
            foreach ($arr as $key => $val) {
                if ($key == $selected) {
                    $shortdate .= "<option selected=\"selected\" value=\"" . $key . "\">" . $val . "</option>\n";
                } else
                    $shortdate .= "<option value=\"" . $key . "\">" . $val . "</option>\n";
            }
            unset($val);
            return $shortdate;
        }
        
        /**
         * Date::getTimeFormat()
         *
         * @param bool $selected
         * @return string
         */
        public static function getTimeFormat($selected = false)
        {
            $cal = IntlCalendar::fromDateTime(date('H:i:s'));
            $arr = array(
                'hh:mm a' => IntlDateFormatter::formatObject($cal, 'hh:mm a'),
                'HH:mm' => IntlDateFormatter::formatObject($cal, 'HH:mm'),
            );
            
            $longdate = '';
            foreach ($arr as $key => $val) {
                if ($key == $selected) {
                    $longdate .= "<option selected=\"selected\" value=\"" . $key . "\">" . $val . "</option>\n";
                } else
                    $longdate .= "<option value=\"" . $key . "\">" . $val . "</option>\n";
            }
            unset($val);
            return $longdate;
        }
        
        /**
         * Date::weekList()
         *
         * @param bool $list
         * @param bool $long
         * @param bool $selected
         * @return string
         */
        public static function weekList($list = true, $long = true, $selected = false)
        {
            $fmt = new IntlDateFormatter(App::Core()->locale, IntlDateFormatter::LONG, IntlDateFormatter::NONE);
            $data = array();
            
            ($long) ? $fmt->setPattern('EEEE') : $fmt->setPattern('EE');
            
            for ($i = 0; $i <= 6; $i++) {
                $data[] = $fmt->format(mktime(0, 0, 0, 0, $i, 1970));
            }
            
            $html = '';
            if ($list) {
                foreach ($data as $key => $val) {
                    $html .= "<option value=\"$key\"";
                    $html .= ($key == $selected) ? ' selected="selected"' : '';
                    $html .= ">$val</option>\n";
                }
            } else {
                $html .= '"' . implode('","', $data) . '"';
            }
            
            unset($val);
            return $html;
        }
        
        /**
         * Date::getPeriod()
         *
         * @param bool $value
         * @return void
         */
        public static function getPeriod($value)
        {
            switch ($value) {
                case "day":
                    return Lang::$word->_DAYS;
                    break;
                case "week":
                    return Lang::$word->_WEEKS;
                    break;
                case "month":
                    return Lang::$word->_MONTHS;
                    break;
                case "year":
                    return Lang::$word->_YEARS;
                    break;
            }
        }
        
        /**
         * Date::getMembershipPeriod()
         *
         * @return array
         */
        public static function getMembershipPeriod()
        {
            return array(
                'D' => Lang::$word->_DAYS,
                'W' => Lang::$word->_WEEKS,
                'M' => Lang::$word->_MONTHS,
                'Y' => Lang::$word->_YEARS
            );
        }
        
        /**
         * Date::getPeriodReadable()
         *
         * @param int $value
         * @param $days
         * @return void
         */
        public static function getPeriodReadable($value, $days)
        {
            switch ($value) {
                case "D":
                    return $days > 1 ? Lang::$word->_DAYS : Lang::$word->_DAY;
                    break;
                case "W":
                    return $days > 1 ? Lang::$word->_WEEKS : Lang::$word->_WEEK;
                    break;
                case "M":
                    return $days > 1 ? Lang::$word->_MONTHS : Lang::$word->_MONTH;
                    break;
                case "Y":
                    return $days > 1 ? Lang::$word->_YEARS : Lang::$word->_YEAR;
                    break;
            }
        }
        
        /**
         * Date::overdueList()
         *
         * @param bool $value
         * @return string|void
         */
        public static function overdueList($value)
        {
            switch ($value) {
                case "1D":
                    return '1 ' . Lang::$word->_DAY;
                    break;
                case "3D":
                    return '3 ' . Lang::$word->_DAYS;
                    break;
                case "5D":
                    return '5 ' . Lang::$word->_DAYS;
                    break;
                case "1W":
                    return '1 ' . Lang::$word->_WEEK;
                    break;
                case "2W":
                    return '2 ' . Lang::$word->_WEEKS;
                    break;
                case "1M":
                    return '1 ' . Lang::$word->_MONTH;
                    break;
            }
        }
        
        
        /**
         * Date::NumberOfDays()
         *
         * @param bool $days
         * eg: +10 day, -1 week
         * @param bool $format
         * @return string
         */
        public static function NumberOfDays($days, $format = false)
        {
            $date = new DateTime();
            $date->modify($days);
            
            return $date->format($format ?: 'Y-m-d H:i:s');
        }
        
        /**
         * Date::calculateDays()
         *
         * @param bool $membership_id
         * @return string
         */
        public static function calculateDays($membership_id)
        {
            
            $row = Db::Go()->select(Content::msTable, array('days', 'period'))->where("id", $membership_id, "=")->first()->run();
            if ($row) {
                $diff = "none";
                switch ($row->period) {
                    case "D":
                        $diff = ' day';
                        break;
                    case "W":
                        $diff = ' week';
                        break;
                    case "M":
                        $diff = ' month';
                        break;
                    case "Y":
                        $diff = ' year';
                        break;
                }
                $expire = self::NumberOfDays('+' . $row->days . $diff);
            } else {
                $expire = "";
            }
            return $expire;
        }
        
        /**
         * Date::today()
         *
         * @param string $date
         * @param bool $format
         * @return string
         * @throws Exception
         */
        public static function today($date = '', $format = false)
        {
            $date = new DateTime($date);
            
            return $date->format($format ?: 'Y-m-d H:i:s');
        }
        
        /**
         * Date::timesince()
         *
         * @param $date
         * @return string
         * @throws Exception
         */
        public static function timesince($date)
        {
            $now = new DateTime('now');
            $since = new DateTime(date('Y-m-d H:i:s', strtotime($date)), $now->getTimezone());
            
            $interval = $since->diff($now);
            if (($i = $interval->y) > 0) {
                $i_str = $i + 1 . ' ' . Lang::$word->_YEARS;
            } elseif (($i = $interval->m) > 0) {
                $i_str = ($i > 10) ? '1 ' . Lang::$word->_MONTH . ' ' : $i + 1 . ' ' . Lang::$word->_MONTHS;
            } elseif (($i = $interval->d) > 0) {
                $i_str = ($i > 29) ? '1 ' . Lang::$word->_DAY . ' ' : $i + 1 . ' ' . Lang::$word->_DAYS;
            } elseif (($i = $interval->h) > 0) {
                $i_str = ($i > 22) ? '1 ' . Lang::$word->_DAY . ' ' : $i + 1 . ' ' . Lang::$word->_HOURS;
            } elseif (($i = $interval->i) > 0) {
                $i_str = ($i > 58) ? '1 ' . Lang::$word->_HOUR . ' ' : $i + 1 . ' ' . Lang::$word->_MINUTES;
            } elseif (($i = $interval->s) > 0) {
                $i_str = ($i > 58) ? '1 ' . Lang::$word->_MINUTE . ' ' : $i + 1 . ' ' . Lang::$word->_SECONDS;
            } else {
                return Lang::$word->JUSTNOW;
            }
            
            return (($i < 11) ? Lang::$word->LESSTHAN . ' ' : '') . $i_str . ' ' . Lang::$word->AGO;
        }
        
        /**
         * Date::monthList()
         *
         * @param bool $list
         * @param bool $long
         * @return string
         */
        public static function monthList($list = true, $long = true)
        {
            $date = DateTimeImmutable::createFromFormat('U', time());
            $selected = is_null(Validator::get('month')) ? $date->format('m') : Validator::get('month');
            
            $fmt = new IntlDateFormatter(App::Core()->locale, IntlDateFormatter::LONG, IntlDateFormatter::NONE);
            $data = array();
            
            ($long) ? $fmt->setPattern('MMMM') : $fmt->setPattern('MMM');
            
            for ($i = 1; $i <= 12; $i++) {
                $data[] = $fmt->format(mktime(0, 0, 0, $i, 1, 1970));
            }
            
            $html = '';
            if ($list) {
                foreach ($data as $key => $val) {
                    $html .= "<option value=\"$key\"";
                    $html .= ($key == $selected) ? ' selected="selected"' : '';
                    $html .= ">$val</option>\n";
                }
            } else {
                $html .= '"' . implode('","', $data) . '"';
            }
            unset($val);
            return $html;
        }
        
        /**
         * Date::getTimezones()
         *
         * @return string
         */
        public static function getTimezones()
        {
            $data = '';
            $tzone = DateTimeZone::listIdentifiers();
            foreach ($tzone as $zone) {
                $selected = ($zone == App::Core()->dtz) ? ' selected="selected"' : '';
                $data .= '<option value="' . $zone . '"' . $selected . '>' . $zone . '</option>';
            }
            return $data;
        }
        
        /**
         * Date::arrayRotate()
         *
         * @param mixed $data
         * @param mixed $steps
         * @return void
         */
        private static function arrayRotate(&$data, $steps)
        {
            $count = count($data);
            if ($steps < 0) {
                $steps = $count + $steps;
            }
            $steps = $steps % $count;
            for ($i = 0; $i < $steps; $i++) {
                $data[] = array_shift($data);
            }
        }
        
        /**
         * Date::localeList()
         *
         * @param bool $selected
         * @return string
         */
        public static function localeList($selected = false)
        {
            $data = array(
                'aa_DJ' => 'Afar (Djibouti)',
                'aa_ER' => 'Afar (Eritrea)',
                'aa_ET' => 'Afar (Ethiopia)',
                'af_ZA' => 'Afrikaans (South Africa)',
                'sq_AL' => 'Albanian (Albania)',
                'sq_MK' => 'Albanian (Macedonia)',
                'am_ET' => 'Amharic (Ethiopia)',
                'ar_DZ' => 'Arabic (Algeria)',
                'ar_BH' => 'Arabic (Bahrain)',
                'ar_EG' => 'Arabic (Egypt)',
                'ar_IN' => 'Arabic (India)',
                'ar_IQ' => 'Arabic (Iraq)',
                'ar_JO' => 'Arabic (Jordan)',
                'ar_KW' => 'Arabic (Kuwait)',
                'ar_LB' => 'Arabic (Lebanon)',
                'ar_LY' => 'Arabic (Libya)',
                'ar_MA' => 'Arabic (Morocco)',
                'ar_OM' => 'Arabic (Oman)',
                'ar_QA' => 'Arabic (Qatar)',
                'ar_SA' => 'Arabic (Saudi Arabia)',
                'ar_SD' => 'Arabic (Sudan)',
                'ar_SY' => 'Arabic (Syria)',
                'ar_TN' => 'Arabic (Tunisia)',
                'ar_AE' => 'Arabic (United Arab Emirates)',
                'ar_YE' => 'Arabic (Yemen)',
                'an_ES' => 'Aragonese (Spain)',
                'hy_AM' => 'Armenian (Armenia)',
                'as_IN' => 'Assamese (India)',
                'ast_ES' => 'Asturian (Spain)',
                'az_AZ' => 'Azerbaijani (Azerbaijan)',
                'az_TR' => 'Azerbaijani (Turkey)',
                'eu_FR' => 'Basque (France)',
                'eu_ES' => 'Basque (Spain)',
                'be_BY' => 'Belarusian (Belarus)',
                'bem_ZM' => 'Bemba (Zambia)',
                'bn_BD' => 'Bengali (Bangladesh)',
                'bn_IN' => 'Bengali (India)',
                'ber_DZ' => 'Berber (Algeria)',
                'ber_MA' => 'Berber (Morocco)',
                'byn_ER' => 'Blin (Eritrea)',
                'bs_BA' => 'Bosnian (Bosnia and Herzegovina)',
                'br_FR' => 'Breton (France)',
                'bg_BG' => 'Bulgarian (Bulgaria)',
                'my_MM' => 'Burmese (Myanmar [Burma])',
                'ca_AD' => 'Catalan (Andorra)',
                'ca_FR' => 'Catalan (France)',
                'ca_IT' => 'Catalan (Italy)',
                'ca_ES' => 'Catalan (Spain)',
                'zh_CN' => 'Chinese (China)',
                'zh_HK' => 'Chinese (Hong Kong SAR China)',
                'zh_SG' => 'Chinese (Singapore)',
                'zh_TW' => 'Chinese (Taiwan)',
                'cv_RU' => 'Chuvash (Russia)',
                'kw_GB' => 'Cornish (United Kingdom)',
                'crh_UA' => 'Crimean Turkish (Ukraine)',
                'hr_HR' => 'Croatian (Croatia)',
                'cs_CZ' => 'Czech (Czech Republic)',
                'da_DK' => 'Danish (Denmark)',
                'dv_MV' => 'Divehi (Maldives)',
                'nl_AW' => 'Dutch (Aruba)',
                'nl_BE' => 'Dutch (Belgium)',
                'nl_NL' => 'Dutch (Netherlands)',
                'dz_BT' => 'Dzongkha (Bhutan)',
                'en_AG' => 'English (Antigua and Barbuda)',
                'en_AU' => 'English (Australia)',
                'en_BW' => 'English (Botswana)',
                'en_CA' => 'English (Canada)',
                'en_DK' => 'English (Denmark)',
                'en_HK' => 'English (Hong Kong SAR China)',
                'en_IN' => 'English (India)',
                'en_IE' => 'English (Ireland)',
                'en_NZ' => 'English (New Zealand)',
                'en_NG' => 'English (Nigeria)',
                'en_PH' => 'English (Philippines)',
                'en_SG' => 'English (Singapore)',
                'en_ZA' => 'English (South Africa)',
                'en_GB' => 'English (United Kingdom)',
                'en_US' => 'English (United States)',
                'en_ZM' => 'English (Zambia)',
                'en_ZW' => 'English (Zimbabwe)',
                'eo' => 'Esperanto',
                'et_EE' => 'Estonian (Estonia)',
                'fo_FO' => 'Faroese (Faroe Islands)',
                'fil_PH' => 'Filipino (Philippines)',
                'fi_FI' => 'Finnish (Finland)',
                'fr_BE' => 'French (Belgium)',
                'fr_CA' => 'French (Canada)',
                'fr_FR' => 'French (France)',
                'fr_LU' => 'French (Luxembourg)',
                'fr_CH' => 'French (Switzerland)',
                'fur_IT' => 'Friulian (Italy)',
                'ff_SN' => 'Fulah (Senegal)',
                'gl_ES' => 'Galician (Spain)',
                'lg_UG' => 'Ganda (Uganda)',
                'gez_ER' => 'Geez (Eritrea)',
                'gez_ET' => 'Geez (Ethiopia)',
                'ka_GE' => 'Georgian (Georgia)',
                'de_AT' => 'German (Austria)',
                'de_BE' => 'German (Belgium)',
                'de_DE' => 'German (Germany)',
                'de_LI' => 'German (Liechtenstein)',
                'de_LU' => 'German (Luxembourg)',
                'de_CH' => 'German (Switzerland)',
                'el_CY' => 'Greek (Cyprus)',
                'el_GR' => 'Greek (Greece)',
                'gu_IN' => 'Gujarati (India)',
                'ht_HT' => 'Haitian (Haiti)',
                'ha_NG' => 'Hausa (Nigeria)',
                'iw_IL' => 'Hebrew (Israel)',
                'he_IL' => 'Hebrew (Israel)',
                'hi_IN' => 'Hindi (India)',
                'hu_HU' => 'Hungarian (Hungary)',
                'is_IS' => 'Icelandic (Iceland)',
                'ig_NG' => 'Igbo (Nigeria)',
                'id_ID' => 'Indonesian (Indonesia)',
                'ia' => 'Interlingua',
                'iu_CA' => 'Inuktitut (Canada)',
                'ik_CA' => 'Inupiaq (Canada)',
                'ga_IE' => 'Irish (Ireland)',
                'it_IT' => 'Italian (Italy)',
                'it_CH' => 'Italian (Switzerland)',
                'ja_JP' => 'Japanese (Japan)',
                'kl_GL' => 'Kalaallisut (Greenland)',
                'kn_IN' => 'Kannada (India)',
                'ks_IN' => 'Kashmiri (India)',
                'csb_PL' => 'Kashubian (Poland)',
                'kk_KZ' => 'Kazakh (Kazakhstan)',
                'km_KH' => 'Khmer (Cambodia)',
                'rw_RW' => 'Kinyarwanda (Rwanda)',
                'ky_KG' => 'Kirghiz (Kyrgyzstan)',
                'kok_IN' => 'Konkani (India)',
                'ko_KR' => 'Korean (South Korea)',
                'ku_TR' => 'Kurdish (Turkey)',
                'lo_LA' => 'Lao (Laos)',
                'lv_LV' => 'Latvian (Latvia)',
                'li_BE' => 'Limburgish (Belgium)',
                'li_NL' => 'Limburgish (Netherlands)',
                'lt_LT' => 'Lithuanian (Lithuania)',
                'nds_DE' => 'Low German (Germany)',
                'nds_NL' => 'Low German (Netherlands)',
                'mk_MK' => 'Macedonian (Macedonia)',
                'mai_IN' => 'Maithili (India)',
                'mg_MG' => 'Malagasy (Madagascar)',
                'ms_MY' => 'Malay (Malaysia)',
                'ml_IN' => 'Malayalam (India)',
                'mt_MT' => 'Maltese (Malta)',
                'gv_GB' => 'Manx (United Kingdom)',
                'mi_NZ' => 'Maori (New Zealand)',
                'mr_IN' => 'Marathi (India)',
                'mn_MN' => 'Mongolian (Mongolia)',
                'ne_NP' => 'Nepali (Nepal)',
                'se_NO' => 'Northern Sami (Norway)',
                'nso_ZA' => 'Northern Sotho (South Africa)',
                'nb_NO' => 'Norwegian Bokmål (Norway)',
                'nn_NO' => 'Norwegian Nynorsk (Norway)',
                'oc_FR' => 'Occitan (France)',
                'or_IN' => 'Oriya (India)',
                'om_ET' => 'Oromo (Ethiopia)',
                'om_KE' => 'Oromo (Kenya)',
                'os_RU' => 'Ossetic (Russia)',
                'pap_AN' => 'Papiamento (Netherlands Antilles)',
                'ps_AF' => 'Pashto (Afghanistan)',
                'fa_IR' => 'Persian (Iran)',
                'pl_PL' => 'Polish (Poland)',
                'pt_BR' => 'Portuguese (Brazil)',
                'pt_PT' => 'Portuguese (Portugal)',
                'pa_IN' => 'Punjabi (India)',
                'pa_PK' => 'Punjabi (Pakistan)',
                'ro_RO' => 'Romanian (Romania)',
                'ru_RU' => 'Russian (Russia)',
                'ru_UA' => 'Russian (Ukraine)',
                'sa_IN' => 'Sanskrit (India)',
                'sc_IT' => 'Sardinian (Italy)',
                'gd_GB' => 'Scottish Gaelic (United Kingdom)',
                'sr_ME' => 'Serbian (Montenegro)',
                'sr_RS' => 'Serbian (Cyrillic )',
                'sr_LAT' => 'Serbian (Latin)',
                'sid_ET' => 'Sidamo (Ethiopia)',
                'sd_IN' => 'Sindhi (India)',
                'si_LK' => 'Sinhala (Sri Lanka)',
                'sk_SK' => 'Slovak (Slovakia)',
                'sl_SI' => 'Slovenian (Slovenia)',
                'so_DJ' => 'Somali (Djibouti)',
                'so_ET' => 'Somali (Ethiopia)',
                'so_KE' => 'Somali (Kenya)',
                'so_SO' => 'Somali (Somalia)',
                'nr_ZA' => 'South Ndebele (South Africa)',
                'st_ZA' => 'Southern Sotho (South Africa)',
                'es_AR' => 'Spanish (Argentina)',
                'es_BO' => 'Spanish (Bolivia)',
                'es_CL' => 'Spanish (Chile)',
                'es_CO' => 'Spanish (Colombia)',
                'es_CR' => 'Spanish (Costa Rica)',
                'es_DO' => 'Spanish (Dominican Republic)',
                'es_EC' => 'Spanish (Ecuador)',
                'es_SV' => 'Spanish (El Salvador)',
                'es_GT' => 'Spanish (Guatemala)',
                'es_HN' => 'Spanish (Honduras)',
                'es_MX' => 'Spanish (Mexico)',
                'es_NI' => 'Spanish (Nicaragua)',
                'es_PA' => 'Spanish (Panama)',
                'es_PY' => 'Spanish (Paraguay)',
                'es_PE' => 'Spanish (Peru)',
                'es_ES' => 'Spanish (Spain)',
                'es_US' => 'Spanish (United States)',
                'es_UY' => 'Spanish (Uruguay)',
                'es_VE' => 'Spanish (Venezuela)',
                'sw_KE' => 'Swahili (Kenya)',
                'sw_TZ' => 'Swahili (Tanzania)',
                'ss_ZA' => 'Swati (South Africa)',
                'sv_FI' => 'Swedish (Finland)',
                'sv_SE' => 'Swedish (Sweden)',
                'tl_PH' => 'Tagalog (Philippines)',
                'tg_TJ' => 'Tajik (Tajikistan)',
                'ta_IN' => 'Tamil (India)',
                'tt_RU' => 'Tatar (Russia)',
                'te_IN' => 'Telugu (India)',
                'th_TH' => 'Thai (Thailand)',
                'bo_CN' => 'Tibetan (China)',
                'bo_IN' => 'Tibetan (India)',
                'tig_ER' => 'Tigre (Eritrea)',
                'ti_ER' => 'Tigrinya (Eritrea)',
                'ti_ET' => 'Tigrinya (Ethiopia)',
                'ts_ZA' => 'Tsonga (South Africa)',
                'tn_ZA' => 'Tswana (South Africa)',
                'tr_CY' => 'Turkish (Cyprus)',
                'tr_TR' => 'Turkish (Turkey)',
                'tk_TM' => 'Turkmen (Turkmenistan)',
                'ug_CN' => 'Uighur (China)',
                'uk_UA' => 'Ukrainian (Ukraine)',
                'hsb_DE' => 'Upper Sorbian (Germany)',
                'ur_PK' => 'Urdu (Pakistan)',
                'uz_UZ' => 'Uzbek (Uzbekistan)',
                've_ZA' => 'Venda (South Africa)',
                'vi_VN' => 'Vietnamese (Vietnam)',
                'wa_BE' => 'Walloon (Belgium)',
                'cy_GB' => 'Welsh (United Kingdom)',
                'fy_DE' => 'Western Frisian (Germany)',
                'fy_NL' => 'Western Frisian (Netherlands)',
                'wo_SN' => 'Wolof (Senegal)',
                'xh_ZA' => 'Xhosa (South Africa)',
                'yi_US' => 'Yiddish (United States)',
                'yo_NG' => 'Yoruba (Nigeria)',
                'zu_ZA' => 'Zulu (South Africa)');
            
            $html = '';
            foreach ($data as $key => $val) {
                if ($key == $selected) {
                    $html .= "<option selected=\"selected\" value=\"" . $key . "\">" . $val . "</option>\n";
                } else
                    $html .= "<option value=\"" . $key . "\">" . $val . "</option>\n";
            }
            unset($val);
            return $html;
        }
    }