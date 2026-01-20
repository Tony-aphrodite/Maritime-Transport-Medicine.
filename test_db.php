<?php
// Database connection test script
// Run with: php test_db.php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Testing database connection...\n";
echo "DB_CONNECTION: " . env('DB_CONNECTION') . "\n";
echo "DB_DATABASE: " . env('DB_DATABASE') . "\n\n";

try {
    $pdo = DB::connection()->getPdo();
    echo "Database connection: SUCCESS\n";
    echo "PDO Driver: " . $pdo->getAttribute(PDO::ATTR_DRIVER_NAME) . "\n";

    // Check if users table exists
    $tables = DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name='users'");
    if (count($tables) > 0) {
        echo "Users table: EXISTS\n";

        // Count users
        $count = DB::table('users')->count();
        echo "User count: $count\n";
    } else {
        echo "Users table: NOT FOUND\n";
    }

} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
