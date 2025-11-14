<?php
/**
 * Quick CURP API Test Script
 * Run this to test the VerificaMex API integration directly
 * Usage: php test-curp-api.php
 */

// Test CURP
$testCurp = 'RICJ830716HTSSNN05';

// VerificaMex API Configuration
$token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiYWVlNjExZWEyN2M0MzM2ZjgzOWI1NTQ1MjVlZTQ3ZTI4MTJlYTRiMGQ3MTQ3Yjk0MDdkYjdhNjhjNjFkNWYxZDhmNDgxMjBiYzdhM2FkODIiLCJpYXQiOjE3NjMwODQ3ODIuMjAwNDYxLCJuYmYiOjE3NjMwODQ3ODIuMjAwNDk5LCJleHAiOjE3OTQ2MjA3ODIuMTg2MTEsInN1YiI6IjgxNzQiLCJzY29wZXMiOltdfQ.UTZKx5J3-w1iH6z6EcwQbFgjCNL5U57rjLXQ_pLK__wva8-4icxvxikICqRVrNIzjLYu5WpETi-2wpg4Qh3W_0MbgVyma854mI2AF_Bffbaf3X6e-UfOelYwIsk6FD1iJrPzETNWZCUqSFkEYI_o9F2-g2tdtbf2pGw4-7CqGVef1n3utJPpftK9P4Q6L5t3q8rg-rY6u22enExNEO6-xAP2ZjhkWmEU1J1rzCtD4KcdWY1zOK6zgYEA-NW0Aobay67Dnhkf-m3zsTRleKK6M0CGGjV89AOlZ186bBx1nHqw3g2nVf_5cl6q9s-RraYDXoXO8ppR0U76bV3lBesoG7_9y8V4aIoZxI8uA-Wp4jYoqsCN8KdUE4lHNG4vyaiOvl23dfcoUs2ELSwe-xNK_JCqEBZV1cRF0qzF7_0V1buKMDAI_43TxPMJ2LFkVFz2nGWyVMd88uKijA-OXS-R1KgvikYJt8s3OH8XvV3SWr4PhlGp1uXiOdxgXbRVmcYbJcxmvEvlwQTk0TdEKUDSDaVvF3kJHom-4ddoA-nMiQx-mtY31l05V01346pm2-5K-sXnQxpjaSRjjIWRHhb9FG09NeHVUCtjc7ApQq7RSSeo8KuEVOHoX5kWsY5H7820D4HqFnqq_7UEyuQCGsLlgk4A2SUxRhiXq2suTc86n-k';
$baseUrl = 'https://api.verificamex.com';

echo "🧪 Testing VerificaMex CURP API\n";
echo "===============================\n";
echo "Test CURP: $testCurp\n";
echo "API Endpoint: $baseUrl/curp\n\n";

// Prepare request data
$data = json_encode(['curp' => $testCurp]);

// Initialize cURL
$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => $baseUrl . '/api/curp',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => $data,
    CURLOPT_HTTPHEADER => [
        'Authorization: Bearer ' . $token,
        'Content-Type: application/json',
        'Accept: application/json'
    ],
]);

echo "📤 Sending request...\n";

$response = curl_exec($curl);
$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
$error = curl_error($curl);

curl_close($curl);

echo "📥 Response received!\n";
echo "HTTP Status Code: $httpCode\n\n";

if ($error) {
    echo "❌ cURL Error: $error\n";
} else {
    echo "✅ Raw Response:\n";
    echo $response . "\n\n";
    
    $responseData = json_decode($response, true);
    
    if ($responseData) {
        echo "📊 Parsed Response:\n";
        echo "Success: " . ($responseData['success'] ?? 'unknown') . "\n";
        echo "Message: " . ($responseData['message'] ?? 'No message') . "\n";
        
        if (isset($responseData['data'])) {
            echo "CURP Data:\n";
            foreach ($responseData['data'] as $key => $value) {
                if (is_array($value)) {
                    echo "  $key: " . json_encode($value) . "\n";
                } else {
                    echo "  $key: $value\n";
                }
            }
        }
    } else {
        echo "❌ Failed to parse JSON response\n";
    }
}

echo "\n🏁 Test completed!\n";

// Test CURP format validation function
function validateCurpFormat($curp) {
    $pattern = '/^[A-Z]{1}[AEIOUX]{1}[A-Z]{2}[0-9]{2}(0[1-9]|1[0-2])(0[1-9]|[12][0-9]|3[01])[HM]{1}[A-Z]{2}[BCDFGHJKLMNPQRSTVWXYZ]{3}[0-9A-Z]{1}[0-9]{1}$/';
    return preg_match($pattern, strtoupper($curp));
}

echo "\n🔍 Format Validation Test:\n";
echo "CURP: $testCurp\n";
echo "Valid format: " . (validateCurpFormat($testCurp) ? "✅ YES" : "❌ NO") . "\n";
?>