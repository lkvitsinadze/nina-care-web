# Nina Care Project

This is the Nina Care project, a Laravel-based web application.

## Prerequisites

Before running the project, ensure you have the following installed on your system:

- Docker
- Docker Compose

## Installation

1. Clone the repository:
   ```bash
   git clone <repository-url>
   cd nina-care
   ```

2. Start the Docker containers using Laravel Sail:
   ```bash
   ./vendor/bin/sail up -d
   ```

3. Install PHP dependencies:
   ```bash
   ./vendor/bin/sail composer install
   ```

4. Install JavaScript dependencies:
   ```bash
   ./vendor/bin/sail npm install
   ```

5. Copy the `.env.example` file to `.env` and configure your environment variables:
   ```bash
   cp .env.example .env
   ```

6. Generate the application key:
   ```bash
   ./vendor/bin/sail artisan key:generate
   ```

7. Set up the database:
   - Update the `.env` file with your database credentials.
   - Run the database migrations:
     ```bash
     ./vendor/bin/sail artisan migrate
     ```

8. (Optional) Seed the database with a large dataset:
   - Run the MillionUsersSeeder to populate the database with a million users:
     ```bash
     ./vendor/bin/sail artisan db:seed --class=MillionUsersSeeder
     ```

## Running the Project

1. Start the development server:
   ```bash
   ./vendor/bin/sail artisan serve
   ```

2. Compile the frontend assets:
   ```bash
   ./vendor/bin/sail npm run dev
   ```

3. Open your browser and navigate to:
   ```
   http://localhost/users
   ```

   The user list is available at `/users`.

## Notes on Search Functionality

- The search functionality is not optimized for superfast performance as there was no specific requirement for it.
- It could be improved using MySQL FULLTEXT search or Laravel Scout for better performance and scalability.

## Testing

Run the test suite using PHPUnit:
```bash
./vendor/bin/sail artisan test
```

## License

This project is licensed under the MIT License.