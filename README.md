
# User Importer

## Project Overview

This project is an admin panel that supports the following features:
- Role-based access control for users, allowing admins to manage the system.
- Display a list of users with paginated data tables.
- Asynchronous user import from CSV files.
- Email notification system about import details
- Export of users to CSV files (with large datasets split into multiple files and zipped if needed).
- Import history display
- Loging and storing errors in database

## Technologies Used

- **Laravel**: Backend framework.
- **Laravel Breeze**: For authentication scaffolding.
- **Inertia.js**: For server-side routing and frontend rendering.
- **React**: Frontend framework.
- **Shadcn/ui**: Pre-styled UI components for React.
- **Stechstudio/laravel-zipstream**: For creating ZIP files during export.
- **Laratrust**: For role and permission management.
- **Zod**: Validation library for React forms.
- **Laravel Sail**: Docker-based development environment.
- **Pest**: Testing framework for PHP.
- **phpMyAdmin**: For database management during development.
- **Mailpit**: For email testing.

## Installation

Follow these steps to set up and run the project:

1. Clone the repository:
    ```bash
    git clone https://github.com/Zdezorientowany/user-importer.git .
    cd user-importer
    ```

2. Install dependencies using Composer and NPM:
    ```bash
    composer install
    npm install
    ```

3. Set up your `.env` file by copying the example:
    ```bash
    cp .env.example .env
    ```
4. Set up Docker using Sail:
    ```bash
    ./vendor/bin/sail up -d
    ```

5. Generate the application key:
    ```bash
    ./vendor/bin/sail php artisan key:generate
    ```

6. Run migrations and seed the database:
    ```bash
    ./vendor/bin/sail php artisan migrate:fresh --seed
    ```

7. Build frontend assets:
    ```bash
    npm run dev
    ```
    
8. Run queue:
    ```bash
    ./vendor/bin/sail php artisan queue:work
    ```

9. Access the application at `http://localhost`.

10. Log in using the seeded admin credentials (during registration you will create basic user):
    - **Email**: `admin@test.com`
    - **Password**: `asd`

## Testing

1. To run the tests, use:
    ```bash
    ./vendor/bin/sail test
    ```

2. Ensure Mailpit is running at `http://localhost:8025` to test emails.
3. phpMyAdmin is available at `http://localhost:8080` for database inspection during testing.
