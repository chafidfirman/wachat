<?php
/**
 * Naming Conventions Checker
 * Checks if files and directories follow the defined naming conventions
 */

require_once __DIR__ . '/config.php';

class NamingConventionsChecker {
    private $errors = [];
    private $warnings = [];
    
    /**
     * Check naming conventions for a directory and its contents
     * @param string $directory Path to directory
     * @param int $depth Current depth (for recursion limit)
     */
    public function checkDirectory($directory, $depth = 0) {
        // Limit recursion depth to prevent infinite loops
        if ($depth > 10) {
            return;
        }
        
        // Skip certain directories
        $skipDirs = ['.git', 'node_modules', 'vendor'];
        $dirName = basename($directory);
        if (in_array($dirName, $skipDirs)) {
            return;
        }
        
        // Check directory name
        $this->checkDirectoryName($directory);
        
        // Scan directory contents
        $items = scandir($directory);
        foreach ($items as $item) {
            // Skip current and parent directory references
            if ($item === '.' || $item === '..') {
                continue;
            }
            
            $path = $directory . DIRECTORY_SEPARATOR . $item;
            
            if (is_dir($path)) {
                // Recursively check subdirectory
                $this->checkDirectory($path, $depth + 1);
            } else {
                // Check file name
                $this->checkFileName($path);
            }
        }
    }
    
    /**
     * Check if directory name follows conventions
     * @param string $directory Path to directory
     */
    private function checkDirectoryName($directory) {
        $dirName = basename($directory);
        
        // Skip if it's a special directory
        if (in_array($dirName, ['.', '..'])) {
            return;
        }
        
        // Check for lowercase
        if ($dirName !== strtolower($dirName)) {
            $this->addError("Directory name should be lowercase: $directory");
        }
        
        // Check for valid characters (letters, numbers, hyphens, underscores)
        if (!preg_match('/^[a-z0-9\-_]+$/', $dirName)) {
            $this->addError("Directory name contains invalid characters: $directory");
        }
        
        // Check for consecutive hyphens or underscores
        if (preg_match('/[-_]{2,}/', $dirName)) {
            $this->addWarning("Directory name contains consecutive separators: $directory");
        }
    }
    
    /**
     * Check if file name follows conventions
     * @param string $file Path to file
     */
    private function checkFileName($file) {
        $fileName = basename($file);
        
        // Skip certain files
        $skipFiles = ['.gitignore', '.htaccess'];
        if (in_array($fileName, $skipFiles)) {
            return;
        }
        
        // Split filename and extension
        $parts = explode('.', $fileName);
        $extension = count($parts) > 1 ? array_pop($parts) : '';
        $name = implode('.', $parts);
        
        // Check for lowercase
        if ($name !== strtolower($name)) {
            $this->addError("File name should be lowercase: $file");
        }
        
        // Check for valid characters (letters, numbers, hyphens, underscores, dots)
        if (!preg_match('/^[a-z0-9\-_.]+$/', $name)) {
            $this->addError("File name contains invalid characters: $file");
        }
        
        // Check for consecutive hyphens or underscores
        if (preg_match('/[-_]{2,}/', $name)) {
            $this->addWarning("File name contains consecutive separators: $file");
        }
        
        // Check for PHP files specifically
        if ($extension === 'php') {
            $this->checkPhpFile($file);
        }
    }
    
    /**
     * Check PHP file for class naming conventions
     * @param string $file Path to PHP file
     */
    private function checkPhpFile($file) {
        $content = file_get_contents($file);
        
        // Look for class declarations
        if (preg_match_all('/class\s+([A-Za-z0-9_]+)/', $content, $matches)) {
            foreach ($matches[1] as $className) {
                // Check if class name follows PascalCase
                if (!$this->isPascalCase($className)) {
                    $this->addError("Class name should use PascalCase: $className in $file");
                }
            }
        }
        
        // Look for function declarations
        if (preg_match_all('/function\s+([A-Za-z0-9_]+)/', $content, $matches)) {
            foreach ($matches[1] as $functionName) {
                // Skip PHP magic methods
                $magicMethods = ['__construct', '__destruct', '__get', '__set', '__call', '__toString'];
                if (in_array($functionName, $magicMethods)) {
                    continue;
                }
                
                // Check if function name follows camelCase
                if (!$this->isCamelCase($functionName)) {
                    $this->addError("Function name should use camelCase: $functionName in $file");
                }
            }
        }
        
        // Look for constant definitions
        if (preg_match_all('/define\s*\(\s*[\'"]([A-Z0-9_]+)[\'"]/', $content, $matches)) {
            foreach ($matches[1] as $constantName) {
                // Check if constant name follows UPPER_CASE
                if (!$this->isUpperCase($constantName)) {
                    $this->addError("Constant name should use UPPER_CASE: $constantName in $file");
                }
            }
        }
    }
    
    /**
     * Check if string follows PascalCase convention
     * @param string $string String to check
     * @return bool True if follows PascalCase
     */
    private function isPascalCase($string) {
        // First character should be uppercase
        if (!ctype_upper($string[0])) {
            return false;
        }
        
        // Should not contain underscores or hyphens
        if (strpos($string, '_') !== false || strpos($string, '-') !== false) {
            return false;
        }
        
        // Should not contain spaces
        if (strpos($string, ' ') !== false) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Check if string follows camelCase convention
     * @param string $string String to check
     * @return bool True if follows camelCase
     */
    private function isCamelCase($string) {
        // First character should be lowercase
        if (!ctype_lower($string[0])) {
            return false;
        }
        
        // Should not contain underscores or hyphens
        if (strpos($string, '_') !== false || strpos($string, '-') !== false) {
            return false;
        }
        
        // Should not contain spaces
        if (strpos($string, ' ') !== false) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Check if string follows UPPER_CASE convention
     * @param string $string String to check
     * @return bool True if follows UPPER_CASE
     */
    private function isUpperCase($string) {
        // Should only contain uppercase letters, numbers, and underscores
        return preg_match('/^[A-Z0-9_]+$/', $string);
    }
    
    /**
     * Add an error
     * @param string $message Error message
     */
    private function addError($message) {
        $this->errors[] = $message;
    }
    
    /**
     * Add a warning
     * @param string $message Warning message
     */
    private function addWarning($message) {
        $this->warnings[] = $message;
    }
    
    /**
     * Get all errors
     * @return array Array of error messages
     */
    public function getErrors() {
        return $this->errors;
    }
    
    /**
     * Get all warnings
     * @return array Array of warning messages
     */
    public function getWarnings() {
        return $this->warnings;
    }
    
    /**
     * Print results
     */
    public function printResults() {
        echo "=== Naming Conventions Check Results ===\n\n";
        
        if (empty($this->errors) && empty($this->warnings)) {
            echo "✓ All files and directories follow naming conventions!\n";
            return;
        }
        
        if (!empty($this->errors)) {
            echo "Errors (" . count($this->errors) . "):\n";
            foreach ($this->errors as $error) {
                echo "  ✗ $error\n";
            }
            echo "\n";
        }
        
        if (!empty($this->warnings)) {
            echo "Warnings (" . count($this->warnings) . "):\n";
            foreach ($this->warnings as $warning) {
                echo "  ⚠ $warning\n";
            }
            echo "\n";
        }
        
        echo "Please review the docs/NAMING_CONVENTIONS.md file for guidelines.\n";
    }
}

// Run the checker if script is executed directly
if (basename(__FILE__) === basename($_SERVER['SCRIPT_NAME'])) {
    $checker = new NamingConventionsChecker();
    $checker->checkDirectory(__DIR__);
    $checker->printResults();
}
?>