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
$id = $_REQUEST["id"]?? '';
$customerId = $_REQUEST['customer_id']?? '';
$token = $_REQUEST['token']?? '';
$cc_save = $_REQUEST['cc_save']?? '';
$stripe_custId = '';
$stripe_cardId = '';
$stripe_sca = new stripe_sca();

if (isset($token) && $token !== '0') {
    $token_query = $GLOBALS['db']->query(sprintf(<<<'EOSQL'
SELECT stripe_token 
  FROM customers_stripe_tokens 
  WHERE id = %s 
    AND customers_id = %s
EOSQL
            , (int)$token, (int)$customerId));

    if (mysqli_num_rows($token_query) === 1) {
        $rec = ($token_query)->fetch_assoc();

        $stripe_token_array = explode(':|:', $rec['stripe_token'], 2);

        $stripe_custId = $stripe_token_array[0];
        $stripe_cardId = $stripe_token_array[1];
    } else {
        echo json_encode(["status" => "fail", "error" => MODULE_PAYMENT_STRIPE_SCA_MISSING_CARD_FOR_TOKEN . $token]);
        exit();
    }
}

if (!isset($id)) {
    echo json_encode(["status" => "fail", "error" => MODULE_PAYMENT_STRIPE_SCA_MISSING_INTENT]);
    exit();
} else {
    $pi = \Stripe\PaymentIntent::retrieve(["id" => $id]);
    $stripe_sca->event_log($customerId, "ajax retrieve", $id, $pi);
    if (isset($token) && $token > 0) {
        $pi->customer = $stripe_custId;
        $pi->payment_method = $stripe_cardId;
        $pi->metadata['stripe_card'] = $token;
    }
    $pi->metadata['cc_save'] = $cc_save;
    try {
        $pi->save();
    } catch (exception $err) {
        echo json_encode(["status" => "fail", "error" => $err->getMessage()]);
        exit();
    }

    echo json_encode(["status" => "ok", "id" => $pi->id, "customer" => $pi->customer, "payment_method" => $pi->payment_method, 'metadata' => $pi->metadata]);
}