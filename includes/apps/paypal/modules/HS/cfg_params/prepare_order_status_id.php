<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

  class OSCOM_PayPal_HS_Cfg_prepare_order_status_id {
    var $default = '0';
    var $title;
    var $description;
    var $sort_order = 300;

    function __construct() {
      global $OSCOM_PayPal;

      $this->title = $OSCOM_PayPal->getDef('cfg_hs_prepare_order_status_id_title');
      $this->description = $OSCOM_PayPal->getDef('cfg_hs_prepare_order_status_id_desc');

      if ( !defined('OSCOM_APP_PAYPAL_HS_PREPARE_ORDER_STATUS_ID') ) {
        $check_query = $GLOBALS['db']->query("select orders_status_id from orders_status where orders_status_name = 'Preparing [PayPal Pro HS]' limit 1");

        if (mysqli_num_rows($check_query) < 1) {
          $status_query = $GLOBALS['db']->query("select max(orders_status_id) as status_id from orders_status");
          $status = $status_query->fetch_assoc();

          $status_id = $status['status_id']+1;

          foreach (array_values(language::load_all()) as $lang) {
            $GLOBALS['db']->query("insert into orders_status (orders_status_id, language_id, orders_status_name) values ('" . $status_id . "', '" . $lang['id'] . "', 'Preparing [PayPal Pro HS]')");
          }

          $flags_query = $GLOBALS['db']->query("describe orders_status public_flag");
          if (mysqli_num_rows($flags_query) == 1) {
            $GLOBALS['db']->query("update orders_status set public_flag = 0 and downloads_flag = 0 where orders_status_id = '" . $status_id . "'");
          }
        } else {
          $check = $check_query->fetch_assoc();

          $status_id = $check['orders_status_id'];
        }
      } else {
        $status_id = OSCOM_APP_PAYPAL_HS_PREPARE_ORDER_STATUS_ID;
      }

      $this->default = $status_id;
    }

    function getSetField() {
      global $OSCOM_PayPal;

      $statuses_array = array(array('id' => '0', 'text' => $OSCOM_PayPal->getDef('cfg_hs_prepare_order_status_id_default')));

      $statuses_query = $GLOBALS['db']->query("select orders_status_id, orders_status_name from orders_status where language_id = '" . (int)$_SESSION['languages_id'] . "' order by orders_status_name");
      while ($statuses = $statuses_query->fetch_assoc()) {
        $statuses_array[] = array('id' => $statuses['orders_status_id'],
                                  'text' => $statuses['orders_status_name']);
      }

      $input = (new Select('prepare_order_status_id', $statuses_array, ['id' => 'inputHsPrepareOrderStatusId']))->set_selection(OSCOM_APP_PAYPAL_HS_PREPARE_ORDER_STATUS_ID);

      $result = <<<EOT
<h5>{$this->title}</h5>
<p>{$this->description}</p>

<div class="mb-3">{$input}</div>
EOT;

      return $result;
    }
  }
