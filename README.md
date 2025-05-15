
# 📝 Todo Project

A Symfony 7 application running in a Docker environment, with Composer support and Doctrine migrations.  
This app helps you create and manage todo lists and their items.

---

## 🧰 Prerequisites

Make sure the following are installed on your machine:

- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/)
- PHP 8.3.6
- Symfony CLI (optional): [https://symfony.com/download](https://symfony.com/download)

---

## 🚀 Getting Started

### 1. Clone the Repository

```bash
git clone git@github.com:vinda-halarnkar/symfony-todo-app.git
cd symfony-todo
```

### 2. Build and Start Docker Containers

```bash
docker compose up -d --build
```

### 3. Install PHP Dependencies with Composer

```bash
docker exec -it symfony_php composer install
```

### 4. Set Up Environment Variables
```bash
cp .env .env.example
```

### 5. Database & Migrations
```bash
docker exec -it symfony_php bin/console make:migration
docker exec -it symfony_php bin/console doctrine:migrations:migrate --no-interaction
```

### 6. Access the Application
Visit the app in your browser: http://localhost:8082/


