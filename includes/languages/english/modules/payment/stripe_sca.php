<?php
/*
* $Id: stripe_sca.php
* $Loc: /includes/languages/english/modules/payment/
*
* Name: StripeSCA
* Version: 1.70
* Release Date: 2025-03-02
* Author: Rainer Schmied
* 	 phoenixcartaddonsaddons.com / raiwa@phoenixcartaddons.com
*
* License: Released under the GNU General Public License
*
* Comments: Author: [Rainer Schmied @raiwa]
* Author URI: [www.phoenixcartaddons.com]
* 
* CE Phoenix, E-Commerce made Easy
* https://phoenixcart.org
* 
* Copyright (c) 2021 Phoenix Cart
* 
* 
*/
  const MODULE_PAYMENT_STRIPE_SCA_TEXT_TITLE = 'Stripe SCA';
  const MODULE_PAYMENT_STRIPE_SCA_TEXT_PUBLIC_TITLE = 'Credit Card (Stripe SCA)';
  const MODULE_PAYMENT_STRIPE_SCA_TEXT_DESCRIPTION = '<i class="fas fa-external-link-alt me-2"></i><a href="https://www.stripe.com" target="_blank" rel="noopener">Visit Stripe Website</a>';

  const MODULE_PAYMENT_STRIPE_SCA_ERROR_ADMIN_CURL = 'This module requires cURL to be enabled in PHP and will not load until it has been enabled on this webserver.';
  const MODULE_PAYMENT_STRIPE_SCA_ERROR_ADMIN_CONFIGURATION = 'This module will not load until the Publishable Key and Secret Key parameters have been configured. Please edit and configure the settings of this module.';
  const MODULE_PAYMENT_STRIPE_SCA_ADMIN_STATUS_TITLE = 'Enable Stripe SCA Module';
  const MODULE_PAYMENT_STRIPE_SCA_ADMIN_STATUS_DESC = 'Do you want to accept Stripe v3 payments?';
  const MODULE_PAYMENT_STRIPE_SCA_ADMIN_SERVER_TITLE = 'Transaction Server';
  const MODULE_PAYMENT_STRIPE_SCA_ADMIN_SERVER_DESC = 'Perform transactions on the production server or on the testing server.';
  const MODULE_PAYMENT_STRIPE_SCA_ADMIN_LIVE_PUB_TITLE = 'Live Publishable API Key';
  const MODULE_PAYMENT_STRIPE_SCA_ADMIN_LIVE_PUB_DESC = 'The Stripe account publishable API key to use for production transactions.';
  const MODULE_PAYMENT_STRIPE_SCA_ADMIN_LIVE_SECRET_TITLE = 'Live Secret API Key';
  const MODULE_PAYMENT_STRIPE_SCA_ADMIN_LIVE_SECRET_DESC = 'The Stripe account secret API key to use with the live publishable key.';
  const MODULE_PAYMENT_STRIPE_SCA_ADMIN_LIVE_WEBHOOK_TITLE = 'Live Webhook Signing Secret';
  const MODULE_PAYMENT_STRIPE_SCA_ADMIN_LIVE_WEBHOOK_DESC = 'The Stripe account live webhook signing secret of the webhook you created to listen for payment_intent.succeeded events.';
  const MODULE_PAYMENT_STRIPE_SCA_ADMIN_TEST_PUB_TITLE = 'Test Publishable API Key';
  const MODULE_PAYMENT_STRIPE_SCA_ADMIN_TEST_PUB_DESC = 'The Stripe account publishable API key to use for testing.';
  const MODULE_PAYMENT_STRIPE_SCA_ADMIN_TEST_SECRET_TITLE = 'Test Secret API Key';
  const MODULE_PAYMENT_STRIPE_SCA_ADMIN_TEST_SECRET_DESC = 'The Stripe account secret API key to use with the test publishable key.';
  const MODULE_PAYMENT_STRIPE_SCA_ADMIN_TEST_WEBHOOK_TITLE = 'Test Webhook Signing Secret';
  const MODULE_PAYMENT_STRIPE_SCA_ADMIN_TEST_WEBHOOK_DESC = 'The Stripe account test webhook signing secret of the webhook you created to listen for payment_intent.succeeded events.';
  const MODULE_PAYMENT_STRIPE_SCA_ADMIN_TOKENS_TITLE = 'Create Tokens';
  const MODULE_PAYMENT_STRIPE_SCA_ADMIN_TOKENS_DESC = 'Create and store tokens for card payments customers can use on their next purchase?';
  const MODULE_PAYMENT_STRIPE_SCA_ADMIN_CARD_DATA_TITLE = 'Use One line Card Data input';
  const MODULE_PAYMENT_STRIPE_SCA_ADMIN_CARD_DATA_DESC = 'Use One Line Card Data Input Field if set to "True" or 3 separate Input Fields if set to "False".';
  const MODULE_PAYMENT_STRIPE_SCA_ADMIN_LOG_TITLE = 'Log Events';
  const MODULE_PAYMENT_STRIPE_SCA_ADMIN_LOG_DESC = 'Log calls to Sripe functions?';
  const MODULE_PAYMENT_STRIPE_SCA_ADMIN_METHOD_TITLE = 'Transaction Method';
  const MODULE_PAYMENT_STRIPE_SCA_ADMIN_METHOD_DESC = 'The processing method to use for each transaction.';
  const MODULE_PAYMENT_STRIPE_SCA_ADMIN_NEW_ORDER_TITLE = 'Set New Order Status';
  const MODULE_PAYMENT_STRIPE_SCA_ADMIN_NEW_ORDER_DESC = 'Set the status of orders created with this payment module to this value';
  const MODULE_PAYMENT_STRIPE_SCA_ADMIN_PROCESSED_TITLE = 'Set Order Processed Status';
  const MODULE_PAYMENT_STRIPE_SCA_ADMIN_PROCESSED_DESC = 'Set the status of orders successfully processed with this payment module to this value';
  const MODULE_PAYMENT_STRIPE_SCA_ADMIN_TRANSACTION_TITLE = 'Transaction Order Status';
  const MODULE_PAYMENT_STRIPE_SCA_ADMIN_TRANSACTION_DESC = 'Include transaction information in this order status level';
  const MODULE_PAYMENT_STRIPE_SCA_ADMIN_ZONE_TITLE = 'Payment Zone';
  const MODULE_PAYMENT_STRIPE_SCA_ADMIN_ZONE_DESC = 'If a zone is selected, only enable this payment method for that zone.';
  const MODULE_PAYMENT_STRIPE_SCA_ADMIN_SSL_TITLE = 'Verify SSL Certificate';
  const MODULE_PAYMENT_STRIPE_SCA_ADMIN_SSL_DESC = 'Verify gateway server SSL certificate on connection?';
  const MODULE_PAYMENT_STRIPE_SCA_ADMIN_PROXY_TITLE = 'Proxy Server';
  const MODULE_PAYMENT_STRIPE_SCA_ADMIN_PROXY_DESC = 'Send API requests through this proxy server. (host:port, eg: 123.45.67.89:8080 or proxy.example.com:8080)';
  const MODULE_PAYMENT_STRIPE_SCA_ADMIN_EMAIL_TITLE = 'Debug E-Mail Address';
  const MODULE_PAYMENT_STRIPE_SCA_ADMIN_EMAIL_DESC = 'All parameters of an invalid transaction will be sent to this email address.';
  const MODULE_PAYMENT_STRIPE_SCA_ADMIN_DAYS_DELETE_TITLE = 'Days waiting to delete Preparing Stripe Orders.';
  const MODULE_PAYMENT_STRIPE_SCA_ADMIN_DAYS_DELETE_DESC = 'After how many days should unfinished Stripe orders be auto deleted? Leave empty to disable.';
  const MODULE_PAYMENT_STRIPE_SCA_ADMIN_SORT_TITLE = 'Sort order of display.';
  const MODULE_PAYMENT_STRIPE_SCA_ADMIN_SOR_DESC = 'Sort order of display. Lowest, non-zero is displayed first.';

  const MODULE_PAYMENT_STRIPE_SCA_CREDITCARD_NEW = 'Enter a new Card';
  const MODULE_PAYMENT_STRIPE_SCA_CREDITCARD_OWNER = 'Card holder name';
  const MODULE_PAYMENT_STRIPE_SCA_CREDITCARD_TYPE = 'Card Number > then Expiry Date > then 3 Numbers on Rear (CVC)';
  const MODULE_PAYMENT_STRIPE_SCA_CREDITCARD_NUMBER = 'Card Number';
  const MODULE_PAYMENT_STRIPE_SCA_CREDITCARD_EXPIRY = 'Expiry Date (MM/YY)';
  const MODULE_PAYMENT_STRIPE_SCA_CREDITCARD_CVC = '3 Numbers on Rear (CVC)';
  const MODULE_PAYMENT_STRIPE_SCA_CREDITCARD_SAVE = 'Save Card for next purchase?';
  const MODULE_PAYMENT_STRIPE_SCA_MISSING_INTENT = 'Missing intent id';
  const MODULE_PAYMENT_STRIPE_SCA_MISSING_CUSTOMER_TOKEN = 'Missing customer token';
  const MODULE_PAYMENT_STRIPE_SCA_MISSING_CARD_FOR_TOKEN = 'No card details found for token ';

  const MODULE_PAYMENT_STRIPE_SCA_WEBHOOK_PARAMETER = 'Unexpected parameter value received';
  const MODULE_PAYMENT_STRIPE_SCA_SECRET_ERROR = 'Invalid webhook signing secret';
  const MODULE_PAYMENT_STRIPE_SCA_WEBHOOK_SERVER = 'Server error - check logs';

  const MODULE_PAYMENT_STRIPE_SCA_ERROR_TITLE = 'There has been an error processing your credit card';
  const MODULE_PAYMENT_STRIPE_SCA_ERROR_GENERAL = 'Please try again and if problems persist, please try another payment method.';
  const MODULE_PAYMENT_STRIPE_SCA_ERROR_CARDSTORED = 'The stored card could not be found. Please try again and if problems persist, please try another payment method.';

  const MODULE_PAYMENT_STRIPE_SCA_DIALOG_CONNECTION_LINK_TITLE = 'Test API Server Connection';
  const MODULE_PAYMENT_STRIPE_SCA_DIALOG_CONNECTION_TITLE = 'API Server Connection Test';
  const MODULE_PAYMENT_STRIPE_SCA_DIALOG_CONNECTION_GENERAL_TEXT = 'Testing connection to server..';
  const MODULE_PAYMENT_STRIPE_SCA_DIALOG_CONNECTION_BUTTON_CLOSE = 'Close';
  const MODULE_PAYMENT_STRIPE_SCA_DIALOG_CONNECTION_TIME = 'Connection Time:';
  const MODULE_PAYMENT_STRIPE_SCA_DIALOG_CONNECTION_SUCCESS = 'Success!';
  const MODULE_PAYMENT_STRIPE_SCA_DIALOG_CONNECTION_FAILED = 'Failed! Please review the Verify SSL Certificate settings and try again.';
  const MODULE_PAYMENT_STRIPE_SCA_DIALOG_CONNECTION_ERROR = 'An error occurred. Please refresh the page, review your settings, and try again.';

  const MODULE_PAYMENT_STRIPE_SCA_PROCESSING_TEXT = 'Thank You. We are placing your Order now...';
  const MODULE_PAYMENT_STRIPE_SCA_FINALIZE_TEXT = ' Finalize and Make Payment';
