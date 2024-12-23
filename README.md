# GPS Tracking API Backend

## Project Overview

This is a **Laravel-based API** designed for managing GPS coordinates, timestamps, and user information. It provides endpoints for authentication, listing movements, and role-based actions. The API also integrates the **Google Geocoding API** to convert addresses into GPS coordinates and stores all data in a **PostgreSQL** database.

### Key Features

- **RESTful Endpoints**: Provides API endpoints for user authentication, GPS data, and role-based access.
- **Database Integration**: Stores GPS coordinates, timestamps, and user details in a relational database.
- **Role-Based Actions**:
  - **Administrator**: Can view all user movements.
  - **Standard User**: Can view only their own movements.
- **Google Geocoding API**: Converts addresses into GPS coordinates for geolocation functionality.

---

## Prerequisites

- **PHP 8.1 or higher**
- **Composer**
- **PostgreSQL**
- **Google Geocoding API Key**

---

## Installation

1. Clone this repository:
   ```bash
   git clone [https://github.com/your-username/backend-laravel-gps.git](https://github.com/Ahava09/back-end-solar-transit]
   cd backend-laravel-gps
2- Install dependencies using Composer:
    ```bash
    composer install
3- Create a .env file in the project root and configure your environment variables:
```env
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:GENERATE_YOUR_KEY
APP_DEBUG=true
APP_URL=http://localhost
FRONTEND_URL=https://learning-squid-teaching.ngrok-free.app
MICROSERVICE_NODE_URL=https://microservice-solar-transit.vercel.app
LOG_CHANNEL=stack
LOG_LEVEL=debug

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=gps_tracking
DB_USERNAME=your_postgres_user
DB_PASSWORD=your_postgres_password
JWT_SECRET=
GOOGLE_GEOCODING_API_KEY=your_google_geocoding_api_key
4- Generate the application key:
```bas
5- php artisan key:generate
```bas
6- Run the database migrations:
```bas
php artisan migrate
7- Seed the database with test data (optional):
```bas
php artisan db:seed
8- Start the development server:
```bas
php artisan serve

## API Endpoints
Login
    POST /api/login
    Request body:
    {
        "email": "rakoto.andry@example.mg",
        "password": "password123"
    }

Register
    POST /api/register
    Request body:
    {
      "name": "John Doe",
      "email": "user@example.com",
      "password": "password",
      "password_confirmation": "password"
    }
Synchronize Users
    GET api/sync-users
Get All Users
    GET api/users
    Fetches a list of all users.
    Middleware: JwtMiddleware
Get User Path
    GET api/users/{id}
GPS Data Management
    Synchronize GPS for a User
        GET api/gps-coordinates/{id}
        Fetches and synchronizes GPS data for a specific user with the microservice.
    Synchronize GPS for All Users
        POST /gps-coordinates/
Geocoding
    Convert Address to Coordinates
        POST api/geocoding
## Database
### The database schema includes the following tables:
    users:
        id
        name
        email
        password
        role (e.g., admin, user)

    gps_coordinates:
        id
        user_id (foreign key to users)
        latitude
        longitude
        timestamp
### Migrations
Ensure migrations are applied to create the database schema:
php artisan migrate
### Add the key to your .env file:
GOOGLE_GEOCODING_API_KEY=your_api_key
## Testing
Run the test suite to ensure all endpoints work correctly:
composer update lcobucci/jwt
php artisan serve

## Deployment
Database : 'host' => env('DB_HOST', 'dpg-ctk5pvdsvqrc738chbr0-a'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'gps_coordinates'),
            'username' => env('DB_USERNAME', 'postgresql'),
            'password' => env('DB_PASSWORD', 'ECGQuQhEh02JNBQ6EFYWV2H4VzLxdmf9'),
postgresql://postgresql:ECGQuQhEh02JNBQ6EFYWV2H4VzLxdmf9@dpg-ctk5pvdsvqrc738chbr0-a.oregon-postgres.render.com/gps_coordinates
https://learning-squid-teaching.ngrok-free.app
