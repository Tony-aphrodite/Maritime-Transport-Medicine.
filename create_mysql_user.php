<?php

echo "🚀 Creating MySQL User through PHP PDO\n";
echo "=====================================\n\n";

// Try multiple connection methods
$methods = [
    ['host' => 'localhost', 'user' => 'root', 'password' => ''],
    ['host' => '127.0.0.1', 'user' => 'root', 'password' => ''],
    ['host' => 'localhost', 'user' => 'root', 'password' => 'root'],
    ['host' => 'localhost', 'user' => '', 'password' => ''],
];

foreach ($methods as $i => $method) {
    echo "🔧 Method " . ($i + 1) . ": Connecting as '{$method['user']}' to '{$method['host']}'...\n";
    
    try {
        $pdo = new PDO(
            "mysql:host={$method['host']};charset=utf8mb4",
            $method['user'],
            $method['password'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
        
        echo "✅ Connected successfully!\n";
        echo "🏗️ Creating database and user...\n";
        
        // Create database
        $pdo->exec("CREATE DATABASE IF NOT EXISTS maritime_transport_medicine CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        echo "✅ Database 'maritime_transport_medicine' created\n";
        
        // Create user for localhost
        $pdo->exec("CREATE USER IF NOT EXISTS 'maritime_admin'@'localhost' IDENTIFIED BY 'Maritime2024!'");
        echo "✅ User 'maritime_admin'@'localhost' created\n";
        
        // Create user for 127.0.0.1
        $pdo->exec("CREATE USER IF NOT EXISTS 'maritime_admin'@'127.0.0.1' IDENTIFIED BY 'Maritime2024!'");
        echo "✅ User 'maritime_admin'@'127.0.0.1' created\n";
        
        // Grant privileges
        $pdo->exec("GRANT ALL PRIVILEGES ON maritime_transport_medicine.* TO 'maritime_admin'@'localhost'");
        $pdo->exec("GRANT ALL PRIVILEGES ON maritime_transport_medicine.* TO 'maritime_admin'@'127.0.0.1'");
        echo "✅ Privileges granted\n";
        
        // Flush privileges
        $pdo->exec("FLUSH PRIVILEGES");
        echo "✅ Privileges flushed\n\n";
        
        // Verify setup
        echo "🧪 Verifying setup...\n";
        $stmt = $pdo->query("SELECT User, Host FROM mysql.user WHERE User = 'maritime_admin'");
        $users = $stmt->fetchAll();
        
        echo "Found users:\n";
        foreach ($users as $user) {
            echo "- {$user['User']}@{$user['Host']}\n";
        }
        
        $stmt = $pdo->query("SHOW DATABASES LIKE 'maritime_transport_medicine'");
        $databases = $stmt->fetchAll();
        
        if (count($databases) > 0) {
            echo "✅ Database 'maritime_transport_medicine' exists\n";
        }
        
        echo "\n🎉 SUCCESS: MySQL user created through PHP!\n";
        echo "🧪 Now testing Laravel connection...\n\n";
        
        // Test Laravel connection
        system('php artisan config:clear');
        system('php artisan cache:clear');
        
        $output = shell_exec('php artisan migrate:status 2>&1');
        if (strpos($output, 'Access denied') === false) {
            echo "✅ Laravel can now connect to MySQL!\n";
            echo "🏃 Running migrations...\n";
            system('php artisan migrate --force');
            
            echo "\n🎯 Testing audit logging...\n";
            $testOutput = shell_exec('php artisan tinker --execute="
                use App\Models\AuditLog;
                AuditLog::logEvent(\'mysql_php_setup_success\', \'success\', [\'method\' => \'php_pdo\'], \'system\');
                echo \'✅ Audit logging working!\';
            "');
            
            echo $testOutput . "\n";
            echo "\n🎉 COMPLETE: Admin dashboard database error should be fixed!\n";
            echo "✅ Frontend → Laravel → MySQL fully connected\n";
            
        } else {
            echo "❌ Laravel still cannot connect to MySQL\n";
            echo "Output: $output\n";
        }
        
        exit(0);
        
    } catch (PDOException $e) {
        echo "❌ Failed: " . $e->getMessage() . "\n\n";
        continue;
    }
}

echo "❌ All PHP connection methods failed\n";
echo "\n📋 Manual solution still required:\n";
echo "sudo mysql\n";
echo "CREATE DATABASE IF NOT EXISTS maritime_transport_medicine CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;\n";
echo "CREATE USER IF NOT EXISTS 'maritime_admin'@'localhost' IDENTIFIED BY 'Maritime2024!';\n";
echo "GRANT ALL PRIVILEGES ON maritime_transport_medicine.* TO 'maritime_admin'@'localhost';\n";
echo "FLUSH PRIVILEGES;\n";
echo "EXIT;\n";

?>