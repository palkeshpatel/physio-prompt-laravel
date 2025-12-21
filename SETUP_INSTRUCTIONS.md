# Setup Instructions for PhysioPrompt Laravel API

## Prerequisites
- PHP 8.2 or higher
- Composer
- MySQL/MariaDB
- Laravel 12

## Installation Steps

### 1. Install Dependencies
```bash
composer install
```

### 2. Install Laravel Sanctum (Required for API Authentication)
```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

### 3. Configure Environment
Copy `.env.example` to `.env` if not exists:
```bash
cp .env.example .env
```

Generate application key:
```bash
php artisan key:generate
```

Update database configuration in `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=physioprompt
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Run Migrations
```bash
php artisan migrate
```

### 5. Run Seeders
This will create:
- 2 Roles (Doctor, Patient)
- 3 Subscription Plans (Free, Basic, Premium)
- 2 Assessment Types (Subjective, Objective)
- 15 Users (5 Doctors, 10 Patients) with some subscriptions

```bash
php artisan db:seed
```

### 6. Start Development Server
```bash
php artisan serve
```

The API will be available at: `http://localhost:8000/api/physio`

## Test Users

After running seeders, you can login with:

**Doctors:**
- `doctor1@example.com` / `password123`
- `doctor2@example.com` / `password123`
- `doctor3@example.com` / `password123`

**Patients:**
- `patient1@example.com` / `password123`
- `patient2@example.com` / `password123`
- `patient3@example.com` / `password123`

## API Documentation

See `POSTMAN_DOCUMENTATION.md` for complete API documentation.

## Important Notes

1. **Laravel Sanctum is required** - Make sure to install it before testing API endpoints
2. **All routes are prefixed with `/api/physio`**
3. **Protected routes require authentication** - Include `Authorization: Bearer {token}` header
4. **Assessment limits** - Free plan: 3/month, Basic: 100/month, Premium: Unlimited

## Troubleshooting

### Migration Error: Table 'roles' doesn't exist
Make sure migrations run in order. The roles table must be created before users table.

### Sanctum Token Not Working
1. Make sure Sanctum is installed and published
2. Check `config/sanctum.php` configuration
3. Verify `HasApiTokens` trait is used in User model (already added)

### API Routes Not Found
1. Verify `bootstrap/app.php` includes API routes: `api: __DIR__.'/../routes/api.php'`
2. Clear route cache: `php artisan route:clear`

## Next Steps

1. Test authentication endpoints (register/login)
2. Create an assessment
3. Fill assessment sections
4. Test subscription management
5. Review Postman documentation for all endpoints

