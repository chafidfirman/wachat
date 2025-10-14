<?php
/**
 * Template Helper Functions
 * Provides standardized functions for rendering templates and layouts
 */

/**
 * Render a view within the main layout
 * 
 * @param string $viewPath Path to the view file
 * @param array $data Data to pass to the view
 * @param string $layout Layout file to use (default: main)
 * @return void
 */
function render_view($viewPath, $data = [], $layout = 'main') {
    // Extract data to variables
    extract($data);
    
    // Start output buffering for the content
    ob_start();
    
    // Include the view file
    $viewFile = __DIR__ . '/../../views/' . $viewPath . '.php';
    if (file_exists($viewFile)) {
        include $viewFile;
    } else {
        // Fallback to check in modules directory
        $moduleViewFile = __DIR__ . '/../../../modules/' . $viewPath . '.php';
        if (file_exists($moduleViewFile)) {
            include $moduleViewFile;
        } else {
            echo "<!-- View file not found: {$viewPath} -->";
        }
    }
    
    // Get the content
    $content = ob_get_clean();
    
    // Include the layout
    $layoutFile = __DIR__ . '/../layouts/' . $layout . '.php';
    if (file_exists($layoutFile)) {
        include $layoutFile;
    } else {
        // If layout doesn't exist, just output the content
        echo $content;
    }
}

/**
 * Render a component
 * 
 * @param string $componentName Name of the component
 * @param array $data Data to pass to the component
 * @return void
 */
function render_component($componentName, $data = []) {
    // Extract data to variables
    extract($data);
    
    // Include the component file
    $componentFile = __DIR__ . '/../components/' . $componentName . '.php';
    if (file_exists($componentFile)) {
        include $componentFile;
    } else {
        echo "<!-- Component not found: {$componentName} -->";
    }
}

/**
 * Extend a layout with sections
 * 
 * @param string $layout Layout file to extend
 * @param array $sections Sections to replace in the layout
 * @return void
 */
function extend_layout($layout, $sections = []) {
    // Store sections for later use
    global $_sections;
    $_sections = $sections;
    
    // Include the layout
    $layoutFile = __DIR__ . '/../layouts/' . $layout . '.php';
    if (file_exists($layoutFile)) {
        include $layoutFile;
    }
}

/**
 * Define a section in a view
 * 
 * @param string $name Section name
 * @param string $content Section content
 * @return void
 */
function section($name, $content = null) {
    global $_sections;
    
    if ($content !== null) {
        // Define a section
        $_sections[$name] = $content;
    } else {
        // Start a section buffer
        ob_start();
    }
}

/**
 * End a section
 * 
 * @param string $name Section name
 * @return void
 */
function end_section($name) {
    global $_sections;
    $_sections[$name] = ob_get_clean();
}

/**
 * Yield a section in a layout
 * 
 * @param string $name Section name
 * @return void
 */
function yield_section($name) {
    global $_sections;
    if (isset($_sections[$name])) {
        echo $_sections[$name];
    }
}
?>