<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  chdir('../../../../');
  require 'includes/application_top.php';

  $hooks->register_pipeline('loginRequired');

// needs to be included earlier to set the success message in the messageStack
  require language::map_to_translation('modules/content/account/cm_account_gdpr_nuke.php');

  if (Form::validate_action_is('process')) {
    if (isset($_POST['nuke'])) {
// delete from db
      $db->query("DELETE FROM address_book WHERE customers_id = " . (int)$_SESSION['customer_id']);
      $db->query("DELETE FROM customers WHERE customers_id = " . (int)$_SESSION['customer_id']);
      $db->query("DELETE FROM customers_basket WHERE customers_id = " . (int)$_SESSION['customer_id']);
      $db->query("DELETE FROM customers_basket_attributes WHERE customers_id = " . (int)$_SESSION['customer_id']);
      $db->query("DELETE FROM customers_info WHERE customers_info_id = " . (int)$_SESSION['customer_id']);
      $db->query("DELETE FROM products_notifications WHERE customers_id = " . (int)$_SESSION['customer_id']);
      $db->query("DELETE FROM whos_online WHERE customer_id = " . (int)$_SESSION['customer_id']);
      $db->query("DELETE r, rd FROM reviews r LEFT JOIN reviews_description rd ON r.reviews_id = rd.reviews_id WHERE r.customers_id = " . (int)$_SESSION['customer_id']);
      $db->query("DELETE t, td FROM testimonials t LEFT JOIN testimonials_description td ON t.testimonials_id = td.testimonials_id WHERE t.customers_id = " . (int)$_SESSION['customer_id']);
      $db->query("DELETE FROM outgoing WHERE customer_id = " . (int)$_SESSION['customer_id']);

// delete cookies
      foreach ($_COOKIE as $k => $v) {
        unset($_COOKIE[$k]);
      }

      $sesskey = session_id();
      $hooks->register_pipeline('logoff');
      $hooks->register_pipeline('reset');
      Session::destroy();

// nuke session
      $db->query("DELETE FROM sessions WHERE sesskey = '" . $sesskey . "'");

      $GLOBALS['messageStack']->add_session(
          'product_action',
          MODULE_CONTENT_GDPR_NUKE_MESSAGESTACK_NUKED,
          'success');

      Href::redirect($Linker->build('index.php'));
    }
  }

  require $Template->map(__FILE__, 'ext');
  require 'includes/application_bottom.php';
