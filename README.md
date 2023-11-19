# Company Management System

## Overview

This project is a Company Management System that facilitates the management of companies and employees. It includes features for CRUD (Create, Read, Update, Delete) operations on both companies and employees.

## Installation

1. Run `composer install` to install PHP dependencies.
2. Copy `.env.example` to `.env` with `cp .env.example .env`.
3. Generate the application key with `php artisan key:generate`.
4. Migrate and seed the database with `php artisan migrate --seed`.
5. Create a storage link with `php artisan storage:link`.

## Features

- **Company CRUD:** Perform CRUD operations on companies.
- **Employee CRUD:** Perform CRUD operations on employees.
- **Custom Middleware:** Utilizes a custom middleware for role-based access control.
- **Email Notification:** Sends email notifications to the company when a new employee is added (configure `MAIL_MAILER` in `.env`).

## Tech Stack

- **Tailwind CSS**
- **Laravel**
- **Vue.js**
- **Vite**
- **Ant Design (Antd)**


## Contributing

If you want to contribute, please follow these steps:

1. Fork the repository.
2. Create a new branch.
3. Make your changes.
4. Submit a pull request.

## License

This project is licensed under the [Your License Name] License - see the [LICENSE](LICENSE) file for details.
