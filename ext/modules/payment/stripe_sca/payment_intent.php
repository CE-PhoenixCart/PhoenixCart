<?php

chdir('../../../../');
require('includes/application_top.php');
require_once 'includes/modules/payment/stripe_sca.php';
require_once('includes/languages/english/modules/payment/stripe_sca.php');
// supply an API key
$secret_key = MODULE_PAYMENT_STRIPE_SCA_TRANSACTION_SERVER == 'Live' ? MODULE_PAYMENT_STRIPE_SCA_LIVE_SECRET_KEY : MODULE_PAYMENT_STRIPE_SCA_TEST_SECRET_KEY;
\Stripe\Stripe::setApiKey($secret_key);
\Stripe\Stripe::setApiVersion('2019-08-14');

// get id and parameters
$id = (string)($_REQUEST["id"] ?? '');
$token = (int)($_REQUEST['token'] ?? 0);
$cc_save = $_REQUEST['cc_save'] ?? '';

$customerId = (int)($_SESSION['customer_id'] ?? 0);

if ($customerId <= 0) {
    http_response_code(403);
    echo json_encode(["status" => "error", "message" => "Not authenticated"]);
    exit;
}

if ($id === '' || !isset($_SESSION['stripe_payment_intent_id']) || !hash_equals((string)$_SESSION['stripe_payment_intent_id'], $id)) {
    http_response_code(403);
    echo json_encode(["status" => "error", "message" => "PaymentIntent does not belong to this session"]);
    exit;
}

// --- Per-session throttling on token lookups ---
const MAX_TOKEN_ATTEMPTS = 5;
const LOCKOUT_SECONDS = 300;

if (!isset($_SESSION['stripe_token_attempts'])) {
    $_SESSION['stripe_token_attempts'] = 0;
    $_SESSION['stripe_token_locked_until'] = 0;
}

if ($_SESSION['stripe_token_locked_until'] > time()) {
    http_response_code(429);
    echo json_encode(["status" => "error", "message" => "Too many attempts, try again later"]);
    exit;
}

$stripe_custId = null;
$stripe_cardId = null;

if ($token > 0) {
    $token_query = $GLOBALS['db']->query(sprintf(<<<'EOSQL'
SELECT stripe_token
  FROM customers_stripe_tokens
  WHERE id = %s
    AND customers_id = %s
EOSQL
        , $token, $customerId));

    if (mysqli_num_rows($token_query) === 1) {
        $rec = ($token_query)->fetch_assoc();
        $stripe_token_array = explode(':|:', $rec['stripe_token'], 2);
        $stripe_custId = $stripe_token_array[0];
        $stripe_cardId = $stripe_token_array[1];
        // Successful lookup resets the counter.
        $_SESSION['stripe_token_attempts'] = 0;
    } else {
        $_SESSION['stripe_token_attempts']++;
        if ($_SESSION['stripe_token_attempts'] >= MAX_TOKEN_ATTEMPTS) {
            $_SESSION['stripe_token_locked_until'] = time() + LOCKOUT_SECONDS;
            error_log("stripe_sca: token lookup lockout for customer_id={$customerId}");
        }
        http_response_code(403);
        echo json_encode(["status" => "error", "message" => "Invalid token"]);
        exit;
    }
}

$pi = \Stripe\PaymentIntent::retrieve(["id" => $id]);

if ($token > 0 && $stripe_custId !== null) {
    $pi->customer = $stripe_custId;
    $pi->payment_method = $stripe_cardId;
    $pi->metadata['stripe_card'] = $token;
}
$pi->save();

echo json_encode([
    "status" => "ok",
    "id" => $pi->id,
    "customer" => $pi->customer,
    "payment_method" => $pi->payment_method,
    'metadata' => $pi->metadata
]);
