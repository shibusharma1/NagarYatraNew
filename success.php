<?php
// echo '<pre>';
// print_r($_GET);
// echo '</pre>';

// Check for encoded `data` parameter from eSewa
if (isset($_GET['data'])) {
    $rawData = $_GET['data'];
    $json = base64_decode($rawData);
    $info = json_decode($json, true);

    if (isset($info['status']) && $info['status'] === 'COMPLETE') {
        echo "<h2 style='color: green;'>✅ Payment Successful and Verified!</h2>";
        echo "<strong>Transaction Code:</strong> " . $info['transaction_code'] . "<br>";
        echo "<strong>Amount:</strong> Rs. " . $info['total_amount'] . "<br>";
        echo "<strong>Transaction ID:</strong> " . $info['transaction_uuid'] . "<br>";
    } else {
        echo "<h2 style='color: orange;'>⚠️ Payment received but status not complete.</h2>";
        echo "<pre>";
        print_r($info);
        echo "</pre>";
    }

} else {
    echo "<h2 style='color: red;'>❌ Invalid or incomplete request!</h2>";
}
?>
