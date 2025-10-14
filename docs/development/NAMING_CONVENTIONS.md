# ChatCart Web Naming Conventions

This document defines the naming conventions used throughout the ChatCart Web project to ensure consistency and maintainability.

## File Naming

### General Rules
- Use lowercase letters only
- Use hyphens (`-`) to separate words, not underscores
- Use descriptive names that clearly indicate the file's purpose
- Use appropriate file extensions (.php, .js, .css, .html, etc.)

### Examples
```
// Good
user-controller.php
product-card.css
main-layout.html
api-helper.js

// Avoid
userController.php
product_card.css
MainLayout.html
apiHelper.js
```

## Directory Naming

### General Rules
- Use lowercase letters only
- Use hyphens (`-`) to separate words
- Use singular form for directories containing a single type of item
- Use plural form for directories containing multiple items of the same type

### Examples
```
// Good
/controllers/
/models/
/views/
/assets/
/components/
/helpers/

// Avoid
/Controller/
/Models/
/View/
/Asset/
/Component/
```

## PHP Class Naming

### General Rules
- Use PascalCase (UpperCamelCase)
- Use descriptive names that clearly indicate the class's purpose
- Use singular form for classes
- Suffix interface names with "Interface"
- Suffix abstract classes with "Abstract"
- Suffix exception classes with "Exception"

### Examples
```php
// Good
class UserController { }
class ProductModel { }
class DatabaseConnection { }
interface PaymentInterface { }
abstract class BaseController { }
class ValidationException { }

// Avoid
class user_controller { }
class product-model { }
class database_connection { }
```

## PHP Function and Variable Naming

### General Rules
- Use camelCase (lowerCamelCase)
- Use descriptive names that clearly indicate the purpose
- Use verbs for functions
- Use nouns for variables
- Use boolean variables with prefixes like "is", "has", "can", etc.

### Examples
```php
// Good
function getUserById($id) { }
function calculateTotalPrice($items) { }
$currentUser = null;
$isAuthenticated = false;
$hasPermission = true;

// Avoid
function get_user_by_id($id) { }
function calculate_total_price($items) { }
$current_user = null;
$authenticated = false;
```

## CSS Class and ID Naming

### General Rules
- Use lowercase letters only
- Use hyphens (`-`) to separate words
- Use descriptive names that clearly indicate the purpose
- Use BEM (Block Element Modifier) methodology when appropriate
- Prefix JavaScript hooks with "js-"

### Examples
```css
/* Good */
.header-navigation { }
.product-card { }
.product-card__title { }
.btn--primary { }
.js-submit-form { }

/* Avoid */
.headerNavigation { }
.product_card { }
.productCardTitle { }
.btn_primary { }
.submitForm { }
```

## JavaScript Variable and Function Naming

### General Rules
- Use camelCase (lowerCamelCase)
- Use descriptive names that clearly indicate the purpose
- Use verbs for functions
- Use nouns for variables
- Use boolean variables with prefixes like "is", "has", "can", etc.
- Prefix jQuery objects with "$"

### Examples
```javascript
// Good
function validateForm() { }
const currentUser = {};
const isAuthenticated = false;
const $submitButton = $('#submit-button');

// Avoid
function validate_form() { }
const current_user = {};
const authenticated = false;
const submitButton = $('#submit-button');
```

## Database Naming

### General Rules
- Use lowercase letters only
- Use underscores (`_`) to separate words
- Use singular form for table names
- Use descriptive names that clearly indicate the purpose
- Prefix foreign key columns with the referenced table name

### Examples
```sql
-- Good
CREATE TABLE user (
    id INT PRIMARY KEY,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    email VARCHAR(100)
);

CREATE TABLE product (
    id INT PRIMARY KEY,
    user_id INT,
    product_name VARCHAR(100),
    FOREIGN KEY (user_id) REFERENCES user(id)
);

-- Avoid
CREATE TABLE Users (
    ID INT PRIMARY KEY,
    FirstName VARCHAR(50),
    LastName VARCHAR(50)
);
```

## API Endpoint Naming

### General Rules
- Use lowercase letters only
- Use hyphens (`-`) to separate words
- Use plural nouns for resource names
- Use HTTP methods to indicate actions
- Use query parameters for filtering, sorting, and pagination

### Examples
```
// Good
GET /api/v1/products
POST /api/v1/products
GET /api/v1/products/123
PUT /api/v1/products/123
DELETE /api/v1/products/123
GET /api/v1/products?category=electronics&limit=10

// Avoid
GET /api/v1/product
GET /api/v1/Product
GET /api/v1/products/get
```

## Configuration Constants

### General Rules
- Use uppercase letters only
- Use underscores (`_`) to separate words
- Use descriptive names that clearly indicate the purpose
- Prefix with a relevant category when appropriate

### Examples
```php
// Good
define('DB_HOST', 'localhost');
define('API_BASE_URL', 'https://api.example.com');
define('MAX_LOGIN_ATTEMPTS', 5);

// Avoid
define('db_host', 'localhost');
define('api-base-url', 'https://api.example.com');
define('maxLoginAttempts', 5);
```

## Git Branch Naming

### General Rules
- Use lowercase letters only
- Use hyphens (`-`) to separate words
- Use prefixes to indicate the type of work
- Be descriptive but concise

### Prefixes
- `feature/` - New features
- `bugfix/` - Bug fixes
- `hotfix/` - Critical bug fixes for production
- `release/` - Release preparation
- `refactor/` - Code refactoring
- `docs/` - Documentation changes

### Examples
```
// Good
feature/user-authentication
bugfix/login-error-message
hotfix/security-patch
release/v2.1.0
refactor/database-connection
docs/api-documentation

// Avoid
feature/userAuthentication
bugFix/login-error
Release/V2.1.0
```

## Commit Message Conventions

### General Rules
- Use present tense ("Add feature" not "Added feature")
- Use imperative mood ("Move cursor to..." not "Moves cursor to...")
- Start with a capital letter
- Keep the first line under 50 characters
- Use the body to explain what and why vs. how

### Format
```
<type>(<scope>): <subject>

<body>

<footer>
```

### Types
- `feat`: A new feature
- `fix`: A bug fix
- `docs`: Documentation only changes
- `style`: Changes that do not affect the meaning of the code
- `refactor`: A code change that neither fixes a bug nor adds a feature
- `perf`: A code change that improves performance
- `test`: Adding missing tests or correcting existing tests
- `build`: Changes that affect the build system or external dependencies
- `ci`: Changes to our CI configuration files and scripts
- `chore`: Other changes that don't modify src or test files
- `revert`: Reverts a previous commit

### Examples
```
// Good
feat(auth): add user login functionality
fix(api): resolve product search timeout issue
docs(readme): update installation instructions
refactor(database): optimize query performance

// Avoid
added login
fixed bug
updated docs
```

## Versioning

### General Rules
- Follow Semantic Versioning (SemVer)
- Format: MAJOR.MINOR.PATCH
- MAJOR version when you make incompatible API changes
- MINOR version when you add functionality in a backward compatible manner
- PATCH version when you make backward compatible bug fixes

### Examples
```
// Good
1.0.0
2.1.3
10.5.2

// Avoid
v1.0
1.0.0.1
version-1-0-0
```

## Summary

Consistent naming conventions improve code readability, maintainability, and collaboration. All team members should follow these conventions to ensure a cohesive codebase.