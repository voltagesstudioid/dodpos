# Getting Started

<cite>
**Referenced Files in This Document**
- [composer.json](file://composer.json)
- [package.json](file://package.json)
- [config/app.php](file://config/app.php)
- [config/database.php](file://config/database.php)
- [bootstrap/app.php](file://bootstrap/app.php)
- [routes/web.php](file://routes/web.php)
- [database/migrations/0001_01_01_000000_create_users_table.php](file://database/migrations/0001_01_01_000000_create_users_table.php)
- [database/migrations/2026_03_12_130000_add_registration_approval_fields_to_users_table.php](file://database/migrations/2026_03_12_130000_add_registration_approval_fields_to_users_table.php)
- [database/migrations/2026_03_12_150000_create_app_roles_table.php](file://database/migrations/2026_03_12_150000_create_app_roles_table.php)
- [app/Http/Middleware/CheckRole.php](file://app/Http/Middleware/CheckRole.php)
- [app/Models/AppRole.php](file://app/Models/AppRole.php)
</cite>

## Table of Contents
1. [Introduction](#introduction)
2. [Prerequisites](#prerequisites)
3. [Installation Steps](#installation-steps)
4. [Environment Configuration](#environment-configuration)
5. [Database Setup](#database-setup)
6. [Initial Migrations and Seeders](#initial-migrations-and-seeders)
7. [Admin User Creation and Role Assignment](#admin-user-creation-and-role-assignment)
8. [First-Time System Configuration](#first-time-system-configuration)
9. [Verification Checklist](#verification-checklist)
10. [Common Issues and Solutions](#common-issues-and-solutions)
11. [Development vs Production Deployment](#development-vs-production-deployment)
12. [Troubleshooting Guide](#troubleshooting-guide)
13. [Conclusion](#conclusion)

## Introduction
This guide walks you through installing and setting up DODPOS, a Laravel 12-based Point of Sale and Inventory Management system. It covers PHP 8.2+ requirements, Laravel framework setup, database configuration, environment variables, npm dependencies, initial migrations, and the creation of your first admin user. It also includes production deployment considerations, common installation pitfalls, and verification steps to ensure a successful setup.

## Prerequisites
Before installing DODPOS, ensure your environment meets the following requirements:
- PHP 8.2 or higher
- Composer (PHP dependency manager)
- Node.js and npm (for asset compilation)
- A web server (Apache/Nginx) or PHP built-in server
- MySQL/MariaDB or SQLite database server
- Git (recommended for version control)

These requirements are reflected in the project configuration:
- PHP version constraint: [composer.json:8-15](file://composer.json#L8-L15)
- Laravel framework requirement: [composer.json:11](file://composer.json#L11)
- Node.js and npm dependencies: [package.json:1-22](file://package.json#L1-L22)

**Section sources**
- [composer.json:8-15](file://composer.json#L8-L15)
- [package.json:1-22](file://package.json#L1-L22)

## Installation Steps
Follow these step-by-step instructions to install DODPOS:

1. **Clone or download the repository** to your web server directory.
2. **Install PHP dependencies** using Composer:
   - Navigate to the project root and run: `composer install`
3. **Create the environment file**:
   - Copy `.env.example` to `.env` (the project includes a Composer script to automate this during setup)
4. **Generate the application key**:
   - Run: `php artisan key:generate`
5. **Run database migrations**:
   - Execute: `php artisan migrate --force`
6. **Install frontend dependencies**:
   - Run: `npm install`
7. **Compile assets**:
   - Run: `npm run build`
8. **Start the development server** (optional):
   - Use: `php artisan serve` and `npm run dev` concurrently for hot-reload

The Composer scripts encapsulate the full setup workflow:
- Setup script: [composer.json:39-46](file://composer.json#L39-L46)
- Development script: [composer.json:47-50](file://composer.json#L47-L50)

**Section sources**
- [composer.json:39-46](file://composer.json#L39-L46)
- [composer.json:47-50](file://composer.json#L47-L50)

## Environment Configuration
Configure your `.env` file with the following key settings:

- Application name and environment:
  - `APP_NAME`: Your system name (default: "Laravel")
  - `APP_ENV`: Environment mode (default: "production")
  - `APP_DEBUG`: Enable/disable debug mode
  - `APP_URL`: Base URL for your application
  - `APP_TIMEZONE`: Default timezone (already configured to Asia/Jakarta)
  - Locales: `APP_LOCALE`, `APP_FALLBACK_LOCALE`, `APP_FAKER_LOCALE`

- Database configuration:
  - Choose a connection: `DB_CONNECTION` (sqlite, mysql, mariadb, pgsql, sqlsrv)
  - For MySQL/MariaDB:
    - `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
    - Optional SSL: `MYSQL_ATTR_SSL_CA` (when applicable)
  - For SQLite:
    - `DB_DATABASE` points to the SQLite file path

- Redis configuration (optional):
  - `REDIS_CLIENT`, `REDIS_HOST`, `REDIS_PORT`, `REDIS_PASSWORD`, `REDIS_DB`, etc.

These defaults and options are defined in the configuration files:
- Application settings: [config/app.php:16-100](file://config/app.php#L16-L100)
- Database connections: [config/database.php:19-116](file://config/database.php#L19-L116)
- Redis settings: [config/database.php:145-181](file://config/database.php#L145-L181)

**Section sources**
- [config/app.php:16-100](file://config/app.php#L16-L100)
- [config/database.php:19-116](file://config/database.php#L19-L116)
- [config/database.php:145-181](file://config/database.php#L145-L181)

## Database Setup
DODPOS supports multiple database systems. By default, the configuration enables SQLite, but MySQL/MariaDB is recommended for production.

Key points:
- Default connection is controlled by `DB_CONNECTION` (default: sqlite)
- MySQL/MariaDB settings include charset, collation, and optional SSL CA
- Redis is configurable for caching and queues

Connection options and defaults:
- Default connection: [config/database.php:19](file://config/database.php#L19)
- SQLite connection: [config/database.php:34-44](file://config/database.php#L34-L44)
- MySQL connection: [config/database.php:46-64](file://config/database.php#L46-L64)
- MariaDB connection: [config/database.php:66-84](file://config/database.php#L66-L84)
- PostgreSQL and SQL Server connections: [config/database.php:86-114](file://config/database.php#L86-L114)
- Redis client and options: [config/database.php:145-181](file://config/database.php#L145-L181)

**Section sources**
- [config/database.php:19](file://config/database.php#L19)
- [config/database.php:34-44](file://config/database.php#L34-L44)
- [config/database.php:46-64](file://config/database.php#L46-L64)
- [config/database.php:66-84](file://config/database.php#L66-L84)
- [config/database.php:86-114](file://config/database.php#L86-L114)
- [config/database.php:145-181](file://config/database.php#L145-L181)

## Initial Migrations and Seeders
DODPOS ships with a comprehensive set of migrations covering users, sessions, roles, inventory, POS, HR/payroll, and more. During setup, run:
- `php artisan migrate --force`

Important migration highlights:
- Users and sessions: [database/migrations/0001_01_01_000000_create_users_table.php:14-38](file://database/migrations/0001_01_01_000000_create_users_table.php#L14-L38)
- Registration approval fields for users: [database/migrations/2026_03_12_130000_add_registration_approval_fields_to_users_table.php:11-17](file://database/migrations/2026_03_12_130000_add_registration_approval_fields_to_users_table.php#L11-L17)
- App roles table and default roles: [database/migrations/2026_03_12_150000_create_app_roles_table.php:12-50](file://database/migrations/2026_03_12_150000_create_app_roles_table.php#L12-L50)

The application registers custom middleware and aliases for role-based access control, which rely on the presence of roles and user records:
- Middleware alias registration: [bootstrap/app.php:16-28](file://bootstrap/app.php#L16-L28)
- Role middleware implementation: [app/Http/Middleware/CheckRole.php:17-73](file://app/Http/Middleware/CheckRole.php#L17-L73)

**Section sources**
- [database/migrations/0001_01_01_000000_create_users_table.php:14-38](file://database/migrations/0001_01_01_000000_create_users_table.php#L14-L38)
- [database/migrations/2026_03_12_130000_add_registration_approval_fields_to_users_table.php:11-17](file://database/migrations/2026_03_12_130000_add_registration_approval_fields_to_users_table.php#L11-L17)
- [database/migrations/2026_03_12_150000_create_app_roles_table.php:12-50](file://database/migrations/2026_03_12_150000_create_app_roles_table.php#L12-L50)
- [bootstrap/app.php:16-28](file://bootstrap/app.php#L16-L28)
- [app/Http/Middleware/CheckRole.php:17-73](file://app/Http/Middleware/CheckRole.php#L17-L73)

## Admin User Creation and Role Assignment
To create an initial admin user and assign roles:

1. **Create a user record**:
   - Use the users table schema as a reference: [database/migrations/0001_01_01_000000_create_users_table.php:14-22](file://database/migrations/0001_01_01_000000_create_users_table.php#L14-L22)

2. **Add role-related fields**:
   - The migration adds approval fields to users: [database/migrations/2026_03_12_130000_add_registration_approval_fields_to_users_table.php:11-17](file://database/migrations/2026_03_12_130000_add_registration_approval_fields_to_users_table.php#L11-L17)

3. **Assign a role**:
   - Roles are stored in the `app_roles` table with predefined keys and labels: [database/migrations/2026_03_12_150000_create_app_roles_table.php:21-49](file://database/migrations/2026_03_12_150000_create_app_roles_table.php#L21-L49)
   - Role model definition: [app/Models/AppRole.php:8-31](file://app/Models/AppRole.php#L8-L31)

4. **Role-based access control**:
   - The role middleware enforces access per user role: [app/Http/Middleware/CheckRole.php:17-73](file://app/Http/Middleware/CheckRole.php#L17-L73)
   - Routes demonstrate role-based permissions: [routes/web.php:42-59](file://routes/web.php#L42-L59)

Note: The project does not include a dedicated CLI command for initial admin creation. You can create the user via database tools or by implementing a console command.

**Section sources**
- [database/migrations/0001_01_01_000000_create_users_table.php:14-22](file://database/migrations/0001_01_01_000000_create_users_table.php#L14-L22)
- [database/migrations/2026_03_12_130000_add_registration_approval_fields_to_users_table.php:11-17](file://database/migrations/2026_03_12_130000_add_registration_approval_fields_to_users_table.php#L11-L17)
- [database/migrations/2026_03_12_150000_create_app_roles_table.php:21-49](file://database/migrations/2026_03_12_150000_create_app_roles_table.php#L21-L49)
- [app/Models/AppRole.php:8-31](file://app/Models/AppRole.php#L8-L31)
- [app/Http/Middleware/CheckRole.php:17-73](file://app/Http/Middleware/CheckRole.php#L17-L73)
- [routes/web.php:42-59](file://routes/web.php#L42-L59)

## First-Time System Configuration
After migrations and user creation, configure the system for first use:

- Set up the default roles (already created by migration) and assign them to users
- Configure store settings if applicable (see related migration and routes)
- Review and adjust middleware and route permissions as needed

The application bootstraps custom middleware and aliases, enabling role-based routing:
- Middleware alias registration: [bootstrap/app.php:16-28](file://bootstrap/app.php#L16-L28)
- Route examples with role checks: [routes/web.php:42-59](file://routes/web.php#L42-L59)

**Section sources**
- [bootstrap/app.php:16-28](file://bootstrap/app.php#L16-L28)
- [routes/web.php:42-59](file://routes/web.php#L42-L59)

## Verification Checklist
Before declaring installation complete, verify:

- PHP version meets requirements: [composer.json:9](file://composer.json#L9)
- Composer dependencies installed and autoload working
- `.env` file exists and contains valid database credentials
- Application key generated: [composer.json:42](file://composer.json#L42)
- Database migrations executed successfully: [composer.json:43](file://composer.json#L43)
- Frontend dependencies installed: [package.json:9-20](file://package.json#L9-L20)
- Assets compiled: [package.json:6-7](file://package.json#L6-L7)
- Web server can serve the application and routes resolve correctly

Optional development verification:
- Serve the app locally: [composer.json:49](file://composer.json#L49)

**Section sources**
- [composer.json:9](file://composer.json#L9)
- [composer.json:42](file://composer.json#L42)
- [composer.json:43](file://composer.json#L43)
- [package.json:6-7](file://package.json#L6-L7)
- [package.json:9-20](file://package.json#L9-L20)
- [composer.json:49](file://composer.json#L49)

## Common Issues and Solutions
Below are typical installation problems and their resolutions:

- PHP version mismatch:
  - Symptom: Composer fails with PHP version constraints
  - Solution: Upgrade to PHP 8.2+ as required by the project

- Missing or invalid database credentials:
  - Symptom: Migration or application errors referencing database connection
  - Solution: Update `.env` with correct `DB_*` values and ensure the database server is reachable

- Composer autoload errors:
  - Symptom: Class not found or autoloader issues
  - Solution: Run `composer install` and clear caches if necessary

- Node/npm dependency failures:
  - Symptom: Build errors or missing modules
  - Solution: Install dependencies with `npm install` and rebuild assets with `npm run build`

- Role middleware access denied:
  - Symptom: 403 errors when accessing protected routes
  - Solution: Assign a valid role to the user and ensure the role exists in `app_roles`

- Session or authentication issues:
  - Symptom: Redirect loops or inability to stay logged in
  - Solution: Verify `SESSION_*` configuration and ensure the sessions table exists after migrations

**Section sources**
- [composer.json:9](file://composer.json#L9)
- [config/database.php:19-116](file://config/database.php#L19-L116)
- [package.json:9-20](file://package.json#L9-L20)
- [app/Http/Middleware/CheckRole.php:17-73](file://app/Http/Middleware/CheckRole.php#L17-L73)

## Development vs Production Deployment
- Development:
  - Use `APP_ENV=local` and enable `APP_DEBUG` for detailed error reporting
  - Run concurrent development servers: [composer.json:49](file://composer.json#L49)
  - Use SQLite for simplicity during local development

- Production:
  - Set `APP_ENV=production` and disable `APP_DEBUG`
  - Use MySQL/MariaDB with proper SSL configuration if required: [config/database.php:62-63](file://config/database.php#L62-L63)
  - Precompile assets and ensure proper file permissions
  - Secure `.env` and avoid committing secrets to version control

**Section sources**
- [config/app.php:29](file://config/app.php#L29)
- [config/app.php:42](file://config/app.php#L42)
- [config/database.php:62-63](file://config/database.php#L62-L63)
- [composer.json:49](file://composer.json#L49)

## Troubleshooting Guide
- Application key not set:
  - Run: `php artisan key:generate`
- Database not migrated:
  - Run: `php artisan migrate --force`
- Assets not compiling:
  - Run: `npm install` followed by `npm run build`
- Role-based access issues:
  - Verify user role assignment and existence of roles in `app_roles`: [database/migrations/2026_03_12_150000_create_app_roles_table.php:21-49](file://database/migrations/2026_03_12_150000_create_app_roles_table.php#L21-L49)
- Middleware misconfiguration:
  - Check middleware aliases and custom role middleware: [bootstrap/app.php:16-28](file://bootstrap/app.php#L16-L28), [app/Http/Middleware/CheckRole.php:17-73](file://app/Http/Middleware/CheckRole.php#L17-L73)

**Section sources**
- [composer.json:42](file://composer.json#L42)
- [composer.json:43](file://composer.json#L43)
- [package.json:6-7](file://package.json#L6-L7)
- [database/migrations/2026_03_12_150000_create_app_roles_table.php:21-49](file://database/migrations/2026_03_12_150000_create_app_roles_table.php#L21-L49)
- [bootstrap/app.php:16-28](file://bootstrap/app.php#L16-L28)
- [app/Http/Middleware/CheckRole.php:17-73](file://app/Http/Middleware/CheckRole.php#L17-L73)

## Conclusion
You now have the essential steps to install and configure DODPOS. Ensure your environment meets the prerequisites, configure `.env` appropriately, run migrations, compile assets, and create your first admin user with the correct role. Use the verification checklist and troubleshooting guide to resolve common issues quickly. For production, harden configurations, switch to MySQL/MariaDB, and precompile assets for optimal performance.