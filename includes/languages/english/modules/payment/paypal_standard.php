<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Paypal Standard Payments
  Basic Paypal Payment Module for Phoenix Cart
  More sophisticated Paypal integration available at https://phoenixcart.org/forum/addons/

  author: John Ferguson @BrockleyJohn phoenix@cartmart.uk

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

const MODULE_PAYMENT_PAYPAL_STANDARD_TEXT_TITLE = 'Paypal Standard Payments';
const MODULE_PAYMENT_PAYPAL_STANDARD_TEXT_PUBLIC_TITLE = 'Paypal';
const MODULE_PAYMENT_PAYPAL_STANDARD_TEXT_DESCRIPTION = '<div class="alert alert-warning text-break">Set Auto Return for Website Payments in your Paypal settings On and configure the return URL to:<br/>%s<br><br>Set PDT On and copy the identity token to the module.<br><br>Turn on Instant Payment Notification and set the Notification URL to:<br/>%s</div><i class="fas fa-external-link-alt me-2"></i><a href="https://www.paypal.com" target="_blank" rel="noopener">Paypal Website</a>';

const MODULE_PAYMENT_PAYPAL_STANDARD_TEXT_RETURN_BUTTON = 'Back to ' . STORE_NAME; // max length 60 chars

const MODULE_PAYMENT_PAYPAL_STANDARD_ERROR_ADMIN_CONFIGURATION_SELLER = 'This module will not load until the Seller Email parameter has been configured. Please edit and configure the settings of this module.';
const MODULE_PAYMENT_PAYPAL_STANDARD_ERROR_ADMIN_CONFIGURATION_PDT = 'The module will not load without the PDT Identity Token for additional payment security.';

const MODULE_PAYMENT_PAYPAL_STANDARD_CONFIG_ERROR = 'Paypal is not configured correctly; please try another payment method or contact us.';
const MODULE_PAYMENT_PAYPAL_STANDARD_UPDATE_COMMENT_ERROR = 'Failed to record order comments. Please try again and if problems persist please contact us.';
const MODULE_PAYMENT_PAYPAL_STANDARD_ERROR_VALIDATE_FAIL = 'Could not verify the Paypal transaction - please try again. If problems persist please try another payment method or contact us.';

const MODULE_PAYMENT_PAYPAL_STANDARD_DIALOG_CONNECTION_LINK_TEXT = 'Test Server Connection';
const MODULE_PAYMENT_PAYPAL_STANDARD_DIALOG_CONNECTION_TITLE = 'API Server Connection Test';
const MODULE_PAYMENT_PAYPAL_STANDARD_DIALOG_CONNECTION_GENERAL_TEXT = 'Testing connection to server...';
const MODULE_PAYMENT_PAYPAL_STANDARD_DIALOG_CONNECTION_CLOSE = 'Close';
const MODULE_PAYMENT_PAYPAL_STANDARD_DIALOG_CONNECTION_TIME = 'Connection Time:';
const MODULE_PAYMENT_PAYPAL_STANDARD_DIALOG_CONNECTION_SUCCESS = 'Success!';
const MODULE_PAYMENT_PAYPAL_STANDARD_DIALOG_CONNECTION_FAILED = 'Failed to connect: please review your settings and try again.';
const MODULE_PAYMENT_PAYPAL_STANDARD_DIALOG_CONNECTION_ERROR = 'An error occurred. Please refresh the page, check your settings and try again.';

