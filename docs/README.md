# ChatCart Web

A modern e-commerce solution for UMKM businesses in Indonesia, enabling customers to shop via WhatsApp.

## Project Structure

```
├── api/                 # RESTful API endpoints
│   └── v1/             # API version 1
│       ├── products/   # Product API
│       ├── categories/ # Category API
│       └── users/      # User API
├── core/               # Core application components
│   └── shared/         # Shared resources
│       ├── components/ # Reusable UI components
│       ├── config/     # Configuration files
│       ├── helpers/    # Helper functions
│       └── layouts/    # Page layouts
├── modules/            # Feature modules
│   ├── product/        # Product management
│   ├── category/       # Category management
│   └── user/           # User management
├── public/             # Publicly accessible files
│   ├── assets/         # Static assets (CSS, JS, images)
│   └── api/            # API entry points
└── tests/              # Test files
```

## Key Features

- Modular architecture with clear separation of concerns
- RESTful API with standardized responses
- Component-based UI system
- Environment-specific configurations
- Consistent naming conventions
- Comprehensive error handling and debugging

## Getting Started

1. Set up the database using the scripts in `/database/`
2. Configure the environment in `config.php`
3. Start the development server using `start_server.bat`

## Documentation

- [Foundation Summary](architecture/FOUNDATION_SUMMARY.md) - Overview of the application foundation
- [Naming Conventions](development/NAMING_CONVENTIONS.md) - Coding standards and naming rules
- [Architecture](architecture/ARCHITECTURE.md) - Detailed system architecture
- [Setup Instructions](setup/SETUP_INSTRUCTIONS.md) - Installation and configuration guide

## Development

This project follows modern PHP development practices with a focus on:
- Clean code principles
- Separation of concerns
- Reusable components
- Standardized APIs
- Comprehensive documentation

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
