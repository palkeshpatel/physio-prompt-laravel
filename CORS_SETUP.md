# CORS Configuration

## Issue
CORS (Cross-Origin Resource Sharing) errors occur when the React frontend (running on `localhost:3000`) tries to access the Laravel API (running on `localhost:8000`).

## Solution
CORS has been configured in `config/cors.php` to allow requests from the React app.

## Configuration

### Development (Current)
- `allowed_origins`: `['*']` - Allows all origins (for development only)
- `supports_credentials`: `false` - Required when using `['*']`
- `allowed_methods`: `['*']` - Allows all HTTP methods
- `allowed_headers`: `['*']` - Allows all headers

### Production
For production, update `config/cors.php`:

```php
'allowed_origins' => [
    'https://yourdomain.com',
    'https://www.yourdomain.com',
],
'supports_credentials' => true,
```

## Testing
1. Make sure Laravel server is running: `php artisan serve`
2. Make sure React app is running: `npm run dev`
3. Try logging in from the React app
4. Check browser console for CORS errors

## Troubleshooting

### If CORS errors persist:
1. Clear config cache: `php artisan config:clear`
2. Clear application cache: `php artisan cache:clear`
3. Restart Laravel server
4. Check that `config/cors.php` exists and is properly configured
5. Verify the API endpoint URL in React matches Laravel server URL

### Common Issues:
- **Preflight requests failing**: Make sure `allowed_methods` includes `OPTIONS`
- **Credentials not working**: When using `['*']` for origins, `supports_credentials` must be `false`
- **Headers blocked**: Ensure `allowed_headers` includes `Authorization` and `Content-Type`

## Notes
- CORS middleware is automatically registered in Laravel 11 when `config/cors.php` exists
- No need to manually register `HandleCors` middleware in `bootstrap/app.php`
- The configuration applies to all routes matching `api/*` pattern

