<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 2000 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

# Library Management System with Ratings and Book Borrowing

This is a Laravel-based Library Management System that allows users to manage books, borrow and return books, and add ratings for books. The system also includes an admin role for managing all data and additional features like filtering books and calculating average ratings.

## Features

- **Book Management**: CRUD (Create, Read, Update, Delete) operations for books.
- **User Management**: CRUD operations for users with different roles (admin, user).
- **Book Borrowing**: Registered users can borrow and return books.
- **Book Rating**: Users can add, update, view, and delete ratings for books. Average rating for each book is automatically calculated.
- **Admin Capabilities**: Admin can approve or reject borrow requests and manage all data.
- **Book Filtering**: Filter books based on author, category, and availability.
- **Authorization**: Role-based access control (admin, user) with JWT for API authentication.

## Project Structure

- **Models**: Represent the database tables and relationships.
- **Controllers**: Handle the logic for each feature (Books, Users, Ratings, etc.).
- **Services**: Business logic to separate concerns from controllers.
- **Requests**: Form request validation to ensure data integrity.
- **Policies**: Used for authorization based on user roles.
- **Resources**: Transform models for JSON API responses.

## Installation

1. Clone the repository:

   ```bash
   git clone  https://github.com/FatenHussen/libraryManagementSystem.git
 
2. Install dependencies:
cd library-management-system
composer install

3. Copy the .env.example file to .env:
cp .env.example .env
4. Generate the application key:
php artisan key:generate

5. In the .env file, configure your database settings:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=library
DB_USERNAME=root
DB_PASSWORD=

6. Run migrations to create the necessary tables:
    php artisan migrate


7. Run database seeders (for creating default admin user and roles):
        php artisan db:seed

8. Install JWT for API authentication:
    php artisan jwt:secret

9. Start the server:
    php artisan serve
API Endpoints
Authentication
Login: /api/login (POST)
Register: /api/register (POST)
Logout: /api/logout (POST)
Book Management (Admin)
List Books: /api/books (GET)
Create Book: /api/books (POST)
Update Book: /api/books/{id} (PUT)
Delete Book: /api/books/{id} (DELETE)
Borrowing Books (User)
Borrow a Book: /api/borrows (POST)
Return a Book: /api/borrows/return (PUT)
Get User Borrow Records: /api/borrows/my (GET)
Book Ratings
List Ratings: /api/ratings (GET) (Admin gets all, or filter by book using ?book_id={bookId})
Create Rating: /api/ratings (POST)
Update Rating: /api/ratings/{id} (PUT)
Delete Rating: /api/ratings/{id} (DELETE)
Book Filtering
Filter Books: /api/books/filter (GET) (Use query params: ?author=, ?category=, ?available=1)
Admin Actions
Approve Borrow: /api/borrows/{id}/accept (PUT)
Reject Borrow: /api/borrows/{id}/reject (PUT)
Authorization
Admin Role: Full control over books, users, and borrow requests.
User Role: Can borrow and return books, rate books.
JWT Authentication
Authentication is handled via JWT (JSON Web Tokens).

After logging in, the API will return a token that must be included in the Authorization header for all subsequent requests.



        
