<?php
    /**
     * Bootstrap Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: bootstrap.php, v1.00 2022-03-05 10:12:05 gewa Exp $
     */
    
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
    
    
    define('BASE', realpath(dirname(__file__)) . '/lib') . '/';
    const DS = DIRECTORY_SEPARATOR;
    
    class Bootstrap
    {
        private static $__loader;
        
        
        /**
         * Bootstrap::__construct()
         *
         */
        private function __construct()
        {
            spl_autoload_register(array($this, 'autoLoad'));
        }
        
        
        /**
         * Bootstrap::init()
         *
         * @return Bootstrap
         */
        public static function init()
        {
            if (self::$__loader == null) {
                self::$__loader = new self();
            }
            
            return self::$__loader;
        }
        
        /**
         * Bootstrap::Autoloader
         *
         * @staticvar boolean $is_init
         * @staticvar array $conf
         * @staticvar array $paths
         * @param array|string|NULL $class_paths
         *  when loading class paths' ex: ['path/one', 'path/two']
         *  when loading class ex: 'myclass'
         *  when returning cached paths: NULL
         * @return array|boolean
         *  (default boolean if class paths registered/loaded, or when debugging
         *  (or NULL passed as $class_paths) array of registered class paths
         *  (and loaded class files, configuration settings) returned)
         */
        public static function Autoloader($class_paths = null): bool|array
        {
            static $is_init = false;
            static $count = 0;
            
            static $conf = array(
                'basepath' => '',
                'extensions' => array('.class.php'), // multiple extensions ex: ('.php', '.class.php')
                'namespace' => ''
            );
            
            static $paths = [];
            
            if (is_null($class_paths)) {
                return $paths;
            }
            
            if (is_array($class_paths) && isset($class_paths[0]) && is_array($class_paths[0])) {
                foreach ($class_paths[0] as $k => $v) {
                    if (isset($conf[$k]) || array_key_exists($k, $conf)) {
                        $conf[$k] = $v;
                    }
                }
                unset($class_paths[0]);
            }
            
            if (!$is_init) {
                spl_autoload_extensions(implode(',', $conf['extensions']));
                $is_init = true;
            }
            
            $paths['conf'] = $conf;
            
            if (!is_array($class_paths)) {
                $class_path = str_replace('', DIRECTORY_SEPARATOR, $class_paths);
                foreach ($paths as $path) {
                    if (!is_array($path)) {
                        foreach ($conf['extensions'] as &$ext) {
                            $ext = trim($ext);
                            
                            if (file_exists($path . $class_path . $ext)) {
                                if (!isset($paths['loaded'])) {
                                    $paths['loaded'] = [];
                                }
                                
                                $paths['loaded'][] = $path . $class_path . $ext;
                                
                                require $path . $class_path . $ext;
                                Debug::AddMessage("params", __METHOD__ . '[' . ++$count . ']', 'autoloaded class "' . $path . $class_path . $ext);
                                
                                return true;
                            }
                        }
                    }
                }
                
                //Debug::AddMessage("params", __METHOD__.'[' . ++$count . ']', 'failed to autoload class "' . $path . $class_path . $ext, "session");
                return false; // failed to autoload class
            } else {
                $is_unregistered = true;
                
                if (count($class_paths) > 0) {
                    foreach ($class_paths as $path) {
                        $tmp_path = File::_fixPath($path);
                        
                        if (!in_array($tmp_path, $paths)) {
                            $paths[] = $tmp_path;
                            Debug::AddMessage("params", __METHOD__ . '[' . ++$count . ']', 'registered path "' . 'autoloaded class "' . $tmp_path, "session");
                        }
                    }
                    
                    if (spl_autoload_register('Bootstrap::autoloader')) {
                        $is_unregistered = false; // flag unable to register
                    } else {
                        Debug::AddMessage("params", __METHOD__ . '[' . ++$count . ']', 'autoload register failed', "session");
                    }
                }
                
                return !DEBUG ? !$is_unregistered : $paths;
            }
        }
        
        /**
         * Bootstrap::autoLoad()
         *
         * @param string $class
         * @return true|void
         */
        public function autoLoad(string $class)
        {
            $exts = array('.php', '.class.php');
            
            spl_autoload_extensions("'" . implode(',', $exts) . "'");
            set_include_path(get_include_path() . PATH_SEPARATOR . BASE);
            
            foreach ($exts as $ext) {
                if (is_readable($path = BASE . strtolower($class . $ext))) {
                    require_once $path;
                    return true;
                }
            }
            self::recursiveAutoLoad();
        }
        
        /**
         * Bootstrap::recursiveAutoLoad()
         *
         * @return void
         */
        private static function recursiveAutoLoad()
        {
            if (is_dir(BASE)) {
                if (($handle = opendir(BASE)) !== false) {
                    while (($resource = readdir($handle)) !== false) {
                        if (($resource == '..') or ($resource == '.')) {
                            continue;
                        }
                        
                        if (is_dir($dir = BASE . DS . $resource)) {
                            continue;
                        } else
                            if (is_readable($file = BASE . DS . $resource)) {
                                require_once $file;
                            }
                    }
                    closedir($handle);
                }
            }
        }
    }