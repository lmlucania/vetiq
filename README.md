# VetIQ
動物病院の予約＆口コミ アプリのAPI

## Included services
- Nginx
- PHP 8.3.0
- MySQL 8.0
- Redis
- Adminer
- Redis Commander
- Mailhog (Mailcatcher alternative)
- Swagger UI

## Framework
- Laravel ^11.9

## Project Setup

1. Clone this repo:

```
git clone git@github.com/lmlucania/vetiq.git
cd vetiq
```

2. Create ```.env``` file

```
cp .env.example .env
cp src/.env.example src/.env
```

3. Build and run containers:

```
docker-compose build
docker-compose up
```

4. Set Up the Application Environment
```
composer install
php artisan key:generate

# DBのマイグレーションとシーダーを実行
php artisan migrate:refresh --seed
```


### API Document

```
localhost:8080/api/documentation
```

### Architecture


#### DDD

```mermaid
flowchart LR
    subgraph Presentation Layer
        direction LR
        Client
        Controller
        Client -->|Request| Controller
        Controller -->|JSON Response| Client
    end
    subgraph Application Layer
        UseCase
    end
    subgraph Domain Layer
        Service
    end
    subgraph Infrastructure Layer
        direction LR
        Repository
        Model
        Repository -->|Parameters| Model

        Model -->|Eloquent Model| Repository
    end
    Controller -->|Parameters| UseCase
    UseCase -->|DTO| Controller
    UseCase -->|Parameters| Service
    Service -->|Parameters| Repository
    Repository -->|Entity| Service
    Service -->|Entity| UseCase
```
