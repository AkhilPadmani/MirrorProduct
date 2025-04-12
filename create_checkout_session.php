<?php
require_once 'config.php';
require_once 'vendor/autoload.php'; // Assuming you've installed Stripe PHP library

\Stripe\Stripe::setApiKey('sk_test_your_secret_key_here');

header('Content-Type: application/json');

try {
    $jsonStr = file_get_contents('php://input');
    $jsonObj = json_decode($jsonStr);
    
    // Validate and sanitize input
    $productId = filter_var($jsonObj->productId, FILTER_SANITIZE_STRING);
    $productName = filter_var($jsonObj->productName, FILTER_SANITIZE_STRING);
    $productPrice = filter_var($jsonObj->productPrice, FILTER_VALIDATE_FLOAT);
    $currency = filter_var($jsonObj->currency, FILTER_SANITIZE_STRING);
    $quantity = filter_var($jsonObj->quantity, FILTER_VALIDATE_INT);
    
    if (!$productPrice || !$quantity) {
        throw new Exception('Invalid price or quantity');
    }
    
    // Create a Stripe Checkout session
    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => strtolower($currency),
                'product_data' => [
                    'name' => $productName,
                ],
                'unit_amount' => $productPrice * 100, // Stripe uses cents
            ],
            'quantity' => $quantity,
        ]],
        'mode' => 'payment',
        'success_url' => 'https://yourdomain.com/success?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => 'https://yourdomain.com/cancel',
    ]);
    
    echo json_encode(['id' => $session->id]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}