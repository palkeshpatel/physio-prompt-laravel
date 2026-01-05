# Install Laravel Sanctum

After running migrations and seeders, you MUST install Laravel Sanctum for API authentication to work.

## Installation Steps

1. Install Sanctum:
```bash
composer require laravel/sanctum
```

2. Publish Sanctum configuration:
```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

3. Run Sanctum migrations:
```bash
php artisan migrate
```

4. Uncomment Sanctum in User model:
   - Open `app/Models/User.php`
   - Uncomment line 13: `use Laravel\Sanctum\HasApiTokens;`
   - Uncomment line 21: `use HasApiTokens;`

5. Update `config/sanctum.php` if needed (usually default is fine)

## Why Sanctum is Required

- All API endpoints use Sanctum tokens for authentication
- The `AuthController` uses `$user->createToken()` method
- Protected routes use `auth:sanctum` middleware

Without Sanctum, the API authentication will not work.



