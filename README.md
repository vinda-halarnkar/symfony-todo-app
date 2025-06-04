
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
cd symfony-todo-app
```

### 2. Set Up Environment Variables
```bash
cp .env.example .env 
```

### 3. Build and Start Docker Containers

```bash
docker compose up --build
```

### 4. Install PHP Dependencies with Composer

```bash
docker exec -it symfony_php composer install
```

### 5. Database & Migrations
```bash
docker exec -it symfony_php bin/console make:migration
```
```bash
docker exec -it symfony_php bin/console doctrine:migrations:migrate --no-interaction
```

### 6. Access the Application
Visit the app in your browser: http://localhost:8082/

1. Create a account http://localhost:8082/register
2. A verification link will be available in console. "Activation Link {link}"
3. Once verified login here http://localhost:8082/login
4. Create list, and add items to the lists
5. You can edit, mark item as complete and delete the item as well
6. Sort by name and date also available for items



