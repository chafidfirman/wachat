<?php
/**
 * Simple Router Class
 * Handles routing requests to appropriate controllers in a modular structure
 */

class Router {
    private $routes = [];
    private $basePath;
    
    public function __construct($basePath = '') {
        $this->basePath = rtrim($basePath, '/');
    }
    
    /**
     * Add a route
     * @param string $method HTTP method (GET, POST, etc.)
     * @param string $pattern URL pattern
     * @param string $module Module name
     * @param string $controller Controller class name
     * @param string $action Controller method name
     */
    public function addRoute($method, $pattern, $module, $controller, $action) {
        $this->routes[] = [
            'method' => strtoupper($method),
            'pattern' => $pattern,
            'module' => $module,
            'controller' => $controller,
            'action' => $action
        ];
    }
    
    /**
     * Add a GET route
     */
    public function get($pattern, $module, $controller, $action) {
        $this->addRoute('GET', $pattern, $module, $controller, $action);
    }
    
    /**
     * Add a POST route
     */
    public function post($pattern, $module, $controller, $action) {
        $this->addRoute('POST', $pattern, $module, $controller, $action);
    }
    
    /**
     * Set a fallback handler for when no routes match
     */
    public function setFallback($module, $controller, $action) {
        $this->routes['fallback'] = [
            'module' => $module,
            'controller' => $controller,
            'action' => $action
        ];
    }
    
    /**
     * Route the request
     */
    public function route($requestMethod, $requestPath, $pdo) {
        $requestMethod = strtoupper($requestMethod);
        $requestPath = trim($requestPath, '/');
        
        // Handle empty path (root)
        if (empty($requestPath)) {
            $requestPath = '';
        }
        
        foreach ($this->routes as $key => $route) {
            // Skip fallback route in this loop
            if ($key === 'fallback') {
                continue;
            }
            
            // Check if method matches
            if ($route['method'] !== $requestMethod) {
                continue;
            }
            
            // Convert pattern to regex
            $pattern = preg_quote($route['pattern'], '/');
            $pattern = preg_replace('/\\\{([^\/]+)\\\}/', '([^\/]+)', $pattern);
            $pattern = '/^' . $pattern . '$/';
            
            // Check if path matches pattern
            if (preg_match($pattern, $requestPath, $matches)) {
                // Remove the full match
                array_shift($matches);
                
                // Try multiple naming conventions for controller files
                $controllerFile = '';
                
                // Try kebab-case
                $kebabCase = strtolower(preg_replace('/([a-z])([A-Z])/', '$1-$2', $route['controller']));
                $kebabFile = __DIR__ . '/../../modules/' . $route['module'] . '/controllers/' . $kebabCase . '.php';
                
                // Try lowercase
                $lowerFile = __DIR__ . '/../../modules/' . $route['module'] . '/controllers/' . strtolower($route['controller']) . '.php';
                
                // Try original case
                $originalFile = __DIR__ . '/../../modules/' . $route['module'] . '/controllers/' . $route['controller'] . '.php';
                
                // Check which file exists
                if (file_exists($kebabFile)) {
                    $controllerFile = $kebabFile;
                } else if (file_exists($lowerFile)) {
                    $controllerFile = $lowerFile;
                } else if (file_exists($originalFile)) {
                    $controllerFile = $originalFile;
                }
                
                if (!file_exists($controllerFile)) {
                    continue;
                }
                
                // Load and instantiate controller
                require_once $controllerFile;
                
                // Create controller instance
                $controllerClass = $route['controller'];
                $controllerInstance = new $controllerClass($pdo);
                
                // Call the action with parameters
                if (method_exists($controllerInstance, $route['action'])) {
                    call_user_func_array([$controllerInstance, $route['action']], $matches);
                    return true;
                }
            }
        }
        
        // Handle fallback if defined
        if (isset($this->routes['fallback'])) {
            $fallback = $this->routes['fallback'];
            
            // Convert PascalCase class name to kebab-case file name
            $fileName = strtolower(preg_replace('/([a-z])([A-Z])/', '$1-$2', $fallback['controller']));
            $controllerFile = __DIR__ . '/../../modules/' . $fallback['module'] . '/controllers/' . $fileName . '.php';
            
            if (!file_exists($controllerFile)) {
                // Try with original name (PascalCase)
                $controllerFile = __DIR__ . '/../../modules/' . $fallback['module'] . '/controllers/' . $fallback['controller'] . '.php';
            }
            
            if (file_exists($controllerFile)) {
                // Load and instantiate controller
                require_once $controllerFile;
                
                // Create controller instance
                $controllerClass = $fallback['controller'];
                $controllerInstance = new $controllerClass($pdo);
                
                // Call the action
                if (method_exists($controllerInstance, $fallback['action'])) {
                    $controllerInstance->{$fallback['action']}();
                    return true;
                }
            }
        }
        
        // No route matched and no fallback, return false for 404
        return false;
    }
}
?>