<?php
/*
  $Id$

  CE Phoenix, E-Commerce made Easy
  https://phoenixcart.org

  Copyright (c) 2024 Phoenix Cart

  Released under the GNU General Public License
*/

  chdir('../../');
  require 'includes/application_top.php';

  if (!strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    die(':-\\');
  }

  if (isset($_SESSION['customer_id'])) {
    $cID = (int)$_SESSION['customer_id'];

    if (isset($_POST['do'])) {
      switch($_POST['do']) {
        case 'review':
          $flag = (int)$_POST['review_id'];
          $anon = Text::input($_POST['anon']);
          if (isset($_POST['action'])) {
            switch($_POST['action']) {
              case 'delete':
              $db->query("DELETE FROM reviews WHERE customers_id = '" . $cID . "' AND reviews_id = '" . $flag . "'");
              $db->query("DELETE FROM reviews_description WHERE reviews_id = '" . $flag . "'");
              break;
              case 'anonymize':
              $db->query(sprintf(<<<'EOSQL'
UPDATE reviews SET is_anon = 'y', last_modified = NOW(), customers_name = '%s' WHERE customers_id = %d AND reviews_id = %d 
EOSQL
              , $db->escape($anon), $cID, $flag));
              break;
            }
          }
          break;
        case 'testimonial':
          $flag = (int)$_POST['testimonial_id'];
          $anon = Text::input($_POST['anon']);
          if (isset($_POST['action'])) {
            switch($_POST['action']) {
              case 'delete':
              $db->query("DELETE FROM testimonials WHERE customers_id = '" . $cID . "' AND testimonials_id = '" . $flag . "'");
              $db->query("DELETE FROM testimonials_description WHERE testimonials_id = '" . $flag . "'");
              break;
              case 'anonymize':
              $db->query(sprintf(<<<'EOSQL'
UPDATE testimonials SET is_anon = 'y', last_modified = NOW(), customers_name = '%s' WHERE customers_id = %d AND testimonials_id = %d
EOSQL
              , $db->escape($anon), $cID, $flag));
              break;
            }
          }
          break;
        case 'notification':
          $flag = (int)$_POST['notification_id'];
          $db->query("DELETE FROM products_notifications WHERE customers_id = '" . $cID . "' AND products_id = '" . $flag . "'");
          break;
        case 'ip':
          $flag = Text::input($_POST['ip_id']);
          $db->query("UPDATE action_recorder SET identifier = '::anonymized::' WHERE identifier = '" . $db->escape($flag) . "' AND user_id = " . (int)$cID);
          break;
        case 'cookies':
          $_cookie_sess = $_POST['cookie'];

          setcookie($_cookie_sess, '', time()-86400, '/');
          unset($_COOKIE[$_cookie_sess]);
        break;
      }
    }
  }

  echo '';
