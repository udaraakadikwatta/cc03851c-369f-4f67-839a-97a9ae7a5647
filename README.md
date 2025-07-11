## 📊 Assessment Reporting System

A PHP CLI application for generating student assessment reports (Diagnostic, Progress, and Feedback) from JSON data files. Built with PHP 8.1, Docker, and PHPUnit.

## 🔧 Requirements

- PHP >= 8.1
- Composer
- Docker & Docker Compose

## 🚀 Setup Instructions

```bash

# Clone the Repository
git clone https://github.com/udaraakadikwatta/cc03851c-369f-4f67-839a-97a9ae7a5647.git
cd cc03851c-369f-4f67-839a-97a9ae7a5647

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

