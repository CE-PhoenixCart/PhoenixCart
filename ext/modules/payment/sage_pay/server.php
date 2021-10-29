<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  chdir('../../../../');
  require 'includes/application_top.php';

  if ( !defined('MODULE_PAYMENT_SAGE_PAY_SERVER_STATUS') || (MODULE_PAYMENT_SAGE_PAY_SERVER_STATUS != 'True') ) {
    exit();
  }

  include language::map_to_translation('/modules/payment/sage_pay_server.php');
  $sage_pay_server = new sage_pay_server();

  $result = null;

  if ( isset($_GET['skcode'], $_POST['VPSSignature'], $_POST['VPSTxId'], $_POST['VendorTxCode'], $_POST['Status']) ) {
    $skcode = Text::input($_GET['skcode']);

    $sp_query = $db->query("SELECT securitykey FROM sagepay_server_securitykeys WHERE code = '" . $db->escape($skcode) . "' LIMIT 1");
    if ( mysqli_num_rows($sp_query) ) {
      $sp = $sp_query->fetch_assoc();

      $transaction_details = ['ID' => $_POST['VPSTxId']];

      $sig = $_POST['VPSTxId'] . $_POST['VendorTxCode'] . $_POST['Status'];

      if ( isset($_POST['TxAuthNo']) ) {
        $sig .= $_POST['TxAuthNo'];
      }

      $sig .= strtolower(substr(MODULE_PAYMENT_SAGE_PAY_SERVER_VENDOR_LOGIN_NAME, 0, 15));

      if ( isset($_POST['AVSCV2']) ) {
        $sig .= $_POST['AVSCV2'];

        $transaction_details['AVS/CV2'] = $_POST['AVSCV2'];
      }

      $sig .= $sp['securitykey'];

      if ( isset($_POST['AddressResult']) ) {
        $sig .= $_POST['AddressResult'];

        $transaction_details['Address'] = $_POST['AddressResult'];
      }

      if ( isset($_POST['PostCodeResult']) ) {
        $sig .= $_POST['PostCodeResult'];

        $transaction_details['Post Code'] = $_POST['PostCodeResult'];
      }

      if ( isset($_POST['CV2Result']) ) {
        $sig .= $_POST['CV2Result'];

        $transaction_details['CV2'] = $_POST['CV2Result'];
      }

      if ( isset($_POST['GiftAid']) ) {
        $sig .= $_POST['GiftAid'];
      }

      if ( isset($_POST['3DSecureStatus']) ) {
        $sig .= $_POST['3DSecureStatus'];

        $transaction_details['3D Secure'] = $_POST['3DSecureStatus'];
      }

      if ( isset($_POST['CAVV']) ) {
        $sig .= $_POST['CAVV'];
      }

      if ( isset($_POST['AddressStatus']) ) {
        $sig .= $_POST['AddressStatus'];

        $transaction_details['PayPal Payer Address'] = $_POST['AddressStatus'];
      }

      if ( isset($_POST['PayerStatus']) ) {
        $sig .= $_POST['PayerStatus'];

        $transaction_details['PayPal Payer Status'] = $_POST['PayerStatus'];
      }

      if ( isset($_POST['CardType']) ) {
        $sig .= $_POST['CardType'];

        $transaction_details['Card'] = $_POST['CardType'];
      }

      if ( isset($_POST['Last4Digits']) ) {
        $sig .= $_POST['Last4Digits'];
      }

      if ( isset($_POST['DeclineCode']) ) {
        $sig .= $_POST['DeclineCode'];
      }

      if ( isset($_POST['ExpiryDate']) ) {
        $sig .= $_POST['ExpiryDate'];
      }

      if ( isset($_POST['FraudResponse']) ) {
        $sig .= $_POST['FraudResponse'];
      }

      if ( isset($_POST['BankAuthCode']) ) {
        $sig .= $_POST['BankAuthCode'];
      }

      $sig = strtoupper(md5($sig));

      if ( $_POST['VPSSignature'] == $sig ) {
        if ( ($_POST['Status'] == 'OK') || ($_POST['Status'] == 'AUTHENTICATED') || ($_POST['Status'] == 'REGISTERED') ) {
          $transaction_details_string = '';

          foreach ( $transaction_details as $k => $v ) {
            $transaction_details_string .= $k . ': ' . $v . "\n";
          }

          $transaction_details_string = Text::input($transaction_details_string);

          $db->query("UPDATE sagepay_server_securitykeys SET verified = 1, transaction_details = '" . $db->escape($transaction_details_string) . "' WHERE code = '" . $db->escape($skcode) . "'");

          $result = 'Status=OK' . chr(13) . chr(10) .
                    'RedirectURL=' . $sage_pay_server->formatURL($Linker->build('checkout_process.php', ['check' => 'PROCESS', 'skcode' => $skcode], false));
        } else {
          $error = isset($_POST['StatusDetail']) ? $sage_pay_server->getErrorMessageNumber($_POST['StatusDetail']) : null;

          if ( MODULE_PAYMENT_SAGE_PAY_SERVER_PROFILE_PAGE == 'Normal' ) {
            $error_url = $Linker->build('checkout_payment.php', ['payment_error' => $sage_pay_server->code], false);
          } else {
            $error_url = $Linker->build('ext/modules/payment/sage_pay/redirect.php', ['payment_error' => $sage_pay_server->code], false);
          }

          if (!Text::is_empty($error)) {
            $error_url->set_parameter('error', $error);
          }

          $result = 'Status=OK' . chr(13) . chr(10) .
                    'RedirectURL=' . $sage_pay_server->formatURL($error_url);

          $db->query("DELETE FROM sagepay_server_securitykeys WHERE code = '" . $db->escape($skcode) . "'");

          $sage_pay_server->sendDebugEmail();
        }
      } else {
        $result = 'Status=INVALID' . chr(13) . chr(10) .
                  'RedirectURL=' . $sage_pay_server->formatURL($Linker->build('shopping_cart.php', [], false));

        $sage_pay_server->sendDebugEmail();
      }
    }
  }

  if ( !isset($result) ) {
    $result = 'Status=ERROR' . chr(13) . chr(10) .
              'RedirectURL=' . $sage_pay_server->formatURL($Linker->build('shopping_cart.php', [], false));
  }

  echo $result;

  Session::destroy();

  exit();

  require 'includes/application_bottom.php';
