<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

  $GLOBALS['db']->query('delete from oscom_app_paypal_log');

  $OSCOM_PayPal->addAlert($OSCOM_PayPal->getDef('alert_delete_success'), 'success');

  Href::redirect($GLOBALS['Admin']->link('paypal.php', ['action' => 'log']));
