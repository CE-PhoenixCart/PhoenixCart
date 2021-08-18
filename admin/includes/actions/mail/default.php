<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2021 Phoenix Cart

  Released under the GNU General Public License
*/

  if (isset($_POST['customers_email_address'])) {
    $mail_sent_to = phoenix_choose_audience($_POST['customers_email_address']);
  } elseif ($action) {
    $messageStack->add(ERROR_NO_CUSTOMER_SELECTED, 'error');
    $action = '';
  }
