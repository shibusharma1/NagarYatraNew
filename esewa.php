<?php
// Sample dynamic values
$total = 100; // Replace this with the actual total from your database or calculation
$tax = 10;
$total_amount = $total + $tax;

// Generate UUID v4
function generateUUIDv4() {
    return sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), // 32 bits for "time_low"
        mt_rand(0, 0xffff),                     // 16 bits for "time_mid"
        mt_rand(0, 0x0fff) | 0x4000,            // 16 bits for "time_hi_and_version"
        mt_rand(0, 0x3fff) | 0x8000,            // 16 bits for "clk_seq_hi_res/clk_seq_low"
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff) // 48 bits for "node"
    );
}

$transaction_uuid = generateUUIDv4();
$product_code = "EPAYTEST";

// Signature generation
$secret_key = "8gBm/:&EnhH.1/q";
$signed_field_names = "total_amount,transaction_uuid,product_code";
$message = "total_amount={$total_amount},transaction_uuid={$transaction_uuid},product_code={$product_code}";

// Generate HMAC-SHA256 and encode in Base64
$signature = base64_encode(hash_hmac('sha256', $message, $secret_key, true));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>eSewa Payment Integration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-green-500 p-4">
            <h1 class="text-white text-xl font-bold text-center">eSewa Payment Gateway</h1>
        </div>

        <div class="p-6">
            <div class="mb-6">
                <h2 class="text-gray-800 text-lg font-semibold mb-2">Order Summary</h2>
                <div class="border-t border-b border-gray-200 py-3">
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600">Product</span>
                        <span class="font-medium">Sample Product</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600">Amount</span>
                        <span class="font-medium">NPR <?php echo number_format($total, 2); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tax</span>
                        <span class="font-medium">NPR <?php echo number_format($tax, 2); ?></span>
                    </div>
                </div>
                <div class="flex justify-between mt-3 font-bold">
                    <span class="text-gray-800">Total</span>
                    <span class="text-green-600">NPR <?php echo number_format($total_amount, 2); ?></span>
                </div>
            </div>

            <!-- eSewa Payment Form -->
            <form id="esewaForm" action="https://rc-epay.esewa.com.np/api/epay/main/v2/form" method="POST">
                <input type="hidden" name="amount" value="<?php echo $total; ?>">
                <input type="hidden" name="tax_amount" value="<?php echo $tax; ?>">
                <input type="hidden" name="total_amount" value="<?php echo $total_amount; ?>">
                <input type="hidden" name="transaction_uuid" value="<?php echo $transaction_uuid; ?>">
                <input type="hidden" name="product_code" value="<?php echo $product_code; ?>">
                <input type="hidden" name="product_service_charge" value="0">
                <input type="hidden" name="product_delivery_charge" value="0">
                <input type="hidden" name="success_url" value="http://localhost/NagarYatra/success.php">
                <input type="hidden" name="failure_url" value="http://localhost/NagarYatra/failure.php">
                <input type="hidden" name="signed_field_names" value="<?php echo $signed_field_names; ?>">
                <input type="hidden" name="signature" value="<?php echo $signature; ?>">

                <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-4 rounded-md transition duration-300">
                    Pay with eSewa
                </button>
            </form>

            <div class="mt-6 text-center text-sm text-gray-500">
                <p>You will be redirected to eSewa to complete your payment.</p>
            </div>
        </div>
    </div>
</body>
</html>
