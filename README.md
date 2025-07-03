# Cow App — DDD-style PHP Application

This project is a simple web application built with PHP, following Domain-Driven Design (DDD) principles and using Symfony components, Twig and Doctrine DBAL, but without relying on a full framework.

## Features

- User authentication and role-based access control
- Event tracking (login, logout, registration, page views, button clicks)
- Admin statistics page with filters
- Reports with charts and tables
- Pages:
    - Registration
    - Login/Logout
    - Page A with "Buy a cow" button
    - Page B with "Download" button
    - Statistics page (admin only)
    - Reports page (admin only)

## Domain-Driven Design

This project follows a clean layered architecture:

- Domain - pure business logic, no infrastructure dependencies
- Application - orchestrates use cases
- Infrastructure - database, templating, routing, etc.
- Presentation - web layer (controllers, templates)

In-memory repositories are provided for fast, isolated unit tests.

## Getting started

### Requirements

- Docker with Compose plugin
- Make

### Steps to install

```bash
git clone https://github.com/leodee/cow-app-ddd.git
cd cow-app-ddd
```


### Quick start

```bash
make up                # Start containers
make composer-install  # Install PHP dependencies
```
App will be available at: http://localhost:8080

### Predefined credentials

| Username | Password | Role  |
| -------- | -------- | ----- |
| admin    | admin    | admin |

### Tests

```bash
make test
```

## Cleanup

```bash
make down
```

## License

MIT — free for personal and educational use.