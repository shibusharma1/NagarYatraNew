<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    

    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://dev.khalti.com/api/v2/epayment/initiate/',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>'{
    "return_url": "http://example.com/",
    "website_url": "https://example.com/",
    "amount": "1000",
    "purchase_order_id": "Order01",
        "purchase_order_name": "test",

    "customer_info": {
        "name": "Test Bahadur",
        "email": "test@khalti.com",
        "phone": "9800000002"
    }
    }

    ',
    CURLOPT_HTTPHEADER => array(
        'Authorization: key ef74d2c2c9654215ab0602198e6cf16e',
        'Content-Type: application/json',
    ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    $response = json_decode($response, true);

    header('Location: ' . $response['payment_url']);
    exit;
}

// --- After payment redirect ---
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
    <h2>💳 Pay Rs. 10 with Khalti</h2>

    <form method="POST" action="">
        <button type="submit" style="padding: 10px 20px; background: purple; color: white; border: none;">
            Pay with Khalti
        </button>
    </form>
</body>
</html>
