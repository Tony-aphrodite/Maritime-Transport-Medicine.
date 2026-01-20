<?php
// Simple email test script
// Run with: php test_email.php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Mail;

// Test email address - change this to your email
$testEmail = 'jlrc83@gmail.com';

echo "Testing email configuration...\n";
echo "MAIL_MAILER: " . env('MAIL_MAILER') . "\n";
echo "MAIL_HOST: " . env('MAIL_HOST') . "\n";
echo "MAIL_PORT: " . env('MAIL_PORT') . "\n";
echo "MAIL_USERNAME: " . env('MAIL_USERNAME') . "\n";
echo "MAIL_FROM_ADDRESS: " . env('MAIL_FROM_ADDRESS') . "\n\n";

try {
    Mail::raw('Este es un correo de prueba desde Maritime Transport Medicine.', function ($message) use ($testEmail) {
        $message->to($testEmail)
                ->subject('Prueba de Email - Maritime Transport Medicine');
    });
    echo "Email sent successfully to: $testEmail\n";
    echo "Please check your inbox (and spam folder).\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "\nFull trace:\n";
    echo $e->getTraceAsString() . "\n";
}
