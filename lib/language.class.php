<?php
    /**
     * Language
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version $Id: lang.class.php, v 1.00 2023-01-10 21:12:05 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
    
    
    final class Lang
    {
        const langdir = "lang/";
        public static $language;
        public static $word = array();
        public static $lang;
        
        
        /**
         * Lang::__construct()
         *
         */
        public function __construct()
        {
        }
        
        
        /**
         * Lang::Run()
         *
         * @return void
         */
        public static function Run()
        {
            self::get();
        }
        
        /**
         * Lang::Index()
         *
         * @return void
         */
        public function Index()
        {
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = "admin/";
            $tpl->title = Lang::$word->LMG_TITLE;
            $tpl->crumbs = ['admin', Lang::$word->LMG_TITLE];
            $tpl->data = simplexml_load_file(BASEPATH . self::langdir . Core::$language . ".lang.xml");
            $tpl->sections = self::getSections();
            $tpl->template = 'admin/language.tpl.php';
        }
        
        /**
         * Lang::get()
         *
         * @return void
         */
        private static function get()
        {
            $core = App::Core();
            if (isset($_COOKIE['LANG_CDP'])) {
                $sel_lang = sanitize($_COOKIE['LANG_CDP'], 2);
                $vlang = self::fetchLanguage();
                if (in_array($sel_lang, $vlang)) {
                    Core::$language = $sel_lang;
                } else {
                    Core::$language = $core->lang;
                }
                if (file_exists(BASEPATH . self::langdir . Core::$language . ".lang.xml")) {
                    self::$word = self::set(BASEPATH . self::langdir . Core::$language . ".lang.xml");
                } else {
                    self::$word = self::set(BASEPATH . self::langdir . $core->lang . ".lang.xml");
                }
            } else {
                Core::$language = $core->lang;
                self::$word = self::set(BASEPATH . self::langdir . $core->lang . ".lang.xml");
                
            }
            self::$lang = "_" . Core::$language;
        }
        
        /**
         * Lang::set()
         *
         * @param $lang
         * @return stdClass
         */
        private static function set($lang)
        {
            $xmlel = simplexml_load_file($lang);
            $data = new stdClass();
            foreach ($xmlel as $pkey) {
                $key = (string)$pkey['data'];
                $data->$key = (string)str_replace(array('\'', '"'), array("&apos;", "&quot;"), $pkey);
            }
            
            return $data;
        }
        
        /**
         * Lang::getSections()
         *
         * @return array
         */
        public static function getSections()
        {
            $xmlel = simplexml_load_file(BASEPATH . self::langdir . Core::$language . ".lang.xml");
            $query = '/language/phrase[not(@section = preceding-sibling::phrase/@section)]/@section';
            
            $sections = [];
            
            foreach ($xmlel->xpath($query) as $text) {
                $sections[] = (string )$text;
            }
            asort($sections);
            return $sections;
        }
        
        /**
         * Lang::fetchLanguage()
         *
         * @return array
         */
        public static function fetchLanguage()
        {
            $directory = BASEPATH . self::langdir;
            return File::findFiles($directory, array('fileTypes' => array('xml'), 'returnType' => 'fileOnly'));
        }
        
        /**
         * Lang:::langIcon()
         *
         * @return string
         */
        public static function langIcon()
        {
            return "<div class=\"wojo primary tiny button\">" . strtoupper(Core::$language) . "</div>";
        }
    }