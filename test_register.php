<?php
// Registration test script
// Run with: php test_register.php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

$testEmail = 'test_' . time() . '@example.com';
$testPassword = 'password123';

echo "Testing registration flow...\n";
echo "Test email: $testEmail\n\n";

try {
    // Create user
    $user = User::create([
        'name' => explode('@', $testEmail)[0],
        'email' => $testEmail,
        'password' => Hash::make($testPassword),
    ]);

    echo "User created successfully!\n";
    echo "User ID: " . $user->id . "\n";
    echo "User email: " . $user->email . "\n\n";

    // Trigger verification email
    echo "Sending verification email...\n";
    event(new Registered($user));
    echo "Verification email event triggered!\n\n";

    // Verify user exists in database
    $dbUser = User::find($user->id);
    echo "User found in database: " . ($dbUser ? "YES" : "NO") . "\n";
    echo "Email verified: " . ($dbUser->hasVerifiedEmail() ? "YES" : "NO") . "\n";

} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "\nFull trace:\n";
    echo $e->getTraceAsString() . "\n";
}
