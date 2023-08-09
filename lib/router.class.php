<?php
    /**
     * Router Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2022
     * @version $Id: router.class.php, v1.00 2022-04-20 18:20:24 gewa Exp $
     */
    
    if (!defined("_WOJO"))
        die('Direct access to this location is not allowed.');
    
    
    class Router
    {
        
        public static $path;
        public $serverBasePath;
        public $segments = array();
        public $patterns = [
            ':any' => '.*', // Any character (including /), zero or more
            ':id' => '[1-9][0-9]*', //Any digit starting with 1
            ':s' => '[a-z0-9\-]+', //One or more word characters (a-z 0-9 _) and the dash (-)
            ':d' => '\d+' //One or more digits (0-9),
        ];
        protected $notFoundCallback;
        private $afterRoutes = array();
        private $beforeRoutes = array();
        private $baseRoute = '';
        
        /**
         * Router::before()
         *
         * @param mixed $methods Allowed methods, | delimited
         * @param mixed $pattern
         * @param mixed $item
         * @return void
         */
        public function before($methods, $pattern, $item)
        {
            $pattern = $this->baseRoute . '/' . trim($pattern, '/');
            $pattern = $this->baseRoute ? rtrim($pattern, '/') : $pattern;
            
            foreach (explode('|', $methods) as $method) {
                $this->beforeRoutes[$method][] = array('pattern' => $pattern, 'item' => $item);
            }
        }
        
        /**
         * Router::all()
         *
         * Shorthand for a route accessed using any method
         * @param mixed $pattern
         * @param mixed $item
         * @return void
         */
        public function all($pattern, $item)
        {
            $this->match('GET|POST|PUT|DELETE|OPTIONS|PATCH|HEAD', $pattern, $item);
        }
        
        /**
         * Router::match()
         *
         * @param mixed $methods Allowed methods, | delimited
         * @param mixed $pattern
         * @param mixed $item
         * @return void
         */
        public function match($methods, $pattern, $item)
        {
            $pattern = $this->baseRoute . '/' . trim($pattern, '/');
            $pattern = $this->baseRoute ? rtrim($pattern, '/') : $pattern;
            
            foreach (explode('|', $methods) as $method) {
                $this->afterRoutes[$method][] = array('pattern' => $pattern, 'item' => $item);
            }
        }
        
        /**
         * Router::get()
         *
         * Shorthand for a route accessed using GET
         * @param mixed $pattern
         * @param mixed $item
         * @return void
         */
        public function get($pattern, $item)
        {
            $this->match('GET', $pattern, $item);
        }
        
        /**
         * Router::post()
         *
         * Shorthand for a route accessed using POST
         * @param mixed $pattern
         * @param mixed $item
         * @return void
         */
        public function post($pattern, $item)
        {
            $this->match('POST', $pattern, $item);
        }
        
        /**
         * Router::patch()
         *
         * Shorthand for a route accessed using PATCH
         * @param mixed $pattern
         * @param mixed $item
         * @return void
         */
        public function patch($pattern, $item)
        {
            $this->match('PATCH', $pattern, $item);
        }
        
        /**
         * Router::delete()
         *
         * Shorthand for a route accessed using DELETE
         * @param mixed $pattern
         * @param mixed $item
         * @return void
         */
        public function delete($pattern, $item)
        {
            $this->match('DELETE', $pattern, $item);
        }
        
        /**
         * Router::put()
         *
         * Shorthand for a route accessed using PUT
         * @param mixed $pattern
         * @param mixed $item
         * @return void
         */
        public function put($pattern, $item)
        {
            $this->match('PUT', $pattern, $item);
        }
        
        /**
         * Router::options()
         *
         * Shorthand for a route accessed using OPTIONS
         * @param mixed $pattern
         * @param mixed $item
         * @return void
         */
        public function options($pattern, $item)
        {
            $this->match('OPTIONS', $pattern, $item);
        }
        
        /**
         * Router::mount()
         *
         * Mounts a collection of callbacks onto a base route
         * @param mixed $baseRoute
         * @param mixed $item
         * @return void
         */
        public function mount($baseRoute, $item)
        {
            $curBaseRoute = $this->baseRoute;
            $this->baseRoute .= $baseRoute;
            call_user_func($item);
            $this->baseRoute = $curBaseRoute;
        }
        
        /**
         * Router::run()
         *
         * Execute the router
         * @param mixed $callback
         * @return bool
         */
        public function run($callback = null)
        {
            // Define which method we need to handle
            //$requestedMethod = '';
            $requestedMethod = $this->getRequestMethod();
            
            // Handle all before middlewares
            if (isset($this->beforeRoutes[$requestedMethod])) {
                Debug::addMessage('params', 'before', $this->beforeRoutes[$requestedMethod][0]['item']);
                $this->handle($this->beforeRoutes[$requestedMethod]);
            }
            
            // Handle all routes
            $numHandled = 0;
            if (isset($this->afterRoutes[$requestedMethod])) {
                $numHandled = $this->handle($this->afterRoutes[$requestedMethod], true);
            }
            
            // If no route was handled, trigger the 404 (if any)
            if ($numHandled === 0) {
                if ($this->notFoundCallback) {
                    $this->invoke($this->notFoundCallback);
                } else {
                    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
                }
                // If a route was handled, perform the finish callback (if any)
            } else {
                if ($callback && is_callable($callback)) {
                    $callback();
                }
            }
            
            // If it originally was a HEAD request, clean up after ourselves by emptying the output buffer
            if ($_SERVER['REQUEST_METHOD'] == 'HEAD') {
                ob_end_clean();
            }
            
            // Return true if a route was handled, false otherwise
            return $numHandled !== 0;
            
        }
        
        /**
         * Router::getRequestMethod()
         *
         * @return mixed|string
         */
        public function getRequestMethod()
        {
            $method = $_SERVER['REQUEST_METHOD'];
            if ($_SERVER['REQUEST_METHOD'] == 'HEAD') {
                ob_start();
                $method = 'GET';
            } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $headers = $this->getRequestHeaders();
                if (isset($headers['X-HTTP-Method-Override']) && in_array($headers['X-HTTP-Method-Override'], array('PUT', 'DELETE', 'PATCH'))) {
                    $method = $headers['X-HTTP-Method-Override'];
                }
            }
            
            return $method;
        }
        
        /**
         * Router::getRequestHeaders()
         *
         * @return array|false
         */
        public function getRequestHeaders()
        {
            $headers = array();
            
            if (function_exists('getallheaders')) {
                $headers = getallheaders();
            }
            
            if ($headers !== false) {
                return $headers;
            }
            
            foreach ($_SERVER as $name => $value) {
                if ((str_starts_with($name, 'HTTP_')) || ($name == 'CONTENT_TYPE') || ($name == 'CONTENT_LENGTH')) {
                    $headers = [str_replace(array(' ', 'Http'), array('-', 'HTTP'), ucwords(strtolower(str_replace('_', ' ', substr($name, 5))))) => $value];
                }
            }
            
            return $headers;
        }
        
        /**
         * Router::handle()
         *
         * Handle a a set of routes: if a match is found, execute the relating handling function
         * @param mixed $routes
         * @param bool $quitAfterRun
         * @return int
         */
        private function handle($routes, $quitAfterRun = false)
        {
            $numHandled = 0;
            $uri = $this->getCurrentUri();
            
            foreach ($routes as $route) {
                $route['pattern'] = preg_replace('/\/{(.*?)}/', '/(.*?)', $route['pattern']);
                if (preg_match_all('#^' . $route['pattern'] . '$#', $uri, $matches, PREG_OFFSET_CAPTURE)) {
                    $matches = array_slice($matches, 1);
                    // Extract the matched URL parameters (and only the parameters)
                    $params = array_map(function ($match, $index) use ($matches) {
                        // take the substring from the current param position until the next one's position
                        if (isset($matches[$index + 1]) && isset($matches[$index + 1][0]) && is_array($matches[$index + 1][0])) {
                            return trim(substr($match[0][0], 0, $matches[$index + 1][0][1] - $match[0][1]), '/');
                        } // return the whole lot
                        return (isset($match[0][0]) ? trim($match[0][0], '/') : null);
                    }, $matches, array_keys($matches));
                    // Call the handling function with the URL parameters if the input is callable
                    $this->invoke($route['item'], $params);
                    $numHandled++;
                    if ($quitAfterRun) {
                        break;
                    }
                }
            }
            Debug::addMessage('params', 'route', $uri);
            self::$path = $uri;
            
            return $numHandled;
            
        }
        
        /**
         * Router::getCurrentUri()
         *
         * @return string
         */
        private function getCurrentUri()
        {
            $uri = substr($_SERVER['REQUEST_URI'], strlen($this->getBasePath()));
            
            if (str_contains($uri, '?')) {
                $uri = substr($uri, 0, strpos($uri, '?'));
            }
            
            $segment = explode('/', trim($uri, '/'));
            $this->segments = empty($segment[0]) ? array('index') : $segment;
            Debug::addMessage('params', 'segment', $this->segments);
            
            return '/' . trim($uri, '/');
        }
        
        /**
         * Router::getBasePath()
         *
         * @return string
         */
        private function getBasePath()
        {
            if (null === $this->serverBasePath) {
                $this->serverBasePath = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/';
            }
            
            return $this->serverBasePath;
        }
        
        /**
         * Router::getCurrentUri()
         *
         * @param $item
         * @param array $params
         * @return void
         */
        private function invoke($item, $params = array())
        {
            if (is_callable($item)) {
                call_user_func_array($item, $params);
            } elseif (stripos($item, '@') !== false) {
                // Explode segments of given route
                $segment = explode('@', $item);
                
                // if custom class location
                if (count($segment) == 3) {
                    Bootstrap::Autoloader(array(BASEPATH . $segment[0]));
                    $controller = $segment[1];
                    $method = $segment[2];
                } else {
                    $controller = $segment[0];
                    $method = $segment[1];
                }
                
                try {
                    $reflectedMethod = new ReflectionMethod($controller, $method);
                    // Make sure it's callable
                    if ($reflectedMethod->isPublic() && (!$reflectedMethod->isAbstract())) {
                        if ($reflectedMethod->isStatic()) {
                            forward_static_call_array(array($controller, $method), $params);
                        } else {
                            // Make sure we have an instance, because a non-static method must not be called statically
                            if (is_string($controller)) {
                                $controller = new $controller();
                            }
                            call_user_func_array(array($controller, $method), $params);
                        }
                    }
                } catch (ReflectionException) {
                    Debug::AddMessage("errors", '<i>Error</i>', 'Class ' . $controller . ' doesnt exist');
                }
            }
        }
        
        /**
         * Router::set404()
         *
         * @param mixed $item
         * @return void
         */
        public function set404($item)
        {
            $this->notFoundCallback = $item;
        }
    }