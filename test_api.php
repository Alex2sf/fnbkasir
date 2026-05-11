<?php

$baseUrl = 'http://127.0.0.1:8005/api';

function callApi($method, $url, $data = [], $token = null) {
    $ch = curl_init();
    $headers = ['Content-Type: application/json', 'Accept: application/json'];
    if ($token) {
        $headers[] = 'Authorization: Bearer ' . $token;
    }

    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, 1);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
    } else if ($method === 'GET' && $data) {
        $url .= '?' . http_build_query($data);
    }

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return ['code' => $httpCode, 'data' => json_decode($response, true) ?? $response];
}

echo "1. Testing Login Endpoint (Warunggalih F&B)...\n";
$loginRes = callApi('POST', "$baseUrl/login", [
    'email' => 'kasir@warunggalih.test',
    'password' => 'password'
]);
echo "Status: " . $loginRes['code'] . "\n";
if ($loginRes['code'] !== 200) {
    echo "Login Error: " . ($loginRes['data']['message'] ?? json_encode($loginRes['data'])) . "\n";
    die();
}
$token = $loginRes['data']['data']['token'] ?? null;
if (!$token) {
    die("No token received.\n" . print_r($loginRes, true));
}
echo "Token received: " . substr($token, 0, 10) . "...\n\n";

echo "2. Testing GET /user ...\n";
$userRes = callApi('GET', "$baseUrl/user", [], $token);
echo "Status: " . $userRes['code'] . "\n";
echo "User Name: " . $userRes['data']['data']['name'] . "\n\n";

echo "3. Testing GET /products ...\n";
$prodRes = callApi('GET', "$baseUrl/products", [], $token);
echo "Status: " . $prodRes['code'] . "\n";
echo "Total Products: " . count($prodRes['data']['data']) . "\n";
if (count($prodRes['data']['data']) > 0) {
    echo "First Product: " . $prodRes['data']['data'][0]['name'] . "\n\n";
}

$firstProductId = $prodRes['data']['data'][0]['id'] ?? 1;
$firstProductPrice = $prodRes['data']['data'][0]['price'] ?? 25000;

echo "4. Testing POST /checkout ...\n";
$checkoutRes = callApi('POST', "$baseUrl/checkout", [
    'items' => [
        ['product_id' => $firstProductId, 'quantity' => 1, 'price' => $firstProductPrice]
    ],
    'payment_method' => 'cash',
    'total_amount' => $firstProductPrice
], $token);

echo "Status: " . $checkoutRes['code'] . "\n";
if ($checkoutRes['code'] === 200) {
    echo "Message: " . $checkoutRes['data']['message'] . "\n";
    echo "Order Number: " . $checkoutRes['data']['data']['order_number'] . "\n";
} else {
    echo "Checkout Failed: " . print_r($checkoutRes, true) . "\n";
}

echo "\n--- API TESTING COMPLETED ---\n";
