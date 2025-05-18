<?php
$amount = 2000; // default in paisa
$amount_in_rs = $amount / 100;

// Handle form POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get user-inputted amount in Rs. and convert to paisa
    $amount_in_rs = isset($_POST['amount']) ? (int)$_POST['amount'] : 10;
    $amount = $amount_in_rs * 100;

    $payload = json_encode([
        "return_url" => "http://example.com/?q=done",
        "website_url" => "https://example.com/",
        "amount" => $amount,
        "purchase_order_id" => "Order01",
        "purchase_order_name" => "Test Order",
        "customer_info" => [
            "name" => "Test Bahadur",
            "email" => "test@khalti.com",
            "phone" => "9800000001"
        ]
    ]);

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://dev.khalti.com/api/v2/epayment/initiate/',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_HTTPHEADER => array(
            'Authorization: key ec7eb85c4f98467193859cfeeef2314d',
            'Content-Type: application/json',
        ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    $response = json_decode($response, true);

    if (isset($response['payment_url'])) {
        header('Location: ' . $response['payment_url']);
        exit;
    } else {
        echo "<pre>❌ Error initiating payment:\n" . print_r($response, true) . "</pre>";
        exit;
    }
}

// After return
if (isset($_GET['q']) && $_GET['q'] === 'done') {
    echo "<h2 style='color: green;'>✅ Payment Completed! You will need to verify using Khalti webhook or manual verification API.</h2>";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pay with Khalti</title>
</head>
<body style="font-family: Arial; padding: 20px;">
    <h2>💳 Pay with Khalti</h2>

    <form method="POST" action="">
        <label for="amount">Enter Amount (in Rs):</label><br><br>
        <input type="number" id="amount" name="amount" min="1" value="<?php echo $amount_in_rs; ?>" required style="padding: 8px;"><br><br>
        <button type="submit" style="padding: 10px 20px; background: purple; color: white; border: none;">
            Pay with Khalti
        </button>
    </form>
</body>
</html>
