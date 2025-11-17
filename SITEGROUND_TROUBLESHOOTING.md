# SiteGround Face Verification Troubleshooting Guide

## Issue: Face Verification Not Working After Deployment

Based on the codebase analysis, here are the potential issues and solutions:

## 1. **API Token Configuration Issues**

### Problem
The `.env` file shows placeholder values for face verification API:
```
FACE_VERIFY_TOKEN=YOUR_FACE_VERIFICATION_TOKEN_HERE
FACE_VERIFY_BASE_URL=https://api.facecompare.com
FACE_VERIFY_ENDPOINT=/v1/compare
```

### Solution
The `FaceVerificationController.php` has a fallback mechanism that uses VerificaMex token when face verification token is not set:

```php
$token = env('FACE_VERIFY_TOKEN', env('VERIFICAMEX_TOKEN'));
$baseUrl = env('FACE_VERIFY_BASE_URL', env('VERIFICAMEX_BASE_URL'));
```

**Action Required:**
1. Either configure proper face verification API credentials
2. Or ensure the fallback simulation is working (lines 343-378 in controller)

## 2. **PHP Configuration on SiteGround**

### File Upload Limits
Check these PHP settings on SiteGround:
- `upload_max_filesize` (should be >= 5M)
- `post_max_size` (should be >= 6M)
- `max_execution_time` (should be >= 60 seconds)
- `memory_limit` (should be >= 256M)

### How to Check:
1. Create a test file: `<?php phpinfo(); ?>`
2. Upload to SiteGround and check the values
3. Update in cPanel PHP Options if needed

## 3. **HTTPS Requirements**

### Problem
Camera access requires HTTPS. The view file checks for this:

```javascript
if (location.protocol !== 'https:' && location.hostname !== 'localhost' && location.hostname !== '127.0.0.1') {
    throw new Error('El acceso a la cámara requiere una conexión segura (HTTPS).');
}
```

### Solution
Ensure `latitudmedica.rios4web.com` is using HTTPS. In SiteGround:
1. Go to cPanel → SSL/TLS
2. Enable "Force HTTPS Redirect"

## 4. **Directory Permissions**

### Storage Directory
Laravel needs write permissions for:
- `storage/logs/` - for logging
- `storage/app/` - for temporary file storage
- `storage/framework/cache/` - for caching

### Set Permissions:
```bash
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

## 5. **Environment Configuration**

### Missing .env Variables
Ensure these are set in production `.env`:
```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://latitudmedica.rios4web.com

# Face verification settings
FACE_VERIFY_TOKEN=YOUR_ACTUAL_TOKEN_OR_LEAVE_EMPTY_FOR_SIMULATION
FACE_VERIFY_BASE_URL=https://api.facecompare.com
FACE_VERIFY_ENDPOINT=/v1/compare

# Database settings
DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=latitudmedica_db
DB_USERNAME=latitudmedica_user
DB_PASSWORD=your_db_password
```

## 6. **Laravel Route Caching**

### Clear All Caches
Run these commands on SiteGround via SSH or file manager:
```bash
php artisan config:clear
php artisan route:clear
php artisan cache:clear
php artisan view:clear
```

## 7. **Debugging Steps**

### Enable Logging
1. Set `APP_DEBUG=true` temporarily
2. Check `storage/logs/laravel.log` for errors
3. Look for face verification specific errors

### Test Face Verification API
The controller has a simulation fallback (lines 355-378) that should work even without real API:

```php
private function simulateFaceVerificationResponse(string $selfieBase64, string $ineBase64): array
{
    Log::info('🎭 Simulating face verification response for testing');
    
    // Simulate processing delay
    sleep(2);

    // Generate simulated results
    $confidence = rand(75, 98);
    $match = $confidence >= 80;

    return [
        'success' => true,
        'data' => [
            'match' => $match,
            'confidence' => $confidence,
            'verification_id' => 'sim_' . uniqid()
        ]
    ];
}
```

### Check Browser Console
Look for JavaScript errors in browser console:
1. Open face verification page
2. Open browser dev tools (F12)
3. Check Console tab for errors
4. Test with different browsers

## 8. **Database Issues**

### Check Database Connection
The audit logging might fail if database is not connected:
```php
AuditLog::logEvent(
    AuditLog::EVENT_FACE_MATCHING_ATTEMPT,
    AuditLog::STATUS_IN_PROGRESS,
    // ...
);
```

### Test Database Connection
Create a test route:
```php
Route::get('/test-db', function() {
    try {
        DB::connection()->getPdo();
        return 'Database connected successfully';
    } catch (Exception $e) {
        return 'Database connection failed: ' . $e->getMessage();
    }
});
```

## 9. **File Upload Issues**

### Check if Files Are Received
The controller expects:
- `selfie` file input
- `ine_photo` file input

### Validation Rules
Files must meet these criteria:
- Format: JPEG, PNG, JPG
- Size: Max 5MB
- Dimensions: Min 300x300, Max 4000x4000 pixels

## 10. **Quick Diagnostic Commands**

Run these to diagnose issues:

```bash
# Check file permissions
ls -la storage/
ls -la storage/logs/

# Check Laravel logs
tail -f storage/logs/laravel.log

# Test route accessibility
curl -I https://latitudmedica.rios4web.com/face-verification

# Test POST endpoint
curl -X POST https://latitudmedica.rios4web.com/face-verification/compare \
  -H "X-CSRF-TOKEN: your-token" \
  -F "selfie=@test-selfie.jpg" \
  -F "ine_photo=@test-ine.jpg"
```

## 11. **Immediate Actions**

1. **Check Error Logs**: Look in `storage/logs/laravel.log`
2. **Verify HTTPS**: Ensure site uses HTTPS
3. **Test Simulation**: The fallback should work even without API
4. **Check File Uploads**: Verify PHP settings allow 5MB uploads
5. **Database Connection**: Ensure MySQL is connected

## 12. **Expected Behavior**

When working correctly:
1. User uploads/captures selfie and INE photo
2. JavaScript sends POST to `/face-verification/compare`
3. Controller processes images and calls API (or simulation)
4. Results are displayed with confidence score
5. User can continue registration if verified

The system should work even with placeholder API tokens due to the simulation fallback mechanism.