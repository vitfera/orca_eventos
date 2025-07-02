# OrçaEventos Backend

Backend em Laravel 12 para o sistema OrçaEventos.

## Requisitos
- Docker e Docker Compose
- PHP 8.4 (já configurado no Dockerfile)
- MariaDB 11.3
- Redis

## Subindo o ambiente

```bash
docker-compose up --build
```

A aplicação estará disponível em http://localhost:8000

## Instalação do Sanctum

No container app, execute:

```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

No arquivo `config/auth.php`, defina:

```php
'defaults' => [
    'guard' => 'sanctum',
    'passwords' => 'users',
],
```

No `app/Http/Kernel.php`, adicione:

```php
'api' => [
    \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
    'throttle:api',
    \Illuminate\Routing\Middleware\SubstituteBindings::class,
],
```

## Rotas principais

- POST `/api/register` — cadastro
- POST `/api/login` — login
- POST `/api/logout` — logout (autenticado)
- CRUD `/api/categories`, `/api/units`, `/api/suppliers`, `/api/items`, `/api/prices`, `/api/budgets`, `/api/budget-items` (autenticado)

## Observações
- O backend está preparado para autenticação via Sanctum.
- Perfis: admin, fornecedor (campo `perfil` na tabela users).
- O banco de dados padrão é MariaDB.

---

Dúvidas? Consulte o PRD ou abra uma issue.
