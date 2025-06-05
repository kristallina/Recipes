# 🍳 Recipes Microservices System

![Docker](https://img.shields.io/badge/Docker-2CA5E0?style=for-the-badge&logo=docker&logoColor=white)
![Lumen](https://img.shields.io/badge/Lumen-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-316192?style=for-the-badge&logo=postgresql&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![PHPStan](https://img.shields.io/badge/PHPStan-8C4A7A?style=for-the-badge&logo=php&logoColor=white)

## 1. Назначение сервиса 

Система управления рецептами представляет собой микросервисную архитектуру из двух взаимосвязанных сервисов:

### Ingredient Service:
  - Управление базой ингредиентов (CRUD-операции)
  - Валидация и хранение данных об ингредиентах
  - API для интеграции с сервисом рецептов

###   Recipe Service:
  - Создание и редактирование кулинарных рецептов
  - Связь рецептов с ингредиентами
  - Поиск и фильтрация рецептов

## 2. Архитектура и зависимости 

### Технологический стек:
| Компонент       | Технологии                          |
|-----------------|-------------------------------------|
| Backend         | PHP 8.2, Lumen (Laravel Microframework) |
| Базы данных     | PostgreSQL 13 (для каждого сервиса) |
| Контейнеризация | Docker + Docker Compose             |
| Тестирование    | PHPUnit 10.x, Postman               |
| Линтинг         | PHPStan           |
| Анализ кода     | PHP-CS-Fixer                        |

### Взаимодействие сервисов:
```mermaid
graph TD
    A[Recipe-Service] -->|HTTP REST API| B[Ingredient-Service]
    B -->|PostgreSQL| C[(Ingredient-DB)]
    A -->|PostgreSQL| D[(Recipe-DB)]
    E[Client] -->|API Gateway| A
    E -->|Прямые запросы| B
```

## 3. Способы запуска сервиса

### Требования:

Docker 20.10+

Docker Compose 2.0+

PHP 8.2 

### Стандартный запуск:

Сборка и запуск всех сервисов

``` docker-compose up -d --build ```

Проверка состояния

``` docker-compose ps ```

Остановка

``` docker-compose down ```

### Конфигурация .env сервиса ингредиентов:
```
APP_NAME=IngredientService
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8001

APP_TIMEZONE=UTC

LOG_CHANNEL=stack

DB_CONNECTION=pgsql
DB_HOST=ingredient-db
DB_PORT=5432
DB_DATABASE=ingredient_db
DB_USERNAME=postgres
DB_PASSWORD=secret
DB_URL=pgsql://postgres:secret@ingredient-db:5432/ingredient_db

CACHE_DRIVER=file
QUEUE_CONNECTION=sync
```

### Конфигурация .env сервиса рецептов:
```
APP_NAME=RecipeService
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8002
APP_TIMEZONE=UTC

LOG_CHANNEL=stack

DB_CONNECTION=pgsql
DB_HOST=recipe-db
DB_PORT=5432
DB_DATABASE=recipe_db
DB_USERNAME=postgres
DB_PASSWORD=secret
DB_URL=pgsql://postgres:secret@recipe_db-db:5432/recipe_db

INGREDIENT_SERVICE_URL=http://ingredient-service/api

CACHE_DRIVER=file
QUEUE_CONNECTION=sync
```

## 4. API документация 

### Основные эндпойнты 

#### Ingredient service
| Метод       | Эндпоинт                  | Описание                          | Пример тела запроса                     |
|-------------|---------------------------|-----------------------------------|------------------------------------------|
| POST        | /api/ingredients          | Создать новый ингредиент          | {"name": "Мука", "description": "..."} |
| GET         | /api/ingredients          | Получить список всех ингредиентов | -                                        |
| GET         | /api/ingredients/{id}     | Получить конкретный ингредиент    | -                                        |
| PUT         | /api/ingredients/{id}     | Обновить ингредиент               | {"name": "Новое название"}             |
| DELETE      | /api/ingredients/{id}     | Удалить ингредиент                | -                                        |

#### Recipes service
| Метод       | Эндпоинт               | Описание                          | Пример тела/параметров запроса           |
|-------------|------------------------|-----------------------------------|------------------------------------------|
| POST        | /api/recipes           | Создать новый рецепт              | {"title": "Блины", "description": "...", "ingredients": [n], "instructions": "..."}|
| GET         | /api/recipes           | Список рецептов      | -                                         |
| GET         | /api/recipes/{id}      | Получить конкретный рецепт        | -                                        |
| PUT         | /api/recipes/{id}      | Обновить рецепт                   | {"title": "Новое название"}            |
| DELETE      | /api/recipes/{id}      | Удалить рецепт                    | -                                        |

[Postman](https://www.postman.com/)


## 5. Поддержка и контакты

🩶✨ [GitHub Issue](https://github.com/kristallina/Recipes/issues/6)


#### 🩶 Белова Екатерина

**Telegram** - @kkkkkatiko

**Gmail** - katiko.3331@gmail.com

#### ✨ Кабанова Кристина

**Telegram** - @kristallina04

**Gmail** - kristallina04@mail.ru
