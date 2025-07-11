## 📊 Assessment Reporting System

A PHP CLI application for generating student assessment reports (Diagnostic, Progress, and Feedback) from JSON data files. Built with PHP 8.2, Docker, and PHPUnit.

## 🔧 Requirements

- PHP >= 8.1
- Composer
- Docker & Docker Compose

## 🚀 Setup Instructions

```bash

# Clone the Repository
git clone https://github.com/udaraakadikwatta/f3ad0a92-a6f2-4ac6-ab91-b18804617a14.git
cd f3ad0a92-a6f2-4ac6-ab91-b18804617a14

# Build and Start Docker Containers
docker-compose build

# Install PHP Dependencies
docker-compose run app composer install

# Run the CLI Application
docker-compose run app

```
## 🧪 Running Tests

```bash

docker-compose run test

