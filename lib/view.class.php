<?php
    
    /**
     * View Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: view.class.php, v1.00 2022-06-20 18:20:24 gewa Exp $
     */
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
    
    
    class View
    {
        public array $properties;
        public string $path;
        public string $template;
        public $dir;
        public array $crumbs;
    
    
        /**
         * View::__construct()
         *
         * @param mixed $path
         */
        public function __construct($path)
        {
            $this->properties = array();
            $this->path = $path;
        }
    
    
        /**
         * View::render()
         *
         * @return false|string
         */
        public function render()
        {
            
            try {
                if (!file_exists($this->path . $this->template)) {
                    Debug::AddMessage("errors", '<i>Exception</i>', 'filename ' . $this->path . $this->template . ' not found', "session");
                    throw new Exception($this->template . " template was not found");
                }
                Debug::addMessage('params', 'template', $this->template, "session");
                ob_start();
                if ($this->dir) {
                    include_once $this->path . $this->dir . 'header.tpl.php';
                }
                include_once($this->path . $this->template);
                if ($this->dir) {
                    include_once $this->path . $this->dir . 'footer.tpl.php';
                }
            } catch (exception $e) {
                echo 'Caught exception: ', Message::msgSingleError($e->getMessage());
            }
            
            return ob_get_clean();
        }
    
        /**
         * View::__set()
         *
         * @param mixed $name
         * @param mixed $value
         * @return void
         */
        public function __set($name, $value)
        {
            $this->properties[$name] = $value;
        }
    
        /**
         * View::__get()
         *
         * @param mixed $name
         * @return mixed|void
         */
        public function __get($name)
        {
            if (array_key_exists($name, $this->properties)) {
                return $this->properties[$name];
            }
            
        }
    }